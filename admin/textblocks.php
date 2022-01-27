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
$tbId  = Request::getInt('tb_id');
$start = Request::getInt('start');
$limit = Request::getInt('limit', $helper->getConfig('adminpager'));
$GLOBALS['xoopsTpl']->assign('start', $start);
$GLOBALS['xoopsTpl']->assign('limit', $limit);

switch ($op) {
    case 'list':
    default:
        // Define Stylesheet
        $GLOBALS['xoTheme']->addStylesheet($style, null);
        $templateMain = 'wgevents_admin_textblocks.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('textblocks.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_ADD_TEXTBLOCK, 'textblocks.php?op=new');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        $textblocksCount = $textblocksHandler->getCountTextblocks();
        $textblocksAll = $textblocksHandler->getAllTextblocks($start, $limit);
        $GLOBALS['xoopsTpl']->assign('textblocks_count', $textblocksCount);
        $GLOBALS['xoopsTpl']->assign('wgevents_url', \WGEVENTS_URL);
        $GLOBALS['xoopsTpl']->assign('wgevents_upload_url', \WGEVENTS_UPLOAD_URL);
        // Table view textblocks
        if ($textblocksCount > 0) {
            foreach (\array_keys($textblocksAll) as $i) {
                $textblock = $textblocksAll[$i]->getValuesTextblocks();
                $GLOBALS['xoopsTpl']->append('textblocks_list', $textblock);
                unset($textblock);
            }
            // Display Navigation
            if ($textblocksCount > $limit) {
                require_once \XOOPS_ROOT_PATH . '/class/pagenav.php';
                $pagenav = new \XoopsPageNav($textblocksCount, $limit, $start, 'start', 'op=list&limit=' . $limit);
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());
            }
        } else {
            $GLOBALS['xoopsTpl']->assign('error', \_AM_WGEVENTS_THEREARENT_TEXTBLOCKS);
        }
        break;
    case 'new':
        $templateMain = 'wgevents_admin_textblocks.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('textblocks.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_TEXTBLOCKS, 'textblocks.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Form Create
        $textblocksObj = $textblocksHandler->create();
        $form = $textblocksObj->getFormTextblocks();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'clone':
        $templateMain = 'wgevents_admin_textblocks.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('textblocks.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_TEXTBLOCKS, 'textblocks.php', 'list');
        $adminObject->addItemButton(\_AM_WGEVENTS_ADD_TEXTBLOCK, 'textblocks.php?op=new');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Request source
        $tbIdSource = Request::getInt('tb_id_source');
        // Get Form
        $textblocksObjSource = $textblocksHandler->get($tbIdSource);
        $textblocksObj = $textblocksObjSource->xoopsClone();
        $form = $textblocksObj->getFormTextblocks();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'save':
        // Security Check
        if (!$GLOBALS['xoopsSecurity']->check()) {
            \redirect_header('textblocks.php', 3, \implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if ($tbId > 0) {
            $textblocksObj = $textblocksHandler->get($tbId);
        } else {
            $textblocksObj = $textblocksHandler->create();
        }
        // Set Vars
        $textblocksObj->setVar('tb_name', Request::getString('tb_name'));
        $textblocksObj->setVar('tb_text', Request::getText('tb_text'));
        $textblocksObj->setVar('tb_weight', Request::getInt('tb_weight'));
        $textblockDatecreatedObj = \DateTime::createFromFormat(\_SHORTDATESTRING, Request::getString('tb_datecreated'));
        $textblocksObj->setVar('tb_datecreated', $textblockDatecreatedObj->getTimestamp());
        $textblocksObj->setVar('tb_submitter', Request::getInt('tb_submitter'));
        // Insert Data
        if ($textblocksHandler->insert($textblocksObj)) {
            $newTbId = $textblocksObj->getNewInsertedIdTextblocks();
            $permId = isset($_REQUEST['tb_id']) ? $tbId : $newTbId;
            $grouppermHandler = \xoops_getHandler('groupperm');
            $mid = $GLOBALS['xoopsModule']->getVar('mid');
            // Permission to view_textblocks
            $grouppermHandler->deleteByModule($mid, 'wgevents_view_textblocks', $permId);
            if (isset($_POST['groups_view_textblocks'])) {
                foreach ($_POST['groups_view_textblocks'] as $onegroupId) {
                    $grouppermHandler->addRight('wgevents_view_textblocks', $permId, $onegroupId, $mid);
                }
            }
            // Permission to submit_textblocks
            $grouppermHandler->deleteByModule($mid, 'wgevents_submit_textblocks', $permId);
            if (isset($_POST['groups_submit_textblocks'])) {
                foreach ($_POST['groups_submit_textblocks'] as $onegroupId) {
                    $grouppermHandler->addRight('wgevents_submit_textblocks', $permId, $onegroupId, $mid);
                }
            }
            // Permission to approve_textblocks
            $grouppermHandler->deleteByModule($mid, 'wgevents_approve_textblocks', $permId);
            if (isset($_POST['groups_approve_textblocks'])) {
                foreach ($_POST['groups_approve_textblocks'] as $onegroupId) {
                    $grouppermHandler->addRight('wgevents_approve_textblocks', $permId, $onegroupId, $mid);
                }
            }
                \redirect_header('textblocks.php?op=list&amp;start=' . $start . '&amp;limit=' . $limit, 2, \_MA_WGEVENTS_FORM_OK);
        }
        // Get Form
        $GLOBALS['xoopsTpl']->assign('error', $textblocksObj->getHtmlErrors());
        $form = $textblocksObj->getFormTextblocks();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'edit':
        $templateMain = 'wgevents_admin_textblocks.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('textblocks.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_ADD_TEXTBLOCK, 'textblocks.php?op=new');
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_TEXTBLOCKS, 'textblocks.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Get Form
        $textblocksObj = $textblocksHandler->get($tbId);
        $textblocksObj->start = $start;
        $textblocksObj->limit = $limit;
        $form = $textblocksObj->getFormTextblocks();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'delete':
        $templateMain = 'wgevents_admin_textblocks.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('textblocks.php'));
        $textblocksObj = $textblocksHandler->get($tbId);
        $tbName = $textblocksObj->getVar('tb_name');
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                \redirect_header('textblocks.php', 3, \implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($textblocksHandler->delete($textblocksObj)) {
                \redirect_header('textblocks.php', 3, \_MA_WGEVENTS_FORM_DELETE_OK);
            } else {
                $GLOBALS['xoopsTpl']->assign('error', $textblocksObj->getHtmlErrors());
            }
        } else {
            $customConfirm = new Common\Confirm(
                ['ok' => 1, 'tb_id' => $tbId, 'start' => $start, 'limit' => $limit, 'op' => 'delete'],
                $_SERVER['REQUEST_URI'],
                \sprintf(\_MA_WGEVENTS_FORM_SURE_DELETE, $textblocksObj->getVar('tb_name')));
            $form = $customConfirm->getFormConfirm();
            $GLOBALS['xoopsTpl']->assign('form', $form->render());
        }
        break;
}
require __DIR__ . '/footer.php';
