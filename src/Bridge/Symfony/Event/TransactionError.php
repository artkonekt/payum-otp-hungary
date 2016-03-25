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
     * TransactionError constructor.
     *
     * @param array $errors
     */
    public function __construct($errors)
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
}