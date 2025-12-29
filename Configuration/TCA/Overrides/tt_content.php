<?php
defined('TYPO3') or die();

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'nt_supporttimes',
    'Pi1',
    'LLL:EXT:nt_supporttimes/Resources/Private/Language/locallang_db.xlf:plugin.pi1.title',
    'ext-ntsupporttimes-icon'
);

$pluginSignature = 'ntsupporttimes_pi1';

// Add FlexForm
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    $pluginSignature,
    'FILE:EXT:nt_supporttimes/Configuration/FlexForms/Pi1.xml'
);

// Add pi_flexform to showitem for the plugin
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';

// Ensure pi_flexform is shown in the backend form
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'pages,recursive,select_key';

