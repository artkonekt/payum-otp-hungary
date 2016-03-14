<?php
namespace Konekt\PayumOtp\Action;

use Payum\Core\Action\GatewayAwareAction;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Reply\HttpRedirect;
use Payum\Core\Request\Capture;
use Payum\Core\Exception\RequestNotSupportedException;

class CaptureAction extends GatewayAwareAction
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

        $shopId = '02299991';
        $transactionId = 'alma';

        $otpCaptureUrl = sprintf('https://www.otpbankdirekt.hu/webshop/do/webShopVasarlasInditas?posId=%s&azonosito=%s', $shopId, $transactionId);
        throw new HttpRedirect($otpCaptureUrl);
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
