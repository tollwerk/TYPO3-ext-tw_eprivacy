<?php
return [
    'ctrl'      => [
        'title'                    => 'LLL:EXT:tw_eprivacy/Resources/Private/Language/locallang_db.xlf:tx_tweprivacy_domain_model_type',
        'label'                    => 'title',
        'tstamp'                   => 'tstamp',
        'crdate'                   => 'crdate',
        'cruser_id'                => 'cruser_id',
        'sortby'                   => 'sorting',
        'versioningWS'             => true,
        'languageField'            => 'sys_language_uid',
        'transOrigPointerField'    => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete'                   => 'deleted',
        'enablecolumns'            => [
            'disabled' => 'hidden',
        ],
        'searchFields'             => 'title',
        'iconfile'                 => 'EXT:tw_eprivacy/Resources/Public/Icons/type.svg',
        'security'                 => [
            'ignorePageTypeRestriction' => true
        ],
    ],
    'types'     => [
        '1' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, title, description, needs_consent'],
    ],
    'columns'   => [
        'sys_language_uid' => [
            'exclude' => true,
            'label'   => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config'  => [
                'type'       => 'language',
            ],
        ],
        'l10n_parent'      => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label'       => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config'      => [
                'type'                => 'select',
                'renderType'          => 'selectSingle',
                'default'             => 0,
                'items'               => [
                    ['', 0],
                ],
                'foreign_table'       => 'tx_tweprivacy_domain_model_type',
                'foreign_table_where' => 'AND {#tx_tweprivacy_domain_model_type}.{#pid}=###CURRENT_PID### AND {#tx_tweprivacy_domain_model_type}.{#sys_language_uid} IN (-1,0)',
            ],
        ],
        'l10n_diffsource'  => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        't3ver_label'      => [
            'label'  => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.versionLabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max'  => 255,
            ],
        ],
        'sorting'          => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'hidden'           => [
            'exclude' => true,
            'label'   => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config'  => [
                'type'       => 'check',
                'renderType' => 'checkboxToggle',
                'items'      => [
                    [
                        0                    => '',
                        1                    => '',
                        'invertStateDisplay' => true
                    ]
                ],
            ],
        ],
        'needs_consent'    => [
            'exclude'   => true,
            'l10n_mode' => 'exclude',
            'label'     => 'LLL:EXT:tw_eprivacy/Resources/Private/Language/locallang_db.xlf:tx_tweprivacy_domain_model_type.needs_consent',
            'config'    => [
                'type'       => 'check',
                'renderType' => 'checkboxToggle',
                'items'      => [
                    [
                        0 => '',
                        1 => '',
                    ]
                ],
                'default'    => 1,
            ],
        ],
        'title'            => [
            'exclude' => true,
            'label'   => 'LLL:EXT:tw_eprivacy/Resources/Private/Language/locallang_db.xlf:tx_tweprivacy_domain_model_type.title',
            'config'  => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'required' => true,
            ],
        ],
        'description'      => [
            'exclude' => true,
            'label'   => 'LLL:EXT:tw_eprivacy/Resources/Private/Language/locallang_db.xlf:tx_tweprivacy_domain_model_type.description',
            'config'  => [
                'type'                  => 'text',
                'enableRichtext'        => true,
                'richtextConfiguration' => 'default',
                'fieldControl'          => [
                    'fullScreenRichtext' => [
                        'disabled' => false,
                    ],
                ],
                'cols'                  => 40,
                'rows'                  => 15,
                'eval'                  => 'trim',
                'required' => true,
            ],
        ],
    ],
];
