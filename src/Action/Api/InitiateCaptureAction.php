<?php

/**
 * Contains class CaptureAction
 *
 * @package     ${NAMESPACE}
 * @copyright   Copyright (c) 2016 Storm Storez Srl-D
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2016-03-21
 * @version     2016-03-21
 */

namespace Konekt\PayumOtp\Action\Api;

use Konekt\PayumOtp\Bridge\OtpSdk4\Util\TransactionIdGenerator;
use Konekt\PayumOtp\Request\Api\Capture;
use Konekt\PayumOtp\Request\Api\InitiateCapture;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Reply\HttpRedirect;
use Payum\Core\Request\GetHumanStatus;
use RequestUtils;

class InitiateCaptureAction extends AbstractApiAwareAction
{
    
    /**
     * @param mixed $request
     *
     * @throws \Konekt\PayumOtp\Action\HttpRedirect
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $details = ArrayObject::ensureArrayObject($request->getModel());

        $transactionIdGenerator = new TransactionIdGenerator();
        $transactionId = $transactionIdGenerator->generate('POTPTEST');

        $response = $this->api->capture(
            $details['shopId'],
            $transactionId,
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
            $request->getBackUrl(),
            RequestUtils::safeParam($_REQUEST, 'zsebAzonosito'),
            RequestUtils::safeParam($_REQUEST, "ketlepcsosFizetes")
        );


        if ($response->isSuccessful()) {

            $details['status'] = GetHumanStatus::STATUS_PENDING;
            $details['captureInstanceId'] = $response->getInstanceId();

            $url = sprintf('https://www.otpbankdirekt.hu/webshop/do/webShopVasarlasInditas?posId=%s&azonosito=%s', urlencode($details['shopId']), urlencode($transactionId));
            throw new HttpRedirect($url);
        }
    }

    /**
     * @param mixed $request
     *
     * @return boolean
     */
    public function supports($request)
    {
        return $request instanceof InitiateCapture;
    }
}