SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

DROP TABLE IF EXISTS `tx_tweprivacy_domain_model_subject`;
CREATE TABLE `tx_tweprivacy_domain_model_subject` (
    `uid`              int(10) UNSIGNED                        NOT NULL,
    `pid`              int(11)                                 NOT NULL DEFAULT '0',
    `tstamp`           int(10) UNSIGNED                        NOT NULL DEFAULT '0',
    `crdate`           int(10) UNSIGNED                        NOT NULL DEFAULT '0',
    `cruser_id`        int(10) UNSIGNED                        NOT NULL DEFAULT '0',
    `deleted`          smallint(5) UNSIGNED                    NOT NULL DEFAULT '0',
    `hidden`           smallint(5) UNSIGNED                    NOT NULL DEFAULT '0',
    `sorting`          int(11)                                 NOT NULL DEFAULT '0',
    `sys_language_uid` int(11)                                 NOT NULL DEFAULT '0',
    `l10n_parent`      int(10) UNSIGNED                        NOT NULL DEFAULT '0',
    `l10n_state`       text COLLATE utf8mb4_unicode_ci,
    `l10n_diffsource`  mediumblob,
    `t3ver_oid`        int(10) UNSIGNED                        NOT NULL DEFAULT '0',
    `t3ver_wsid`       int(10) UNSIGNED                        NOT NULL DEFAULT '0',
    `t3ver_state`      smallint(6)                             NOT NULL DEFAULT '0',
    `t3ver_stage`      int(11)                                 NOT NULL DEFAULT '0',
    `t3ver_count`      int(10) UNSIGNED                        NOT NULL DEFAULT '0',
    `t3ver_tstamp`     int(10) UNSIGNED                        NOT NULL DEFAULT '0',
    `t3ver_move_id`    int(10) UNSIGNED                        NOT NULL DEFAULT '0',
    `title`            varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `identifier`       varchar(64) COLLATE utf8mb4_unicode_ci  NOT NULL DEFAULT '',
    `purpose`          text COLLATE utf8mb4_unicode_ci,
    `type`             int(11) UNSIGNED                                 DEFAULT '1',
    `name`             varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `provider`         varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `mode`             tinyint(11) UNSIGNED                             DEFAULT '0',
    `parent_set`       int(11) UNSIGNED                                 DEFAULT '0',
    `lifetime`         int(11) UNSIGNED                                 DEFAULT '0',
    `public`           tinyint(4) UNSIGNED                              DEFAULT '1',
    `session`          tinyint(4) UNSIGNED                              DEFAULT '0'
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

INSERT INTO `tx_tweprivacy_domain_model_subject` (`uid`, `pid`, `tstamp`, `crdate`, `cruser_id`, `deleted`, `hidden`,
                                                  `sorting`, `sys_language_uid`, `l10n_parent`, `l10n_state`,
                                                  `l10n_diffsource`, `t3ver_oid`, `t3ver_wsid`, `t3ver_state`,
                                                  `t3ver_stage`, `t3ver_count`, `t3ver_tstamp`, `t3ver_move_id`,
                                                  `title`, `identifier`, `purpose`, `type`, `name`, `provider`, `mode`,
                                                  `parent_set`, `lifetime`, `public`, `session`)
VALUES (1, 394, 1591968236, 1581669674, 1, 0, 0, 256, 0, 0, NULL,
        0x613a31333a7b733a31363a227379735f6c616e67756167655f756964223b4e3b733a363a2268696464656e223b4e3b733a363a227075626c6963223b4e3b733a353a227469746c65223b4e3b733a343a226d6f6465223b4e3b733a343a2274797065223b4e3b733a31303a226964656e746966696572223b4e3b733a383a2270726f7669646572223b4e3b733a343a226e616d65223b4e3b733a31303a22706172656e745f736574223b4e3b733a383a226c69666574696d65223b4e3b733a373a2273657373696f6e223b4e3b733a373a22707572706f7365223b4e3b7d,
        0, 0, 0, 0, 0, 0, 0, 'ePrivacy Consent', 'eprivacy.consent',
        'Used to store the selection of ePrivacy subjects (e.g. cookies) the user has given their consent to.', 1,
        'eprivacy_consent', 'TYPO3', 0, 0, 2629800, 1, 0);

DROP TABLE IF EXISTS `tx_tweprivacy_domain_model_type`;
CREATE TABLE `tx_tweprivacy_domain_model_type` (
    `uid`              int(10) UNSIGNED                        NOT NULL,
    `pid`              int(11)                                 NOT NULL DEFAULT '0',
    `tstamp`           int(10) UNSIGNED                        NOT NULL DEFAULT '0',
    `crdate`           int(10) UNSIGNED                        NOT NULL DEFAULT '0',
    `cruser_id`        int(10) UNSIGNED                        NOT NULL DEFAULT '0',
    `deleted`          smallint(5) UNSIGNED                    NOT NULL DEFAULT '0',
    `hidden`           smallint(5) UNSIGNED                    NOT NULL DEFAULT '0',
    `sorting`          int(11)                                 NOT NULL DEFAULT '0',
    `sys_language_uid` int(11)                                 NOT NULL DEFAULT '0',
    `l10n_parent`      int(10) UNSIGNED                        NOT NULL DEFAULT '0',
    `l10n_state`       text COLLATE utf8mb4_unicode_ci,
    `l10n_diffsource`  mediumblob,
    `t3ver_oid`        int(10) UNSIGNED                        NOT NULL DEFAULT '0',
    `t3ver_wsid`       int(10) UNSIGNED                        NOT NULL DEFAULT '0',
    `t3ver_state`      smallint(6)                             NOT NULL DEFAULT '0',
    `t3ver_stage`      int(11)                                 NOT NULL DEFAULT '0',
    `t3ver_count`      int(10) UNSIGNED                        NOT NULL DEFAULT '0',
    `t3ver_tstamp`     int(10) UNSIGNED                        NOT NULL DEFAULT '0',
    `t3ver_move_id`    int(10) UNSIGNED                        NOT NULL DEFAULT '0',
    `title`            varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `needs_consent`    tinyint(4) UNSIGNED                              DEFAULT '0',
    `description`      text COLLATE utf8mb4_unicode_ci
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

INSERT INTO `tx_tweprivacy_domain_model_type` (`uid`, `pid`, `tstamp`, `crdate`, `cruser_id`, `deleted`, `hidden`,
                                               `sorting`, `sys_language_uid`, `l10n_parent`, `l10n_state`,
                                               `l10n_diffsource`, `t3ver_oid`, `t3ver_wsid`, `t3ver_state`,
                                               `t3ver_stage`, `t3ver_count`, `t3ver_tstamp`, `t3ver_move_id`, `title`,
                                               `needs_consent`, `description`)
VALUES (1, 394, 1591968334, 1581668678, 1, 0, 0, 256, 0, 0, NULL,
        0x613a353a7b733a31363a227379735f6c616e67756167655f756964223b4e3b733a363a2268696464656e223b4e3b733a353a227469746c65223b4e3b733a31313a226465736372697074696f6e223b4e3b733a31333a226e656564735f636f6e73656e74223b4e3b7d,
        0, 0, 0, 0, 0, 0, 0, 'Functional Cookies', 0,
        'Functional cookies are required to provide you with basic functions while using the website.'),
       (2, 394, 1591968379, 1581668689, 1, 0, 0, 512, 0, 0, NULL,
        0x613a353a7b733a31363a227379735f6c616e67756167655f756964223b4e3b733a363a2268696464656e223b4e3b733a353a227469746c65223b4e3b733a31313a226465736372697074696f6e223b4e3b733a31333a226e656564735f636f6e73656e74223b4e3b7d,
        0, 0, 0, 0, 0, 0, 0, 'Presentational Cookies', 1,
        'Presentational functions make it easier for you to use the website and find content.&nbsp;
'),
(3, 394, 1591968403, 1581668708, 1, 0, 0, 768, 0, 0, NULL, 0x613a353a7b733a31363a227379735f6c616e67756167655f756964223b4e3b733a363a2268696464656e223b4e3b733a353a227469746c65223b4e3b733a31313a226465736372697074696f6e223b4e3b733a31333a226e656564735f636f6e73656e74223b4e3b7d, 0, 0, 0, 0, 0, 0, 0, 'Marketing Cookies', 1, 'We collect anonymised data for statistics and analysis in order to further improve our service and website. With the help of these cookies we can, for example, determine the number of visitors and the effect of certain pages on our website and optimise our content.');


ALTER TABLE `tx_tweprivacy_domain_model_subject`
    ADD PRIMARY KEY (`uid`),
    ADD UNIQUE KEY `idlang` (`identifier`, `sys_language_uid`),
    ADD KEY `parent` (`pid`, `deleted`, `hidden`),
    ADD KEY `t3ver_oid` (`t3ver_oid`, `t3ver_wsid`);

ALTER TABLE `tx_tweprivacy_domain_model_type`
    ADD PRIMARY KEY (`uid`),
    ADD KEY `parent` (`pid`, `deleted`, `hidden`),
    ADD KEY `t3ver_oid` (`t3ver_oid`, `t3ver_wsid`);

ALTER TABLE `tx_tweprivacy_domain_model_subject`
    MODIFY `uid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 2;

ALTER TABLE `tx_tweprivacy_domain_model_type`
    MODIFY `uid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 4;
COMMIT;
