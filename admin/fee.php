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
$op    = Request::getCmd('op', 'list');
$Id    = Request::getInt('id');
$start = Request::getInt('start');
$limit = Request::getInt('limit', $helper->getConfig('adminpager'));
$GLOBALS['xoopsTpl']->assign('start', $start);
$GLOBALS['xoopsTpl']->assign('limit', $limit);

switch ($op) {
    case 'list':
    default:
        // Define Stylesheet
        $GLOBALS['xoTheme']->addStylesheet($style, null);
        $templateMain = 'wgevents_admin_fee.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('fee.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_ADD_FEE, 'fee.php?op=new');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        $feeCount = $feeHandler->getCountFee();
        $feeAll = $feeHandler->getAllFee($start, $limit);
        $GLOBALS['xoopsTpl']->assign('fee_count', $feeCount);
        $GLOBALS['xoopsTpl']->assign('wgevents_url', \WGEVENTS_URL);
        $GLOBALS['xoopsTpl']->assign('wgevents_upload_url', \WGEVENTS_UPLOAD_URL);
        // Table view fee
        if ($feeCount > 0) {
            foreach (\array_keys($feeAll) as $i) {
                $fee = $feeAll[$i]->getValuesFee();
                $GLOBALS['xoopsTpl']->append('fee_list', $fee);
                unset($fee);
            }
            // Display Navigation
            if ($feeCount > $limit) {
                require_once \XOOPS_ROOT_PATH . '/class/pagenav.php';
                $pagenav = new \XoopsPageNav($feeCount, $limit, $start, 'start', 'op=list&limit=' . $limit);
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());
            }
        } else {
            $GLOBALS['xoopsTpl']->assign('error', \_AM_WGEVENTS_THEREARENT_FEE);
        }
        break;
    case 'new':
        $templateMain = 'wgevents_admin_fee.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('fee.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_FEE, 'fee.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Form Create
        $feeObj = $feeHandler->create();
        $form = $feeObj->getFormFee();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'clone':
        $templateMain = 'wgevents_admin_fee.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('fee.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_FEE, 'fee.php', 'list');
        $adminObject->addItemButton(\_AM_WGEVENTS_ADD_FEE, 'fee.php?op=new');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Request source
        $IdSource = Request::getInt('id_source');
        // Get Form
        $feeObjSource = $feeHandler->get($IdSource);
        $feeObj = $feeObjSource->xoopsClone();
        $form = $feeObj->getFormFee();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'save':
        // Security Check
        if (!$GLOBALS['xoopsSecurity']->check()) {
            \redirect_header('fee.php', 3, \implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if ($Id > 0) {
            $feeObj = $feeHandler->get($Id);
        } else {
            $feeObj = $feeHandler->create();
        }
        // Set Vars
        $feeObj->setVar('evid', Request::getInt('evid'));
        $feeObj->setVar('amount', Request::getFloat('amount'));
        $feeObj->setVar('desc', Request::getString('desc'));
        $feeDatecreatedObj = \DateTime::createFromFormat(\_SHORTDATESTRING, Request::getString('datecreated'));
        $feeObj->setVar('datecreated', $feeDatecreatedObj->getTimestamp());
        // Insert Data
        if ($feeHandler->insert($feeObj)) {
                \redirect_header('fee.php?op=list&amp;start=' . $start . '&amp;limit=' . $limit, 2, \_AM_WGEVENTS_FORM_OK);
        }
        // Get Form
        $GLOBALS['xoopsTpl']->assign('error', $feeObj->getHtmlErrors());
        $form = $feeObj->getFormFee();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'edit':
        $templateMain = 'wgevents_admin_fee.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('fee.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_ADD_FEE, 'fee.php?op=new');
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_FEE, 'fee.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Get Form
        $feeObj = $feeHandler->get($Id);
        $feeObj->start = $start;
        $feeObj->limit = $limit;
        $form = $feeObj->getFormFee();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'delete':
        $templateMain = 'wgevents_admin_fee.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('fee.php'));
        $feeObj = $feeHandler->get($Id);
        $Evid = $feeObj->getVar('evid');
        if (isset($_REQUEST['ok']) && 1 === (int)$_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                \redirect_header('fee.php', 3, \implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($feeHandler->delete($feeObj)) {
                \redirect_header('fee.php', 3, \_AM_WGEVENTS_FORM_DELETE_OK);
            } else {
                $GLOBALS['xoopsTpl']->assign('error', $feeObj->getHtmlErrors());
            }
        } else {
            $customConfirm = new Common\Confirm(
                ['ok' => 1, 'id' => $Id, 'start' => $start, 'limit' => $limit, 'op' => 'delete'],
                $_SERVER['REQUEST_URI'],
                \sprintf(\_AM_WGEVENTS_FORM_SURE_DELETE, $feeObj->getVar('evid')));
            $form = $customConfirm->getFormConfirm();
            $GLOBALS['xoopsTpl']->assign('form', $form->render());
        }
        break;
}
require __DIR__ . '/footer.php';
