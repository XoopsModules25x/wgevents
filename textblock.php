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
 * @author       Goffy - Wedega - Email:webmaster@wedega.com - Website:https://xoops.wedega.com
 */

use Xmf\Request;
use XoopsModules\Wgevents;
use XoopsModules\Wgevents\Constants;
use XoopsModules\Wgevents\Common;

require __DIR__ . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'wgevents_textblock.tpl';
require_once \XOOPS_ROOT_PATH . '/header.php';

$op    = Request::getCmd('op', 'list');
$tbId  = Request::getInt('id');
$start = Request::getInt('start');
$limit = Request::getInt('limit', $helper->getConfig('userpager'));
$GLOBALS['xoopsTpl']->assign('start', $start);
$GLOBALS['xoopsTpl']->assign('limit', $limit);

// Define Stylesheet
$GLOBALS['xoTheme']->addStylesheet($style, null);
// Paths
$GLOBALS['xoopsTpl']->assign('xoops_icons32_url', \XOOPS_ICONS32_URL);
$GLOBALS['xoopsTpl']->assign('wgevents_url', \WGEVENTS_URL);
// Keywords
$keywords = [];
// Breadcrumbs
$xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_INDEX, 'link' => 'index.php'];
// Permission
$permSubmit = $permissionsHandler->getPermTextblocksSubmit();
$GLOBALS['xoopsTpl']->assign('permSubmit', $permSubmit);
$GLOBALS['xoopsTpl']->assign('showItem', $tbId > 0);

switch ($op) {
    case 'show':
    case 'list':
    default:
        // Breadcrumbs
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_TEXTBLOCKS_LIST];
        $crTextblock = new \CriteriaCompo();
        if ($tbId > 0) {
            $crTextblock->add(new \Criteria('id', $tbId));
        }
        $textblocksCount = $textblockHandler->getCount($crTextblock);
        $GLOBALS['xoopsTpl']->assign('textblocksCount', $textblocksCount);
        if (0 === $tbId) {
            $crTextblock->setStart($start);
            $crTextblock->setLimit($limit);
        }
        $textblocksAll = $textblockHandler->getAll($crTextblock);
        if ($textblocksCount > 0) {
            $textblocks = [];
            $tbName = '';
            // Get All Textblock
            foreach (\array_keys($textblocksAll) as $i) {
                $textblocks[$i] = $textblocksAll[$i]->getValuesTextblocks();
                $tbName = $textblocksAll[$i]->getVar('name');
                $keywords[$i] = $tbName;
                $permEdit = $permissionsHandler->getPermTextblocksEdit($textblocksAll[$i]->getVar('submitter'));
                $textblocks[$i]['permEdit'] = $permEdit;
            }
            $GLOBALS['xoopsTpl']->assign('textblocks', $textblocks);
            unset($textblocks);
            // Display Navigation
            if ($textblocksCount > $limit) {
                require_once \XOOPS_ROOT_PATH . '/class/pagenav.php';
                $pagenav = new \XoopsPageNav($textblocksCount, $limit, $start, 'start', 'op=list&limit=' . $limit);
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());
            }
            $GLOBALS['xoopsTpl']->assign('table_type', $helper->getConfig('table_type'));
            $GLOBALS['xoopsTpl']->assign('panel_type', $helper->getConfig('panel_type'));
            $GLOBALS['xoopsTpl']->assign('divideby', $helper->getConfig('divideby'));
            $GLOBALS['xoopsTpl']->assign('numb_col', $helper->getConfig('numb_col'));
            if ('show' === $op && '' !== $tbName) {
                $GLOBALS['xoopsTpl']->assign('xoops_pagetitle', \strip_tags($tbName . ' - ' . $GLOBALS['xoopsModule']->getVar('name')));
            }
        } else {
            $GLOBALS['xoopsTpl']->assign('error', \_MA_WGEVENTS_TEXTBLOCKS_THEREARENT);
            if ($permSubmit) {
                $GLOBALS['xoopsTpl']->assign('permSubmitFirst', true);
            }
        }
        break;
    case 'save':
        // Security Check
        if (!$GLOBALS['xoopsSecurity']->check()) {
            \redirect_header('textblock.php', 3, \implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }

        if ($tbId > 0) {
            $textblockObj = $textblockHandler->get($tbId);
            // Check permissions
            if (!$permissionsHandler->getPermTextblocksEdit($textblockObj->getVar('submitter'))) {
                \redirect_header('textblock.php?op=list', 3, \_NOPERM);
            }
        } else {
            // Check permissions
            if (!$permissionsHandler->getPermTextblocksSubmit()) {
                \redirect_header('textblock.php?op=list', 3, \_NOPERM);
            }
            $textblockObj = $textblockHandler->create();
        }
        $textblockObj->setVar('name', Request::getString('name'));
        $textblockObj->setVar('text', Request::getText('text'));
        $textblockObj->setVar('weight', Request::getInt('weight'));
        $textblockDatecreatedObj = \DateTime::createFromFormat(\_SHORTDATESTRING, Request::getString('datecreated'));
        $textblockObj->setVar('datecreated', $textblockDatecreatedObj->getTimestamp());
        $textblockObj->setVar('submitter', Request::getInt('submitter'));
        // Insert Data
        if ($textblockHandler->insert($textblockObj)) {
            $grouppermHandler = \xoops_getHandler('groupperm');
            $mid = $GLOBALS['xoopsModule']->getVar('mid');
            // Permission to view_textblocks
            $grouppermHandler->deleteByModule($mid, 'wgevents_view_textblocks', $newTbId);
            if (isset($_POST['groups_view_textblocks'])) {
                foreach ($_POST['groups_view_textblocks'] as $onegroupId) {
                    $grouppermHandler->addRight('wgevents_view_textblocks', $newTbId, $onegroupId, $mid);
                }
            }
            // Permission to submit_textblocks
            $grouppermHandler->deleteByModule($mid, 'wgevents_submit_textblocks', $newTbId);
            if (isset($_POST['groups_submit_textblocks'])) {
                foreach ($_POST['groups_submit_textblocks'] as $onegroupId) {
                    $grouppermHandler->addRight('wgevents_submit_textblocks', $newTbId, $onegroupId, $mid);
                }
            }
            // Permission to approve_textblocks
            $grouppermHandler->deleteByModule($mid, 'wgevents_approve_textblocks', $newTbId);
            if (isset($_POST['groups_approve_textblocks'])) {
                foreach ($_POST['groups_approve_textblocks'] as $onegroupId) {
                    $grouppermHandler->addRight('wgevents_approve_textblocks', $newTbId, $onegroupId, $mid);
                }
            }
            // redirect after insert
            \redirect_header('textblock.php?op=list&amp;start=' . $start . '&amp;limit=' . $limit, 2, \_MA_WGEVENTS_FORM_OK);
        }
        // Get Form Error
        $GLOBALS['xoopsTpl']->assign('error', $textblockObj->getHtmlErrors());
        $form = $textblockObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'new':
        // Breadcrumbs
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_TEXTBLOCK_ADD];
        // Check permissions
        if (!$permissionsHandler->getPermTextblocksSubmit()) {
            \redirect_header('textblock.php?op=list', 3, \_NOPERM);
        }
        // Form Create
        $textblockObj = $textblockHandler->create();
        $form = $textblockObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'edit':
        // Breadcrumbs
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_TEXTBLOCK_EDIT];
        // Check params
        if (0 === $tbId) {
            \redirect_header('textblock.php?op=list', 3, \_MA_WGEVENTS_INVALID_PARAM);
        }
        // Get Form
        $textblockObj = $textblockHandler->get($tbId);
        // Check permissions
        if (!$permissionsHandler->getPermTextblocksEdit($textblockObj->getVar('submitter'))) {
            \redirect_header('textblock.php?op=list', 3, \_NOPERM);
        }
        $textblockObj->start = $start;
        $textblockObj->limit = $limit;
        $form = $textblockObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'clone':
        // Breadcrumbs
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_TEXTBLOCK_CLONE];
        // Check permissions
        if (!$permissionsHandler->getPermGlobalSubmit()) {
            \redirect_header('textblock.php?op=list', 3, \_NOPERM);
        }
        // Request source
        $tbIdSource = Request::getInt('id_source');
        // Check params
        if (0 === $tbIdSource) {
            \redirect_header('textblock.php?op=list', 3, \_MA_WGEVENTS_INVALID_PARAM);
        }
        // Get Form
        $textblockObjSource = $textblockHandler->get($tbIdSource);
        $textblockObj = $textblockObjSource->xoopsClone();
        $form = $textblockObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'delete':
        // Breadcrumbs
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_TEXTBLOCK_DELETE];
        // Check permissions
        if (!$permissionsHandler->getPermGlobalSubmit()) {
            \redirect_header('textblock.php?op=list', 3, \_NOPERM);
        }
        // Check params
        if (0 === $tbId) {
            \redirect_header('textblock.php?op=list', 3, \_MA_WGEVENTS_INVALID_PARAM);
        }
        $textblockObj = $textblockHandler->get($tbId);
        $tbName = $textblockObj->getVar('name');
        if (isset($_REQUEST['ok']) && 1 === (int)$_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                \redirect_header('textblock.php', 3, \implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($textblockHandler->delete($textblockObj)) {
                \redirect_header('textblock.php', 3, \_MA_WGEVENTS_FORM_DELETE_OK);
            } else {
                $GLOBALS['xoopsTpl']->assign('error', $textblockObj->getHtmlErrors());
            }
        } else {
            $customConfirm = new Common\Confirm(
                ['ok' => 1, 'id' => $tbId, 'start' => $start, 'limit' => $limit, 'op' => 'delete'],
                $_SERVER['REQUEST_URI'],
                \sprintf(\_MA_WGEVENTS_CONFIRMDELETE_TEXTBLOCK, $textblockObj->getVar('name')), \_MA_WGEVENTS_CONFIRMDELETE_TITLE, \_MA_WGEVENTS_CONFIRMDELETE_LABEL);
            $form = $customConfirm->getFormConfirm();
            $GLOBALS['xoopsTpl']->assign('form', $form->render());
        }
        break;
}

// Keywords
wgeventsMetaKeywords($helper->getConfig('keywords') . ', ' . \implode(',', $keywords));
unset($keywords);

// Description
wgeventsMetaDescription(\_MA_WGEVENTS_TEXTBLOCKS_DESC);
$GLOBALS['xoopsTpl']->assign('xoops_mpageurl', \WGEVENTS_URL.'/textblock.php');
$GLOBALS['xoopsTpl']->assign('wgevents_upload_url', \WGEVENTS_UPLOAD_URL);

require __DIR__ . '/footer.php';
