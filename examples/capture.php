<?php

/**
 * Contains file capture.php
 *
 * @copyright   Copyright (c) 2016 Storm Storez Srl-D
 * @author      Lajos Fazakas
 * @license     MIT
 * @since       2016-03-14
 * @version     2016-03-14
 */

use Payum\Core\Reply\HttpRedirect;
use Payum\Core\Request\Capture;

include 'config.php';

$requestVerifier = $payum->getHttpRequestVerifier();

$token = $requestVerifier->verify($_REQUEST);

$gatewayName = $token->getGatewayName();
$gateway = $payum->getGateway($gatewayName);

if ($reply = $gateway->execute(new Capture($token), true)) {
    if ($reply instanceof HttpRedirect) {
        header("Location: ".$reply->getUrl());
        die();
    }

    throw new \LogicException('Unsupported reply', null, $reply);
}

$payum->getHttpRequestVerifier()->invalidate($token);

header("Location: ".$token->getAfterUrl());