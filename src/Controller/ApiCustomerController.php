<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use nusoap_client;

class ApiCustomerController 
{
    /**
     * @Route("/api/customer", name="apiCustomer")
     */
    public function createCustomer(Request $request)
    {   
    	set_time_limit(0);
    	ini_set("memory_limit", -1);
    
    	$client = new nusoap_client('http://'.$_SERVER['HTTP_HOST'].'/index.php/soap/customer?wsdl', 'wsdl');
        $client->setEndpoint('http://'.$_SERVER['HTTP_HOST'].'/index.php/soap/customer');

		$client->soap_defencoding = 'UTF-8';
		$client->decode_utf8 = false;
    	$params = json_decode($request->getContent(), true);
    	if (array_key_exists("identificationNumber", $params) && array_key_exists("fullname", $params) && array_key_exists("email", $params) && array_key_exists("cellphone", $params)){
    		$result = $client->call('createcustomer', [
    			'identificationNumber' => $params['identificationNumber'],
    			'fullname' => $params['fullname'],
    			'email' => $params['email'],
    			'cellphone' => $params['cellphone']
    		]);
    		$response = json_decode($result);

    	}else{
    		$response = array( 'success'=>false,
    			'error_code' => 422,
    			'error_message' =>'Los parametros: identificationNumber,fullname,email y cellphone son obligatorios!'
    		);
    	}

        return new JsonResponse($response, Response::HTTP_OK);
    }
}
