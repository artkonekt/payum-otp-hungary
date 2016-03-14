<?php

namespace spec\Konekt\PayumOtp\Action;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AuthorizeActionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Konekt\PayumOtp\Action\AuthorizeAction');
    }
}
