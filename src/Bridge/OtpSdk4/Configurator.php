<?php
/**
 * Contains class Configurator
 *
 * @package     Konekt\PayumOtp\Bridge\OtpSdk4
 * @copyright   Copyright (c) 2016 Storm Storez Srl-D
 * @author      Lajos Fazakas
 * @license     MIT
 * @since       2016-03-15
 * @version     2016-03-15
 */

namespace Konekt\PayumOtp\Bridge\OtpSdk4;

class Configurator
{
    private $sdkLibDir;
    private $privateKeyFileName;
    private $posId;



    const CONFIG_FILE_NAME = 'otp_webshop_client.conf';

    public function __construct($config)
    {
        $this->privateKeyFileName = $config['secret_key'];
        $this->sdkLibDir = $config['sdk_dir'] . '/lib';
        $this->posId = $config['pos_id'];

        $confFileDir = sys_get_temp_dir();
        $this->generateConfigFile($confFileDir, $config);

        define('WEBSHOP_LIB_DIR', $this->sdkLibDir);
        define('WEBSHOP_CONF_DIR', $confFileDir);
    }

    private function generateConfigFile($dir, $config)
    {
        $originalConfigFile = $this->sdkLibDir .'/../config/' . self::CONFIG_FILE_NAME;

        $contents = file_get_contents($originalConfigFile);

        //private keyfile config
        $contents = preg_replace('/otp\.webshop\.PRIVATE_KEY_FILE_#02299991=.*/', 'otp.webshop.PRIVATE_KEY_FILE_#02299991=' . $this->privateKeyFileName, $contents);

        //log directory for the transactions keyfile config
        if (isset($config['sdk_transaction_log_dir'])) {
            $contents = preg_replace('/otp\.webshop\.sdk_transaction_log_dir=.*/', 'otp.webshop.sdk_transaction_log_dir=' . $config['sdk_transaction_log_dir'], $contents);
        }

        if (isset($config['sdk_transaction_log_dir.success'])) {
            $contents = preg_replace('/otp\.webshop\.sdk_transaction_log_dir\.SUCCESS_DIR=.*/', 'otp.webshop.sdk_transaction_log_dir.SUCCESS_DIR=' . $config['sdk_transaction_log_dir.success'], $contents);
        }

        if (isset($config['sdk_transaction_log_dir.failed'])) {
            $contents = preg_replace('/otp\.webshop\.sdk_transaction_log_dir\.FAILED_DIR=.*/', 'otp.webshop.sdk_transaction_log_dir.FAILED_DIR=' . $config['sdk_transaction_log_dir.failed'], $contents);
        }

        //log directory for the webshopclient

        if (isset($config['sdk_log4php_file'])) {
            $contents = preg_replace('/log4php\.appender\.WebShopClient\.File=.*/', 'log4php.appender.WebShopClient.File=' . $config['sdk_log4php_file'], $contents);
        }

        file_put_contents($dir  . '/' . self::CONFIG_FILE_NAME, $contents);


    }

    public function getMainServiceFile()
    {
        return $this->sdkLibDir . '/iqsys/otpwebshop/WebShopService.php';
    }

    /**
     * @return mixed
     */
    public function getPosId()
    {
        return $this->posId;
    }
}
