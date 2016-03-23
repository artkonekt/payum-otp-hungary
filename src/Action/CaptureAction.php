<?php
namespace Konekt\PayumOtp\Action;

use Konekt\PayumOtp\Action\Api\AbstractApiAwareAction;
use Konekt\PayumOtp\Request\Api\FetchCaptureResult;
use Konekt\PayumOtp\Request\Api\InitiateCapture;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\Capture;
use Payum\Core\Request\GetHumanStatus;

class CaptureAction extends AbstractApiAwareAction
{
    /**
     * {@inheritDoc}
     *
     * @param Capture $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $this->gateway->execute($status = new GetHumanStatus($request->getModel()));

        if ($status->isNew()) {
            $initiateCaptureRequest = new InitiateCapture($request->getModel(), $request->getToken()->getTargetUrl());
            $this->gateway->execute($initiateCaptureRequest);
        } elseif ($status->isPending()) { //TODO: is this correct, or we have to introduce a new status?
            //we are back from otp site so we have to fetch the transaction's status.
            $this->gateway->execute(new FetchCaptureResult($request->getModel()));
        }
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Capture &&
            $request->getModel() instanceof \ArrayAccess;
    }
}
