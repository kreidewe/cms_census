<?php
defined('TYPO3') || die();

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'CmsCensus',
    'Chartcmscensus',
    'ChartCmsCensus'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'CmsCensus',
    'Proposalcmscensus',
    'ProposalCmsCensus'
);

// Add Flexform for theme plugin
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['cmscensus_chartcmscensus'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    'cmscensus_chartcmscensus',
    'FILE:EXT:cms_census/Configuration/FlexForms/Flexform_chart_plugin.xml'
);
