<?php

namespace App\Service;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Repository\CustomerRepository;

class CustomerService
{
    private $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
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

                        $this->customerRepository->saveCustomer($identificationNumber,$fullname,$email,$cellphone);
                        $response = array(
                            'success' => true,
                            'message' => 'El cliente se creó correctamente!'
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