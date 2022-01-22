<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

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
            'enableMultiSelectFilterTextfield' => true,
            'itemsProcFunc' => \Tollwerk\TwEprivacy\Utilities\TcaUtility::class . '->getConsentItems'
        ],
    ],
];
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $newColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tt_content', 'tx_tweprivacy_consent', '', 'after:hidden');
