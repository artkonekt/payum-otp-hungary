<?php
/**
 * Contains class CheckTransactionStatus
 *
 * @package     Konekt\PayumOtp\Action\Api
 * @copyright   Copyright (c) 2016 Storm Storez Srl-D
 * @author      Lajos Fazakas
 * @license     MIT
 * @since       2016-03-21
 * @version     2016-03-21
 */

namespace Konekt\PayumOtp\Action\Api;


use Konekt\PayumOtp\Request\Api\FetchCaptureResult;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\GetHumanStatus;

class FetchCaptureResultAction extends AbstractApiAwareAction
{
    /**
     * {@inheritDoc}
     *
     * @param mixed $request
     *
     * @throws \Payum\Core\Exception\RequestNotSupportedException if the action dose not support the request.
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $details = ArrayObject::ensureArrayObject($request->getModel());

        $response = $this->api->getTransactionStatus($details['azonosito']);

        if ($response) {
            $details['fetchInstanceId'] = $response->getInstanceId();
            
            $answer = $response->getAnswer();
            if ($response->isSuccessful() && $response->getAnswer() && count($answer->getWebShopFizetesAdatok()) > 0) {
                $fizetesAdatok = $answer->getWebShopFizetesAdatok();
                $tranzAdatok = current($fizetesAdatok);

                $responseCode = $tranzAdatok->getPosValaszkod();
                if ($tranzAdatok->isSuccessful()) {
                    $details['status'] = GetHumanStatus::STATUS_CAPTURED;
                    $details['authorizationCode'] = $tranzAdatok->getAuthorizaciosKod();
                } else if ("VISSZAUTASITOTTFIZETES" == $responseCode) {
                    $details['status'] = GetHumanStatus::STATUS_CANCELED; //TODO??
                } else if ("FIZETESTIMEOUT" == $responseCode) {
                    $details['status'] = GetHumanStatus::STATUS_EXPIRED;
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

    /**
     * {@inheritDoc}
     * 
     * @param mixed $request
     *
     * @return boolean
     */
    public function supports($request)
    {
        return $request instanceof FetchCaptureResult;
    }
}