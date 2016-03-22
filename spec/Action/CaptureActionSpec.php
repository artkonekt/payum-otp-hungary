<?php

namespace spec\Konekt\PayumOtp\Action;

use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Gateway;
use Payum\Core\Request\Capture;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @mixin \Konekt\PayumOtp\Action\CaptureAction
 */
class CaptureActionSpec extends ObjectBehavior
{
    function let(Gateway $gateway)
    {
        $this->setGateway($gateway);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Konekt\PayumOtp\Action\CaptureAction');
    }

    function it_should_be_GatewayAware()
    {
        $this->shouldHaveType('Payum\Core\Action\GatewayAwareAction');
    }

    function it_should_support_capture_request()
    {
        $request = new Capture(new ArrayObject());
        $this->supports($request)->shouldBe(true);
    }
}
