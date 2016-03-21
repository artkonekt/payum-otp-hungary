<?php
namespace Konekt\PayumOtp\Action;

use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Reply\HttpRedirect;
use Payum\Core\Request\Capture;
use Payum\Core\Exception\RequestNotSupportedException;
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

        $details = ArrayObject::ensureArrayObject($request->getModel());
        $this->gateway->execute($status = new GetHumanStatus($details));

        if ($status->isNew()) {
            $this->capture($request);
        } elseif ($status->isPending()) { //TODO: is this correct, or we have to introduce a new status?
            //we are back from otp site so we have to just update model.
            $this->runStatusCheck($request);
        }
    }

    private function capture(Capture $request)
    {
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
            $request->setModel($details);

            $url = sprintf('https://www.otpbankdirekt.hu/webshop/do/webShopVasarlasInditas?posId=%s&azonosito=%s', urlencode($shopId), urlencode($transId));
            throw new HttpRedirect($url);
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

    private function runStatusCheck(Capture $request)
    {
        $details = ArrayObject::ensureArrayObject($request->getModel());

        $response = $this->api->getTransactionStatus(
            $details['shopId'],
            $details['transId'],
            1,
            time() - (12 * 60 * 60),
            time() + (12 * 60 * 60)
        );

        if ($response) {
            $answer = $response->getAnswer();
            if ($response->isSuccessful() && $response->getAnswer() && count($answer->getWebShopFizetesAdatok()) > 0) {
                $fizetesAdatok = $answer->getWebShopFizetesAdatok();
                $tranzAdatok = current($fizetesAdatok);

                $responseCode = $tranzAdatok->getPosValaszkod();
                if ($tranzAdatok->isSuccessful()) {
                    $details['status'] = GetHumanStatus::STATUS_CAPTURED;
                } else if ("VISSZAUTASITOTTFIZETES" == $responseCode) {
                    $details['status'] = GetHumanStatus::STATUS_CANCELED; //TODO??
                } else {
                    $details['status'] = GetHumanStatus::STATUS_FAILED; //TODO??
                }
            } else {
                //Ha nincs valasz az OTP-tol
                $details['status'] = GetHumanStatus::STATUS_FAILED; //TODO??
            }
        }

        $request->setModel($details);
    }
}
