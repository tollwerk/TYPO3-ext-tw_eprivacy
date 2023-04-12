<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function() {

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Tollwerk.TwEprivacy',
            'Eprivacy',
            [Tollwerk\TwEprivacy\Controller\SubjectController::class => 'list, addConsent'],
            [Tollwerk\TwEprivacy\Controller\SubjectController::class => 'addConsent']
        );

        // wizards
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
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
        $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);

        $iconRegistry->registerIcon(
            'tw_eprivacy-plugin-eprivacy',
            \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
            ['source' => 'EXT:tw_eprivacy/Resources/Public/Icons/subject.svg']
        );

        // Register Fluid namespace
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['eprivacy'] = ['Tollwerk\\TwEprivacy\\ViewHelpers'];

        // Add Hooks
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['stdWrap'][] = \Tollwerk\TwEprivacy\Hooks\ContentObject\StdWrapHook::class;
    }
);
