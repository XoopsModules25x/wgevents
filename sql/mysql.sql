# SQL Dump for wgevents module
# PhpMyAdmin Version: 4.0.4
# http://www.phpmyadmin.net
#
# Host: localhost
# Generated on: Tue Jan 04, 2022 to 09:28:23
# Server version: 5.5.5-10.4.10-MariaDB
# PHP Version: 8.0.1

#
# Structure table for `wgevents_event`
#

CREATE TABLE `wgevents_event` (
    `id`                   INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
    `identifier`           VARCHAR(255)    NULL,
    `catid`                INT(10)         NOT NULL DEFAULT '0',
    `subcats`              VARCHAR(255)    NOT NULL DEFAULT '',
    `name`                 VARCHAR(255)    NOT NULL DEFAULT '',
    `logo`                 VARCHAR(255)    NOT NULL DEFAULT '',
    `desc`                 TEXT            NULL,
    `datefrom`             INT(11)         NOT NULL DEFAULT '0',
    `dateto`               INT(11)         NOT NULL DEFAULT '0',
    `allday`               INT(1)          NOT NULL DEFAULT '0',
    `contact`              TEXT            NULL,
    `email`                VARCHAR(255)    NOT NULL DEFAULT '',
    `url`                  VARCHAR(255)    NOT NULL DEFAULT '',
    `location`             TEXT            NULL,
    `locgmlat`             FLOAT(16,8)     NOT NULL DEFAULT '0.00',
    `locgmlon`             FLOAT(16,8)     NOT NULL DEFAULT '0.00',
    `locgmzoom`            INT(1)          NOT NULL DEFAULT '0',
    `fee_type`             INT(1)          NOT NULL DEFAULT '0',
    `fee`                  TEXT            NULL,
    `paymentinfo`          TEXT            NULL,
    `register_use`         INT(1)          NOT NULL DEFAULT '0',
    `register_from`        INT(11)         NOT NULL DEFAULT '0',
    `register_to`          INT(11)         NOT NULL DEFAULT '0',
    `register_max`         INT(10)         NOT NULL DEFAULT '0',
    `register_listwait`    INT(1)          NOT NULL DEFAULT '0',
    `register_autoaccept`  INT(1)          NOT NULL DEFAULT '0',
    `register_notify`      TEXT            NULL,
    `register_sendermail`  VARCHAR(255)    NOT NULL DEFAULT '',
    `register_sendername`  VARCHAR(255)    NOT NULL DEFAULT '',
    `register_signature`   TEXT            NULL,
    `register_forceverif`  INT(1)          NOT NULL DEFAULT '0',
    `status`               INT(1)          NOT NULL DEFAULT '0',
    `galid`                VARCHAR(255)    NOT NULL DEFAULT '',
    `groups`               VARCHAR(255)    NOT NULL DEFAULT '00000',
    `url_info`             VARCHAR(255)    NOT NULL DEFAULT '',
    `url_registration`     VARCHAR(255)    NOT NULL DEFAULT '',
    `recurr_id`            INT(11)         NOT NULL DEFAULT '0',
    `datecreated`          INT(11)         NOT NULL DEFAULT '0',
    `submitter`            INT(10)         NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

#
# Structure table for `wgevents_question`
#

CREATE TABLE `wgevents_question` (
    `id`              INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
    `evid`            INT(10)         NOT NULL DEFAULT '0',
    `fdid`            INT(10)         NOT NULL DEFAULT '0',
    `type`            INT(10)         NOT NULL DEFAULT '0',
    `caption`         VARCHAR(255)    NOT NULL DEFAULT '',
    `desc`            TEXT            NULL ,
    `values`          TEXT            NULL,
    `placeholder`     VARCHAR(255)    NOT NULL DEFAULT '',
    `required`        INT(1)          NOT NULL DEFAULT '0',
    `print`           INT(1)          NOT NULL DEFAULT '0',
    `weight`          INT(10)         NOT NULL DEFAULT '0',
    `datecreated`     INT(11)         NOT NULL DEFAULT '0',
    `submitter`       INT(10)         NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

#
# Structure table for `wgevents_answer`
#

CREATE TABLE `wgevents_answer` (
    `id`              INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
    `evid`            INT(10)         NOT NULL DEFAULT '0',
    `regid`           INT(10)         NOT NULL DEFAULT '0',
    `queid`           INT(10)         NOT NULL DEFAULT '0',
    `text`            TEXT            NULL,
    `datecreated`     INT(11)         NOT NULL DEFAULT '0',
    `submitter`       INT(10)         NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

#
# Structure table for `wgevents_answer_hist`
#

CREATE TABLE `wgevents_answer_hist` (
    `hist_id`             INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
    `hist_info`           VARCHAR(255)    NOT NULL DEFAULT '',
    `hist_datecreated`    INT(11)         NOT NULL DEFAULT '0',
    `hist_submitter`      INT(10)         NOT NULL DEFAULT '0',
    `id`              INT(8)          NOT NULL DEFAULT '0',
    `evid`            INT(10)         NOT NULL DEFAULT '0',
    `regid`           INT(10)         NOT NULL DEFAULT '0',
    `queid`           INT(10)         NOT NULL DEFAULT '0',
    `text`            TEXT            NULL,
    `datecreated`     INT(11)         NOT NULL DEFAULT '0',
    `submitter`       INT(10)         NOT NULL DEFAULT '0',
    PRIMARY KEY (`hist_id`)
) ENGINE=InnoDB;

#
# Structure table for `wgevents_registration`
#

CREATE TABLE `wgevents_registration` (
    `id`              INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
    `evid`            INT(10)         NOT NULL DEFAULT '0',
    `salutation`      INT(10)         NOT NULL DEFAULT '0',
    `firstname`       VARCHAR(255)    NOT NULL DEFAULT '',
    `lastname`        VARCHAR(255)    NOT NULL DEFAULT '',
    `email`           VARCHAR(255)    NOT NULL DEFAULT '',
    `email_send`      INT(1)          NOT NULL DEFAULT '0',
    `gdpr`            INT(1)          NOT NULL DEFAULT '0',
    `ip`              VARCHAR(45)     NOT NULL DEFAULT '',
    `verifkey`        VARCHAR(255)    NOT NULL DEFAULT '',
    `status`          INT(1)          NOT NULL DEFAULT '0',
    `financial`       INT(1)          NOT NULL DEFAULT '0',
    `paidamount`      FLOAT(16,2)     NOT NULL DEFAULT '0.00',
    `listwait`        INT(1)          NOT NULL DEFAULT '0',
    `datecreated`     INT(11)         NOT NULL DEFAULT '0',
    `submitter`       INT(10)         NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;


#
# Structure table for `wgevents_registration_hist`
#

CREATE TABLE `wgevents_registration_hist` (
    `hist_id`             INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
    `hist_info`           VARCHAR(255)    NOT NULL DEFAULT '',
    `hist_datecreated`    INT(11)         NOT NULL DEFAULT '0',
    `hist_submitter`      INT(10)         NOT NULL DEFAULT '0',
    `id`              INT(8)          NOT NULL DEFAULT '0',
    `evid`            INT(10)         NOT NULL DEFAULT '0',
    `salutation`      INT(10)         NOT NULL DEFAULT '0',
    `firstname`       VARCHAR(255)    NOT NULL DEFAULT '',
    `lastname`        VARCHAR(255)    NOT NULL DEFAULT '',
    `email`           VARCHAR(255)    NOT NULL DEFAULT '',
    `email_send`      INT(1)          NOT NULL DEFAULT '0',
    `gdpr`            INT(1)          NOT NULL DEFAULT '0',
    `ip`              VARCHAR(45)     NOT NULL DEFAULT '',
    `verifkey`        VARCHAR(255)    NOT NULL DEFAULT '',
    `status`          INT(1)          NOT NULL DEFAULT '0',
    `financial`       INT(1)          NOT NULL DEFAULT '0',
    `paidamount`      FLOAT(16,2)     NOT NULL DEFAULT '0.00',
    `listwait`        INT(1)          NOT NULL DEFAULT '0',
    `datecreated`     INT(11)         NOT NULL DEFAULT '0',
    `submitter`       INT(10)         NOT NULL DEFAULT '0',
    PRIMARY KEY (`hist_id`)
) ENGINE=InnoDB;

#
# Structure table for `wgevents_category`
#

CREATE TABLE `wgevents_category` (
    `id`              INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
    `pid`             INT(10)         NOT NULL DEFAULT '0',
    `name`            VARCHAR(255)    NOT NULL DEFAULT '',
    `desc`            TEXT            NULL,
    `logo`            VARCHAR(255)    NOT NULL DEFAULT '',
    `image`           VARCHAR(255)    NOT NULL DEFAULT '',
    `color`           VARCHAR(7)      NOT NULL DEFAULT '',
    `bordercolor`     VARCHAR(7)      NOT NULL DEFAULT '',
    `bgcolor`         VARCHAR(7)      NOT NULL DEFAULT '',
    `othercss`        VARCHAR(255)    NOT NULL DEFAULT '',
    `type`            INT(1)          NOT NULL DEFAULT '0',
    `status`          INT(1)          NOT NULL DEFAULT '0',
    `weight`          INT(10)         NOT NULL DEFAULT '0',
    `identifier`      VARCHAR(255)    NOT NULL DEFAULT '',
    `datecreated`     INT(11)         NOT NULL DEFAULT '0',
    `submitter`       INT(10)         NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

#
# Structure table for `wgevents_field`
#

CREATE TABLE `wgevents_field` (
    `id`                   INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
    `type`                 INT(10)         NOT NULL DEFAULT '0',
    `caption`              VARCHAR(255)    NOT NULL DEFAULT '',
    `desc`                 TEXT            NULL,
    `values`               TEXT            NULL,
    `placeholder`          VARCHAR(255)    NOT NULL DEFAULT '',
    `required`             INT(1)          NOT NULL DEFAULT '0',
    `default`              INT(1)          NOT NULL DEFAULT '0',
    `print`                INT(1)          NOT NULL DEFAULT '0',
    `display_desc`         INT(1)          NOT NULL DEFAULT '0',
    `display_values`       INT(1)          NOT NULL DEFAULT '0',
    `display_placeholder`  INT(1)          NOT NULL DEFAULT '0',
    `status`               INT(1)          NOT NULL DEFAULT '0',
    `custom`               INT(1)          NOT NULL DEFAULT '0',
    `weight`               INT(10)         NOT NULL DEFAULT '0',
    `setid`                INT(10)         NOT NULL DEFAULT '0',
    `datecreated`          INT(11)         NOT NULL DEFAULT '0',
    `submitter`            INT(10)         NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

#
# Structure table for `wgevents_textblock`
#

CREATE TABLE `wgevents_textblock` (
    `id`               INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
    `catid`            INT(10)         NOT NULL DEFAULT '0',
    `name`             VARCHAR(255)    NOT NULL DEFAULT '',
    `text`             TEXT            NULL,
    `class`            INT(10)         NOT NULL DEFAULT '0',
    `weight`           INT(10)         NOT NULL DEFAULT '0',
    `datecreated`      INT(11)         NOT NULL DEFAULT '0',
    `submitter`        INT(10)         NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

#
# Structure table for `wgevents_log`
#

CREATE TABLE `wgevents_log` (
    `id` INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
    `text` TEXT NOT NULL,
    `datecreated` INT(11) NOT NULL DEFAULT '0',
    `submitter` INT(10) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

#
# Structure table for `wgevents_account`
#

CREATE TABLE `wgevents_account` (
     `id` INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
     `type` INT(10) NOT NULL DEFAULT '0',
     `name` VARCHAR(100) NOT NULL DEFAULT '',
     `yourname` VARCHAR(100) NOT NULL DEFAULT '',
     `yourmail` VARCHAR(100) NOT NULL DEFAULT '',
     `username` VARCHAR(100) NOT NULL DEFAULT '',
     `password` VARCHAR(100) NOT NULL DEFAULT '',
     `server_in` VARCHAR(100) NOT NULL DEFAULT '',
     `port_in` INT(10) NOT NULL DEFAULT '0',
     `securetype_in` VARCHAR(20) NOT NULL DEFAULT '',
     `server_out` VARCHAR(100) NOT NULL DEFAULT '',
     `port_out` INT(10) NOT NULL DEFAULT '0',
     `securetype_out` VARCHAR(20) NOT NULL DEFAULT '',
     `limit_hour` INT(10) NOT NULL DEFAULT '0',
     `primary` INT(1) NOT NULL DEFAULT '0',
     `datecreated` INT(11) NOT NULL DEFAULT '0',
     `submitter`      INT(8)       NOT NULL DEFAULT '0',
     PRIMARY KEY (`id`)
) ENGINE=InnoDB;

#
# Structure table for `wgevents_task` 10
#

CREATE TABLE `wgevents_task` (
    `id`          INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
    `type`        INT(10)      NOT NULL DEFAULT '0',
    `params`      TEXT         NOT NULL ,
    `recipient`   VARCHAR(200) NOT NULL DEFAULT '',
    `datecreated` INT(11)      NOT NULL DEFAULT '0',
    `submitter`   INT(10)      NOT NULL DEFAULT '0',
    `status`      INT(1)       NOT NULL DEFAULT '0',
    `datedone`    INT(11)      NULL     DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY `idx_datecreated` (`datecreated`)
) ENGINE=InnoDB;