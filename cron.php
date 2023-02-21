<?php

declare(strict_types=1);

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * wgEvents module for xoops
 *
 * @copyright    2021 XOOPS Project (https://xoops.org)
 * @license      GPL 2.0 or later
 * @package      wgevents
 * @author       Goffy - Wedega - Email:webmaster@wedega.com - Website:https://xoops.wedega.com
 */

use XoopsModules\Wgevents;
use XoopsModules\Wgevents\{
    Constants,
    TaskHandler
};

$currentFile = basename(__FILE__);
require_once __DIR__ . '/header.php';

$cronInfoStart = '';
$cronInfoResult = '';
$start    = \formatTimestamp(time(), 'm');
echo '<br>Start cron.php: ' . $start;

$helper = \XoopsModules\Wgevents\Helper::getInstance();
$logHandler = $helper->getHandler('Log');

// log_level (see also Constants)
// 0 = no log items will be created
// 1 = log will be created when mail sent or an error occurs (recommended)
// 2 = log with details will be created for all actions (only for testing)
$log_level = (int)$helper->getConfig('use_logs');

// check for open/pending tasks
$countOpen = $taskHandler->getCountTasksOpen();

if ($countOpen > 0) {
    if ($log_level > Constants::LOG_NONE) {
        $cronInfoStart  .= '<br>Cron task(s) available<br>- Start: ' . $start;
        $cronInfoStart  .= '<br>- Log level:' . $log_level;
        $cronInfoStart  .= '<br>- Open tasks at start: ' . $countOpen;
        $logObj = $logHandler->create();
        if (!is_object($logObj)) {
            echo '<br>is_object(logObj): FALSE';
        }
        // get limit_hour from primary account
        $accountHandler = $helper->getHandler('Account');
        $limitHour = $accountHandler->getLimitHour();
        if ($log_level > Constants::LOG_SIMPLE) {
            $cronInfoStart  .= '<br>-- is_object(helper):' . is_object($helper);
            $cronInfoStart  .= '<br>-- is_object(logObj):' . is_object($logObj);
            $cronInfoStart  .= '<br>-- Limit per hour: ' . $limitHour;
        }
        // Set Vars
        $logObj->setVar('text', $cronInfoStart );
        $logObj->setVar('datecreated', time());
        $logObj->setVar('submitter', 0);
        // Insert Data
        if ($logHandler->insert($logObj)) {
            $cronInfoStart .= '<br>- Log start successfully created';
        } else {
            $cronInfoStart .= '<br>- Errors when creating log start';
            $cronInfoStart .= '<br>-- htmlErrors: ' . $logObj->getHtmlErrors();
        }
    }
    // execute all pending tasks
    $result_exec = $taskHandler->processTasks($log_level);

    if ($log_level > Constants::LOG_NONE) {
        $logObj = $logHandler->create();
        if (!is_object($logObj)) {
            echo '<br>is_object(logObj): FALSE';
        }
        // get limit_hour from primary account
        $accountHandler = $helper->getHandler('Account');
        $limitHour = $accountHandler->getLimitHour();
        if ($log_level > Constants::LOG_SIMPLE) {
            $cronInfoResult  .= '<br>- Result Process Tasks Detail: ' . $result_exec['resprocess'];
        }
        $cronInfoResult  .= '<br>- Result DONE: ' . $result_exec['done'];
        if ((int)$result_exec['still_open'] > 0 ){
            $cronInfoResult  .= '<br><span style="color:#ff0000;font-weight:700">- Result Still Open: ' . $result_exec['still_open'] . '</span>';
        } else {
            $cronInfoResult  .= '<br>- Result Still Open: ' . $result_exec['still_open'];
        }
        // Set Vars
        $logObj->setVar('text', $cronInfoResult );
        $logObj->setVar('datecreated', time());
        $logObj->setVar('submitter', 0);
        // Insert Data
        if ($logHandler->insert($logObj)) {
            $cronInfoResult .= '<br>- Log result successfully created';
        } else {
            $cronInfoResult .= '<br>- Errors when creating log result';
            $cronInfoResult .= '<br>-- htmlErrors: ' . $logObj->getHtmlErrors();
        }
        echo $cronInfoStart . $cronInfoResult;
    }
} else {
    $cronInfoResult .= '<br>Cron: no task';
    if ($log_level > Constants::LOG_NONE) {
        $logObj = $logHandler->create();
        $cronInfoResult .= '<br>is_object(logObj):'.is_object($logObj);
        // Set Vars
        $logObj->setVar('text', 'Cron: no open task');
        $logObj->setVar('datecreated', time());
        $logObj->setVar('submitter', 0);
        // Insert Data
        if ($logHandler->insert($logObj)) {
            $cronInfoResult .= '<br>Log successfully created';
        } else {
            echo $logObj->getHtmlErrors();
            $cronInfoResult .= '<br>Errors when creating log';
        }
    }
    echo $cronInfoResult;
}

echo '<br>Finished cron.php: ' . \formatTimestamp(time(), 'm');
