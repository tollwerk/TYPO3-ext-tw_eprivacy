<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function() {

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'Tollwerk.TwEprivacy',
            'Eprivacy',
            'ePrivacy'
        );

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
            'tw_eprivacy',
            'Configuration/TypoScript/Static',
            'tollwerk ePrivacy Consent Manager'
        );

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
            'tx_tweprivacy_domain_model_type',
            'EXT:tw_eprivacy/Resources/Private/Language/locallang_csh_tx_tweprivacy_domain_model_type.xlf'
        );
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_tweprivacy_domain_model_type');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
            'tx_tweprivacy_domain_model_subject',
            'EXT:tw_eprivacy/Resources/Private/Language/locallang_csh_tx_tweprivacy_domain_model_subject.xlf'
        );
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_tweprivacy_domain_model_subject');

    }
);
