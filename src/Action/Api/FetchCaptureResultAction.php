<?php
/**
 * Contains class CheckTransactionStatus
 *
 * @package     Konekt\PayumOtp\Action\Api
 * @copyright   Copyright (c) 2016 Storm Storez Srl-D
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
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
     * @param mixed $request
     *
     * @throws \Payum\Core\Exception\RequestNotSupportedException if the action dose not support the request.
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

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

    /**
     * @param mixed $request
     *
     * @return boolean
     */
    public function supports($request)
    {
        return $request instanceof FetchCaptureResult;
    }
}