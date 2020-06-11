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
        // Register the ePrivacy settings plugin
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'Tollwerk.TwEprivacy',
            'Eprivacy',
            'LLL:EXT:tw_eprivacy/Resources/Private/Language/locallang_db.xlf:tx_tw_eprivacy_eprivacy.name',
            'tw_eprivacy-plugin-eprivacy'
        );

        // Register the static TypoScript
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
            'tw_eprivacy',
            'Configuration/TypoScript/Static',
            'tollwerk ePrivacy Consent Manager'
        );

        // Register the type table
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
            'tx_tweprivacy_domain_model_type',
            'EXT:tw_eprivacy/Resources/Private/Language/locallang_csh_tx_tweprivacy_domain_model_type.xlf'
        );
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_tweprivacy_domain_model_type');
        $GLOBALS['TCA']['tx_tweprivacy_domain_model_type'] = [
            'ctrl' => [
                'title'                    => 'LLL:EXT:tw_eprivacy/Resources/Private/Language/locallang_db.xlf:tx_tweprivacy_domain_model_type',
                'label'                    => 'title',
                'tstamp'                   => 'tstamp',
                'crdate'                   => 'crdate',
                'cruser_id'                => 'cruser_id',
                'dividers2tabs'            => true,
                'sortby'                   => 'sorting',
                'languageField'            => 'sys_language_uid',
                'transOrigPointerField'    => 'l10n_parent',
                'transOrigDiffSourceField' => 'l10n_diffsource',
                'delete'                   => 'deleted',
                'enablecolumns'            => [
                    'disabled' => 'hidden',
                ],
                'searchFields'             => 'title',
                'dynamicConfigFile'        => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('tw_eprivacy',
                    'Configuration/TCA/Type.php'),
                'iconfile'                 => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('tw_eprivacy').'Resources/Public/Icons/type.svg',
            ],
        ];

        // Register the subject table
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
            'tx_tweprivacy_domain_model_subject',
            'EXT:tw_eprivacy/Resources/Private/Language/locallang_csh_tx_tweprivacy_domain_model_subject.xlf'
        );
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_tweprivacy_domain_model_subject');
        $GLOBALS['TCA']['tx_tweprivacy_domain_model_subject'] = [
            'ctrl' => [
                'title'                    => 'LLL:EXT:tw_eprivacy/Resources/Private/Language/locallang_db.xlf:tx_tweprivacy_domain_model_subject',
                'label'                    => 'title',
                'type'                     => 'mode',
                'tstamp'                   => 'tstamp',
                'crdate'                   => 'crdate',
                'cruser_id'                => 'cruser_id',
                'sortby'                   => 'sorting',
                'languageField'            => 'sys_language_uid',
                'transOrigPointerField'    => 'l10n_parent',
                'transOrigDiffSourceField' => 'l10n_diffsource',
                'delete'                   => 'deleted',
                'enablecolumns'            => [
                    'disabled' => 'hidden',
                ],
                'searchFields'             => 'title,identifier,purpose',
                'dynamicConfigFile'        => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('tw_eprivacy',
                    'Configuration/TCA/Subject.php'),
                'iconfile'                 => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('tw_eprivacy').'Resources/Public/Icons/subject.svg',
            ],
        ];
    }
);
