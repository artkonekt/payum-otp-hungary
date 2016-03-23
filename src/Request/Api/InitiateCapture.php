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

use Payum\Core\Model\Token;
use Payum\Core\Request\Generic;

class InitiateCapture extends Generic
{
    private $backUrl;

    public function __construct($model, $backUrl)
    {
        $this->backUrl = $backUrl;
        parent::__construct($model);
    }

    /**
     * @return mixed
     */
    public function getBackUrl()
    {
        return $this->backUrl;
    }
}