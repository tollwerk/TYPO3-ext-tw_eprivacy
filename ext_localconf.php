<?php

declare(strict_types=1);
defined('TYPO3') || die();

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;
use Tollwerk\TwEprivacy\Controller\SubjectController;
use Tollwerk\TwEprivacy\Hooks\ContentObject\StdWrapHook;

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

        // Make plugins available in content wizard. TODO: Remove / Migrate?
        ExtensionManagementUtility::addPageTSConfig(
            'mod {
            wizards.newContentElement.wizardItems.plugins {
                elements {
                    eprivacy {
                        iconIdentifier = tw_eprivacy-plugin-eprivacy
                        title = LLL:EXT:tw_eprivacy/Resources/Private/Language/locallang_db.xlf:tx_tw_eprivacy_eprivacy.name
                        description = LLL:EXT:tw_eprivacy/Resources/Private/Language/locallang_db.xlf:tx_tw_eprivacy_eprivacy.description
                        tt_content_defValues {
                            CType = list
                            list_type = tweprivacy_eprivacy
                        }
                    }
                }
                show = *
            }
       }'
        );

        // Add Hooks
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['stdWrap'][] = StdWrapHook::class;
    }
);
