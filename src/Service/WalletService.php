<?php

namespace App\Service;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Repository\CustomerRepository;
use App\Repository\WalletRepository;
 
class WalletService
{
    private $customerRepository;
    private $walletRepository;

    public function __construct(
        CustomerRepository $customerRepository,
        WalletRepository $walletRepository
    ){
        $this->customerRepository = $customerRepository;
        $this->walletRepository = $walletRepository;
    }

    public function rechargeWallet($identificationNumber,$cellphone,$value)
    {   
        try {
            if (empty($identificationNumber) || empty($cellphone) || empty($value)) {

               throw new NotFoundHttpException('Los parametros: identificationNumber,cellphone y value son obligatorios!');
            }else{

                $customer = $this->customerRepository->findOneBy(['identificationNumber'=>$identificationNumber,'cellphone'=>$cellphone]);
                if($customer){
                        
                    $recharge = $this->walletRepository->rechargeWallet($customer,$value);
                    $response = array(
                        'success' => true,
                        'message' => 'La billetera se recargó correctamente el nuevo saldo es: '
                    );
                }else{

                   $response = array('success' => false,
                    'cod_error' => '422',
                    'message_error' => 'No se encontro el cliente con número de identificación: '.$identificationNumber.' y el celular:'.$cellphone
                    );
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

    public function chekWalletBalance($identificationNumber,$cellphone)
    {   
        try {
            if (empty($identificationNumber) || empty($cellphone)) {

               throw new NotFoundHttpException('Los parametros: identificationNumber y cellphone son obligatorios!');
            }else{

                $customer = $this->customerRepository->findOneBy(array('identificationNumber'=>$identificationNumber,'cellphone'=>$cellphone));
                if($customer){
                        
                    $wallet = $customer->getWallet();
                    $currentBallance = $wallet->getBalance();
                    $response = array(
                        'success' => true,
                        'message' => 'El saldo actual de la billetera es: '.$currentBallance
                    );
                }else{

                   $response = array('success' => false,
                    'cod_error' => '422',
                    'message_error' => 'No se encontro el cliente con número de identificación: '.$identificationNumber.' y el celular:'.$cellphone
                    );
                   
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
}