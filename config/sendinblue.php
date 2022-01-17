<?php

return [
    'apikey' => env('SENDINBLUE_APIKEY', ''),
    'base_url' => 'https://api.sendinblue.com/v3/',

    'attributes' => [
        'CIVILITE' => [
            '1' => 'Madame',
            '2' => 'Monsieur',
            '3' => 'Frau',
            '4' => 'Herr',
            '5' => 'Madam',
            '6' => 'Mister'
        ],
        'LANGUE' => [
            '1' => 'FR',
            '2' => 'DE',
            '3' => 'EN'
        ]
    ]
];
