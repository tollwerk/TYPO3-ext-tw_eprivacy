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

use Tollwerk\TwEprivacy\Domain\Model\Subject;

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

if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

$GLOBALS['TCA']['tx_tweprivacy_domain_model_subject'] = [
    'ctrl'      => $GLOBALS['TCA']['tx_tweprivacy_domain_model_subject']['ctrl'],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, public, title, name, provider, identifier, purpose, type, session',
    ],
    'types'     => [
        '0' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, --palette--;;hiddenpublic, , --palette--;;titletype, --palette--;;nameidentifier, --palette--;;lifetime, purpose;;;richtext:rte_transform[mode=ts_links]'],
        '1' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, --palette--;;hiddenpublic, , --palette--;;titletype, purpose;;;richtext:rte_transform[mode=ts_links]'],
    ],
    'palettes'  => [
        'hiddenpublic'   => ['canNotCollapse' => true, 'showitem' => 'hidden, public'],
        'titletype'      => ['canNotCollapse' => true, 'showitem' => 'title, mode, type, identifier'],
        'nameidentifier' => ['canNotCollapse' => true, 'showitem' => 'provider, name, parent_set'],
        'lifetime'       => ['canNotCollapse' => true, 'showitem' => 'lifetime, session'],
    ],
    'columns'   => [
        'sys_language_uid' => array(
            'exclude' => 1,
            'label'   => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
            'config'  => array(
                'type'                => 'select',
                'renderType'          => 'selectSingle',
                'foreign_table'       => 'sys_language',
                'foreign_table_where' => 'ORDER BY sys_language.title',
                'items'               => array(
                    array('LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages', -1),
                    array('LLL:EXT:lang/locallang_general.xlf:LGL.default_value', 0)
                ),
            ),
        ),
        'l10n_parent'      => array(
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude'     => 1,
            'label'       => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
            'config'      => array(
                'type'                => 'select',
                'renderType'          => 'selectSingle',
                'items'               => array(
                    array('', 0),
                ),
                'foreign_table'       => 'tx_tweprivacy_domain_model_subject',
                'foreign_table_where' => 'AND tx_tweprivacy_domain_model_subject.pid=###CURRENT_PID### AND tx_tweprivacy_domain_model_subject.sys_language_uid IN (-1,0)',
            ),
        ),
        'l10n_diffsource'  => array(
            'config' => array(
                'type' => 'passthrough',
            ),
        ),

        't3ver_label' => array(
            'label'  => 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'max'  => 255,
            )
        ),
        'hidden'      => array(
            'exclude' => 1,
            'label'   => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
            'config'  => array(
                'type' => 'check',
            ),
        ),
        'sorting'     => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'title'       => [
            'exclude' => true,
            'label'   => 'LLL:EXT:tw_eprivacy/Resources/Private/Language/locallang_db.xlf:tx_tweprivacy_domain_model_subject.title',
            'config'  => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'name'        => [
            'exclude'   => true,
            'l10n_mode' => 'exclude',
            'label'     => 'LLL:EXT:tw_eprivacy/Resources/Private/Language/locallang_db.xlf:tx_tweprivacy_domain_model_subject.name',
            'config'    => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'identifier'  => [
            'exclude'   => true,
            'l10n_mode' => 'exclude',
            'label'     => 'LLL:EXT:tw_eprivacy/Resources/Private/Language/locallang_db.xlf:tx_tweprivacy_domain_model_subject.identifier',
            'config'    => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'provider'    => [
            'exclude' => true,
            'label'   => 'LLL:EXT:tw_eprivacy/Resources/Private/Language/locallang_db.xlf:tx_tweprivacy_domain_model_subject.provider',
            'config'  => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'purpose'     => [
            'exclude'       => true,
            'label'         => 'LLL:EXT:tw_eprivacy/Resources/Private/Language/locallang_db.xlf:tx_tweprivacy_domain_model_subject.purpose',
            'config'        => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim,required',
            ],
            'defaultExtras' => 'richtext[]:rte_transform[mode=ts_links]'
        ],
        'type'        => [
            'exclude'   => true,
            'l10n_mode' => 'exclude',
            'label'     => 'LLL:EXT:tw_eprivacy/Resources/Private/Language/locallang_db.xlf:tx_tweprivacy_domain_model_subject.type',
            'config'    => [
                'type'          => 'select',
                'renderType'    => 'selectSingle',
                'foreign_table' => 'tx_tweprivacy_domain_model_type',
                'minitems'      => 0,
                'maxitems'      => 1,
            ],
        ],
        'mode'        => [
            'exclude'   => true,
            'l10n_mode' => 'exclude',
            'label'     => 'LLL:EXT:tw_eprivacy/Resources/Private/Language/locallang_db.xlf:tx_tweprivacy_domain_model_subject.mode',
            'config'    => [
                'type'       => 'select',
                'renderType' => 'selectSingle',
                'items'      => [
                    [
                        'LLL:EXT:tw_eprivacy/Resources/Private/Language/locallang_db.xlf:tx_tweprivacy_domain_model_subject.mode.cookie',
                        Subject::MODE_COOKIE,
                    ],
                    [
                        'LLL:EXT:tw_eprivacy/Resources/Private/Language/locallang_db.xlf:tx_tweprivacy_domain_model_subject.mode.set',
                        Subject::MODE_SET,
                    ],
                ],
                'minitems'   => 1,
                'maxitems'   => 1,
            ],
        ],
        'parent_set'  => [
            'exclude'   => true,
            'l10n_mode' => 'exclude',
            'label'     => 'LLL:EXT:tw_eprivacy/Resources/Private/Language/locallang_db.xlf:tx_tweprivacy_domain_model_subject.parent_set',
            'config'    => [
                'type'                => 'select',
                'renderType'          => 'selectSingle',
                'foreign_table'       => 'tx_tweprivacy_domain_model_subject',
                'foreign_table_where' => 'AND tx_tweprivacy_domain_model_subject.pid=###CURRENT_PID### AND tx_tweprivacy_domain_model_subject.sys_language_uid IN (-1,0) AND tx_tweprivacy_domain_model_subject.mode = '.Subject::MODE_SET.' ORDER BY tx_tweprivacy_domain_model_subject.name',
                'items'               => [['---', 0]],
                'minitems'            => 1,
                'maxitems'            => 1,
            ],
        ],
        'lifetime'    => [
            'exclude'   => true,
            'l10n_mode' => 'exclude',
            'label'     => 'LLL:EXT:tw_eprivacy/Resources/Private/Language/locallang_db.xlf:tx_tweprivacy_domain_model_subject.lifetime',
            'config'    => [
                'type'    => 'input',
                'size'    => 5,
                'eval'    => 'int,required',
                'default' => '0'
            ],
        ],
        'public'      => [
            'exclude'   => true,
            'l10n_mode' => 'exclude',
            'label'     => 'LLL:EXT:tw_eprivacy/Resources/Private/Language/locallang_db.xlf:tx_tweprivacy_domain_model_subject.public',
            'config'    => [
                'type'    => 'check',
                'default' => 1,
            ],
        ],
        'session'     => [
            'exclude'   => true,
            'l10n_mode' => 'exclude',
            'label'     => 'LLL:EXT:tw_eprivacy/Resources/Private/Language/locallang_db.xlf:tx_tweprivacy_domain_model_subject.session',
            'config'    => [
                'type'    => 'check',
                'default' => 0,
            ],
        ],
    ],
];
