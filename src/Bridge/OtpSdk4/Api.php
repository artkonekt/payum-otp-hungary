<?php
/**
 * Contains class Api
 *
 * @package     Konekt\PayumOtp\Bridge\OtpSdk4
 * @copyright   Copyright (c) 2016 Storm Storez Srl-D
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2016-03-15
 * @version     2016-03-15
 */

namespace Konekt\PayumOtp\Bridge\OtpSdk4;

class Api
{
    private $service;

    public function __construct($config)
    {
        $configurator = new Configurator($config);
        $serviceName = $configurator->getMainServiceFile();

        $originalErrorReporting = ini_get('error_reporting');

        require_once($serviceName);
        $this->service = new \WebShopService();

        ini_set('error_reporting', $originalErrorReporting);
    }

    public function generateTransactionId($shopId)
    {
        return $this->service->tranzakcioAzonositoGeneralas($shopId);
    }
}