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
include('params.php');

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

$config = array_merge(
    ['factory' => 'otp_offsite'],
    $params
);

/** @var Payum $payum */
$payum = (new PayumBuilder())
    ->addDefaultStorages()
    ->addGatewayFactory('otp_offsite', function (array $config, GatewayFactoryInterface $coreGatewayFactory) {
        return new OtpOffsiteGatewayFactory($config, $coreGatewayFactory);
    })
    ->addGateway($gatewayName, $config)
    ->setGenericTokenFactoryPaths([
        'capture' => $basePrefixPath . '/examples/capture.php',
        'notify' => $basePrefixPath . '/examples/notify.php',
        'authorize' => $basePrefixPath . '/examples/authorize.php',
        'refund' => $basePrefixPath . '/examples/refund.php'
    ])
    ->getPayum();

//create a log channel
$logger = new Logger('payum-otp');
$logger->pushHandler(new RotatingFileHandler(__DIR__ . '/../var/logs/payum-actions/actions', Logger::WARNING));

$gateway = $payum->getGateway($gatewayName);
$gateway->addExtension(new LogExecutedActionsExtension($logger));
