<?php

return [
    'url' => env('PROCESSWIRE_URL'),

    /*
    |--------------------------------------------------------------------------
    | CDWG parent group
    |--------------------------------------------------------------------------
    |
    | CDWGs and SC-CDWGs have no explicit parent_id in GPM: they all hang off one
    | working group (currently "Clinical Domain Working Group Oversight
    | Committee"). PanelExporter resolves that group and sends its gpm_id as
    | parent_id so ProcessWire can attach the pages correctly.
    |
    | Set this to that group's affiliate id. It is deliberately explicit: the
    | previous approach matched on a title containing "Clinical Domain Working",
    | which is ambiguous (it matches both the Oversight Committee and any plain
    | "Clinical Domain Working Group") and breaks whenever the group is renamed.
    |
    | Leave null to fall back to the legacy title match.
    |
    */
    'cdwg_parent_affiliate_id' => env('PROCESSWIRE_CDWG_PARENT_AFFILIATE_ID'),
];
