<?php
/**
 * Contains class OtpHungaryOffsiteGatewayFactory
 *
 * @package     Konekt\PayumOtp\Bridge\Symfony
 * @copyright   Copyright (c) 2016 Storm Storez Srl-D
 * @author      Lajos Fazakas
 * @license     MIT
 * @since       2016-03-23
 * @version     2016-03-23
 */

namespace Konekt\PayumOtp\Bridge\Symfony;

use Konekt\PayumOtp\Bridge\OtpSdk4\Util\TransactionIdGenerator;
use Payum\Bundle\PayumBundle\DependencyInjection\Factory\Gateway\AbstractGatewayFactory;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class OtpHungaryOffsiteGatewayFactory extends AbstractGatewayFactory
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'otp_hungary_offsite';
    }

    /**
     * {@inheritdoc}
     */
    public function addConfiguration(ArrayNodeDefinition $builder)
    {
        parent::addConfiguration($builder);
        $builder->children()
            ->booleanNode('sandbox')->defaultTrue()->end()
            ->scalarNode('sdk_dir')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('secret_key')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('pos_id')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('transactionid_prefix')
                ->validate()
                ->ifTrue(function ($prefix) {
                    return TransactionIdGenerator::assertPrefixValid($prefix);
                })
                ->thenInvalid('Invalid prefix')
                ->end()
            ->end();
    }


    /**
     * {@inheritDoc}
     */
    protected function getPayumGatewayFactoryClass()
    {
        return 'Konekt\PayumOtp\OtpOffsiteGatewayFactory';
    }

    /**
     * {@inheritDoc}
     */
    protected function getComposerPackage()
    {
        return 'konekt/payum-otp-hungary';
    }
}