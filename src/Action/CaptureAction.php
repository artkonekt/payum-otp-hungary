<?php
namespace Konekt\PayumOtp\Action;

use Konekt\PayumOtp\Bridge\OtpSdk4\WebShopService;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Reply\HttpRedirect;
use Payum\Core\Request\Capture;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\GetHttpRequest;
use Payum\Core\Request\GetHumanStatus;
use RequestUtils;

class CaptureAction extends BaseApiAwareAction
{
    /**
     * {@inheritDoc}
     *
     * @param Capture $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        //$this->gateway->execute($status = new GetHumanStatus);

        //we are back from otp site so we have to just update model.
        //if (isset($status->isPending())) {
        //    //$model->replace($httpRequest->query);
        //} else {

            $shopId = '#02299991';
            $transId = uniqid('POTPTEST');
            $backUrl = $request->getToken()->getTargetUrl();

            $response = $this->api->capture(
                $shopId,
                $transId,
                69,
                "HUF",
                "hu",
                RequestUtils::safeParam($_REQUEST, 'nevKell'),
                RequestUtils::safeParam($_REQUEST, 'orszagKell'),
                RequestUtils::safeParam($_REQUEST, 'megyeKell'),
                RequestUtils::safeParam($_REQUEST, 'telepulesKell'),
                RequestUtils::safeParam($_REQUEST, 'iranyitoszamKell'),
                RequestUtils::safeParam($_REQUEST, 'utcaHazszamKell'),
                RequestUtils::safeParam($_REQUEST, 'mailCimKell'),
                RequestUtils::safeParam($_REQUEST, 'kozlemenyKell'),
                RequestUtils::safeParam($_REQUEST, 'vevoVisszaigazolasKell'),
                RequestUtils::safeParam($_REQUEST, 'ugyfelRegisztracioKell'),
                RequestUtils::safeParam($_REQUEST, 'regisztraltUgyfelId'),
                "Valami megjegyzes",
                $backUrl,
                RequestUtils::safeParam($_REQUEST, 'zsebAzonosito'),
                RequestUtils::safeParam($_REQUEST, "ketlepcsosFizetes")
            );

            //if ($response->isSuccessful()) {
            //    $model;
            //    $url = sprintf('https://www.otpbankdirekt.hu/webshop/do/webShopVasarlasInditas?posId=%s&azonosito=%s', urlencode($shopId), urlencode($transId));
            //    throw new HttpRedirect($url);
            //}
        //}



        //$this->testPing();

        //$this->testTransactionIdGeneration();

        //$this->testCapture($request);

        //$this->testRetrieveStatus();
    }

    private function testPing()
    {
        $result = $this->api->ping();

        var_dump($result);
    }

    private function testTransactionIdGeneration()
    {
        $shopId = '#02299991';

        $result = $this->api->generateTransactionId($shopId);

        var_dump($result);
    }

    private function testRetrieveStatus()
    {
        $shopId = '#02299991';
        $transId ='POTPTEST56e8468eef2f7';

        $response = $this->api->getTransactionStatus(
            $shopId,
            $transId,
            1,
            time() - (12 * 60 * 60),
            time() + (12 * 60 * 60)
        );

        var_dump($response);
    }
    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Capture &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}
