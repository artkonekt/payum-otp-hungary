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

    public function __construct($config, $sdkService = null)
    {
        $configurator = new Configurator($config);

        $this->suppressLibraryErrors();

        if (!$sdkService) {
            $serviceName = $configurator->getMainServiceFile();
            //There is no autoloading in the SDK, we need to include the class file
            require_once($serviceName);

            //original SDK checksum(md5sum): aae7d5f60a87511a685767f26b8af4ca
            //TODO: relocate this to the documentation
            $this->service = new \WebShopService();
        }

        $this->restoreErrorReporting();
    }

    public function setSandbox()
    {

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

    public function getTransactionStatus($posId,
                                    $azonosito,
                                    $maxRekordSzam,
                                    $idoszakEleje,
                                    $idoszakVege)
    {
        return $this->service->tranzakcioStatuszLekerdezes($posId,
            $azonosito,
            $maxRekordSzam,
            $idoszakEleje,
            $idoszakVege);
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