#
# Table structure for table 'tx_tweprivacy_domain_model_type'
#
CREATE TABLE tx_tweprivacy_domain_model_type
(
    title         varchar(255)        DEFAULT '' NOT NULL,
    description   text,
    needs_consent tinyint(4) unsigned DEFAULT '0',
);

#
# Table structure for table 'tx_tweprivacy_domain_model_subject'
#
CREATE TABLE tx_tweprivacy_domain_model_subject
(
    title       varchar(255)     DEFAULT '' NOT NULL,
    name        varchar(255)     DEFAULT '' NOT NULL,
    identifier  varchar(255)     DEFAULT '' NOT NULL,
    description text,
    type        int(11) unsigned DEFAULT '1',
);
