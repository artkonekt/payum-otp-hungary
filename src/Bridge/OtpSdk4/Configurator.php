<?php
/**
 * Contains class Configurator
 *
 * @package     Konekt\PayumOtp\Bridge\OtpSdk4
 * @copyright   Copyright (c) 2016 Storm Storez Srl-D
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2016-03-15
 * @version     2016-03-15
 */

namespace Konekt\PayumOtp\Bridge\OtpSdk4;

class Configurator
{
    private $sdkLibDir;
    private $privateKeyFileName;

    const CONFIG_FILE_NAME = 'otp_webshop_client.conf';

    public function __construct($config)
    {

        $this->privateKeyFileName = $config['payum.api.privateKeyFile'];
        $this->sdkLibDir = $config['payum.api.sdkDir'] . '/kliensek/php/otpwebshop/lib';


        $confFileDir = sys_get_temp_dir();
        $this->generateConfigFile($confFileDir);

        define('WEBSHOP_LIB_DIR', $this->sdkLibDir);
        define('WEBSHOP_CONF_DIR', $confFileDir);
    }

    private function generateConfigFile($dir)
    {
        $originalConfigFile = $this->sdkLibDir .'/../config/' . self::CONFIG_FILE_NAME;

        $contents = file_get_contents($originalConfigFile);
        $replacedContents = preg_replace('/otp\.webshop\.PRIVATE_KEY_FILE_#02299991=.*/', 'otp.webshop.PRIVATE_KEY_FILE_#02299991=' . $this->privateKeyFileName, $contents);
        file_put_contents($dir  . '/' . self::CONFIG_FILE_NAME, $replacedContents);
    }

    public function getMainServiceFile()
    {
        return $this->sdkLibDir . '/iqsys/otpwebshop/WebShopService.php';
    }
}