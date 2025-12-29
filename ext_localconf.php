<?php

defined('TYPO3') or die();

(function () {
    if (!isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['nt_supporttimes_cache'])) {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['nt_supporttimes_cache'] = [
            'frontend' => \TYPO3\CMS\Core\Cache\Frontend\VariableFrontend::class,
            'backend' => \TYPO3\CMS\Core\Cache\Backend\FileBackend::class,
            'options' => [
                'defaultLifetime' => 86400,
            ],
            'groups' => ['system'],
        ];
    }
})();

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'NtSupporttimes',
    'Pi1',
    [
        \Netthinks\NtSupporttimes\Controller\SupportTimesController::class => 'roadmap'
    ],
    // non-cacheable actions
    [
        \Netthinks\NtSupporttimes\Controller\SupportTimesController::class => 'roadmap'
    ]
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
    'mod {
        wizards.newContentElement.wizardItems.plugins {
            elements {
                pi1 {
                    iconIdentifier = ext-ntsupporttimes-icon
                    title = TYPO3 Support Roadmap
                    description = Interactive Gantt chart of TYPO3 support times
                    tt_content_defValues {
                        CType = list
                        list_type = ntsupporttimes_pi1
                    }
                }
            }
            show := addToList(pi1)
        }
    }'
);
