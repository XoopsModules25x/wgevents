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
$tbId  = Request::getInt('id');
$start = Request::getInt('start');
$limit = Request::getInt('limit', $helper->getConfig('adminpager'));
$GLOBALS['xoopsTpl']->assign('start', $start);
$GLOBALS['xoopsTpl']->assign('limit', $limit);

$moduleDirName = \basename(\dirname(__DIR__));

$GLOBALS['xoopsTpl']->assign('mod_url', XOOPS_URL . '/modules/' . $moduleDirName);

switch ($op) {
    case 'list':
    default:
        $GLOBALS['xoTheme']->addScript(\WGEVENTS_URL . '/assets/js/jquery-ui.min.js');
        $GLOBALS['xoTheme']->addScript(\WGEVENTS_URL . '/assets/js/sortables.js');
        // Define Stylesheet
        $GLOBALS['xoTheme']->addStylesheet($style, null);
        $templateMain = 'wgevents_admin_textblock.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('textblock.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_ADD_TEXTBLOCK, 'textblock.php?op=new');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        $textblockCount = $textblockHandler->getCountTextblocks();
        $textblockAll = $textblockHandler->getAllTextblocks();
        $GLOBALS['xoopsTpl']->assign('textblockCount', $textblockCount);
        $GLOBALS['xoopsTpl']->assign('wgevents_url', \WGEVENTS_URL);
        $GLOBALS['xoopsTpl']->assign('wgevents_upload_url', \WGEVENTS_UPLOAD_URL);
        $GLOBALS['xoopsTpl']->assign('wgevents_icons_url_16', \WGEVENTS_ICONS_URL_16);
        // Table view textblocks
        if ($textblockCount > 0) {
            foreach (\array_keys($textblockAll) as $i) {
                $textblock = $textblockAll[$i]->getValuesTextblocks();
                $GLOBALS['xoopsTpl']->append('textblocks_list', $textblock);
                unset($textblock);
            }
            /*
            // Display Navigation
            if ($textblockCount > $limit) {
                require_once \XOOPS_ROOT_PATH . '/class/pagenav.php';
                $pagenav = new \XoopsPageNav($textblockCount, $limit, $start, 'start', 'op=list&limit=' . $limit);
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());
            }*/
        } else {
            $GLOBALS['xoopsTpl']->assign('error', \_AM_WGEVENTS_THEREARENT_TEXTBLOCKS);
        }
        break;
    case 'new':
        $templateMain = 'wgevents_admin_textblock.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('textblock.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_TEXTBLOCKS, 'textblock.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Form Create
        $textblockObj = $textblockHandler->create();
        $form = $textblockObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'clone':
        $templateMain = 'wgevents_admin_textblock.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('textblock.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_TEXTBLOCKS, 'textblock.php', 'list');
        $adminObject->addItemButton(\_AM_WGEVENTS_ADD_TEXTBLOCK, 'textblock.php?op=new');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Request source
        $tbIdSource = Request::getInt('id_source');
        // Get Form
        $textblockObjSource = $textblockHandler->get($tbIdSource);
        $textblockObj = $textblockObjSource->xoopsClone();
        $form = $textblockObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'save':
        // Security Check
        if (!$GLOBALS['xoopsSecurity']->check()) {
            \redirect_header('textblock.php', 3, \implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if ($tbId > 0) {
            $textblockObj = $textblockHandler->get($tbId);
        } else {
            $textblockObj = $textblockHandler->create();
        }
        // Set Vars
        $textblockObj->setVar('name', Request::getString('name'));
        $textblockObj->setVar('text', Request::getText('text'));
        $textblockObj->setVar('weight', Request::getInt('weight'));
        $textblockObj->setVar('catid', Request::getInt('catid'));
        $textblockObj->setVar('class', Request::getInt('class'));
        $textblockDatecreatedObj = \DateTime::createFromFormat(\_SHORTDATESTRING, Request::getString('datecreated'));
        $textblockObj->setVar('datecreated', $textblockDatecreatedObj->getTimestamp());
        $textblockObj->setVar('submitter', Request::getInt('submitter'));
        // Insert Data
        if ($textblockHandler->insert($textblockObj)) {
            \redirect_header('textblock.php?op=list&amp;start=' . $start . '&amp;limit=' . $limit, 2, \_MA_WGEVENTS_FORM_OK);
        }
        // Get Form
        $GLOBALS['xoopsTpl']->assign('error', $textblockObj->getHtmlErrors());
        $form = $textblockObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'edit':
        $templateMain = 'wgevents_admin_textblock.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('textblock.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_ADD_TEXTBLOCK, 'textblock.php?op=new');
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_TEXTBLOCKS, 'textblock.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Get Form
        $textblockObj = $textblockHandler->get($tbId);
        $textblockObj->start = $start;
        $textblockObj->limit = $limit;
        $form = $textblockObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'delete':
        $templateMain = 'wgevents_admin_textblock.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('textblock.php'));
        $textblockObj = $textblockHandler->get($tbId);
        $tbName = $textblockObj->getVar('name');
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
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
                \sprintf(\_MA_WGEVENTS_FORM_SURE_DELETE, $textblockObj->getVar('name')));
            $form = $customConfirm->getFormConfirm();
            $GLOBALS['xoopsTpl']->assign('form', $form->render());
        }
        break;
    case 'order':
        $order = $_POST['order'];
        for ($i = 0, $iMax = \count($order); $i < $iMax; $i++) {
            $textblockObj = $textblockHandler->get($order[$i]);
            $textblockObj->setVar('weight', $i + 1);
            $textblockHandler->insert($textblockObj);
        }
        break;
}
require __DIR__ . '/footer.php';
