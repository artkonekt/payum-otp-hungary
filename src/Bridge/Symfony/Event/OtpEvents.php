<?php
/**
 * Contains class OtpEvents
 *
 * @package     Konekt\PayumOtp\Bridge\Symfony\Event
 * @copyright   Copyright (c) 2016 Storm Storez Srl-D
 * @author      Lajos Fazakas
 * @license     MIT
 * @since       2016-03-25
 * @version     2016-03-25
 */

namespace Konekt\PayumOtp\Bridge\Symfony\Event;

/**
 * Class OtpEvents
 */
final class OtpEvents
{
    /**
     * An OTP transaction error.
     */
    const TRANSACTION_ERROR = 'payum.otp.transaction_error';
}