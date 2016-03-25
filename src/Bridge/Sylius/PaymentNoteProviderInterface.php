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

/**
 * Interface PaymentNoteProviderInterface. You can implement it in your application to be able to override the the note
 * (shopMegjegyzes) sent to OTP capture with a transaction based on the Sylius models. The implemented service should be
 * injected into the ConvertPaymentToOtpAction.
 */
interface PaymentNoteProviderInterface
{
    /**
     * Returns the note (shopMegjegyzes) sent to OTP capture with a transaction.
     *
     * @param PaymentInterface $payment
     * @param OrderInterface   $order
     *
     * @return string
     */
    public function getNote(PaymentInterface $payment, OrderInterface $order);
}