<?php

if (!function_exists('dislpayCitation')) {
    function displayCitation($obj, $exp = false) {

        if ($exp)
        {
            $str = '<strong><a href="' . $obj->iri . '" rel="noopener noreferrer" target="_pmid" data-toggle="popover" data-placement="top" data-trigger="hover" data-content="'
                     . ($obj->label ?? 'No title') . '">PMID: ' . basename($obj->iri) . '  <i class="glyphicon glyphicon-new-window"></i></a></strong>';

            $str .= '<br>' . $obj->first_author . ', ';

            if ($obj->multiple_authors)
                $str .= 'et al., ';

            $str .= $obj->year_published . ', ' . $obj->label;

            return $str;

        }

        $str = $obj->first_author . ', ';

        if ($obj->multiple_authors)
            $str .= 'et al., ';

        $str .= $obj->year_published . ', <a href="' . $obj->iri . '" rel="noopener noreferrer" target="_pmid" data-toggle="popover" data-placement="top" data-trigger="hover" data-content="'
                . ($obj->label ?? 'No title') . '">PMID: ' . basename($obj->iri) . '  <i class="glyphicon glyphicon-new-window"></i></a>';

        return $str;

    }
}
