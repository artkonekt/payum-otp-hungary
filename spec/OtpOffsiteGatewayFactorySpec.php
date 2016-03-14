<?php

namespace spec\Konekt\PayumOtp;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class OtpOffsiteGatewayFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Konekt\PayumOtp\OtpOffsiteGatewayFactory');
    }
}
