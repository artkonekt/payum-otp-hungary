<?php
namespace Konekt\PayumOtp\Action;

use Konekt\PayumOtp\Bridge\OtpSdk4\WebShopService;
use Payum\Core\Request\Capture;
use Payum\Core\Exception\RequestNotSupportedException;
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

        //$model = ArrayObject::ensureArrayObject($request->getModel());
        //
        //$shopId = '02299991';
        //$transactionId = 'alma';
        //
        //$otpCaptureUrl = sprintf('https://www.otpbankdirekt.hu/webshop/do/webShopVasarlasInditas?posId=%s&azonosito=%s', $shopId, $transactionId);
        //throw new HttpRedirect($otpCaptureUrl);

        //$this->testPing();

        //$this->testTransactionIdGeneration();

        $this->testCapture();

        die;

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

    private function testCapture()
    {
        $shopId = '#02299991';
        $transId = uniqid('POTPTEST');

        //$url = sprintf('https://www.otpbankdirekt.hu/webshop/do/webShopVasarlasInditas?posId=%s&azonosito=%s', urlencode($shopId), urlencode($transId));
        //
        //ob_start();
        //header("Connection: close");
        //header("Location: " . $url);
        //header("Content-Length: " . ob_get_length());
        //ob_end_flush();
        //flush();

        $response = $this->api->capture(
            $shopId,
            $transId,
            12,
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
            "http://artkonekt-general.lcl/payum-otp/examples/vissza.php",
            RequestUtils::safeParam($_REQUEST, 'zsebAzonosito'),
            RequestUtils::safeParam($_REQUEST, "ketlepcsosFizetes")
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
