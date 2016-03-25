<?php
/**
 * Contains class ErrorNotifierExtension
 *
 * @package     Konekt\PayumOtp\Bridge\Symfony
 * @copyright   Copyright (c) 2016 Storm Storez Srl-D
 * @author      Lajos Fazakas
 * @license     MIT
 * @since       2016-03-25
 * @version     2016-03-25
 */

namespace Konekt\PayumOtp\Bridge\Symfony;


use Konekt\PayumOtp\Bridge\Symfony\Event\OtpEvents;
use Konekt\PayumOtp\Bridge\Symfony\Event\TransactionError;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Extension\Context;
use Payum\Core\Extension\ExtensionInterface;
use Payum\Core\Model\ModelAwareInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ErrorNotifierExtension implements ExtensionInterface
{
    private $errors;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * ErrorNotifierExtension constructor.
     *
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @var Context $context
     */
    public function onPreExecute(Context $context)
    {
        return;
    }

    /**
     * @var Context $context
     */
    public function onExecute(Context $context)
    {
        return;
    }

    /**
     * @var Context $context
     */
    public function onPostExecute(Context $context)
    {
        $request = $context->getRequest();
        if (!$request instanceof ModelAwareInterface) {
            return;
        }

        $model = $request->getModel();
        $details = ArrayObject::ensureArrayObject($model);

        if (isset($details['errors'])) {
            $this->errors = $details['errors'];
        }

        if ($this->errors && !$context->getPrevious()) {
            $this->eventDispatcher->dispatch(OtpEvents::TRANSACTION_ERROR, new TransactionError($this->errors, (array) $details));
        }
    }
}