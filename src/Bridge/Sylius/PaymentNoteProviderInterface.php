<?php
/**
 * Contains interface PaymentNoteProvider
 *
 * @package     Konekt\PayumOtp\Bridge\Sylius
 * @copyright   Copyright (c) 2016 Storm Storez Srl-D
 * @author      Lajos Fazakas
 * @license     MIT
 * @since       2016-03-24
 * @version     2016-03-24
 */

namespace Konekt\PayumOtp\Bridge\Sylius;


use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;

interface PaymentNoteProviderInterface
{
    /**
     * @param PaymentInterface $payment
     * @param OrderInterface   $order
     *
     * @return string
     */
    public function getNote(PaymentInterface $payment, OrderInterface $order);
}