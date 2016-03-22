<?php
namespace Konekt\PayumOtp;

use Konekt\PayumOtp\Action\Api\CaptureAction as ApiCaptureAction;
use Konekt\PayumOtp\Action\Api\FetchCaptureResultAction;
use Konekt\PayumOtp\Action\Api\InitiateCaptureAction;
use Konekt\PayumOtp\Action\AuthorizeAction;
use Konekt\PayumOtp\Action\CancelAction;
use Konekt\PayumOtp\Action\ConvertPaymentAction;
use Konekt\PayumOtp\Action\CaptureAction;
use Konekt\PayumOtp\Action\NotifyAction;
use Konekt\PayumOtp\Action\RefundAction;
use Konekt\PayumOtp\Action\StatusAction;
use Konekt\PayumOtp\Bridge\OtpSdk4\Api;
use Konekt\PayumOtp\Request\Api\Capture;
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
            'payum.action.status' => new StatusAction(),
            'payum.action.convert_payment' => new ConvertPaymentAction(),

            'payum.action.initiate_capture' => new InitiateCaptureAction(),
            'payum.action.fetch_capture_result' => new FetchCaptureResultAction(),
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

            $config['payum.api'] = function (ArrayObject $config) {
                $config->validateNotEmpty($config['payum.required_options']);

                return new Api((array) $config);
            };
        }
    }
}
