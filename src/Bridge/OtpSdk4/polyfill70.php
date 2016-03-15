<?php
/**
 * Contains class ${NAME}
 *
 *
 * @package     ${NAMESPACE}
 * @copyright   Copyright (c) 2016 Storm Storez Srl-D
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2016-03-15
 * @version     2016-03-15
 */

function ereg_replace($pattern, $replacement, $string)
{
    //Fix invalid regex pattern defined in kliensek/php/otpwebshop/lib/apache/log4php/helpers/LoggerPatternConverter.php:289
    if ($pattern == "[^\\]u") {
        $pattern = ',([0-9]*u?)';
    }

    $pregPattern = '/' . addcslashes($pattern, '/') . '/';
    return preg_replace($pregPattern, $replacement, $string);
}