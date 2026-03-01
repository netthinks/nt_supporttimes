<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'TYPO3 Support Times',
    'description' => 'Displays TYPO3 support times and roadmap in the backend and frontend',
    'category' => 'be',
    'author' => 'Netthinks',
    'state' => 'stable',
    'clearCacheOnLoad' => 0,
    'version' => '1.2.2',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.0-14.99.99',
            'dashboard' => '12.4.0-14.99.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
