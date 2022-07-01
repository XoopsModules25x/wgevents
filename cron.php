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
 * @since        1.0.0
 * @min_xoops    2.5.11 Beta1
 * @author       Goffy - Wedega - Email:webmaster@wedega.com - Website:https://xoops.wedega.com
 */

use XoopsModules\Wgevents;
use XoopsModules\Wgevents\{
    Constants,
    TaskHandler
};

$currentFile = basename(__FILE__);
require_once __DIR__ . '/header.php';

echo '<br>start cron.php';

//require_once __DIR__ . '/class/TaskHandler.php';
// log_level
// 0 = no log items will be created
// 1 = log will be created when newsletter sent or an error occurs (recommended)
// 2 = log will be created for all events (only for testing)
$log_level = 2;

// execute all pending tasks
$result_exec = $taskHandler->processTasks();

if ($log_level > 0) {
    $helper = \XoopsModules\Wgevents\Helper::getInstance();

    $logHandler = $helper->getHandler('Log');
    // get limit_hour from primary account
    $accountHandler = $helper->getHandler('Account');
    $limitHour = $accountHandler->getLimitHour();

    echo '<br>log_level:' . $log_level;
    echo '<br>is_object(helper):' . is_object($helper);
    echo '<br>limit_hour: ' . $limitHour;
    if (0 === $result_exec['pending']) {
        echo '<br>status: ' . 'cron no task';
        if (2 == $log_level) {
            echo '<br>no mails for sending available';
            $logObj = $logHandler->create();
            echo '<br>is_object(logObj):'.is_object($logObj);
            // Set Vars
            $logObj->setVar('text', 'cron no pending task');
            $logObj->setVar('datecreated', time());
            $logObj->setVar('submitter', 0);
            // Insert Data
            if ($logHandler->insert($logObj)) {
                echo '<br>log successfully created';
            } else {
                echo $logObj->getHtmlErrors();
                echo '<br>errors when creating log';
            }
        }
    } else {
        echo '<br>status: ' . 'cron task available';
        echo "<br>result cron: Pending: " . $result_exec['pending'] . " / Done: " . $result_exec['done'];
    }
}
echo '<br>finished cron.php';
