<?php

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * Wgevents module for xoops
 *
 * @param mixed      $module
 * @param null|mixed $prev_version
 * @package        Wgevents
 * @since          1.0
 * @min_xoops      2.5.11
 * @author         Wedega - Email:<webmaster@wedega.com> - Website:<https://wedega.com>
 * @version        $Id: 1.0 update.php 1 Mon 2018-03-19 10:04:53Z XOOPS Project (www.xoops.org) $
 * @copyright      module for xoops
 * @license        GPL 2.0 or later
 */

use XoopsModules\Wgevents\Common\ {
    Configurator,
    Migrate,
    MigrateHelper
};

/**
 * @param      $module
 * @param null $prev_version
 *
 * @return bool|null
 */
function xoops_module_update_wgevents($module, $prev_version = null)
{
    $moduleDirName = $module->dirname();

    //check preload folder and remove replace index.php by index.html if exist
    if (\is_file(\XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/preloads/index.php')) {
        //delete olf file
        \unlink(\XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/preloads/index.php');
        //create html file
        $myfile = \fopen(\XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/preloads/index.html', 'wb');
        \fwrite($myfile, '<script>history.go(-1);</script>');
        \fclose($myfile);
    }

    //wgevents_check_db($module);
    
    // update DB corresponding to sql/mysql.sql
    $configurator = new Configurator();
    $migrate = new Migrate($configurator);
    //$migrate->saveCurrentSchema();

    $fileSql = \XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/sql/mysql.sql';
    // ToDo: add function setDefinitionFile to .\class\libraries\vendor\xoops\xmf\src\Database\Migrate.php
    // Todo: once we are using setDefinitionFile this part has to be adapted
    //$fileYaml = \XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/sql/update_' . $moduleDirName . '_migrate.yml';
    //try {
    //$migrate->setDefinitionFile('update_' . $moduleDirName);
    //} catch (\Exception $e) {
    // as long as this is not done default file has to be created
    $moduleVersionOld = $module->getInfo('version');
    $moduleVersionNew = \str_replace(['.', '-'], '_', $moduleVersionOld);
    $fileYaml = \XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . "/sql/{$moduleDirName}_{$moduleVersionNew}_migrate.yml";
    //}

    // create a schema file based on sql/mysql.sql
    $migratehelper = new MigrateHelper($fileSql, $fileYaml);
    if (!$migratehelper->createSchemaFromSqlfile()) {
        \xoops_error('Error: creation schema file failed!');
        return false;
    }

    //create copy for XOOPS 2.5.11 Beta 1 and older versions
    $fileYaml2 = \XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . "/sql/{$moduleDirName}_{$moduleVersionOld}_migrate.yml";
    \copy($fileYaml, $fileYaml2);

    // run standard procedure for db migration
    $migrate->getTargetDefinitions();
    $migrate->synchronizeSchema();

    //check upload directory
    require_once __DIR__ . '/install.php';
    xoops_module_install_wgevents($module);

    $moduleVersion = (int)\str_replace(['.', '-'], '', $module->getInfo('version'));
    if ($moduleVersion < 104) {
        wgevents_update_fee($module);
    }
    if ($moduleVersion < 105) {
        wgevents_update_subcats($module);
    }

    $errors = $module->getErrors();
    if (!empty($errors)) {
        \print_r($errors);
    }

    return true;

}

/**
 * @param $module
 *
 * @return bool
 */
function wgevents_update_fee($module)
{
    // transform float value into json
    $table   = $GLOBALS['xoopsDB']->prefix('wgevents_event');
    $result  = $GLOBALS['xoopsDB']->queryF('SELECT * FROM `' . $table . '`');
    $numRows = $GLOBALS['xoopsDB']->getRowsNum($result);
    if ($numRows > 0) {
        while ($row = $GLOBALS['xoopsDB']->fetchArray($result)) {
            $newValue = \json_encode([[$row['fee'], '']]);
            $sql = 'UPDATE `' . $table . "` SET `fee` = '" . $newValue . "' WHERE `" . $table . '`.`id` = ' . $row['id'];
            if (!$GLOBALS['xoopsDB']->queryF($sql)) {
                xoops_error($GLOBALS['xoopsDB']->error() . '<br>' . $sql);
                $module->setErrors("Error when updating 'fee' in table '$table'.");
            }
        }
    }
    return true;
}
/**
 * @param $module
 *
 * @return bool
 */
function wgevents_update_subcats($module)
{
    // fix wrong default value
    $table   = $GLOBALS['xoopsDB']->prefix('wgevents_event');
    $result  = $GLOBALS['xoopsDB']->queryF('SELECT * FROM `' . $table . '`');
    $numRows = $GLOBALS['xoopsDB']->getRowsNum($result);
    if ($numRows > 0) {
        while ($row = $GLOBALS['xoopsDB']->fetchArray($result)) {
            if ("'" === (string)$row['subcats']) {
                $newValue = \serialize([]);
                $sql = 'UPDATE `' . $table . "` SET `subcats` = '" . $newValue . "' WHERE `" . $table . '`.`id` = ' . $row['id'];
                if (!$GLOBALS['xoopsDB']->queryF($sql)) {
                    xoops_error($GLOBALS['xoopsDB']->error() . '<br>' . $sql);
                    $module->setErrors("Error when updating 'fee' in table '$table'.");
                }
            }
        }
    }
    return true;
}

/**
 * @param $module
 *
 * @return bool
 */
function wgevents_check_db($module)
{
    //insert here code for database check

    /*
    // Example: update table (add new field)
    $table   = $GLOBALS['xoopsDB']->prefix('wgevents_images');
    $field   = 'img_exif';
    $check   = $GLOBALS['xoopsDB']->queryF('SHOW COLUMNS FROM `' . $table . "` LIKE '" . $field . "'");
    $numRows = $GLOBALS['xoopsDB']->getRowsNum($check);
    if (!$numRows) {
        $sql = "ALTER TABLE `$table` ADD `$field` TEXT NULL AFTER `img_state`;";
        if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
            xoops_error($GLOBALS['xoopsDB']->error() . '<br>' . $sql);
            $module->setErrors("Error when adding '$field' to table '$table'.");
            $ret = false;
        }
    }

    // Example: create new table
    $table   = $GLOBALS['xoopsDB']->prefix('wgevents_categories');
    $check   = $GLOBALS['xoopsDB']->queryF("SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='$table'");
    $numRows = $GLOBALS['xoopsDB']->getRowsNum($check);
    if (!$numRows) {
        // create new table 'wgevents_categories'
        $sql = "CREATE TABLE `$table` (
                  `id`        INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
                  `text`      VARCHAR(100)    NOT NULL DEFAULT '',
                  `date`      INT(8)          NOT NULL DEFAULT '0',
                  `submitter` INT(8)          NOT NULL DEFAULT '0',
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB;";
        if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
            xoops_error($GLOBALS['xoopsDB']->error() . '<br>' . $sql);
            $module->setErrors("Error when creating table '$table'.");
            $ret = false;
        }
    }
    */
    return true;
}
