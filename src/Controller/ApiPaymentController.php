<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use nusoap_client;

class ApiPaymentController extends AbstractController
{
    /**
     * @Route("/api/payment/make", name="apiPayment")
     */
    public function makePayment(Request $request)
    {   
    	set_time_limit(0);
    	ini_set("memory_limit", -1);
    
    	$client = new nusoap_client('http://'.$_SERVER['HTTP_HOST'].'/index.php/soap/payment/make?wsdl', 'wsdl');
        $client->setEndpoint('http://'.$_SERVER['HTTP_HOST'].'/index.php/soap/payment/make');

		$client->soap_defencoding = 'UTF-8';
		$client->decode_utf8 = false;
    	$params = json_decode($request->getContent(), true);
    	if (array_key_exists("identificationNumber", $params) && array_key_exists("cellphone", $params) && array_key_exists("value", $params) ){
    		$result = $client->call('makepayment', [
    			'identificationNumber' => $params['identificationNumber'],
    			'cellphone' => $params['cellphone'],
    			'value' => $params['value']
    		]);
    		$response = json_decode($result);

    	}else{
    		$response = array( 'success'=>false,
    			'error_code' => 422,
    			'error_message' =>'Los parametros: identificationNumber,cellphone y value son obligatorios!'
    		);
    	}

        return new JsonResponse($response, Response::HTTP_OK);
    }

    /**
     * @Route("/api/payment/confirm", name="api_payment")
     */
    public function confirmPayment(Request $request)
    {   
    	set_time_limit(0);
    	ini_set("memory_limit", -1);
    
    	$client = new nusoap_client('http://'.$_SERVER['HTTP_HOST'].'/index.php/soap/payment/confirm?wsdl', 'wsdl');
        $client->setEndpoint('http://'.$_SERVER['HTTP_HOST'].'/index.php/soap/payment/confirm');

		$client->soap_defencoding = 'UTF-8';
		$client->decode_utf8 = false;
    	$params = json_decode($request->getContent(), true);
    	if (array_key_exists("sessionId", $params) && array_key_exists("token", $params)){
    		$result = $client->call('confirmpayment', [
    			'sessionId' => $params['sessionId'],
    			'token' => $params['token'],
    		]);
    		$response = json_decode($result);

    	}else{
    		$response = array( 'success'=>false,
    			'error_code' => 422,
    			'error_message' =>'Los parametros: sessionId y token son obligatorios!'
    		);
    	}

        return new JsonResponse($response, Response::HTTP_OK);
    }
}
