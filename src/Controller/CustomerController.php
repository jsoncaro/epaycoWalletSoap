<?php

namespace App\Controller;


use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\CustomerService;



use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;



class CustomerController extends AbstractController
//class CustomerController extends FOSRestController
{
    /**
     * @Route("/soap/customer",name="customerSoap")
     */
    public function createCustomer(Request $request,CustomerService $customerService)
    {    
        $soapServer = new \SoapServer(dirname(__DIR__,1).DIRECTORY_SEPARATOR .'Wsdl'. DIRECTORY_SEPARATOR .'customer.wsdl');
        $soapServer->setObject($customerService);

        $response = new Response();
        $response->headers->set('Content-Type', 'text/xml; charset=ISO-8859-1');

        ob_start();
        $soapServer->handle();
        $response->setContent(ob_get_clean());

        return $response;

    }
}