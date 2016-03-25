# Custom Payum gateway for OTP Bank Hungary
 
Consists of a standard payum gateway library with which you can make online payments through OTP Bank Hungary.
It also provides basic integration with [Symfony](http://symfony.com/) and [Sylius](http://sylius.org/).
 
Disclaimer: the library for now it provides only simple offsite (haromszereplos) payment and for now it only supports
a single shop (no multishop support). Feel free to contribute or ask for support if you need further functionality.

## Architecture

The library builds on top of OTP's official SDK. It hides some of the library's inconveniences, like having to edit a 
configuration file, no autoloading, messy warnings/notices and no support for PHP7.
The official SDK's loggin 

You will have to download the SDK [from OTP's site](https://www.otpbank.hu/portal/hu/Kartyaelfogadas/Webshop) in order to
be able to use the library.

## SDK
 
After you downloaded the SDK (currently a ZIP file named Webshop_4.0) you will find the needed php library in the zip on the
following path: ```kliensek/php/otpwebshop```. This is the library that is used and you have to put it in a place accessible 
to your web server. From now on we will call the full path where you have the library ```SDK_DIR```.

Because the SDK has no proper versioning, to check if it is the same code that this library is building on you will have to generate
the checksum of the library like this (assuming you're on Unix):
 
```
find SDK_DIR/ -type f -name "*.php" -exec md5sum {} + | awk '{print $1}' | sort | md5sum1
```

The tested library has a checksum of 5a57d623e8da3541c8b8d6fd3848862c. If you have another one it means the SDK code has changed. 
If something is not working as expected this can be a reason for that.

## Using the standalone library

You will have to implement actions as described in the [Payum documentation](https://github.com/Payum/Payum/blob/master/src/Payum/Core/Resources/docs/scripts/index.md). In the ```examples``` folder you will find working scripts
which can help you.

### Configuration 

You will find the parameters you will have to configure in ```examples/params.dist```. 

* sdk_dir: The full path to the SDK (as described above). Mandatory.
* sandbox: Can be true or false, if it is true, you don't need to provide the secret_key and pos_id. Default: true.
* pos_id: Your shop ID given to you by OTP. Mandatory when not in sandbox mode.
* secret_key: The full path to the secret key file belonging to the shop id given to you by OTP. Mandatory when not in sandbox mode.
* transactionid_prefix:  For each payment the library generates a unique ID, you can specify any prefix to it here. It should be 
 no more than 10 characters and should be alphanumeric.
 
## Using the library with Sylius

You will have to register the action which converts the Sylius models into the gateway's data as a service.

```yaml
app.payum.otp.action.convert:
    class: Konekt\PayumOtp\Bridge\Sylius\ConvertPaymentToOtpAction
```

You also can define another service where you can control the payment note (shopMegjegyzes) sent to OTP. If you do this you have to implement
\Konekt\PayumOtp\Bridge\Sylius\PaymentNoteProviderInterface in your app and define it as a service, then inject it to the converter. In this case
the above definition looks like:

```yaml
app.payum.otp.note_provider:
    class: AppBundle\MyNoteProvider #this class implements \Konekt\PayumOtp\Bridge\Sylius\PaymentNoteProviderInterface
    public: false

app.payum.otp.action.convert:
    class: Konekt\PayumOtp\Bridge\Sylius\ConvertPaymentToOtpAction
    arguments:
        - @app.payum.otp.note_provider
```

To register the custom payum library into your app, you should add the following to your AppBundle:

```php
$extension = $container->getExtension('payum');
$extension->addGatewayFactory(new Konekt\PayumOtp\Bridge\Symfony\OtpHungaryOffsiteGatewayFactory());
```

Then you will have to configure the payum bundle to know about the new custom library like this:

```yaml
payum:
    gateways:
        otp_hungary_offsite:
            otp_hungary_offsite:
                sandbox: %payum.otp.is_sandbox%
                secret_key: %payum.otp.secret_key%
                sdk_dir: %payum.otp.sdk_dir%
                pos_id: %payum.otp.pos_id%
                transactionid_prefix: %payum.otp.transactionid_prefix%
                actions:
                    - app.payum.otp.action.convert
                extensions:
                    - app.payum.otp.extension.error_notifier
```

You can define the parameters in your ```parameters.yml``` (recommended). For the meaning of the parameters see the Configuration section above.
Note: even if you are in sandbox mode you will have to provide a value for secret_key and pos_id, but in this case it doesn't matter what you provide 
there (this is a technical depth for now).

### Error handling

You also have the possibility to handle the transaction errors in a decoupled way. For example you want to get an email if any transaction fails by
reasons such as wrong amount sent or the private key was rejected etc. In this case you will have to register the library's event notifier extension
class as a service:

```yaml
app.payum.otp.extension.error_notifier:
    class:  Konekt\PayumOtp\Bridge\Symfony\ErrorNotifierExtension
    arguments:
        - @event_dispatcher
```

Then you will have to write a listener in your app and define it as a service. Implement ```MyOtpListener``` which sends an email if an error 
occurs:

```php
<?php

namespace AppBundle;

use Konekt\PayumOtp\Bridge\Symfony\Event\TransactionError;

class MyOtpListener
{
    /**
     * @param \Konekt\PayumOtp\Bridge\Symfony\Event\TransactionError $event
     */
    public function handleTransactionErrors(TransactionError $event)
    {
        //var_dump($event->getErrors());
        //var_dump($event->getDetails());

        //TODO: implement here some notification logic: ie. send email to the support team
    }
}
```

Then register it as a service:

```yaml
app.listener.otp_error:
    class: AppBundle\MyOtpListener
    tags:
        - { name: kernel.event_listener, event: payum.otp.transaction_error, method: handleTransactionErrors }
```

In the ```MyOtpListener::handleTransactionErrors``` method you will get a ```TransactionError``` event, which contains the 
array of occured errors and other details of the transaction. You can write your handling logic as you need.

# FINAL NOTES

If you have any questions feel free to open an issue. Any improvements or further functionality is very welcomed, feel free to
make a pull request.