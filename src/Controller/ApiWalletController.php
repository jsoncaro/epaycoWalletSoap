<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use nusoap_client;

//class ApiWalletController extends AbstractController

class ApiWalletController 
{
    /**
     * @Route("/api/wallet/recharge", name="apiCustomer")
     */
    public function rechargeWallet(Request $request)
    {   
    	set_time_limit(0);
    	ini_set("memory_limit", -1);
    
    	$client = new nusoap_client('http://'.$_SERVER['HTTP_HOST'].'/index.php/soap/wallet/recharge?wsdl', 'wsdl');
        $client->setEndpoint('http://'.$_SERVER['HTTP_HOST'].'/index.php/soap/wallet/recharge');

		$client->soap_defencoding = 'UTF-8';
		$client->decode_utf8 = false;
    	$params = json_decode($request->getContent(), true);
    	if (array_key_exists("identificationNumber", $params) && array_key_exists("cellphone", $params) && array_key_exists("value", $params) ){
    		$result = $client->call('rechargewallet', [
    			'identificationNumber' => $params['identificationNumber'],
    			'cellphone' => $params['cellphone'],
    			'value' => $params['value'],
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
     * @Route("/api/wallet/balance", name="apiCustomer")
     */
    public function checkWalletBalance(Request $request)
    {   
    	set_time_limit(0);
    	ini_set("memory_limit", -1);
    
    	$client = new nusoap_client('http://'.$_SERVER['HTTP_HOST'].'/index.php/soap/wallet/balance?wsdl', 'wsdl');
        $client->setEndpoint('http://'.$_SERVER['HTTP_HOST'].'/index.php/soap/wallet/balance');

		$client->soap_defencoding = 'UTF-8';
		$client->decode_utf8 = false;
    	$params = json_decode($request->getContent(), true);
    	 if (array_key_exists("identificationNumber", $params) && array_key_exists("cellphone", $params)){
    		$result = $client->call('checkwalletbalance', [
    			'identificationNumber' => $params['identificationNumber'],
    			'cellphone' => $params['cellphone']
    		]);
    		$response = json_decode($result);

    	}else{
    		$response = array( 'success'=>false,
    			'error_code' => 422,
    			'error_message' =>'Los parametros: identificationNumber y cellphone son obligatorios!'
    		);
    	}
        return new JsonResponse($response, Response::HTTP_OK);
    }

   
}

