<?php
/**
 * Contains class PingAction
 *
 * @package     Konekt\PayumOtp\Action
 * @copyright   Copyright (c) 2016 Storm Storez Srl-D
 * @author      Lajos Fazakas
 * @license     MIT
 * @since       2016-03-15
 * @version     2016-03-15
 */

namespace Konekt\PayumOtp\Action;


use Payum\Core\Action\GatewayAwareAction;
use Payum\Core\Request\Generic;

class PingAction extends GatewayAwareAction
{

    /**
     * @param mixed $request
     *
     * @throws \Payum\Core\Exception\RequestNotSupportedException if the action dose not support the request.
     */
    public function execute($request)
    {
        // TODO: Implement execute() method.
    }

    /**
     * @param mixed $request
     *
     * @return boolean
     */
    public function supports($request)
    {
        return $request instanceof Generic;
    }
}