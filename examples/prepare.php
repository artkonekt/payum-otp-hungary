<?php
/**
 * Contains file prepare.php
 *
 * @copyright   Copyright (c) 2016 Storm Storez Srl-D
 * @author      Lajos Fazakas
 * @license     MIT
 * @since       2016-03-14
 * @version     2016-03-14
 */

require 'config.php';

$storage = $payum->getStorage($paymentClass);

$payment = $storage->create();

$payment->setCurrencyCode('HUF');
$payment->setTotalAmount(543);
$payment->setDescription('Hat ez egy uj fizetes lesz, gyurikam');
$payment->setClientId('2345');
$payment->setClientEmail('mikulas@beles.com');

$storage->update($payment);

$captureToken = $payum->getTokenFactory()->createCaptureToken($gatewayName, $payment, $basePrefixPath . '/examples/done.php');

var_dump($captureToken);

header("Location: ".$captureToken->getTargetUrl());