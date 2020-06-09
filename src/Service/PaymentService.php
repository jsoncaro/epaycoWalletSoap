<?php

namespace App\Service;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Repository\CustomerRepository;
use App\Repository\WalletRepository;
use App\Repository\PaymentRepository;
use App\Repository\SessionRepository;
use App\Service\WalletService;
 
class PaymentService
{
    private $customerRepository;
    private $walletRepository;
    private $sessionRepository;

    public function __construct(
        CustomerRepository $customerRepository,
        WalletRepository $walletRepository,
        PaymentRepository $paymentRepository,
        WalletService $walletService,
        SessionRepository $sessionRepository,
        \Swift_Mailer $mailer
    ){
        $this->customerRepository = $customerRepository;
        $this->walletRepository = $walletRepository;
        $this->paymentRepository = $paymentRepository;
        $this->walletService = $walletService;
        $this->sessionRepository = $sessionRepository;
        $this->mailer = $mailer;
    }

    public function makePayment($identificationNumber,$cellphone,$value)
    {   
        try {
            $wallet =$this->walletService->getWalletByCustomer($identificationNumber,$cellphone,$value);
            $payment = $this->paymentRepository->savePayment($wallet,$value);
            $email = $wallet->getCustomer()->getEmail();
            $messageSent = $this->sendMail($email,$payment);
            if ($payment && $messageSent) {
                $response = array(
                    'success' => true,
                    'message' => 'Se generó el pago correctamente por favor confirmar el token enviado a el correo: '.$email
                );
            }
        }catch(\Exception $e) {
            $response = array(
                'success' => false,
                'cod_error' => $e->getCode(),
                'message_error' => $e->getMessage()
            );
        }
        return json_encode($response);
    }

    public function confirmPayment($sessionId,$token)
    {   
        try {
            if (empty($sessionId) || empty($token)) {

                throw new NotFoundHttpException('Los parametros: sessionId y token son obligatorios!');
            }else{
                $validSession = $this->validateSession($sessionId);

                if ($validSession) {
                    $payment = $validSession->getPayment();
                    $paymentToken = $payment->getToken();
                    if((string)$paymentToken == strtoupper((string)$token)){
                        if (!$payment->getConfirmed()) {
                            $wallet = $payment->getWallet();
                            if(($wallet->getBalance() - $payment->getValue()) < 0){
                                $response = array(
                                'success' => false,
                                'cod_error' => 428,
                                'message_error' => 'Saldo insuficiente para el pago'
                                );
                            }else{
                                    $this->walletRepository->discountBalance($wallet,$payment->getValue());
                                    $this->paymentRepository->processPayment($payment);
                                    $response = array(
                                        'success' => true,
                                        'message' => 'El pago se realizó correctamente su nuevo saldo es: '.$wallet->getBalance()
                                    );
                            }
                        }else{
                            $response = array(
                                'success' => false,
                                'cod_error' => 428,
                                'message_error' =>'El pago ya fue procesado'
                            );
                        }
                    }else{
                        throw new NotFoundHttpException('Token erroneo');
                    }
                }else{
                    throw new NotFoundHttpException('El session es erronea o expirada');
                }
            }
        }catch(\Exception $e) {
            $response = array(
                'success' => false,
                'cod_error' => $e->getCode(),
                'message_error' => $e->getMessage()
            );
        }
        return json_encode($response);
    }

    private function sendMail($email,$payment){
            $token = $payment->getToken();
            $message = ( new \Swift_Message( 'Para confirmar la compra ingrese los siguientes datos' ) )
            ->setFrom( 'jeisoncaroestrada@gmail.com' )
            ->setTo($email)
            ->setBody($token,'text/html');

        return $this->mailer->send( $message );
    }

    private function validateSession($sessionId)
    {
        $session = $this->sessionRepository->findOneBy(['sessionUuid'=>$sessionId]);
        if($session){
            $time = new \DateTime();
            $time->setTimezone(new \DateTimeZone('America/Bogota'));
            $now = $time->format('Y-m-d H:i:s');
            $expiresAt = $session->getExpiresAt()->format('Y-m-d-H-i-s');
            if($now < $expiresAt){
                return $session;
            }
        }

        return null;
    }
}