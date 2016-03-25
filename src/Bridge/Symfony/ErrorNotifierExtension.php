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

/**
 * Extension which dispatches a Symfony event in case of a transaction error.
 */
class ErrorNotifierExtension implements ExtensionInterface
{
    /**
     * @var array
     */
    private $details;

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
     * Dispatches a Symfony event in case of a transaction error.
     *
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
            $this->details = $details;
        }

        if ($this->details && !$context->getPrevious()) {
            $this->eventDispatcher->dispatch(OtpEvents::TRANSACTION_ERROR, new TransactionError($this->details));
        }
    }
}