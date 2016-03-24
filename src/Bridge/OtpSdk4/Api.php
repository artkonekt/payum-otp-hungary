<?php
/**
 * Contains class Api
 *
 * @package     Konekt\PayumOtp\Bridge\OtpSdk4
 * @copyright   Copyright (c) 2016 Storm Storez Srl-D
 * @author      Lajos Fazakas
 * @license     MIT
 * @since       2016-03-15
 * @version     2016-03-15
 */

namespace Konekt\PayumOtp\Bridge\OtpSdk4;

use RequestUtils;

class Api
{
    private $originalErrorReporting;

    private $service;

    private $configurator;

    public function __construct(Configurator $configurator, $sdkService = null)
    {
        $this->configurator = $configurator;

        $this->suppressLibraryErrors();

        if (!$sdkService) {
            $serviceName = $this->configurator->getMainServiceFile();
            //There is no autoloading in the SDK, we need to include the class file
            require_once($serviceName);

            //original SDK checksum(md5sum): aae7d5f60a87511a685767f26b8af4ca
            //TODO: relocate this to the documentation
            $this->service = new \WebShopService();
        }

        $this->restoreErrorReporting();
    }

    public function generateTransactionId()
    {
        $posId = $this->getPosId();
        return $this->service->tranzakcioAzonositoGeneralas($posId);
    }

    public function capture($details, $backUrl)
    {
        $this->suppressLibraryErrors();

        $result = $this->service->fizetesiTranzakcio(
            $this->getPosId(),
            $details['azonosito'],
            $details['osszeg'],
            $details['devizanem'],
            "hu",
            RequestUtils::safeParam($_REQUEST, 'nevKell'),
            RequestUtils::safeParam($_REQUEST, 'orszagKell'),
            RequestUtils::safeParam($_REQUEST, 'megyeKell'),
            RequestUtils::safeParam($_REQUEST, 'telepulesKell'),
            RequestUtils::safeParam($_REQUEST, 'iranyitoszamKell'),
            RequestUtils::safeParam($_REQUEST, 'utcaHazszamKell'),
            RequestUtils::safeParam($_REQUEST, 'mailCimKell'),
            RequestUtils::safeParam($_REQUEST, 'kozlemenyKell'),
            RequestUtils::safeParam($_REQUEST, 'vevoVisszaigazolasKell'),
            RequestUtils::safeParam($_REQUEST, 'ugyfelRegisztracioKell'),
            RequestUtils::safeParam($_REQUEST, 'regisztraltUgyfelId'),
            $details['shopMegjegyzes'],
            $backUrl,
            RequestUtils::safeParam($_REQUEST, 'zsebAzonosito'),
            RequestUtils::safeParam($_REQUEST, "ketlepcsosFizetes")
        );

        $this->restoreErrorReporting();

        return $result;
    }

    public function getTransactionStatus($azonosito,
                                    $maxRekordSzam,
                                    $idoszakEleje,
                                    $idoszakVege)
    {
        $this->suppressLibraryErrors();

        return $this->service->tranzakcioStatuszLekerdezes($this->configurator->getPosId(),
            $azonosito,
            $maxRekordSzam,
            $idoszakEleje,
            $idoszakVege);

        $this->restoreErrorReporting();
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

    public function getPosId()
    {
        return $this->configurator->getPosId();
    }
}