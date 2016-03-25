<?php

/**
 * Contains class CaptureAction
 *
 * @package     ${NAMESPACE}
 * @copyright   Copyright (c) 2016 Storm Storez Srl-D
 * @author      Lajos Fazakas
 * @license     MIT
 * @since       2016-03-21
 * @version     2016-03-21
 */

namespace Konekt\PayumOtp\Action\Api;

use Konekt\PayumOtp\Request\Api\Capture;
use Konekt\PayumOtp\Request\Api\InitiateCapture;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Reply\HttpRedirect;
use Payum\Core\Request\GetHumanStatus;

class InitiateCaptureAction extends AbstractApiAwareAction
{
    /**
     * {@inheritDoc}
     *
     * @param mixed $request
     *
     * @throws \Konekt\PayumOtp\Action\HttpRedirect
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $details = ArrayObject::ensureArrayObject($request->getModel());

        $details['azonosito'] = $this->api->generateTransactionId();

        $response = $this->api->initiateCapture($details, $request->getBackUrl());

        if ($response->isSuccessful()) {

            $details['status'] = GetHumanStatus::STATUS_PENDING;
            $details['captureInstanceId'] = $response->getInstanceId();

            $url = sprintf('https://www.otpbankdirekt.hu/webshop/do/webShopVasarlasInditas?posId=%s&azonosito=%s', urlencode($this->api->getPosId()), urlencode($details['azonosito']));
            throw new HttpRedirect($url);
        } else {
            //TOREVIEW
            $details['status'] = GetHumanStatus::STATUS_FAILED;
            $details['errors'] = $response->getErrors();
        }
    }

    /**
     * {@inheritDoc}
     *
     * @param mixed $request
     *
     * @return boolean
     */
    public function supports($request)
    {
        return $request instanceof InitiateCapture;
    }
}