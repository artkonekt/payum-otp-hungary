<?php
/**
 * Contains class TransactionError
 *
 * @package     Konekt\PayumOtp\Bridge\Symfony\Event
 * @copyright   Copyright (c) 2016 Storm Storez Srl-D
 * @author      Lajos Fazakas
 * @license     MIT
 * @since       2016-03-25
 * @version     2016-03-25
 */

namespace Konekt\PayumOtp\Bridge\Symfony\Event;


use Symfony\Component\EventDispatcher\Event;

/**
 * Event representing an OTP transaction error.
 */
class TransactionError extends Event
{
    /**
     * @var array
     */
    private $details;

    /**
     * TransactionError constructor.
     *
     * @param array $details All OTP payment details
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Returns all details of the transaction with error.
     * 
     * @return array
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Returns an array with the errors.
     *
     * @return array
     */
    public function getErrors()
    {
        if (isset($this->details['errors'])) {
            return $this->details['errors'];
        }
    }
}