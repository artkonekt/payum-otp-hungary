<?php
namespace Konekt\PayumOtp\Action;

use Konekt\PayumOtp\Bridge\OtpSdk4\WebShopService;
use Payum\Core\Request\Capture;
use Payum\Core\Exception\RequestNotSupportedException;

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

        $this->testTransactionIdGeneration();

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
