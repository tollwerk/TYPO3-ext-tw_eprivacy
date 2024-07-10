<?php

declare(strict_types=1);
defined('TYPO3') || die();

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;
use Tollwerk\TwEprivacy\Controller\SubjectController;
use Tollwerk\TwEprivacy\Hooks\Frontend\CreateHashBaseHook;

call_user_func(
    function() {
        // Register Fluid namespace.
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['eprivacy'] = ['Tollwerk\\TwEprivacy\\ViewHelpers'];

        // Register icons.
        $iconRegistry = GeneralUtility::makeInstance(IconRegistry::class);
        $iconRegistry->registerIcon(
            'tw_eprivacy-plugin-eprivacy',
            SvgIconProvider::class,
            ['source' => 'EXT:tw_eprivacy/Resources/Public/Icons/subject.svg']
        );

        // Configure plugins.
        ExtensionUtility::configurePlugin(
            'TwEprivacy',
            'Eprivacy',
            [SubjectController::class => 'list, addConsent'],
            [SubjectController::class => 'list, addConsent']
        );
        ExtensionUtility::configurePlugin(
            'TwEprivacy',
            'EprivacyDialog',
            [SubjectController::class => 'dialog'],
            [SubjectController::class => 'dialog']
        );

        // Add Hooks
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['createHashBase'][] = CreateHashBaseHook::class . '->modifyParams';

        // Exclude some parameters from chash.
        $GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_tweprivacy_eprivacy[action]';
        $GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_tweprivacy_eprivacy[pid]';
        $GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_tweprivacy_eprivacy[pluginName]';
        $GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_tweprivacy_eprivacy[controller]';
        $GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_tweprivacy_eprivacy[pid]';
        $GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_tweprivacy_eprivacy[addIdentifiers][0]';
        $GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_tweprivacy_eprivacy[addIdentifiers][1]';
        $GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_tweprivacy_eprivacy[addIdentifiers][2]';
        $GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_tweprivacy_eprivacy[addIdentifiers][3]';
        $GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_tweprivacy_eprivacy[addIdentifiers][4]';
        $GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_tweprivacy_eprivacy[addIdentifiers][5]';
        $GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_tweprivacy_eprivacy[addIdentifiers][6]';
        $GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_tweprivacy_eprivacy[addIdentifiers][7]';
        $GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_tweprivacy_eprivacy[addIdentifiers][8]';
        $GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_tweprivacy_eprivacy[addIdentifiers][9]';
    }
);
