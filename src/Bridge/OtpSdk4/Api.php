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
    private $originalErrorReporting;

    private $service;

    public function __construct($config)
    {
        if (PHP_MAJOR_VERSION > 5) {
            require_once('polyfill70.php');
        }

        $configurator = new Configurator($config);
        $serviceName = $configurator->getMainServiceFile();

        $this->suppressLibraryErrors();

        require_once($serviceName);
        $this->service = new \WebShopService();

        $this->restoreErrorReporting();
    }

    public function generateTransactionId($shopId)
    {
        return $this->service->tranzakcioAzonositoGeneralas($shopId);
    }

    public function capture($posId,
                            $azonosito,
                            $osszeg,
                            $devizanem,
                            $nyelvkod,
                            $nevKell,
                            $orszagKell,
                            $megyeKell,
                            $telepulesKell,
                            $iranyitoszamKell,
                            $utcaHazszamKell,
                            $mailCimKell,
                            $kozlemenyKell,
                            $vevoVisszaigazolasKell,
                            $ugyfelRegisztracioKell,
                            $regisztraltUgyfelId,
                            $shopMegjegyzes,
                            $backURL,
                            $zsebAzonosito,
                            $ketlepcsosFizetes = NULL)
    {
        $this->suppressLibraryErrors();

        $result = $this->service->fizetesiTranzakcio($posId,
            $azonosito,
            $osszeg,
            $devizanem,
            $nyelvkod,
            $nevKell,
            $orszagKell,
            $megyeKell,
            $telepulesKell,
            $iranyitoszamKell,
            $utcaHazszamKell,
            $mailCimKell,
            $kozlemenyKell,
            $vevoVisszaigazolasKell,
            $ugyfelRegisztracioKell,
            $regisztraltUgyfelId,
            $shopMegjegyzes,
            $backURL,
            $zsebAzonosito);

        $this->restoreErrorReporting();

        return $result;
    }

    private function suppressLibraryErrors()
    {
        $this->originalErrorReporting = ini_get('error_reporting');
        error_reporting(E_ERROR | E_PARSE);
    }

    private function restoreErrorReporting()
    {
        error_reporting($this->originalErrorReporting);
    }
}