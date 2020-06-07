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

    public function getWalletByCustomer($identificationNumber,$cellphone,$value)
    {   
        if (empty($identificationNumber) || empty($cellphone) || empty($value)) {

           throw new NotFoundHttpException('Los parametros: identificationNumber,cellphone y value son obligatorios!');
        }else{

            $customer = $this->customerRepository->findOneBy(['identificationNumber'=>$identificationNumber,'cellphone'=>$cellphone]);
            if($customer){
                return $customer->getWallet();
            }else{
                throw new NotFoundHttpException('No se encontro el cliente con número de identificación: '.$identificationNumber.' y el celular:'.$cellphone);
               
            }
        }
        return json_encode($response);
    }

    public function rechargeWallet($identificationNumber,$cellphone,$value)
    {
        try {
            $wallet = $this->getWalletByCustomer($identificationNumber,$cellphone,$value);
            $recharge = $this->walletRepository->rechargeWallet($wallet,$value);
            if ($recharge) {
                $response = array(
                    'success' => true,
                    'message' => 'La billetera se recargó correctamente el nuevo saldo es: '.$wallet->getBalance()
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

    public function checkWalletBalance($identificationNumber,$cellphone,$value=1)
    {
        try {
            $wallet = $this->getWalletByCustomer($identificationNumber,$cellphone,$value);
            $response = array(
                    'success' => true,
                    'message' => 'El saldo actual de la billetera es: '.$wallet->getBalance()
                );
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