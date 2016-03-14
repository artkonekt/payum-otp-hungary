<?php

/**
 * Contains file config.php
 *
 * @copyright   Copyright (c) 2016 Storm Storez Srl-D
 * @author      Lajos Fazakas
 * @license     MIT
 * @since       2016-03-14
 * @version     2016-03-14
 */

include('../vendor/autoload.php');

use Monolog\Handler\FilterHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Payum\Core\GatewayFactoryInterface;
use Payum\Core\PayumBuilder;
use Payum\Core\Payum;
use Payum\Core\Model\Payment;

$paymentClass = Payment::class;
$gatewayName = 'otp_offsite';

/** @var Payum $payum */
$payum = (new PayumBuilder())
    ->addDefaultStorages()
    ->addGatewayFactory('otp_offsite', function(array $config, GatewayFactoryInterface $coreGatewayFactory) {
        return new \Konekt\PayumOtp\OtpOffsiteGatewayFactory($config, $coreGatewayFactory);
    })
    ->addGateway('otp_offsite', [
        'factory' => 'otp_offsite'
    ])
    ->setGenericTokenFactoryPaths([
        'capture' => 'payum-otp/examples/capture.php',
        'notify' => 'payum-otp/examples/notify.php',
        'authorize' => 'payum-otp/examples/authorize.php',
        'refund' => 'payum-otp/examples/refund.php'
    ])
    ->getPayum();

// create a log channel
$logger = new Logger('payum-otp');
$logger->pushHandler(new RotatingFileHandler(__DIR__ . '/../var/logs/actions', Logger::WARNING));

$gateway = $payum->getGateway($gatewayName);
$gateway->addExtension(new \Payum\Core\Bridge\Psr\Log\LogExecutedActionsExtension($logger));
