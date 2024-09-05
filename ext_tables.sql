#
# Table structure for table 'tx_tweprivacy_domain_model_type'
#
CREATE TABLE tx_tweprivacy_domain_model_type
(
    title         varchar(255)        DEFAULT '' NOT NULL,
    description   text,
    needs_consent tinyint(4) unsigned DEFAULT '0'
);

#
# Table structure for table 'tx_tweprivacy_domain_model_subject'
#
CREATE TABLE tx_tweprivacy_domain_model_subject
(
    title         varchar(255)         DEFAULT '' NOT NULL,
    name          varchar(255)         DEFAULT '' NOT NULL,
    identifier    varchar(64)          DEFAULT '' NOT NULL,
    provider      varchar(255)         DEFAULT '' NOT NULL,
    purpose       text,
    purpose_short text,
    type          int(11) unsigned     DEFAULT '1',
    mode          tinyint(11) unsigned DEFAULT '0',
    is_third_party_cookie tinyint(11) unsigned DEFAULT '0',
    third_party_host      text,
    parent_set    int(11) unsigned     DEFAULT '0',
    lifetime      int(11) unsigned     DEFAULT '0',
    public        tinyint(4) unsigned  DEFAULT '1',
    session       tinyint(4) unsigned  DEFAULT '0',

    UNIQUE idlang (identifier, sys_language_uid)
);


#
# Table structure for table 'tt_content'
#
CREATE TABLE tt_content
(
    tx_tweprivacy_consent varchar(255) DEFAULT '' NOT NULL
);
