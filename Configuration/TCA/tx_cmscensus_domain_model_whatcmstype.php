<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:cms_census/Resources/Private/Language/locallang_db.xlf:tx_cmscensus_domain_model_whatcmstype',
        'label' => 'label',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'delete' => 'deleted',
        'default_sortby' => 'ORDER BY id',
        'searchFields' => 'label, name',
        'typeicon_classes' => [
            'default' => 'tx_cmscensus_domain_model_whatcmstype'
        ],
    ],
    'external' => [
        'general' => [
            0 => [
                'connector' => 'json',
                'parameters' => [
                    'uri' => 'https://whatcms.org/API/List'
                ],
                'data' => 'array',
                'arrayPath' => 'result{code === 200}/list',
                'referenceUid' => 'id',
                'customSteps' => [
                    [
                        'class' => AUBA\CmsCensus\Step\CustomizeStoragePIDStep::class,
                        'position' => 'before:' . \Cobweb\ExternalImport\Step\StoreDataStep::class
                    ]
                ],
                'priority' => 5300,
                'description' => 'Import List of whatcms.org Types'
            ]
        ]
    ],
    'types' => [
        '1' => ['showitem' => 'id, label, name, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access, hidden'],
    ],
    'columns' => [
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'invertStateDisplay' => true
                    ]
                ],
            ],
        ],
        'id' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cms_census/Resources/Private/Language/locallang_db.xlf:tx_cmscensus_domain_model_whatcmstype.id',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int',
                'default' => 0
            ],
            'external' => [
                0 => [
                    'field' => 'id',
                    'transformations' => [
                        10 => [
                            'trim' => false
                        ]
                    ]
                ]
            ]
        ],
        'label' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cms_census/Resources/Private/Language/locallang_db.xlf:tx_cmscensus_domain_model_whatcmstype.label',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
            'external' => [
                0 => [
                    'field' => 'label',
                    'transformations' => [
                        30 => [
                            'trim' => true
                        ]
                    ]
                ]
            ]
        ],
        'name' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cms_census/Resources/Private/Language/locallang_db.xlf:tx_cmscensus_domain_model_whatcmstype.name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
            'external' => [
                0 => [
                    'field' => 'type',
                    'transformations' => [
                        10 => [
                            'userFunction' => [
                                'class' => AUBA\CmsCensus\UserFunction\Transformation::class,
                                'method' => 'getCommaSeparatedValuesFromArray'
                            ]
                        ]
                    ],
                ]
            ]
        ],

    ],
];
