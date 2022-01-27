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
$addId = Request::getInt('add_id');
$evId  = Request::getInt('ev_id');
$start = Request::getInt('start');
$limit = Request::getInt('limit', $helper->getConfig('adminpager'));
$GLOBALS['xoopsTpl']->assign('start', $start);
$GLOBALS['xoopsTpl']->assign('limit', $limit);

switch ($op) {
    case 'list':
    default:
        // Define Stylesheet
        $GLOBALS['xoTheme']->addStylesheet($style, null);
        $templateMain = 'wgevents_admin_additionals.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('additionals.php'));
        if ($evId > 0) {
            $adminObject->addItemButton(\_AM_WGEVENTS_ADD_ADDITIONAL, 'additionals.php?op=new&amp;ev_id=' . $evId);
            $adminObject->addItemButton(\_AM_WGEVENTS_GOTO_FORMSELECT, 'additionals.php', 'list');
            $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
            $crAdditionals = new \CriteriaCompo();
            $crAdditionals->add(new \Criteria('add_evid', $evId));
            $additionalsCount = $additionalsHandler->getCount($crAdditionals);
            $GLOBALS['xoopsTpl']->assign('additionalsCount', $additionalsCount);
            $crAdditionals->setSort('add_weight ASC, add_id');
            $crAdditionals->setOrder('DESC');
            $crAdditionals->setStart($start);
            $crAdditionals->setLimit($limit);
            $GLOBALS['xoopsTpl']->assign('additionals_count', $additionalsCount);
            $GLOBALS['xoopsTpl']->assign('wgevents_url', \WGEVENTS_URL);
            $GLOBALS['xoopsTpl']->assign('wgevents_upload_url', \WGEVENTS_UPLOAD_URL);
            // Table view additionals
            if ($additionalsCount > 0) {
                $additionalsAll = $additionalsHandler->getAll($crAdditionals);
                foreach (\array_keys($additionalsAll) as $i) {
                    $additional = $additionalsAll[$i]->getValuesAdditionals();
                    $GLOBALS['xoopsTpl']->append('additionals_list', $additional);
                    unset($additional);
                }
                // Display Navigation
                if ($additionalsCount > $limit) {
                    require_once \XOOPS_ROOT_PATH . '/class/pagenav.php';
                    $pagenav = new \XoopsPageNav($additionalsCount, $limit, $start, 'start', 'op=list&limit=' . $limit);
                    $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());
                }
            } else {
                $GLOBALS['xoopsTpl']->assign('error', \_AM_WGEVENTS_THEREARENT_ADDITIONALS);
            }
        } else {
            $form = $eventsHandler->getFormEventSelect();
            $GLOBALS['xoopsTpl']->assign('form', $form->render());
        }
        break;
    case 'new':
        $templateMain = 'wgevents_admin_additionals.tpl';
        $GLOBALS['xoTheme']->addScript(\WGEVENTS_URL . '/assets/js/forms.js');
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('additionals.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_ADDITIONALS, 'additionals.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Form Create
        $additionalsObj = $additionalsHandler->create();
        $additionalsObj->setVar('add_evid', $evId);
        $form = $additionalsObj->getFormAdditionals();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'clone':
        $templateMain = 'wgevents_admin_additionals.tpl';
        $GLOBALS['xoTheme']->addScript(\WGEVENTS_URL . '/assets/js/forms.js');
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('additionals.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_ADDITIONALS, 'additionals.php', 'list');
        $adminObject->addItemButton(\_AM_WGEVENTS_ADD_ADDITIONAL, 'additionals.php?op=new');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Request source
        $addIdSource = Request::getInt('add_id_source');
        // Get Form
        $additionalsObjSource = $additionalsHandler->get($addIdSource);
        $additionalsObj = $additionalsObjSource->xoopsClone();
        $form = $additionalsObj->getFormAdditionals();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'save':
        // Security Check
        if (!$GLOBALS['xoopsSecurity']->check()) {
            \redirect_header('additionals.php', 3, \implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if ($addId > 0) {
            $additionalsObj = $additionalsHandler->get($addId);
        } else {
            $additionalsObj = $additionalsHandler->create();
        }
        // Set Vars
        $additionalsObj->setVar('add_evid', Request::getInt('add_evid'));
        $addType = Request::getInt('add_type');
        $additionalsObj->setVar('add_atid', $addType);
        $addtypesObj = $addtypesHandler->get($addType);
        $additionalsObj->setVar('add_type', $addtypesObj->getVar('at_type'));
        $additionalsObj->setVar('add_caption', Request::getString('add_caption'));
        $additionalsObj->setVar('add_desc', Request::getText('add_desc'));
        $addValuesText = '';
        $addValues = Request::getString('add_values');
        if ('' != $addValues) {
            if (Constants::ADDTYPE_COMBOBOX == $addType || Constants::ADDTYPE_SELECTBOX == $addType || Constants::ADDTYPE_RADIO == $addType) {
                $addValuesText = \serialize(\preg_split('/\r\n|\r|\n/', $addValues));
            } else {
                $tmpArr = [$addValues];
                $addValuesText = \serialize($tmpArr);
            }
        }
        $additionalsObj->setVar('add_values', $addValuesText);
        $additionalsObj->setVar('add_placeholder', Request::getString('add_placeholder'));
        $additionalsObj->setVar('add_weight', Request::getInt('add_weight'));
        $additionalsObj->setVar('add_required', Request::getInt('add_required'));
        $additionalsObj->setVar('add_print', Request::getInt('add_print'));
        $additionalDatecreatedObj = \DateTime::createFromFormat(\_SHORTDATESTRING, Request::getString('add_datecreated'));
        $additionalsObj->setVar('add_datecreated', $additionalDatecreatedObj->getTimestamp());
        $additionalsObj->setVar('add_submitter', Request::getInt('add_submitter'));
        // Insert Data
        if ($additionalsHandler->insert($additionalsObj)) {
            \redirect_header('additionals.php?op=list&amp;start=' . $start . '&amp;limit=' . $limit, 2, \_MA_WGEVENTS_FORM_OK);
        }
        // Get Form
        $GLOBALS['xoopsTpl']->assign('error', $additionalsObj->getHtmlErrors());
        $form = $additionalsObj->getFormAdditionals();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'edit':
        $templateMain = 'wgevents_admin_additionals.tpl';
        $GLOBALS['xoTheme']->addScript(\WGEVENTS_URL . '/assets/js/forms.js');
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('additionals.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_ADD_ADDITIONAL, 'additionals.php?op=new');
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_ADDITIONALS, 'additionals.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Get Form
        $additionalsObj = $additionalsHandler->get($addId);
        $additionalsObj->start = $start;
        $additionalsObj->limit = $limit;
        $form = $additionalsObj->getFormAdditionals();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'delete':
        $templateMain = 'wgevents_admin_additionals.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('additionals.php'));
        $additionalsObj = $additionalsHandler->get($addId);
        $addEvid = $additionalsObj->getVar('add_evid');
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                \redirect_header('additionals.php', 3, \implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($additionalsHandler->delete($additionalsObj)) {
                \redirect_header('additionals.php', 3, \_MA_WGEVENTS_FORM_DELETE_OK);
            } else {
                $GLOBALS['xoopsTpl']->assign('error', $additionalsObj->getHtmlErrors());
            }
        } else {
            $customConfirm = new Common\Confirm(
                ['ok' => 1, 'add_id' => $addId, 'start' => $start, 'limit' => $limit, 'op' => 'delete'],
                $_SERVER['REQUEST_URI'],
                \sprintf(\_MA_WGEVENTS_FORM_SURE_DELETE, $additionalsObj->getVar('add_evid')));
            $form = $customConfirm->getFormConfirm();
            $GLOBALS['xoopsTpl']->assign('form', $form->render());
        }
        break;
}
require __DIR__ . '/footer.php';
