<?php
defined('TYPO3') or die();

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'nt_supporttimes',
    'Pi1',
    'LLL:EXT:nt_supporttimes/Resources/Private/Language/locallang_db.xlf:plugin.pi1.title',
    'ext-ntsupporttimes-icon'
);

$pluginSignature = 'ntsupporttimes_pi1';

// Add FlexForm configuration
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'pages,recursive,select_key';

// Configure pi_flexform field for this plugin
$GLOBALS['TCA']['tt_content']['columns']['pi_flexform']['config']['ds'][$pluginSignature] = 'FILE:EXT:nt_supporttimes/Configuration/FlexForms/Pi1.xml';
