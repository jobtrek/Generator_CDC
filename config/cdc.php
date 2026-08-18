<?php

return [

    'header' => [
        'centre_formation' => env('CDC_CENTRE_FORMATION', 'Centre de formation - DEV - Brief projet'),
    ],

    'footer' => [
        'version'   => env('CDC_VERSION', 'Version 1.1-ordo2k104-21 (18.01.2025)'),
        'copyright' => env('CDC_COPYRIGHT', '© I-CQ VD 2017/25'),
    ],

    'document' => [
        'qualification'  => env('CDC_QUALIFICATION', 'Procédure de qualification : 88600/1/2/3 - 88614 Informaticien/ne CFC'),
        'ordo'           => env('CDC_ORDO', '(Ordo 2014/21)'),
        'titre'          => env('CDC_TITRE', 'Cahier des charges'),
        'version_prefix' => env('CDC_VERSION_PREFIX', 'Version 1.1 - '),
    ],

];
