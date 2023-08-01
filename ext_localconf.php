<?php

declare(strict_types=1);
defined('TYPO3') || die();

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;
use Tollwerk\TwEprivacy\Controller\SubjectController;
use Tollwerk\TwEprivacy\Hooks\ContentObject\PostInitHook;
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

        // Add Hooks
        // TODO: Re-enable tt_content check
        // $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['stdWrap'][] = StdWrapHook::class;
        // $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['postInit'][] = PostInitHook::class;
    }
);
