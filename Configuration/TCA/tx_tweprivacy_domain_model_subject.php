<?php
return [
    'ctrl'      => [
        'title'                    => 'LLL:EXT:tw_eprivacy/Resources/Private/Language/locallang_db.xlf:tx_tweprivacy_domain_model_subject',
        'label'                    => 'title',
        'type'                     => 'mode',
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
        'searchFields'             => 'title,identifier,purpose',
        'iconfile'                 => 'EXT:tw_eprivacy/Resources/Public/Icons/subject.svg'
    ],
    'types'     => [
        '0' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, --palette--;;hiddenpublic, , --palette--;;titletype, --palette--;;nameidentifier, --palette--;;lifetime, purpose'],
        '1' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, --palette--;;hiddenpublic, , --palette--;;titletype, purpose'],
    ],
    'palettes'  => [
        'hiddenpublic'   => ['showitem' => 'hidden, public'],
        'titletype'      => ['showitem' => 'title, mode, type, identifier'],
        'nameidentifier' => ['showitem' => 'provider, name, parent_set'],
        'lifetime'       => ['showitem' => 'lifetime, session'],
    ],
    'columns'   => [
        'sys_language_uid' => [
            'exclude' => true,
            'label'   => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config'  => [
                'type'       => 'select',
                'renderType' => 'selectSingle',
                'special'    => 'languages',
                'items'      => [
                    [
                        'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages',
                        -1,
                        'flags-multiple'
                    ]
                ],
                'default'    => 0,
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
                'foreign_table'       => 'tx_tweprivacy_domain_model_subject',
                'foreign_table_where' => 'AND {#tx_tweprivacy_domain_model_subject}.{#pid}=###CURRENT_PID### AND {#tx_tweprivacy_domain_model_subject}.{#sys_language_uid} IN (-1,0)',
            ],
        ],
        'sorting'          => [
            'config' => [
                'type' => 'passthrough',
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
        'hidden'           => [
            'exclude'   => true,
            'label'     => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config'    => [
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

        'title'      => [
            'exclude' => true,
            'label'   => 'LLL:EXT:tw_eprivacy/Resources/Private/Language/locallang_db.xlf:tx_tweprivacy_domain_model_subject.title',
            'config'  => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'name'       => [
            'exclude'   => true,
            'l10n_mode' => 'exclude',
            'label'     => 'LLL:EXT:tw_eprivacy/Resources/Private/Language/locallang_db.xlf:tx_tweprivacy_domain_model_subject.name',
            'config'    => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'identifier' => [
            'exclude'   => true,
            'l10n_mode' => 'exclude',
            'label'     => 'LLL:EXT:tw_eprivacy/Resources/Private/Language/locallang_db.xlf:tx_tweprivacy_domain_model_subject.identifier',
            'config'    => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'provider'   => [
            'exclude' => true,
            'label'   => 'LLL:EXT:tw_eprivacy/Resources/Private/Language/locallang_db.xlf:tx_tweprivacy_domain_model_subject.provider',
            'config'  => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'purpose'    => [
            'exclude' => true,
            'label'   => 'LLL:EXT:tw_eprivacy/Resources/Private/Language/locallang_db.xlf:tx_tweprivacy_domain_model_subject.purpose',
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
                'eval'                  => 'trim,required',
            ],

        ],
        'type'       => [
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
        'mode'       => [
            'exclude'   => true,
            'l10n_mode' => 'exclude',
            'label'     => 'LLL:EXT:tw_eprivacy/Resources/Private/Language/locallang_db.xlf:tx_tweprivacy_domain_model_subject.mode',
            'config'    => [
                'type'       => 'select',
                'renderType' => 'selectSingle',
                'items'      => [
                    [
                        'LLL:EXT:tw_eprivacy/Resources/Private/Language/locallang_db.xlf:tx_tweprivacy_domain_model_subject.mode.cookie',
                        \Tollwerk\TwEprivacy\Domain\Model\Subject::MODE_COOKIE,
                    ],
                    [
                        'LLL:EXT:tw_eprivacy/Resources/Private/Language/locallang_db.xlf:tx_tweprivacy_domain_model_subject.mode.set',
                        \Tollwerk\TwEprivacy\Domain\Model\Subject::MODE_SET,
                    ],
                ],
                'minitems'   => 1,
                'maxitems'   => 1,
            ],
        ],
        'parent_set' => [
            'exclude'   => true,
            'l10n_mode' => 'exclude',
            'label'     => 'LLL:EXT:tw_eprivacy/Resources/Private/Language/locallang_db.xlf:tx_tweprivacy_domain_model_subject.parent_set',
            'config'    => [
                'type'                => 'select',
                'renderType'          => 'selectSingle',
                'foreign_table'       => 'tx_tweprivacy_domain_model_subject',
                'foreign_table_where' => 'AND {#tx_tweprivacy_domain_model_subject}.{#pid}=###CURRENT_PID### AND {#tx_tweprivacy_domain_model_subject}.{#sys_language_uid} IN (-1,0) AND tx_tweprivacy_domain_model_subject.mode = '.\Tollwerk\TwEprivacy\Domain\Model\Subject::MODE_SET.' ORDER BY tx_tweprivacy_domain_model_subject.name',

                'items'               => [['---', 0]],
                'minitems'            => 0,
                'maxitems'            => 1,
                'eval'                => 'int',
                'default'             => '0'
            ],
        ],
        'lifetime'   => [
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
        'public'     => [
            'exclude'   => true,
            'l10n_mode' => 'exclude',
            'label'     => 'LLL:EXT:tw_eprivacy/Resources/Private/Language/locallang_db.xlf:tx_tweprivacy_domain_model_subject.public',
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
        'session'    => [
            'exclude'   => true,
            'l10n_mode' => 'exclude',
            'label'     => 'LLL:EXT:tw_eprivacy/Resources/Private/Language/locallang_db.xlf:tx_tweprivacy_domain_model_subject.session',
            'config'    => [
                'type'       => 'check',
                'renderType' => 'checkboxToggle',
                'items'      => [
                    [
                        0 => '',
                        1 => '',
                    ]
                ],
                'default'    => 0,
            ],
        ],
    ],
];
