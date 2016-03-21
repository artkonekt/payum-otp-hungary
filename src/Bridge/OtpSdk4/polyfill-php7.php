<?php

if (!function_exists('ereg_replace')) {
    function ereg_replace($pattern, $replacement, $string)
    {
        //Fix invalid regex pattern defined in SDK (lib/apache/log4php/helpers/LoggerPatternConverter.php:289)
        if ($pattern == "[^\\]u") {
            $pattern = ',([0-9]*u?)';
        }

        $pregPattern = '/' . addcslashes($pattern, '/') . '/';

        return preg_replace($pregPattern, $replacement, $string);
    }
}