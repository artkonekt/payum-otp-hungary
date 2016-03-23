<?php
/**
 * Contains class BaseApiAwareAction
 *
 * @package     Konekt\PayumOtp\Action
 * @copyright   Copyright (c) 2016 Storm Storez Srl-D
 * @author      Lajos Fazakas
 * @license     MIT
 * @since       2016-03-21
 * @version     2016-03-21
 */

namespace Konekt\PayumOtp\Action\Api;

use Konekt\PayumOtp\Bridge\OtpSdk4\Api;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\Exception\UnsupportedApiException;

abstract class AbstractApiAwareAction implements ActionInterface, ApiAwareInterface
{
    protected $api;

    /**
     * {@inheritDoc}
     */
    public function setApi($api)
    {
        if (false == $api instanceof Api) {
            throw new UnsupportedApiException('Not supported. Expected \Konekt\PayumOtp\Bridge\OtpSdk4\Api instance to be set as api.');
        }

        $this->api = $api;
    }
}