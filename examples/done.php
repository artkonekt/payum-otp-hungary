<?php
/**
 * Contains file done.php
 *
 * @copyright   Copyright (c) 2016 Storm Storez Srl-D
 * @author      Lajos Fazakas
 * @license     MIT
 * @since       2016-03-14
 * @version     2016-03-14
 */


use Payum\Core\Request\GetHumanStatus;

include 'config.php';

$token = $payum->getHttpRequestVerifier()->verify($_REQUEST);
$gateway = $payum->getGateway($token->getGatewayName());

// you can invalidate the token. The url could not be requested any more.
// $payum->getHttpRequestVerifier()->invalidate($token);

// Once you have token you can get the model from the storage directly.
//$identity = $token->getDetails();
//$payment = $payum->getStorage($identity->getClass())->find($identity);

// or Payum can fetch the model for you while executing a request (Preferred).
$gateway->execute($status = new GetHumanStatus($token));

if ($status->isCaptured()) {
    echo 'CAPTURED';
} elseif ($status->isFailed()) {
    echo 'FAILED';
} elseif ($status->isCanceled()) {
    echo 'CANCELLED';
}

var_dump($status);