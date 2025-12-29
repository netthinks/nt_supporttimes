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
    'nt_supporttimes',
    'Pi1',
    [
        \Netthinks\NtSupporttimes\Controller\SupportTimesController::class => 'roadmap'
    ],
    // non-cacheable actions
    [
        \Netthinks\NtSupporttimes\Controller\SupportTimesController::class => 'roadmap'
    ]
);
