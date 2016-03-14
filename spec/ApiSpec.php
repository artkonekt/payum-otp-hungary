<?php

namespace spec\Konekt\PayumOtp;

use Payum\Core\Bridge\Guzzle\HttpClient;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ApiSpec extends ObjectBehavior
{
    function let(HttpClient $client)
    {
        $this->beConstructedWith([], $client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Konekt\PayumOtp\Api');
    }
}
