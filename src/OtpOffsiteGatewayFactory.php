<?php
namespace Konekt\PayumOtp;

use Konekt\PayumOtp\Action\AuthorizeAction;
use Konekt\PayumOtp\Action\CancelAction;
use Konekt\PayumOtp\Action\ConvertPaymentAction;
use Konekt\PayumOtp\Action\CaptureAction;
use Konekt\PayumOtp\Action\NotifyAction;
use Konekt\PayumOtp\Action\RefundAction;
use Konekt\PayumOtp\Action\StatusAction;
use Konekt\PayumOtp\Bridge\OtpSdk4\Api;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayFactory;

class OtpOffsiteGatewayFactory extends GatewayFactory
{
    /**
     * {@inheritDoc}
     */
    protected function populateConfig(ArrayObject $config)
    {
        $config->defaults([
            'payum.factory_name' => 'otp_offsite',
            'payum.factory_title' => 'Otp Offsite',
            'payum.action.capture' => new CaptureAction(),
            'payum.action.authorize' => new AuthorizeAction(),
            'payum.action.refund' => new RefundAction(),
            'payum.action.cancel' => new CancelAction(),
            'payum.action.notify' => new NotifyAction(),
            'payum.action.status' => new StatusAction(),
            'payum.action.convert_payment' => new ConvertPaymentAction(),
        ]);

        if (false == $config['payum.api']) {
            $config['payum.default_options'] = array(
                'sandbox' => true,
            );
            $config->defaults($config['payum.default_options']);
            $config['payum.required_options'] = [
                'payum.api.privateKeyFile',
                'payum.api.sdkDir'
            ];

            $config['payum.api.sdkDir'] = '/home/flajos/Documents/work/artkonekt/payum-otp/docs/otp_original';
            $config['payum.api.privateKeyFile'] = '/home/flajos/Documents/work/artkonekt/payum-otp/docs/otp_original/sign_tool/#02299991.privKey.pem';

            $config['payum.api'] = function (ArrayObject $config) {
                $config->validateNotEmpty($config['payum.required_options']);

                return new Api((array) $config);
            };
        }
    }
}
