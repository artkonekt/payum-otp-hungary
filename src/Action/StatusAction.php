<?php
namespace Konekt\PayumOtp\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Request\GetHumanStatus;
use Payum\Core\Request\GetStatusInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;

class StatusAction implements ActionInterface
{
    /**
     * Marks the request based on the model's status set in the appropriate actions.
     *
     * @param GetStatusInterface $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        $request->markNew();

        if (isset($model['status'])) {
            switch ($model['status']) {
                case (GetHumanStatus::STATUS_PENDING):
                    $request->markPending();
                    break;
                case (GetHumanStatus::STATUS_CAPTURED):
                    $request->markCaptured();
                    break;
                case (GetHumanStatus::STATUS_FAILED):
                    $request->markFailed();
                    break;
                case (GetHumanStatus::STATUS_CANCELED):
                    $request->markCanceled();
                    break;
                case (GetHumanStatus::STATUS_EXPIRED):
                    $request->markExpired();
                    break;
                default:
                    $request->markUnknown();
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof GetStatusInterface &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}
