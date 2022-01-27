# SQL Dump for wgevents module
# PhpMyAdmin Version: 4.0.4
# http://www.phpmyadmin.net
#
# Host: localhost
# Generated on: Tue Jan 04, 2022 to 09:28:23
# Server version: 5.5.5-10.4.10-MariaDB
# PHP Version: 8.0.1

#
# Structure table for `wgevents_events`
#

CREATE TABLE `wgevents_events` (
  `ev_id`                   INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ev_catid`                INT(10)         NOT NULL DEFAULT '0',
  `ev_name`                 VARCHAR(255)    NOT NULL DEFAULT '',
  `ev_logo`                 VARCHAR(255)    NOT NULL DEFAULT '',
  `ev_desc`                 TEXT            NULL,
  `ev_datefrom`             INT(11)         NOT NULL DEFAULT '0',
  `ev_dateto`               INT(11)         NOT NULL DEFAULT '0',
  `ev_contact`              TEXT            NULL,
  `ev_email`                VARCHAR(255)    NOT NULL DEFAULT '',
  `ev_location`             VARCHAR(255)    NOT NULL DEFAULT '',
  `ev_locgmlat`             FLOAT(16,2)     NOT NULL DEFAULT '0.00',
  `ev_locgmlon`             FLOAT(16,2)     NOT NULL DEFAULT '0.00',
  `ev_locgmzoom`            INT(1)          NOT NULL DEFAULT '0',
  `ev_fee`                  FLOAT(16,2)     NOT NULL DEFAULT '0.00',
  `ev_register_use`         INT(1)          NOT NULL DEFAULT '0',
  `ev_register_from`        INT(11)         NOT NULL DEFAULT '0',
  `ev_register_to`          INT(11)         NOT NULL DEFAULT '0',
  `ev_register_max`         INT(10)         NOT NULL DEFAULT '0',
  `ev_register_listwait`    INT(1)          NOT NULL DEFAULT '0',
  `ev_register_autoaccept`  INT(10)         NOT NULL DEFAULT '0',
  `ev_register_notify`      TEXT            NULL,
  `ev_register_sendermail`  VARCHAR(255)    NOT NULL DEFAULT '',
  `ev_register_sendername`  VARCHAR(255)    NOT NULL DEFAULT '',
  `ev_register_signature`   TEXT            NULL,
  `ev_status`               INT(1)          NOT NULL DEFAULT '0',
  `ev_galid`                INT(10)         NOT NULL DEFAULT '0',
  `ev_datecreated`          INT(11)         NOT NULL DEFAULT '0',
  `ev_submitter`            INT(10)         NOT NULL DEFAULT '0',
  PRIMARY KEY (`ev_id`)
) ENGINE=InnoDB;

#
# Structure table for `wgevents_additionals`
#

CREATE TABLE `wgevents_additionals` (
  `add_id`              INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `add_evid`            INT(10)         NOT NULL DEFAULT '0',
  `add_atid`            INT(10)         NOT NULL DEFAULT '0',
  `add_type`            INT(10)         NOT NULL DEFAULT '0',
  `add_caption`         VARCHAR(255)    NOT NULL DEFAULT '',
  `add_desc`            TEXT            NULL ,
  `add_values`          TEXT            NULL,
  `add_placeholder`     VARCHAR(255)    NOT NULL DEFAULT '',
  `add_required`        INT(1)          NOT NULL DEFAULT '0',
  `add_print`           INT(1)          NOT NULL DEFAULT '0',
  `add_weight`          INT(10)         NOT NULL DEFAULT '0',
  `add_datecreated`     INT(11)         NOT NULL DEFAULT '0',
  `add_submitter`       INT(10)         NOT NULL DEFAULT '0',
  PRIMARY KEY (`add_id`)
) ENGINE=InnoDB;

#
# Structure table for `wgevents_answers`
#

CREATE TABLE `wgevents_answers` (
  `ans_id`              INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ans_evid`            INT(10)         NOT NULL DEFAULT '0',
  `ans_regid`           INT(10)         NOT NULL DEFAULT '0',
  `ans_addid`           INT(10)         NOT NULL DEFAULT '0',
  `ans_text`            TEXT            NULL,
  `ans_datecreated`     INT(11)         NOT NULL DEFAULT '0',
  `ans_submitter`       INT(10)         NOT NULL DEFAULT '0',
  PRIMARY KEY (`ans_id`)
) ENGINE=InnoDB;

#
# Structure table for `wgevents_answers_hist`
#

CREATE TABLE `wgevents_answers_hist` (
  `hist_id`             INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `hist_info`           VARCHAR(255)    NOT NULL DEFAULT '',
  `hist_datecreated`    INT(11)         NOT NULL DEFAULT '0',
  `hist_submitter`      INT(10)         NOT NULL DEFAULT '0',
  `ans_id`              INT(8)          NOT NULL DEFAULT '0',
  `ans_evid`            INT(10)         NOT NULL DEFAULT '0',
  `ans_regid`           INT(10)         NOT NULL DEFAULT '0',
  `ans_addid`           INT(10)         NOT NULL DEFAULT '0',
  `ans_text`            TEXT            NULL,
  `ans_datecreated`     INT(11)         NOT NULL DEFAULT '0',
  `ans_submitter`       INT(10)         NOT NULL DEFAULT '0',
  PRIMARY KEY (`hist_id`)
) ENGINE=InnoDB;

#
# Structure table for `wgevents_registrations`
#

CREATE TABLE `wgevents_registrations` (
  `reg_id`              INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `reg_evid`            INT(10)         NOT NULL DEFAULT '0',
  `reg_salutation`      INT(10)         NOT NULL DEFAULT '0',
  `reg_firstname`       VARCHAR(255)    NOT NULL DEFAULT '',
  `reg_lastname`        VARCHAR(255)    NOT NULL DEFAULT '',
  `reg_email`           VARCHAR(255)    NOT NULL DEFAULT '',
  `reg_email_send`      INT(1)          NOT NULL DEFAULT '0',
  `reg_gdpr`            INT(1)          NOT NULL DEFAULT '0',
  `reg_ip`              VARCHAR(45)     NOT NULL DEFAULT '',
  `reg_verifkey`        VARCHAR(255)    NOT NULL DEFAULT '',
  `reg_status`          INT(1)          NOT NULL DEFAULT '0',
  `reg_financial`       INT(1)          NOT NULL DEFAULT '0',
  `reg_listwait`        INT(1)          NOT NULL DEFAULT '0',
  `reg_datecreated`     INT(11)         NOT NULL DEFAULT '0',
  `reg_submitter`       INT(10)         NOT NULL DEFAULT '0',
  PRIMARY KEY (`reg_id`)
) ENGINE=InnoDB;


#
# Structure table for `wgevents_registrations_hist`
#

CREATE TABLE `wgevents_registrations_hist` (
  `hist_id`             INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `hist_info`           VARCHAR(255)    NOT NULL DEFAULT '',
  `hist_datecreated`    INT(11)         NOT NULL DEFAULT '0',
  `hist_submitter`      INT(10)         NOT NULL DEFAULT '0',
  `reg_id`              INT(8)          NOT NULL DEFAULT '0',
  `reg_evid`            INT(10)         NOT NULL DEFAULT '0',
  `reg_salutation`      INT(10)         NOT NULL DEFAULT '0',
  `reg_firstname`       VARCHAR(255)    NOT NULL DEFAULT '',
  `reg_lastname`        VARCHAR(255)    NOT NULL DEFAULT '',
  `reg_email`           VARCHAR(255)    NOT NULL DEFAULT '',
  `reg_email_send`      INT(1)          NOT NULL DEFAULT '0',
  `reg_gdpr`            INT(1)          NOT NULL DEFAULT '0',
  `reg_ip`              VARCHAR(45)     NOT NULL DEFAULT '',
  `reg_verifkey`        VARCHAR(255)    NOT NULL DEFAULT '',
  `reg_status`          INT(1)          NOT NULL DEFAULT '0',
  `reg_financial`       INT(1)          NOT NULL DEFAULT '0',
  `reg_listwait`        INT(1)          NOT NULL DEFAULT '0',
  `reg_datecreated`     INT(11)         NOT NULL DEFAULT '0',
  `reg_submitter`       INT(10)         NOT NULL DEFAULT '0',
  PRIMARY KEY (`hist_id`)
) ENGINE=InnoDB;

#
# Structure table for `wgevents_categories`
#

CREATE TABLE `wgevents_categories` (
  `cat_id`              INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cat_pid`             INT(10)         NOT NULL DEFAULT '0',
  `cat_name`            VARCHAR(255)    NOT NULL DEFAULT '',
  `cat_desc`            TEXT            NULL,
  `cat_logo`            VARCHAR(255)    NOT NULL DEFAULT '',
  `cat_color`           VARCHAR(7)      NOT NULL DEFAULT '',
  `cat_bordercolor`     VARCHAR(7)      NOT NULL DEFAULT '',
  `cat_bgcolor`         VARCHAR(7)      NOT NULL DEFAULT '',
  `cat_othercss`        VARCHAR(255)    NOT NULL DEFAULT '',
  `cat_status`          INT(1)          NOT NULL DEFAULT '0',
  `cat_weight`          INT(10)         NOT NULL DEFAULT '0',
  `cat_datecreated`     INT(11)         NOT NULL DEFAULT '0',
  `cat_submitter`       INT(10)         NOT NULL DEFAULT '0',
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB;

#
# Structure table for `wgevents_addtypes`
#

CREATE TABLE `wgevents_addtypes` (
  `at_id`                   INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `at_type`                 INT(10)         NOT NULL DEFAULT '0',
  `at_caption`              VARCHAR(255)    NOT NULL DEFAULT '',
  `at_desc`                 TEXT            NULL,
  `at_values`               TEXT            NULL,
  `at_placeholder`          VARCHAR(255)    NOT NULL DEFAULT '',
  `at_required`             INT(1)          NOT NULL DEFAULT '0',
  `at_default`              INT(1)          NOT NULL DEFAULT '0',
  `at_print`                INT(1)          NOT NULL DEFAULT '0',
  `at_display_values`       INT(1)          NOT NULL DEFAULT '0',
  `at_display_placeholder`  INT(1)          NOT NULL DEFAULT '0',
  `at_status`               INT(1)          NOT NULL DEFAULT '0',
  `at_custom`               INT(1)          NOT NULL DEFAULT '0',
  `at_weight`               INT(10)         NOT NULL DEFAULT '0',
  `at_setid`                INT(10)         NOT NULL DEFAULT '0',
  `at_datecreated`          INT(11)         NOT NULL DEFAULT '0',
  `at_submitter`            INT(10)         NOT NULL DEFAULT '0',
  PRIMARY KEY (`at_id`)
) ENGINE=InnoDB;

#
# Structure table for `wgevents_textblocks`
#

CREATE TABLE `wgevents_textblocks` (
  `tb_id`               INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tb_name`             VARCHAR(255)    NOT NULL DEFAULT '',
  `tb_text`             TEXT            NULL,
  `tb_weight`           INT(10)         NOT NULL DEFAULT '0',
  `tb_datecreated`      INT(11)         NOT NULL DEFAULT '0',
  `tb_submitter`        INT(10)         NOT NULL DEFAULT '0',
  PRIMARY KEY (`tb_id`)
) ENGINE=InnoDB;

#
# Structure table for `wgevents_logs`
#

CREATE TABLE `wgevents_logs` (
  `log_id` INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `log_text` VARCHAR(255) NOT NULL DEFAULT '',
  `log_datecreated` INT(11) NOT NULL DEFAULT '0',
  `log_submitter` INT(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB;

#
# Structure table for `wgevents_accounts`
#

CREATE TABLE `wgevents_accounts` (
     `acc_id` INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
     `acc_type` INT(10) NOT NULL DEFAULT '0',
     `acc_name` VARCHAR(100) NOT NULL DEFAULT '',
     `acc_yourname` VARCHAR(100) NOT NULL DEFAULT '',
     `acc_yourmail` VARCHAR(100) NOT NULL DEFAULT '',
     `acc_username` VARCHAR(100) NOT NULL DEFAULT '',
     `acc_password` VARCHAR(100) NOT NULL DEFAULT '',
     `acc_server_in` VARCHAR(100) NOT NULL DEFAULT '',
     `acc_port_in` INT(10) NOT NULL DEFAULT '0',
     `acc_securetype_in` VARCHAR(20) NOT NULL DEFAULT '',
     `acc_server_out` VARCHAR(100) NOT NULL DEFAULT '',
     `acc_port_out` INT(10) NOT NULL DEFAULT '0',
     `acc_securetype_out` VARCHAR(20) NOT NULL DEFAULT '',
     `acc_default` INT(1) NOT NULL DEFAULT '0',
     `acc_inbox` VARCHAR(100) NOT NULL DEFAULT '',
     `acc_datecreated` INT(11) NOT NULL DEFAULT '0',
     `acc_submitter`      INT(8)       NOT NULL DEFAULT '0',
     PRIMARY KEY (`acc_id`)
) ENGINE=InnoDB;

