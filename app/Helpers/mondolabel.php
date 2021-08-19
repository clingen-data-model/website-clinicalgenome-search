<?php

if (!function_exists('dislpayMondoLabel')) {
    function displayMondoLabel($str) {

        return (strpos($str, 'obsolete') === 0 ? substr($str, 8) : $str);

    }
}
