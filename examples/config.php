<?php

include('../vendor/autoload.php');

use Payum\Core\PayumBuilder;
use Payum\Core\Payum;
use Payum\Core\Model\Payment;

$paymentClass = Payment::class;

/** @var Payum $payum */
$payum = (new PayumBuilder())
    ->addDefaultStorages()
    ->addGateway('otp_offsite', [
        'factory' => 'otp_offsite'
    ])
    ->getPayum()
;

var_dump($payum);