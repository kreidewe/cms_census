<?php

declare(strict_types=1);

use AUBA\CmsCensus\Controller\AjaxController;
use AUBA\CmsCensus\Controller\CategoryController;
use AUBA\CmsCensus\Controller\ChartController;
use AUBA\CmsCensus\Controller\ProposalController;
use AUBA\CmsCensus\Controller\UrlController;

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

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:cms_census/Configuration/TSconfig/ContentElementWizard.tsconfig">'
    );
})();
