<?php

/**
 * tollwerk ePrivacy Consent Manager
 *
 * @category   Tollwerk
 * @package    Tollwerk\TwEprivacy
 * @author     Joschi Kuphal <joschi@tollwerk.de> / @jkphl
 * @copyright  Copyright © 2020 Joschi Kuphal <joschi@tollwerk.de> / @jkphl
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU GPLv2
 */

/***************************************************************
 * Copyright © 2020 Joschi Kuphal <joschi@tollwerk.de>
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301, USA.
 ***************************************************************/

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
