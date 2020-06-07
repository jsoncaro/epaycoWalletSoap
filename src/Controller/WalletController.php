<?php

namespace App\Controller;


use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\WalletService;

use Symfony\Component\HttpFoundation\JsonResponse;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;


class WalletController extends AbstractController
{
    /**
     * @Route("/soap/wallet/recharge",name="rechargeWalletSoap", methods={"GET","POST"})
     */
    public function rechargeWallet(Request $request,WalletService $walletService)
    {    
        $soapServer = new \SoapServer(dirname(__DIR__,1).DIRECTORY_SEPARATOR .'Wsdl'. DIRECTORY_SEPARATOR .'wallet.wsdl');
        $soapServer->setObject($walletService);

        $response = new Response();
        $response->headers->set('Content-Type', 'text/xml; charset=ISO-8859-1');

        ob_start();
        $soapServer->handle();
        $response->setContent(ob_get_clean());

        return $response;

    }

    /**
     * @Route("/soap/wallet/balance",name="chekWalletBalanceSoap")
     */
    public function chekWalletBalance(Request $request,WalletService $walletService)
    {    
        $soapServer = new \SoapServer(dirname(__DIR__,1).DIRECTORY_SEPARATOR .'Wsdl'. DIRECTORY_SEPARATOR .'wallet.wsdl');
        $soapServer->setObject($walletService);

        $response = new Response();
        $response->headers->set('Content-Type', 'text/xml; charset=ISO-8859-1');

        ob_start();
        $soapServer->handle();
        $response->setContent(ob_get_clean());

        return $response;

    }
}