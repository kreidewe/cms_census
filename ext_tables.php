<?php
defined('TYPO3') || die();

(static function() {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_cmscensus_domain_model_url', 'EXT:cms_census/Resources/Private/Language/locallang_csh_tx_cmscensus_domain_model_url.xlf');
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_cmscensus_domain_model_url');

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_cmscensus_domain_model_category', 'EXT:cms_census/Resources/Private/Language/locallang_csh_tx_cmscensus_domain_model_category.xlf');
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_cmscensus_domain_model_category');

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_cmscensus_domain_model_whatcmstype', 'EXT:cms_census/Resources/Private/Language/locallang_csh_tx_cmscensus_domain_model_whatcmstype.xlf');
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_cmscensus_domain_model_whatcmstype');
})();

// Register sprite icons for new tables
/** @var \TYPO3\CMS\Core\Imaging\IconRegistry $iconRegistry */
$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);

$iconRegistry->registerIcon(
    'tx_cmscensus_domain_model_whatcmstype',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
    [
        'source' => 'EXT:cms_census/Resources/Public/Icons/ImportData.svg'
    ]
);
