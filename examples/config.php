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

use Konekt\PayumOtp\OtpOffsiteGatewayFactory;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Payum\Core\Bridge\Psr\Log\LogExecutedActionsExtension;
use Payum\Core\GatewayFactoryInterface;
use Payum\Core\PayumBuilder;
use Payum\Core\Payum;
use Payum\Core\Model\Payment;

$paymentClass = Payment::class;
$gatewayName = 'otp_offsite';

$config = [
    'factory' => 'otp_offsite',

    'payum.api.sdkDir' => '/home/flajos/Documents/work/artkonekt/payum-otp/docs/otp_original/kliensek/php/otpwebshop',
    'payum.api.privateKeyFile' => '/home/flajos/Documents/work/artkonekt/payum-otp/docs/otp_original/sign_tool/#02299991.privKey.pem',

    'payum.api.transactionLogDir' => '/home/flajos/Documents/work/artkonekt/payum-otp/var/logs/transactions',
    'payum.api.transactionLogDir.success' => '/home/flajos/Documents/work/artkonekt/payum-otp/var/logs/transactions/success',
    'payum.api.transactionLogDir.failed' => '/home/flajos/Documents/work/artkonekt/payum-otp/var/logs/transactions/failed',

    'payum.api.log4php.file' => '/home/flajos/Documents/work/artkonekt/payum-otp/var/logs/log4php.log'
];

/** @var Payum $payum */
$payum = (new PayumBuilder())
    ->addDefaultStorages()
    ->addGatewayFactory('otp_offsite', function (array $config, GatewayFactoryInterface $coreGatewayFactory) {
        return new OtpOffsiteGatewayFactory($config, $coreGatewayFactory);
    })
    ->addGateway($gatewayName, $config)
    ->setGenericTokenFactoryPaths([
        'capture' => 'payum-otp/examples/capture.php',
        'notify' => 'payum-otp/examples/notify.php',
        'authorize' => 'payum-otp/examples/authorize.php',
        'refund' => 'payum-otp/examples/refund.php'
    ])
    ->getPayum();

//create a log channel
$logger = new Logger('payum-otp');
$logger->pushHandler(new RotatingFileHandler(__DIR__ . '/../var/logs/actions', Logger::WARNING));

$gateway = $payum->getGateway($gatewayName);
$gateway->addExtension(new LogExecutedActionsExtension($logger));
