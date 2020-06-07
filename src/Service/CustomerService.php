<?php

namespace App\Service;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Repository\CustomerRepository;
use App\Repository\WalletRepository;

class CustomerService
{
    private $customerRepository;

    public function __construct(
        CustomerRepository $customerRepository,
        WalletRepository $walletRepository
    ){
        $this->customerRepository = $customerRepository;
        $this->walletRepository = $walletRepository;
    }

    public function createCustomer($identificationNumber,$fullname,$email,$cellphone)
    {   
        try {
            if (empty($identificationNumber) || empty($fullname) || empty($email) || empty($cellphone)) {

               throw new NotFoundHttpException('Los parametros: identificationNumber,fullname,email y cellphone son obligatorios!');
            }else{

                if($this->customerRepository->findOneByIdentificationNumber($identificationNumber)){
                   $response = array('success' => false,
                    'cod_error' => '422',
                    'message_error' => 'Ya existe un cliente creado con el numero de identificacion: '.$identificationNumber
                    );
                }else{

                    if($this->customerRepository->findOneByEmail($email)){
                       $response = array('success' => false,
                        'cod_error' => '422',
                        'message_error' => 'Ya existe un cliente creado con el correo: '.$email
                        );
                    }else{

                        $newCustomer = $this->customerRepository->saveCustomer($identificationNumber,$fullname,$email,$cellphone);
                        $this->walletRepository->saveWallet($newCustomer);
                        $response = array(
                            'success' => true,
                            'message' => 'El cliente y su nueva billetera se crearon correctamente!'
                        );
                    }
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