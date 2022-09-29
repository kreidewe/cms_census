<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'CMS Census Extension',
    'description' => 'extension to provide whatcms.org data',
    'category' => 'plugin',
    'author' => 'Alexander Ullrich',
    'author_email' => 'alexander.ullrich@digitaler-mittelstand-dresden.de',
    'state' => 'alpha',
    'clearCacheOnLoad' => 0,
    'version' => '0.0.1',
    'constraints' => [
        'depends' => [
            'external_import' => '6.0.0-0.0.0',
            'svconnector_json' => '3.0.0-0.0.0',
            'typo3' => '11.5.0-11.5.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
