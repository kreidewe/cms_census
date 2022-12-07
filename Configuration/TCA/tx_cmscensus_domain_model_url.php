<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:cms_census/Resources/Private/Language/locallang_db.xlf:tx_cmscensus_domain_model_url',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'delete' => 'deleted',
        'default_sortby' => 'ORDER BY uid',
        'searchFields' => 'name,description',
        'iconfile' => 'EXT:cms_census/Resources/Public/Icons/tx_cmscensus_domain_model_url.gif'
    ],
    'external' => [
        'general' => [
            'whatCmsJsonFileImport' => [
                'connector' => 'json',
                'parameters' => [
                    'uri' => 'fileadminImportFolderPath/importFileName',
                    'encoding' => 'utf8'
                ],
                'data' => 'array',
                'arrayPath' => '',
                'referenceUid' => 'name',
                'disabledOperations' => 'delete',
                'customSteps' => [
                    [
                        'class' => AUBA\CmsCensus\Step\CustomizeFileImportSetupStep::class,
                        'position' => 'before:' . \Cobweb\ExternalImport\Step\CheckPermissionsStep::class
                    ],
                    [
                        'class' => AUBA\CmsCensus\Step\CustomizeStoragePIDStep::class,
                        'position' => 'before:' . \Cobweb\ExternalImport\Step\StoreDataStep::class
                    ]
                ],
                'priority' => 5300,
                'description' => 'Import whatcms.org JSON File'
            ],
            'whatCmsApiImport' => [
                'connector' => 'json',
                'parameters' => [
                    'uri' => 'https://whatcms.org/API/CMS?key=whatCmsApiKey&url=requestUrl'
                ],
                'data' => 'array',
                'arrayPath' => '',
                'referenceUid' => 'name',
                'disabledOperations' => 'delete',
                'customSteps' => [
                    [
                        'class' => AUBA\CmsCensus\Step\PlanningAutoUpdateStep::class,
                        'position' => 'before:' . \Cobweb\ExternalImport\Step\CheckPermissionsStep::class
                    ],
                    [
                        'class' => AUBA\CmsCensus\Step\CustomizeSetupStep::class,
                        'position' => 'after:' . \Cobweb\ExternalImport\Step\CheckPermissionsStep::class
                    ],
                    [
                        'class' => AUBA\CmsCensus\Step\CustomizeReferenceUIDMappingStep::class,
                        'position' => 'before:' . \Cobweb\ExternalImport\Step\ValidateDataStep::class
                    ],
                    [
                        'class' => AUBA\CmsCensus\Step\CustomizeStoragePIDStep::class,
                        'position' => 'before:' . \Cobweb\ExternalImport\Step\StoreDataStep::class
                    ]
                ],
                'priority' => 5310,
                'description' => 'Import CMS Type for URL from whatcms.org'
            ]
        ]
    ],
    'types' => [
        '1' => ['showitem' => 'name, description, is_proposal, only_next_auto_update, every_auto_update, is_auto_update_planned, categories, whatcmstype, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access'],
    ],
    'columns' => [
        'name' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cms_census/Resources/Private/Language/locallang_db.xlf:tx_cmscensus_domain_model_url.name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
            'external' => [
                'whatCmsJsonFileImport' => [
                    'field' => 'url',
                    'transformations' => [
                        10 => [
                            'trim' => true
                        ]
                    ]
                ],
                'whatCmsApiImport' => [
                    'field' => 'requestUrl',
                    'transformations' => [
                        10 => [
                            'trim' => true
                        ]
                    ]
                ]
            ]
        ],
        'description' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cms_census/Resources/Private/Language/locallang_db.xlf:tx_cmscensus_domain_model_url.description',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
                'default' => ''
            ]
        ],
        'is_proposal' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cms_census/Resources/Private/Language/locallang_db.xlf:tx_cmscensus_domain_model_url.is_proposal',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '',
                        1 => '',
                    ]
                ],
                'default' => 0,
            ]
        ],
        'only_next_auto_update' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cms_census/Resources/Private/Language/locallang_db.xlf:tx_cmscensus_domain_model_url.only_next_auto_update',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '',
                        1 => '',
                    ]
                ],
                'default' => 0,
            ]
        ],
        'every_auto_update' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cms_census/Resources/Private/Language/locallang_db.xlf:tx_cmscensus_domain_model_url.every_auto_update',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '',
                        1 => '',
                    ]
                ],
                'default' => 0,
            ]
        ],
        'is_auto_update_planned' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cms_census/Resources/Private/Language/locallang_db.xlf:tx_cmscensus_domain_model_url.is_auto_update_planned',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '',
                        1 => '',
                    ]
                ],
                'default' => 0,
            ],
            'external' => [
                'whatCmsJsonFileImport' => [
                    'transformations' => [
                        20 => [
                            'value' => 0,
                        ]
                    ]
                ],
                'whatCmsApiImport' => [
                    'transformations' => [
                        20 => [
                            'value' => 0,
                        ]
                    ]
                ]
            ]
        ],
        'categories' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cms_census/Resources/Private/Language/locallang_db.xlf:tx_cmscensus_domain_model_url.categories',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_cmscensus_domain_model_category',
                'MM' => 'tx_cmscensus_url_category_mm',
                'size' => 10,
                'autoSizeMax' => 30,
                'maxitems' => 9999,
                'multiple' => 0,
                'fieldControl' => [
                    'editPopup' => [
                        'disabled' => false,
                    ],
                    'addRecord' => [
                        'disabled' => false,
                    ],
                    'listModule' => [
                        'disabled' => true,
                    ],
                ],
                'default' => 0,
            ],
        ],
        'whatcmstype' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cms_census/Resources/Private/Language/locallang_db.xlf:tx_cmscensus_domain_model_url.whatcmstype',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
            'external' => [
                'whatCmsJsonFileImport' => [
                    'field' => 'cms_name',
                    'transformations' => [
                        30 => [
                            'trim' => true
                        ]
                    ]
                ],
                'whatCmsApiImport' => [
                    'field' => 'name',
                    'transformations' => [
                        30 => [
                            'trim' => true
                        ]
                    ]
                ]
            ]
        ],
        'tstamp' => [
            'label' => 'tstamp',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime',
            ]
        ],
    ],
];
