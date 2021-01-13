<?php
/**
 * Contains class Api
 *
 * @copyright   Copyright (c) 2016 Storm Storez Srl-D
 * @author      Lajos Fazakas
 * @license     MIT
 * @since       2016-03-15
 * @version     2021-01-13
 */

namespace Konekt\PayumOtp\Bridge\OtpSdk4;

use Konekt\PayumOtp\Bridge\OtpSdk4\Util\TransactionIdGenerator;
use RequestUtils;
use WResponse;

/**
 * The class is a wrapper for the SDK's service. It hides the SDK's method calls and also
 * does some error suppressing  functionality. It suppresses the notices issued by the
 * SDK and after the call it restores the original error reporting. The SDK sets up
 * error reporting, but for Payum integration we want to use our variant instead
 */
class Api
{
    /**
     * @var int
     */
    private $originalErrorReporting;

    /**
     * @var \WebShopService
     */
    private $service;

    /**
     * @var Configurator
     */
    private $configurator;

    /**
     * Api constructor.
     *
     * @param \Konekt\PayumOtp\Bridge\OtpSdk4\Configurator $configurator
     */
    public function __construct(Configurator $configurator)
    {
        $this->configurator = $configurator;

        $serviceName = $this->configurator->getMainServiceFile();
        //There is no autoloading in the SDK, we need to include the class file
        require_once($serviceName);

        //TODO: relocate this to the documentation
        $this->suppressLibraryErrors();
        $this->service = new \WebShopService();
        $this->restoreErrorReporting();
    }

    /**
     * Generates the unique transaction ID for the capture.
     *
     * Based on the configuration it can use our own transaction ID generator (recommended), or ask for a transaction
     * identifier from OTP (requires a call to OTP).
     *
     * @return WResponse
     */
    public function generateTransactionId()
    {
        if ($this->configurator->useOwnTransactionId()) {
            //TODO: this should be injected instead
            $transactionIdGenerator = new TransactionIdGenerator();
            $transactionId = $transactionIdGenerator->generate($this->configurator->getTransactionIdPrefix()); //THIS SHOULD BE CONFIGURABLE
        } else {
            $posId = $this->getPosId();

            $this->suppressLibraryErrors();
            $transactionId = $this->service->tranzakcioAzonositoGeneralas($posId);
            $this->restoreErrorReporting();
        }

        return $transactionId;
    }

    /**
     * Initiates the capture of a payment.
     *
     * TODO: review (the source of the parameters is different, is this OK?)
     *
     * @param array $details
     * @param $backUrl
     *
     * @return WResponse
     */
    public function initiateCapture($details, $backUrl)
    {
        $this->suppressLibraryErrors();

        $result = $this->service->fizetesiTranzakcio(
            $this->getPosId(),
            RequestUtils::safeParam($details, 'azonosito'),
            RequestUtils::safeParam($details, 'osszeg'),
            RequestUtils::safeParam($details, 'devizanem'),
            "hu",
            RequestUtils::safeParam($details, 'nevKell'),
            RequestUtils::safeParam($details, 'orszagKell'),
            RequestUtils::safeParam($details, 'megyeKell'),
            RequestUtils::safeParam($details, 'telepulesKell'),
            RequestUtils::safeParam($details, 'iranyitoszamKell'),
            RequestUtils::safeParam($details, 'utcaHazszamKell'),
            RequestUtils::safeParam($details, 'mailCimKell'),
            RequestUtils::safeParam($details, 'kozlemenyKell'),
            RequestUtils::safeParam($details, 'vevoVisszaigazolasKell'),
            RequestUtils::safeParam($details, 'ugyfelRegisztracioKell'),
            RequestUtils::safeParam($details, 'regisztraltUgyfelId'),
            RequestUtils::safeParam($details, 'shopMegjegyzes'),
            $backUrl,
            RequestUtils::safeParam($_REQUEST, 'zsebAzonosito'),
            RequestUtils::safeParam($_REQUEST, "ketlepcsosFizetes")
        );

        $this->restoreErrorReporting();

        return $result;
    }

    /**
     * Returns the status of a transaction.
     *
     * @param string $transactionId
     *
     * @return WResponse
     */
    public function getTransactionStatus($transactionId)
    {
        $this->suppressLibraryErrors();

        return $this->service->tranzakcioStatuszLekerdezes(
            $this->configurator->getPosId(),
            $transactionId,
            1,
            time() - 60*60*24,
            time() + 60*60*24
        );

        $this->restoreErrorReporting();
    }

    /**
     * Sets the error reporting to a low level. It should be used before a call to the SDK to get rid of warnings and
     * notices issued by the shitty code.
     */
    private function suppressLibraryErrors()
    {
        $this->originalErrorReporting = ini_get('error_reporting');
        error_reporting(E_ERROR | E_PARSE);
    }

    /**
     * Restores the original error reporting level. It should be used after a call to the SDK.
     */
    private function restoreErrorReporting()
    {
        error_reporting($this->originalErrorReporting);
    }

    /**
     * Returns the pos_id (aka. SHOP ID)
     * @return string
     */
    public function getPosId()
    {
        return $this->configurator->getPosId();
    }

    /** @return bool */
    public function runningInSandboxMode()
    {
        return $this->configurator->isSandbox();
    }
}
