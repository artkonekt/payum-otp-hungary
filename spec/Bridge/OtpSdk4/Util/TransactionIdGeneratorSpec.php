<?php

namespace spec\Konekt\PayumOtp\Bridge\OtpSdk4\Util;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @mixin \Konekt\PayumOtp\Bridge\OtpSdk4\Util\TransactionIdGenerator
 */
class TransactionIdGeneratorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Konekt\PayumOtp\Bridge\OtpSdk4\Util\TransactionIdGenerator');
    }

    function it_should_accept_alphanumeric_prefix()
    {
        $this->generate('ALPHA2')->shouldStartWith('ALPHA2');
    }

    function it_should_not_accept_non_alphanumeric_prefix()
    {
        $this->shouldThrow('\Payum\Core\Exception\InvalidArgumentException')->during('generate', ['TEST-']);
    }

    function it_should_not_accept_prefix_with_length_greater_than_10()
    {
        $this->shouldThrow('\Payum\Core\Exception\InvalidArgumentException')->during('generate', ['12345678901']);
    }

    function it_should_return_alphanumeric_value()
    {
        $this->generate('ALPHA2')->shouldMatch('/^[0-9a-zA-Z]+$/');
    }

    function it_should_return_a_string_with_maximum_length_of_32()
    {
        $this->generate('1234567890')->shouldMatch('/^[0-9a-zA-Z]{10,32}$/');
    }

    function it_should_return_different_results_for_every_call()
    {
        $firstId = $this->generate('PREFIX');
        $this->generate('PREFIX')->shouldNotBe($firstId);
    }
}
