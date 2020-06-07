<?php

namespace App\Service;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Repository\CustomerRepository;
use App\Repository\WalletRepository;
use App\Repository\PaymentRepository;
use App\Service\WalletService;
 
class PaymentService
{
    private $customerRepository;
    private $walletRepository;

    public function __construct(
        CustomerRepository $customerRepository,
        WalletRepository $walletRepository,
        PaymentRepository $paymentRepository,
        WalletService $walletService
    ){
        $this->customerRepository = $customerRepository;
        $this->walletRepository = $walletRepository;
        $this->paymentRepository = $paymentRepository;
        $this->walletService = $walletService;
    }

    public function makePayment($identificationNumber,$cellphone,$value)
    {   
        try {
            $wallet =$this->walletService->getWalletByCustomer($identificationNumber,$cellphone,$value);
            $payment = $this->paymentRepository->savePayment($wallet,$value);
            if ($payment) {
                $response = array(
                    'success' => true,
                    'message' => 'Se generó el pago correctamente por favor confirmar el token enviado a el correo: '.$wallet->getCustomer()->getEmail()
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
}