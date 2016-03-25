<?php
/**
 * Contains class ConvertPaymentToOtpAction
 *
 * @package     Konekt\PayumOtp\Bridge\Sylius
 * @copyright   Copyright (c) 2016 Storm Storez Srl-D
 * @author      Lajos Fazakas
 * @license     MIT
 * @since       2016-03-24
 * @version     2016-03-24
 */

namespace Konekt\PayumOtp\Bridge\Sylius;

use Payum\Core\Action\GatewayAwareAction;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\Convert;
use Sylius\Component\Core\Model\PaymentInterface;

/**
 * Class ConvertPaymentToOtpAction.
 *
 * Deals with converting Sylius models into a format needed by OTP.
 */
class ConvertPaymentToOtpAction extends GatewayAwareAction
{
    /**
     * @var PaymentNoteProviderInterface
     */
    private $paymentNoteProvider;

    /**
     * ConvertPaymentToOtpAction constructor.
     *
     * @param PaymentNoteProviderInterface $paymentNoteProvider
     */
    public function __construct(PaymentNoteProviderInterface $paymentNoteProvider = null)
    {
        $this->paymentNoteProvider = $paymentNoteProvider;
    }
    /**
     * Converts Sylius models into a format needed by OTP.
     *
     * @param mixed $request
     *
     * @throws \Payum\Core\Exception\RequestNotSupportedException if the action dose not support the request.
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        /** @var PaymentInterface $payment */
        $payment = $request->getSource();
        $order = $payment->getOrder();

        $details = [];
        $details['osszeg'] = $order->getTotal() / 100;
        $details['devizanem'] = $payment->getCurrency();
        
        if ($this->paymentNoteProvider) {
            $note = $this->paymentNoteProvider->getNote($payment, $order);
        } else {
            $note = sprintf('Order containing %d items for a total of %01.2f', $order->getItems()->count(), $order->getTotal() / 100);
        }

        $details['shopMegjegyzes'] = $note;

        $request->setResult($details);
    }

    /**
     * {@inheritdoc}
     * 
     * @param mixed $request
     *
     * @return boolean
     */
    public function supports($request)
    {
        return
            $request instanceof Convert &&
            $request->getSource() instanceof PaymentInterface &&
            $request->getTo() === 'array'
        ;
    }
}