<?php
if (!function_exists('ereg_replace')) {

    /**
     * Simulation of the 'ereg_replace' method removed from PHP7.
     * @see http://php.net/manual/en/function.ereg-replace.php
     */
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