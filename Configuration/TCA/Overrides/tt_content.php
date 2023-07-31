<?php

defined('TYPO3') || die();

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

ExtensionUtility::registerPlugin(
    'TwEprivacy',
    'Eprivacy',
    'LLL:EXT:tw_eprivacy/Resources/Private/Language/locallang_db.xlf:tx_tw_eprivacy_eprivacy.name',
    'tw_eprivacy-plugin-eprivacy'
);

// Add new fields
$newColumns = [
    'tx_tweprivacy_consent' => [
        'l10n_mode' => 'exclude',
        'exclude' => true,
        'label' => 'LLL:EXT:tw_eprivacy/Resources/Private/Language/locallang_db.xlf:tt_content.tx_tweprivacy_consent',
        'config' => [
            'exclude' => true,
            'type' => 'select',
            'renderType' => 'selectMultipleSideBySide',
            'itemsProcFunc' => \Tollwerk\TwEprivacy\Utilities\TcaUtility::class . '->getConsentItems'
        ],
    ],
];
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $newColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tt_content', 'tx_tweprivacy_consent', '', 'after:hidden');
