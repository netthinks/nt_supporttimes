<?php
defined('TYPO3') or die();

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'NtSupporttimes',
    'Pi1',
    'LLL:EXT:nt_supporttimes/Resources/Private/Language/locallang_db.xlf:plugin.pi1.title',
    'ext-ntsupporttimes-icon'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    'ntsupporttimes_pi1',
    'FILE:EXT:nt_supporttimes/Configuration/FlexForms/Pi1.xml'
);

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['ntsupporttimes_pi1'] = 'pi_flexform';
