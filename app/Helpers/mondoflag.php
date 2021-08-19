<?php

if (!function_exists('dislpayMondoObsolete')) {
    function displayMondoObsolete($str) {

        return (strpos($str, 'obsolete') === 0 ?
            '<span class="badge bg-light text-muted border-1 text-normal small" title="MONARCH has deprecated this term">Obsolete Term</span>'
            : '');

    }
}
