<?php
defined('TYPO3') or die();

use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

$versionInformation = GeneralUtility::makeInstance(Typo3Version::class);
$version = $versionInformation->getMajorVersion();

if ($version >= 14) {
    // TYPO3 14: Pass FlexForm directly to registerPlugin
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'nt_supporttimes',
        'Pi1',
        'LLL:EXT:nt_supporttimes/Resources/Private/Language/locallang_db.xlf:plugin.pi1.title',
        'ext-ntsupporttimes-icon',
        'plugins',
        'LLL:EXT:nt_supporttimes/Resources/Private/Language/locallang_db.xlf:plugin.pi1.description',
        'FILE:EXT:nt_supporttimes/Configuration/FlexForms/Pi1.xml'
    );
} else {
    // TYPO3 12 & 13: Old method without FlexForm parameter
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'nt_supporttimes',
        'Pi1',
        'LLL:EXT:nt_supporttimes/Resources/Private/Language/locallang_db.xlf:plugin.pi1.title',
        'ext-ntsupporttimes-icon'
    );
    
    $pluginSignature = 'ntsupporttimes_pi1';
    
    // Add FlexForm the old way
    ExtensionManagementUtility::addPiFlexFormValue(
        $pluginSignature,
        'FILE:EXT:nt_supporttimes/Configuration/FlexForms/Pi1.xml'
    );
    
    // Standard configuration
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'pages,recursive,select_key';
}
