<?php
/**
 * Contains class Capture
 *
 * @package     Konekt\PayumOtp\Request\Api
 * @copyright   Copyright (c) 2016 Storm Storez Srl-D
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2016-03-21
 * @version     2016-03-21
 */

namespace Konekt\PayumOtp\Request\Api;

use Payum\Core\Model\Token;
use Payum\Core\Request\Generic;

class InitiateCapture extends Generic
{
    /**
     * InitiateCapture constructor.
     *
     * @param mixed $model
     */
    public function __construct($model)
    {
        if (!$model instanceof Token) {
            throw new \InvalidArgumentException('A token object is expected');
        }

        parent::__construct($model);
    }

}