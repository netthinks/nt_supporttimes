<?php
defined('TYPO3') or die();

use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\GeneralUtility;

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'nt_supporttimes',
    'Pi1',
    'LLL:EXT:nt_supporttimes/Resources/Private/Language/locallang_db.xlf:plugin.pi1.title',
    'ext-ntsupporttimes-icon'
);

$pluginSignature = 'ntsupporttimes_pi1';

// Get TYPO3 version
$versionInformation = GeneralUtility::makeInstance(Typo3Version::class);
$version = $versionInformation->getMajorVersion();

// Add FlexForm
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    $pluginSignature,
    'FILE:EXT:nt_supporttimes/Configuration/FlexForms/Pi1.xml'
);

// Standard configuration for TYPO3 12 and 13
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'pages,recursive,select_key';

// TYPO3 14 specific: Ensure FlexForm is in columnsOverrides
if ($version >= 14) {
    $GLOBALS['TCA']['tt_content']['types']['list']['columnsOverrides']['pi_flexform']['config']['ds'][$pluginSignature] = 
        'FILE:EXT:nt_supporttimes/Configuration/FlexForms/Pi1.xml';
}
