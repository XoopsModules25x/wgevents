<?php declare(strict_types=1);

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
$op    = Request::getCmd('op', 'list');
$logId = Request::getInt('id');
$start = Request::getInt('start');
$limit = Request::getInt('limit', $helper->getConfig('adminpager'));
$GLOBALS['xoopsTpl']->assign('start', $start);
$GLOBALS['xoopsTpl']->assign('limit', $limit);

$moduleDirName = \basename(\dirname(__DIR__));

$GLOBALS['xoopsTpl']->assign('mod_url', XOOPS_URL . '/modules/' . $moduleDirName);

switch ($op) {
    case 'list':
    default:
        // Define Stylesheet
        $GLOBALS['xoTheme']->addStylesheet($style, null);
        $templateMain = 'wgevents_admin_log.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('log.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_DELETE_LOGS, 'log.php?op=deleteall', 'delete');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        $logCount = $logHandler->getCountLogs();
        $logAll = $logHandler->getAllLogs();
        $GLOBALS['xoopsTpl']->assign('logCount', $logCount);
        $GLOBALS['xoopsTpl']->assign('wgevents_url', \WGEVENTS_URL);
        $GLOBALS['xoopsTpl']->assign('wgevents_upload_url', \WGEVENTS_UPLOAD_URL);
        // Table view logs
        if ($logCount > 0) {
            foreach (\array_keys($logAll) as $i) {
                $log = $logAll[$i]->getValuesLogs();
                $GLOBALS['xoopsTpl']->append('logs_list', $log);
                unset($log);
            }
        } else {
            $GLOBALS['xoopsTpl']->assign('error', \_AM_WGEVENTS_THEREARENT_LOGS);
        }
        break;
    case 'save':
        // Security Check
        if (!$GLOBALS['xoopsSecurity']->check()) {
            \redirect_header('log.php', 3, \implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if ($logId > 0) {
            $logObj = $logHandler->get($logId);
        } else {
            $logObj = $logHandler->create();
        }
        // Set Vars
        $logObj->setVar('text', Request::getString('text'));
        $logDatecreatedObj = \DateTime::createFromFormat(\_SHORTDATESTRING, Request::getString('datecreated'));
        $logObj->setVar('datecreated', $logDatecreatedObj->getTimestamp());
        $logObj->setVar('submitter', Request::getInt('submitter'));
        // Insert Data
        if ($logHandler->insert($logObj)) {
                \redirect_header('log.php?op=list&amp;start=' . $start . '&amp;limit=' . $limit, 2, \_AM_WGEVENTS_FORM_OK);
        }
        // Get Form
        $GLOBALS['xoopsTpl']->assign('error', $logObj->getHtmlErrors());
        $form = $logObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'edit':
        $templateMain = 'wgevents_admin_log.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('log.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_ADD_LOG, 'log.php?op=new');
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_LOGS, 'log.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Get Form
        $logObj = $logHandler->get($logId);
        $logObj->start = $start;
        $logObj->limit = $limit;
        $form = $logObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'delete':
        $templateMain = 'wgevents_admin_log.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('log.php'));
        $logObj = $logHandler->get($logId);
        $logText = $logObj->getVar('text');
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                \redirect_header('log.php', 3, \implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($logHandler->delete($logObj)) {
                \redirect_header('log.php', 3, \_AM_WGEVENTS_FORM_DELETE_OK);
            } else {
                $GLOBALS['xoopsTpl']->assign('error', $logObj->getHtmlErrors());
            }
        } else {
            $customConfirm = new Common\Confirm(
                ['ok' => 1, 'id' => $logId, 'start' => $start, 'limit' => $limit, 'op' => 'delete'],
                $_SERVER['REQUEST_URI'],
                \sprintf(\_AM_WGEVENTS_FORM_SURE_DELETE, $logObj->getVar('text')));
            $form = $customConfirm->getFormConfirm();
            $GLOBALS['xoopsTpl']->assign('form', $form->render());
        }
        break;
    case 'deleteall':
        $templateMain = 'wgevents_admin_log.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('log.php'));
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                \redirect_header('log.php', 3, \implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            $logHandler->deleteAll();
            \redirect_header('log.php', 3, \_MA_WGEVENTS_FORM_DELETE_OK);
        } else {
            $customConfirm = new Common\Confirm(
                ['ok' => 1, 'op' => 'deleteall'],
                $_SERVER['REQUEST_URI'],
                \sprintf(\_AM_WGEVENTS_FORM_SURE_DELETE_ALL, 'Table Log'));
            $form = $customConfirm->getFormConfirm();
            $GLOBALS['xoopsTpl']->assign('form', $form->render());
        }
        break;
}
require __DIR__ . '/footer.php';
