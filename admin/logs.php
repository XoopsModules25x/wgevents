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
$logId = Request::getInt('log_id');
$start = Request::getInt('start');
$limit = Request::getInt('limit', $helper->getConfig('adminpager'));
$GLOBALS['xoopsTpl']->assign('start', $start);
$GLOBALS['xoopsTpl']->assign('limit', $limit);

switch ($op) {
    case 'list':
    default:
        // Define Stylesheet
        $GLOBALS['xoTheme']->addStylesheet($style, null);
        $templateMain = 'wgevents_admin_logs.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('logs.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_DELETE_LOGS, 'logs.php?op=deleteall', 'delete');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        $logsCount = $logsHandler->getCountLogs();
        $logsAll = $logsHandler->getAllLogs($start, $limit);
        $GLOBALS['xoopsTpl']->assign('logs_count', $logsCount);
        $GLOBALS['xoopsTpl']->assign('wgevents_url', \WGEVENTS_URL);
        $GLOBALS['xoopsTpl']->assign('wgevents_upload_url', \WGEVENTS_UPLOAD_URL);
        // Table view logs
        if ($logsCount > 0) {
            foreach (\array_keys($logsAll) as $i) {
                $log = $logsAll[$i]->getValuesLogs();
                $GLOBALS['xoopsTpl']->append('logs_list', $log);
                unset($log);
            }
            // Display Navigation
            if ($logsCount > $limit) {
                require_once \XOOPS_ROOT_PATH . '/class/pagenav.php';
                $pagenav = new \XoopsPageNav($logsCount, $limit, $start, 'start', 'op=list&limit=' . $limit);
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());
            }
        } else {
            $GLOBALS['xoopsTpl']->assign('error', \_AM_WGEVENTS_THEREARENT_LOGS);
        }
        break;
    case 'save':
        // Security Check
        if (!$GLOBALS['xoopsSecurity']->check()) {
            \redirect_header('logs.php', 3, \implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if ($logId > 0) {
            $logsObj = $logsHandler->get($logId);
        } else {
            $logsObj = $logsHandler->create();
        }
        // Set Vars
        $logsObj->setVar('log_text', Request::getString('log_text'));
        $logDatecreatedObj = \DateTime::createFromFormat(\_SHORTDATESTRING, Request::getString('log_datecreated'));
        $logsObj->setVar('log_datecreated', $logDatecreatedObj->getTimestamp());
        $logsObj->setVar('log_submitter', Request::getInt('log_submitter'));
        // Insert Data
        if ($logsHandler->insert($logsObj)) {
                \redirect_header('logs.php?op=list&amp;start=' . $start . '&amp;limit=' . $limit, 2, \_AM_WGEVENTS_FORM_OK);
        }
        // Get Form
        $GLOBALS['xoopsTpl']->assign('error', $logsObj->getHtmlErrors());
        $form = $logsObj->getFormLogs();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'edit':
        $templateMain = 'wgevents_admin_logs.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('logs.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_ADD_LOG, 'logs.php?op=new');
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_LOGS, 'logs.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Get Form
        $logsObj = $logsHandler->get($logId);
        $logsObj->start = $start;
        $logsObj->limit = $limit;
        $form = $logsObj->getFormLogs();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'delete':
        $templateMain = 'wgevents_admin_logs.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('logs.php'));
        $logsObj = $logsHandler->get($logId);
        $logText = $logsObj->getVar('log_text');
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                \redirect_header('logs.php', 3, \implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($logsHandler->delete($logsObj)) {
                \redirect_header('logs.php', 3, \_AM_WGEVENTS_FORM_DELETE_OK);
            } else {
                $GLOBALS['xoopsTpl']->assign('error', $logsObj->getHtmlErrors());
            }
        } else {
            $customConfirm = new Common\Confirm(
                ['ok' => 1, 'log_id' => $logId, 'start' => $start, 'limit' => $limit, 'op' => 'delete'],
                $_SERVER['REQUEST_URI'],
                \sprintf(\_AM_WGEVENTS_FORM_SURE_DELETE, $logsObj->getVar('log_text')));
            $form = $customConfirm->getFormConfirm();
            $GLOBALS['xoopsTpl']->assign('form', $form->render());
        }
        break;
    case 'deleteall':
        $templateMain = 'wgevents_admin_logs.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('logs.php'));
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                \redirect_header('logs.php', 3, \implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            $logsHandler->deleteAll();
            \redirect_header('logs.php', 3, \_MA_WGEVENTS_FORM_DELETE_OK);
        } else {
            $customConfirm = new Common\Confirm(
                ['ok' => 1, 'op' => 'deleteall'],
                $_SERVER['REQUEST_URI'],
                \sprintf(\_AM_WGEVENTS_FORM_SURE_DELETE_ALL, 'Table Logs'));
            $form = $customConfirm->getFormConfirm();
            $GLOBALS['xoopsTpl']->assign('form', $form->render());
        }
        break;
}
require __DIR__ . '/footer.php';
