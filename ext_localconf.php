<?php

defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function() {

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Tollwerk.TwEprivacy',
            'Eprivacy',
            [
                'Subject' => 'list'
            ],
            // non-cacheable actions
            [
                'Subject' => ''
            ]
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
}');

    }
);

/**
 * Custom ePrivacy TypoScript condition
 *
 * @return bool Whether the given subject identifiers have the user's consent
 * @throws Exception
 */
function user_ePrivacy()
{
    $subjects = [];
    foreach (func_get_args() as $subject) {
        $subject = trim(trim($subject, '"\''));
        if (!empty($subject)) {
            $subjects[] = $subject;
        }
    }
    if (empty($subjects)) {
        return false;
    }

    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager
     * @var \Tollwerk\TwEprivacy\Utilities\EprivacyShield $ePrivacyShield
     */
    $objectManager  = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);
    $ePrivacyShield = $objectManager->get(\Tollwerk\TwEprivacy\Utilities\EprivacyShield::class);
    $result         = true;

    foreach ($subjects as $subject) {
        if (!$ePrivacyShield->isAllowedIdentifier($subject)) {
            $result = false;
            break;
        }
    }

    // Reset the persistence mangager state
    $objectManager->get(\TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager::class)->clearState();

    return $result;
}
