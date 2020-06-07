<?php
namespace App\Controller;


use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\PaymentService;

use Symfony\Component\HttpFoundation\JsonResponse;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;


class PaymentController extends AbstractController
{
    /**
     * @Route("/soap/payment/make",name="makePaymentSoap", methods={"GET","POST"})
     */
    public function makePayment(Request $request,PaymentService $paymentService)
    {    
        $soapServer = new \SoapServer(dirname(__DIR__,1).DIRECTORY_SEPARATOR .'Wsdl'. DIRECTORY_SEPARATOR .'payment.wsdl');
        $soapServer->setObject($paymentService);

        $response = new Response();
        $response->headers->set('Content-Type', 'text/xml; charset=ISO-8859-1');

        ob_start();
        $soapServer->handle();
        $response->setContent(ob_get_clean());

        return $response;
    }

    /**
     * @Route("/soap/payment/confirm",name="confirmPaymentSoap")
     */
    public function confirmPayment(Request $request,PaymentService $paymentService)
    {    
        $soapServer = new \SoapServer(dirname(__DIR__,1).DIRECTORY_SEPARATOR .'Wsdl'. DIRECTORY_SEPARATOR .'payment.wsdl');
        $soapServer->setObject($paymentService);

        $response = new Response();
        $response->headers->set('Content-Type', 'text/xml; charset=ISO-8859-1');

        ob_start();
        $soapServer->handle();
        $response->setContent(ob_get_clean());

        return $response;

    }
}