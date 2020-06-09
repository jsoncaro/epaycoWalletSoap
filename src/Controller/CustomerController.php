<?php

namespace App\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\CustomerService;
use App\Service\WalletService;
use App\Service\PaymentService;

class CustomerController extends AbstractController
{
    /**
     * @Route("/soap/customer",name="customerSoap")
     */
    public function createCustomer(Request $request,CustomerService $customerService)
    {    
        $soapServer = new \SoapServer(\dirname(__DIR__,2).DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR .'wsdl'. DIRECTORY_SEPARATOR .'customer.wsdl');
        $soapServer->setObject($customerService);

        $response = new Response();
        $response->headers->set('Content-Type', 'text/xml; charset=ISO-8859-1');
        //$response->headers->set('Content-Type', 'text/xml:text/xml; charset=UTF-8');

        ob_start();
        $soapServer->handle();
        $response->setContent(ob_get_clean());

        return $response;

    }
}