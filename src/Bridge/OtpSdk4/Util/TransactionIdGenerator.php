<?php
/**
 * Contains class TransactionIdGenerator
 *
 * @package     Konekt\PayumOtp\Bridge\OtpSdk4\Util
 * @copyright   Copyright (c) 2016 Storm Storez Srl-D
 * @author      Lajos Fazakas
 * @license     MIT
 * @since       2016-03-22
 * @version     2016-03-22
 */

namespace Konekt\PayumOtp\Bridge\OtpSdk4\Util;


use Payum\Core\Exception\InvalidArgumentException;

/**
 * Class TransactionIdGenerator dealing with the generation of a unique transaction ID for a capture.
 */
class TransactionIdGenerator
{
    /**
     * Generates a unique ID with the specified prefix for an OTP transaction.
     *
     * @param $prefix
     *
     * @return mixed
     */
    public function generate($prefix)
    {
        self::assertPrefixValid($prefix);

        $transId = uniqid($prefix, true);

        return str_replace('.', '', $transId);
    }

    /**
     * Throws exception in case of invalid prefix.
     *
     * @param $prefix
     */
    public static function assertPrefixValid($prefix)
    {
        if (strlen($prefix) > 10) {
            throw new InvalidArgumentException(sprintf('The prefix (%s) must have a max length of 10.', $prefix));
        }

        if (!preg_match('/^[A-Za-z0-9]*$/', $prefix)) {
            throw new InvalidArgumentException(sprintf('The transaction prefix (%s) must be alphanumeric.', $prefix));
        }
    }
}