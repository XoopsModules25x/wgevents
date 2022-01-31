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
$GLOBALS['xoopsOption']['template_main'] = 'wgevents_textblocks.tpl';
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
// Permissions
$permEdit = $permissionsHandler->getPermGlobalSubmit();
$GLOBALS['xoopsTpl']->assign('permEdit', $permEdit);
$GLOBALS['xoopsTpl']->assign('showItem', $tbId > 0);

switch ($op) {
    case 'show':
    case 'list':
    default:
        // Breadcrumbs
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_TEXTBLOCKS_LIST];
        $crTextblocks = new \CriteriaCompo();
        if ($tbId > 0) {
            $crTextblocks->add(new \Criteria('id', $tbId));
        }
        $textblocksCount = $textblocksHandler->getCount($crTextblocks);
        $GLOBALS['xoopsTpl']->assign('textblocksCount', $textblocksCount);
        if (0 === $tbId) {
            $crTextblocks->setStart($start);
            $crTextblocks->setLimit($limit);
        }
        $textblocksAll = $textblocksHandler->getAll($crTextblocks);
        if ($textblocksCount > 0) {
            $textblocks = [];
            $tbName = '';
            // Get All Textblocks
            foreach (\array_keys($textblocksAll) as $i) {
                $textblocks[$i] = $textblocksAll[$i]->getValuesTextblocks();
                $tbName = $textblocksAll[$i]->getVar('name');
                $keywords[$i] = $tbName;
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
            if ('show' == $op && '' != $tbName) {
                $GLOBALS['xoopsTpl']->assign('xoops_pagetitle', \strip_tags($tbName . ' - ' . $GLOBALS['xoopsModule']->getVar('name')));
            }
        }
        break;
    case 'save':
        // Security Check
        if (!$GLOBALS['xoopsSecurity']->check()) {
            \redirect_header('textblocks.php', 3, \implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        // Check permissions
        if (!$permissionsHandler->getPermGlobalSubmit()) {
            \redirect_header('textblocks.php?op=list', 3, \_NOPERM);
        }
        if ($tbId > 0) {
            $textblocksObj = $textblocksHandler->get($tbId);
        } else {
            $textblocksObj = $textblocksHandler->create();
        }
        $textblocksObj->setVar('name', Request::getString('name'));
        $textblocksObj->setVar('text', Request::getText('text'));
        $textblocksObj->setVar('weight', Request::getInt('weight'));
        $textblockDatecreatedObj = \DateTime::createFromFormat(\_SHORTDATESTRING, Request::getString('datecreated'));
        $textblocksObj->setVar('datecreated', $textblockDatecreatedObj->getTimestamp());
        $textblocksObj->setVar('submitter', Request::getInt('submitter'));
        // Insert Data
        if ($textblocksHandler->insert($textblocksObj)) {
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
                \redirect_header('textblocks.php?op=list&amp;start=' . $start . '&amp;limit=' . $limit, 2, \_MA_WGEVENTS_FORM_OK);
        }
        // Get Form Error
        $GLOBALS['xoopsTpl']->assign('error', $textblocksObj->getHtmlErrors());
        $form = $textblocksObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'new':
        // Breadcrumbs
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_TEXTBLOCK_ADD];
        // Check permissions
        if (!$permissionsHandler->getPermGlobalSubmit()) {
            \redirect_header('textblocks.php?op=list', 3, \_NOPERM);
        }
        // Form Create
        $textblocksObj = $textblocksHandler->create();
        $form = $textblocksObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'edit':
        // Breadcrumbs
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_TEXTBLOCK_EDIT];
        // Check permissions
        if (!$permissionsHandler->getPermGlobalSubmit()) {
            \redirect_header('textblocks.php?op=list', 3, \_NOPERM);
        }
        // Check params
        if (0 == $tbId) {
            \redirect_header('textblocks.php?op=list', 3, \_MA_WGEVENTS_INVALID_PARAM);
        }
        // Get Form
        $textblocksObj = $textblocksHandler->get($tbId);
        $textblocksObj->start = $start;
        $textblocksObj->limit = $limit;
        $form = $textblocksObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'clone':
        // Breadcrumbs
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_TEXTBLOCK_CLONE];
        // Check permissions
        if (!$permissionsHandler->getPermGlobalSubmit()) {
            \redirect_header('textblocks.php?op=list', 3, \_NOPERM);
        }
        // Request source
        $tbIdSource = Request::getInt('id_source');
        // Check params
        if (0 == $tbIdSource) {
            \redirect_header('textblocks.php?op=list', 3, \_MA_WGEVENTS_INVALID_PARAM);
        }
        // Get Form
        $textblocksObjSource = $textblocksHandler->get($tbIdSource);
        $textblocksObj = $textblocksObjSource->xoopsClone();
        $form = $textblocksObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'delete':
        // Breadcrumbs
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_TEXTBLOCK_DELETE];
        // Check permissions
        if (!$permissionsHandler->getPermGlobalSubmit()) {
            \redirect_header('textblocks.php?op=list', 3, \_NOPERM);
        }
        // Check params
        if (0 == $tbId) {
            \redirect_header('textblocks.php?op=list', 3, \_MA_WGEVENTS_INVALID_PARAM);
        }
        $textblocksObj = $textblocksHandler->get($tbId);
        $tbName = $textblocksObj->getVar('name');
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
                ['ok' => 1, 'id' => $tbId, 'start' => $start, 'limit' => $limit, 'op' => 'delete'],
                $_SERVER['REQUEST_URI'],
                \sprintf(\_MA_WGEVENTS_CONFIRMDELETE_TEXTBLOCK, $textblocksObj->getVar('name')), \_MA_WGEVENTS_CONFIRMDELETE_TITLE, \_MA_WGEVENTS_CONFIRMDELETE_LABEL);
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
$GLOBALS['xoopsTpl']->assign('xoops_mpageurl', \WGEVENTS_URL.'/textblocks.php');
$GLOBALS['xoopsTpl']->assign('wgevents_upload_url', \WGEVENTS_UPLOAD_URL);

require __DIR__ . '/footer.php';
