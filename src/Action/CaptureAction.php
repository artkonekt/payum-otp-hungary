<?php
namespace Konekt\PayumOtp\Action;

use Konekt\PayumOtp\Request\FetchCaptureResult;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Reply\HttpRedirect;
use Payum\Core\Request\Capture;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\GetHumanStatus;
use RequestUtils;

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

            $details = ArrayObject::ensureArrayObject($request->getModel());

            $shopId = '#02299991';
            $transId = uniqid('POTPTEST');

            $details['shopId'] = $shopId;
            $details['transId'] = $transId;

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


            if ($response->isSuccessful()) {
                $details['status'] = GetHumanStatus::STATUS_PENDING;
                $details['captureInstanceId'] = $response->getInstanceId();
                
                $url = sprintf('https://www.otpbankdirekt.hu/webshop/do/webShopVasarlasInditas?posId=%s&azonosito=%s', urlencode($details['shopId']), urlencode($details['transId']));
                throw new HttpRedirect($url);
            }
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
