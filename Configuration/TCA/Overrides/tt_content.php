<?php
defined('TYPO3') or die();

use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

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
ExtensionManagementUtility::addPiFlexFormValue(
    $pluginSignature,
    'FILE:EXT:nt_supporttimes/Configuration/FlexForms/Pi1.xml'
);

if ($version >= 14) {
    // TYPO3 14: Use addToAllTCAtypes to explicitly add pi_flexform to the plugin tab
    ExtensionManagementUtility::addToAllTCAtypes(
        'tt_content',
        'pi_flexform',
        $pluginSignature,
        'after:subheader'
    );
} else {
    // TYPO3 12 & 13: Standard configuration
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
}

// Exclude standard fields for all versions
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'pages,recursive,select_key';
