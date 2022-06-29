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

use Xmf\Request;
use XoopsModules\Wgevents;
use XoopsModules\Wgevents\Constants;
use XoopsModules\Wgevents\Common;

require __DIR__ . '/header.php';
// Get all request values
$op     = Request::getCmd('op', 'list');
$taskId = Request::getInt('id');
$start  = Request::getInt('start');
$limit  = Request::getInt('limit', $helper->getConfig('adminpager'));
$GLOBALS['xoopsTpl']->assign('start', $start);
$GLOBALS['xoopsTpl']->assign('limit', $limit);

switch ($op) {
    case 'list':
    default:
        // Define Stylesheet
        $GLOBALS['xoTheme']->addStylesheet($style, null);
        $templateMain = 'wgevents_admin_task.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('task.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_ADD_TASK, 'task.php?op=new');
        $adminObject->addItemButton(\_AM_WGEVENTS_DELETE_TASKS_PENDING, 'task.php?op=delete_all&amp;deltype=pending');
        $adminObject->addItemButton(\_AM_WGEVENTS_DELETE_TASKS_DONE, 'task.php?op=delete_all&amp;deltype=done');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        $tasksCount = $taskHandler->getCountTasks();
        $tasksAll = $taskHandler->getAllTasks();
        $GLOBALS['xoopsTpl']->assign('tasks_count', $tasksCount);
        $GLOBALS['xoopsTpl']->assign('wgevents_url', \WGEVENTS_URL);
        $GLOBALS['xoopsTpl']->assign('wgevents_icons_url_16', \WGEVENTS_ICONS_URL_16);
        $GLOBALS['xoopsTpl']->assign('wgevents_upload_url', \WGEVENTS_UPLOAD_URL);
        $GLOBALS['xoopsTpl']->assign('mod_url', XOOPS_URL . '/modules/' . $moduleDirName);
        // Table view tasks
        if ($tasksCount > 0) {
            foreach (\array_keys($tasksAll) as $i) {
                $task = $tasksAll[$i]->getValuesTasks();
                $GLOBALS['xoopsTpl']->append('tasks_list', $task);
                unset($task);
            }
        } else {
            $GLOBALS['xoopsTpl']->assign('error', \_AM_WGEVENTS_THEREARENT_TASKS);
        }
        break;
    case 'new':
        $templateMain = 'wgevents_admin_task.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('task.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_TASKS, 'task.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Form Create
        $taskObj = $taskHandler->create();
        $form = $taskObj->getFormTasks();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'save':
        // Security Check
        if (!$GLOBALS['xoopsSecurity']->check()) {
            \redirect_header('task.php', 3, \implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if ($taskId > 0) {
            $taskObj = $taskHandler->get($taskId);
        } else {
            $taskObj = $taskHandler->create();
        }
        // Set Vars
        $taskObj->setVar('type', Request::getInt('type'));
        $taskObj->setVar('params', Request::getText('params'));
        $taskObj->setVar('recipient', Request::getString('recipient'));
        $taskDatecreatedArr = Request::getArray('datecreated');
        $taskDatecreatedObj = \DateTime::createFromFormat(\_SHORTDATESTRING, $taskDatecreatedArr['date']);
        $taskDatecreatedObj->setTime(0, 0, 0);
        $taskDatecreated = $taskDatecreatedObj->getTimestamp() + (int)$taskDatecreatedArr['time'];
        $taskObj->setVar('datecreated', $taskDatecreated);
        $taskObj->setVar('submitter', Request::getInt('submitter'));
        $taskObj->setVar('status', Request::getInt('status'));
        $taskDatedoneArr = Request::getArray('datedone');
        $taskDatedoneObj = \DateTime::createFromFormat(\_SHORTDATESTRING, $taskDatedoneArr['date']);
        $taskDatedoneObj->setTime(0, 0, 0);
        $taskDatedone = $taskDatedoneObj->getTimestamp() + (int)$taskDatedoneArr['time'];
        $taskObj->setVar('datedone', $taskDatedone);
        // Insert Data
        if ($taskHandler->insert($taskObj)) {
            \redirect_header('task.php?op=list&amp;start=' . $start . '&amp;limit=' . $limit, 2, \_MA_WGEVENTS_FORM_OK);
        }
        // Get Form
        $GLOBALS['xoopsTpl']->assign('error', $taskObj->getHtmlErrors());
        $form = $taskObj->getFormTasks();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'edit':
        $templateMain = 'wgevents_admin_task.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('task.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_ADD_TASK, 'task.php?op=new');
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_TASKS, 'task.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Get Form
        $taskObj = $taskHandler->get($taskId);
        $taskObj->start = $start;
        $taskObj->limit = $limit;
        $form = $taskObj->getFormTasks();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'delete':
        $templateMain = 'wgevents_admin_task.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('task.php'));
        $taskObj = $taskHandler->get($taskId);
        $taskEvid = $taskObj->getVar('task_evid');
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                \redirect_header('task.php', 3, \implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($taskHandler->delete($taskObj)) {
                \redirect_header('task.php', 3, \_MA_WGEVENTS_FORM_DELETE_OK);
            } else {
                $GLOBALS['xoopsTpl']->assign('error', $taskObj->getHtmlErrors());
            }
        } else {
            $customConfirm = new Common\Confirm(
                ['ok' => 1, 'task_id' => $taskId, 'start' => $start, 'limit' => $limit, 'op' => 'delete'],
                $_SERVER['REQUEST_URI'],
                \sprintf(\_MA_WGEVENTS_FORM_SURE_DELETE, $taskObj->getVar('id')));
            $form = $customConfirm->getFormConfirm();
            $GLOBALS['xoopsTpl']->assign('form', $form->render());
        }
        break;
    case 'delete_all':
        $delType = Request::getString('deltype', 'done');
        $templateMain = 'wgevents_admin_task.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('task.php'));
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            $crTask = new \CriteriaCompo();
            if ('pending' === $delType) {
                $crTask->add(new \Criteria('status', Constants::STATUS_PENDING));
            } else {
                $crTask->add(new \Criteria('status', Constants::STATUS_DONE));
            }
            if (!$GLOBALS['xoopsSecurity']->check()) {
                \redirect_header('task.php', 3, \implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if (0 === $taskHandler->getCount($crTask) || $taskHandler->deleteAll($crTask, true)) {
                \redirect_header('task.php', 3, \_MA_WGEVENTS_FORM_DELETE_OK);
            } else {
                $GLOBALS['xoopsTpl']->assign('error', $taskHandler->getHtmlErrors());
            }
        } else {
            if ('pending' === $delType) {
                $textConfirm = \_AM_WGEVENTS_DELETE_TASKS_PENDING;
            } else {
                $textConfirm = \_AM_WGEVENTS_DELETE_TASKS_DONE;
            }
            $customConfirm = new Common\Confirm(
                ['ok' => 1, 'op' => 'delete_all', 'deltype' => $delType],
                $_SERVER['REQUEST_URI'],
                \sprintf(\_MA_WGEVENTS_FORM_SURE_DELETE, $textConfirm));
            $form = $customConfirm->getFormConfirm();
            $GLOBALS['xoopsTpl']->assign('form', $form->render());
        }
        break;
}
require __DIR__ . '/footer.php';
