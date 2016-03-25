<?php
/**
 * Contains class Capture
 *
 * @package     Konekt\PayumOtp\Request\Api
 * @copyright   Copyright (c) 2016 Storm Storez Srl-D
 * @author      Lajos Fazakas
 * @license     MIT
 * @since       2016-03-21
 * @version     2016-03-21
 */

namespace Konekt\PayumOtp\Request\Api;

use Payum\Core\Request\Generic;

/**
 * Request class for capture initialization.
 */
class InitiateCapture extends Generic
{
    /**
     * @var string
     */
    private $backUrl;

    /**
     * InitiateCapture constructor.
     *
     * @param mixed  $model   The payment model
     * @param string $backUrl The URL where OTP should redirect after the client did the payment on the OTP site.
     */
    public function __construct($model, $backUrl)
    {
        $this->backUrl = $backUrl;
        parent::__construct($model);
    }

    /**
     * Returns the URL where OTP should redirect after the client did the payment on the OTP site.
     * 
     * @return mixed
     */
    public function getBackUrl()
    {
        return $this->backUrl;
    }
}