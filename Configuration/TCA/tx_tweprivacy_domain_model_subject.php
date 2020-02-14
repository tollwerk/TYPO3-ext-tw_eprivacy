<?php
return [
    'ctrl'      => [
        'title'                    => 'LLL:EXT:tw_eprivacy/Resources/Private/Language/locallang_db.xlf:tx_tweprivacy_domain_model_subject',
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
        'searchFields'             => 'title,identifier,description',
        'iconfile'                 => 'EXT:tw_eprivacy/Resources/Public/Icons/subject.svg'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, title, name, identifier, description, type',
    ],
    'types'     => [
        '1' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, --palette--;;titletype, --palette--;;nameidentifier, description'],
    ],
    'palettes'  => [
        'titletype' => ['showitem' => 'title, type'],
        'nameidentifier' => ['showitem' => 'name, identifier'],
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
            'exclude'     => true,
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
            'exclude' => true,
            'label'   => 'LLL:EXT:tw_eprivacy/Resources/Private/Language/locallang_db.xlf:tx_tweprivacy_domain_model_subject.name',
            'config'  => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'identifier'  => [
            'exclude' => true,
            'label'   => 'LLL:EXT:tw_eprivacy/Resources/Private/Language/locallang_db.xlf:tx_tweprivacy_domain_model_subject.identifier',
            'config'  => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required,unique'
            ],
        ],
        'description' => [
            'exclude' => true,
            'label'   => 'LLL:EXT:tw_eprivacy/Resources/Private/Language/locallang_db.xlf:tx_tweprivacy_domain_model_subject.description',
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
        'type'        => [
            'exclude' => true,
            'label'   => 'LLL:EXT:tw_eprivacy/Resources/Private/Language/locallang_db.xlf:tx_tweprivacy_domain_model_subject.type',
            'config'  => [
                'type'          => 'select',
                'renderType'    => 'selectSingle',
                'foreign_table' => 'tx_tweprivacy_domain_model_type',
                'minitems'      => 0,
                'maxitems'      => 1,
            ],
        ],

    ],
];
