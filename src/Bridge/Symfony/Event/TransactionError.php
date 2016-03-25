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
 * Class TransactionError
 */
class TransactionError extends Event
{
    /**
     * @var array
     */
    private $errors;

    /**
     * @var array
     */
    private $details;

    /**
     * TransactionError constructor.
     *
     * @param array $errors The errors array
     * @param array $details All OTP payment details
     */
    public function __construct($errors, $details)
    {
        $this->errors = $errors;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return array
     */
    public function getDetails()
    {
        return $this->details;
    }
}