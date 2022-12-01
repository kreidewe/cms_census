<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'CMS Census Extension',
    'description' => 'Extension to draw market share of content management systems on https://cmscensus.eu/, detected by whatcms.org.',
    'category' => 'plugin',
    'author' => 'NITSAN, Alexander Ullrich, Sebastian Kreideweiß',
    'author_email' => 'sebastian@kreideweiss.info',
    'state' => 'stable',
    'clearCacheOnLoad' => 0,
    'version' => '1.1.0',
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
