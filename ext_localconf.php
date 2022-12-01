<?php

declare(strict_types=1);

use AUBA\CmsCensus\Controller\AjaxController;
use AUBA\CmsCensus\Controller\CategoryController;
use AUBA\CmsCensus\Controller\ChartController;
use AUBA\CmsCensus\Controller\ProposalController;
use AUBA\CmsCensus\Controller\UrlController;
use AUBA\CmsCensus\Controller\VersionController;

defined('TYPO3') or die();

(static function() {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'CmsCensus',
        'Chartcmscensus',
        [
            ChartController::class => 'show, search',
            AjaxController::class => 'cmsPerCategoryUrls'
        ],
        // non-cacheable actions
        [
            ChartController::class => 'show, search',
            AjaxController::class => 'cmsPerCategoryUrls'
        ]
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'CmsCensus',
        'Proposalcmscensus',
        [
            ProposalController::class => 'addUrlForm, addCategoryForm, createUrl, createCategory'
        ],
        // non-cacheable actions
        [
            ProposalController::class => 'addUrlForm, addCategoryForm, createUrl, createCategory'
        ]
    );


    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'CmsCensus',
        'Versionscmscensus',
        [
            VersionController::class => 'show'
        ],
        // non-cacheable actions
        [
            VersionController::class => 'show'
        ]
    );

    // wizards
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        'mod {
            wizards.newContentElement.wizardItems.plugins {
                elements {
                    chartcmscensus {
                        iconIdentifier = cms_census-plugin-chartcmscensus
                        title = LLL:EXT:cms_census/Resources/Private/Language/locallang_db.xlf:tx_cms_census_chartcmscensus.name
                        description = LLL:EXT:cms_census/Resources/Private/Language/locallang_db.xlf:tx_cms_census_chartcmscensus.description
                        tt_content_defValues {
                            CType = list
                            list_type = cmscensus_chartcmscensus
                        }
                    },
                    proposalcmscensus {
                        iconIdentifier = cms_census-plugin-proposalcmscensus
                        title = LLL:EXT:cms_census/Resources/Private/Language/locallang_db.xlf:tx_cms_census_proposalcmscensus.name
                        description = LLL:EXT:cms_census/Resources/Private/Language/locallang_db.xlf:tx_cms_census_proposalcmscensus.description
                        tt_content_defValues {
                            CType = list
                            list_type = cmscensus_proposalcmscensus
                        }
                    },
                    versionscmscensus {
                        iconIdentifier = cms_census-plugin-versionscmscensus
                        title = LLL:EXT:cms_census/Resources/Private/Language/locallang_db.xlf:tx_cms_census_versionscmscensus.name
                        description = LLL:EXT:cms_census/Resources/Private/Language/locallang_db.xlf:tx_cms_census_versionscmscensus.description
                        tt_content_defValues {
                            CType = list
                            list_type = cmscensus_versionscmscensus
                        }
                    },
                }
                show = *
            }
       }'
    );
})();
