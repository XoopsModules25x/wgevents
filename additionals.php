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
use XoopsModules\Wgevents\{
    Constants,
    Common
};

require __DIR__ . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'wgevents_additionals.tpl';
require_once \XOOPS_ROOT_PATH . '/header.php';

$op      = Request::getCmd('op', 'list');
$addId   = Request::getInt('add_id');
$addEvid = Request::getInt('add_evid');
$start   = Request::getInt('start');
$limit   = Request::getInt('limit', $helper->getConfig('userpager'));
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

$GLOBALS['xoopsTpl']->assign('addEvid', $addEvid);

switch ($op) {
    case 'show':
    case 'list':
    default:
        $GLOBALS['xoTheme']->addScript(\WGEVENTS_URL . '/assets/js/jquery-ui.min.js');
        $GLOBALS['xoTheme']->addScript(\WGEVENTS_URL . '/assets/js/sortables.js');
        // Breadcrumbs
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_ADDITIONALS_LIST];
        // get default fields
        $regdefaults = [];
        $regdefaults[] = [
            'type_text' => \_MA_WGEVENTS_ADDTYPE_TEXTBOX,
            'caption' => \_MA_WGEVENTS_REGISTRATION_FIRSTNAME,
            'value_list' => '',
            'placeholder' => \_MA_WGEVENTS_REGISTRATION_FIRSTNAME_PLACEHOLDER,
            'required' => \_YES,
            'print' => \_YES
        ];
        $regdefaults[] = [
            'type_text' => \_MA_WGEVENTS_ADDTYPE_TEXTBOX,
            'caption' => \_MA_WGEVENTS_REGISTRATION_LASTNAME,
            'value_list' => '',
            'placeholder' => \_MA_WGEVENTS_REGISTRATION_LASTNAME_PLACEHOLDER,
            'required' => \_YES,
            'print' => \_YES
        ];
        $regdefaults[] = [
            'type_text' => \_MA_WGEVENTS_ADDTYPE_TEXTBOX,
            'caption' => \_MA_WGEVENTS_REGISTRATION_EMAIL,
            'value_list' => '',
            'placeholder' => \_MA_WGEVENTS_REGISTRATION_EMAIL_PLACEHOLDER,
            'required' => \_YES,
            'print' => \_YES
        ];
        $GLOBALS['xoopsTpl']->assign('regdefaults', $regdefaults);

        // get additional fields
        $crAdditionals = new \CriteriaCompo();
        $crAdditionals->add(new \Criteria('add_evid', $addEvid));
        $additionalsCount = $additionalsHandler->getCount($crAdditionals);
        $GLOBALS['xoopsTpl']->assign('additionalsCount', $additionalsCount);
        $crAdditionals->setSort('add_weight ASC, add_id');
        $crAdditionals->setOrder('DESC');
        $crAdditionals->setStart($start);
        $crAdditionals->setLimit($limit);
        $additionalsAll = $additionalsHandler->getAll($crAdditionals);
        if ($additionalsCount > 0) {
            $additionals = [];
            $evName = '';
            $evSubmitter = 0;
            $evStatus = 0;
            // Get All Additionals
            foreach (\array_keys($additionalsAll) as $i) {
                $additionals[$i] = $additionalsAll[$i]->getValuesAdditionals();
                if ('' == $evName) {
                    $eventsObj = $eventsHandler->get($additionalsAll[$i]->getVar('add_evid'));
                    $evName = $eventsObj->getVar('ev_name');
                    $evSubmitter = $eventsObj->getVar('ev_submitter');
                    $evStatus = $eventsObj->getVar('ev_status');
                    $keywords[$i] = $evName;
                }
            }
            $GLOBALS['xoopsTpl']->assign('additionals', $additionals);
            unset($additionals);
            // Display Navigation
            if ($additionalsCount > $limit) {
                require_once \XOOPS_ROOT_PATH . '/class/pagenav.php';
                $pagenav = new \XoopsPageNav($additionalsCount, $limit, $start, 'start', 'op=list&limit=' . $limit);
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());
            }
            $GLOBALS['xoopsTpl']->assign('eventName', $evName);
            $permEdit = $permissionsHandler->getPermAdditionalsAdmin($evSubmitter, $evStatus);
            $GLOBALS['xoopsTpl']->assign('permEdit', $permEdit);
            $GLOBALS['xoopsTpl']->assign('wgevents_icons_url_16', \WGEVENTS_ICONS_URL_16);

            $GLOBALS['xoopsTpl']->assign('xoops_pagetitle', \strip_tags($evName . ' - ' . $GLOBALS['xoopsModule']->getVar('name')));

        }
        break;
    case 'save':
        // Security Check
        if (!$GLOBALS['xoopsSecurity']->check()) {
            \redirect_header('additionals.php', 3, \implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        $eventsObj = $eventsHandler->get($addEvid);
        // Check permissions
        if (!$permissionsHandler->getPermAdditionalsAdmin($eventsObj->getVAr('ev_submitter'), $eventsObj->getVar('ev_status'))) {
            \redirect_header('index.php?op=list', 3, \_NOPERM);
        }
        if ($addId > 0) {
            $additionalsObj = $additionalsHandler->get($addId);
        } else {
            $additionalsObj = $additionalsHandler->create();
        }
        $additionalsObj->setVar('add_evid', $addEvid);
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
        $additionalsObj->setVar('add_required', Request::getInt('add_required'));
        $additionalsObj->setVar('add_print', Request::getInt('add_print'));
        $additionalsObj->setVar('add_weight', Request::getInt('add_weight'));
        if (Request::hasVar('add_datecreated_int')) {
            $additionalsObj->setVar('add_datecreated', Request::getInt('add_datecreated_int'));
        } else {
            $additionalDatecreatedObj = \DateTime::createFromFormat(\_SHORTDATESTRING, Request::getString('add_datecreated'));
            $additionalsObj->setVar('add_datecreated', $additionalDatecreatedObj->getTimestamp());
        }
        $additionalsObj->setVar('add_submitter', Request::getInt('add_submitter'));
        // Insert Data
        if ($additionalsHandler->insert($additionalsObj)) {
            // redirect after insert
            \redirect_header('additionals.php?op=list&amp;add_evid=' . $addEvid . '&amp;start=' . $start . '&amp;limit=' . $limit, 2, \_MA_WGEVENTS_FORM_OK);
        }
        // Get Form Error
        $GLOBALS['xoopsTpl']->assign('error', $additionalsObj->getHtmlErrors());
        $form = $additionalsObj->getFormAdditionals();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'newset':
        $eventsObj = $eventsHandler->get($addEvid);
        // Check permissions
        if (!$permissionsHandler->getPermAdditionalsAdmin($eventsObj->getVAr('ev_submitter'), $eventsObj->getVar('ev_status'))) {
            \redirect_header('index.php?op=list', 3, \_NOPERM);
        }
        $additionalsHandler->createAdditionalsDefaultset($addEvid);
        \redirect_header('additionals.php?op=list&amp;add_evid=' . $addEvid . '&amp;start=' . $start . '&amp;limit=' . $limit, 0, \_MA_WGEVENTS_FORM_OK);
        break;
    case 'new':
        $eventsObj = $eventsHandler->get($addEvid);
        // Check permissions
        if (!$permissionsHandler->getPermAdditionalsAdmin($eventsObj->getVAr('ev_submitter'), $eventsObj->getVar('ev_status'))) {
            \redirect_header('index.php?op=list', 3, \_NOPERM);
        }
        $GLOBALS['xoTheme']->addScript(\WGEVENTS_URL . '/assets/js/forms.js');
        // Breadcrumbs
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_ADDITIONAL_ADD];
        // Form Create
        $additionalsObj = $additionalsHandler->create();
        $additionalsObj->setVar('add_evid', $addEvid);
        $form = $additionalsObj->getFormAdditionals();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'test':
        $eventsObj = $eventsHandler->get($addEvid);
        // Check permissions
        if (!$permissionsHandler->getPermAdditionalsAdmin($eventsObj->getVAr('ev_submitter'), $eventsObj->getVar('ev_status'))) {
            \redirect_header('index.php?op=list', 3, \_NOPERM);
        }
        // Breadcrumbs
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_ADDITIONAL_ADD];
        // Form Create
        $registrationsObj = $registrationsHandler->create();
        $registrationsObj->setVar('reg_evid', $addEvid);
        $form = $registrationsObj->getFormRegistrations('', true);
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'edit':
        $eventsObj = $eventsHandler->get($addEvid);
        // Check permissions
        if (!$permissionsHandler->getPermAdditionalsAdmin($eventsObj->getVAr('ev_submitter'), $eventsObj->getVar('ev_status'))) {
            \redirect_header('index.php?op=list', 3, \_NOPERM);
        }
        $GLOBALS['xoTheme']->addScript(\WGEVENTS_URL . '/assets/js/forms.js');
        // Breadcrumbs
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_ADDITIONAL_EDIT];
        // Check params
        if (0 == $addId) {
            \redirect_header('additionals.php?op=list', 3, \_MA_WGEVENTS_INVALID_PARAM);
        }
        // Get Form
        $additionalsObj = $additionalsHandler->get($addId);
        $additionalsObj->start = $start;
        $additionalsObj->limit = $limit;
        $form = $additionalsObj->getFormAdditionals();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'clone':
        // Breadcrumbs
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_ADDITIONAL_CLONE];
        // Request source
        $addIdSource = Request::getInt('add_id_source');
        // Check params
        if (0 == $addIdSource) {
            \redirect_header('additionals.php?op=list', 3, \_MA_WGEVENTS_INVALID_PARAM);
        }
        // Get Form
        $additionalsObjSource = $additionalsHandler->get($addIdSource);
        $additionalsObj = $additionalsHandler->create();
        $additionalsObj->setVar('add_evid', $additionalsObjSource->getVar('add_evid'));
        $additionalsObj->setVar('add_atid', $additionalsObjSource->getVar('add_atid'));
        $additionalsObj->setVar('add_type', $additionalsObjSource->getVar('add_type'));
        $additionalsObj->setVar('add_caption', $additionalsObjSource->getVar('add_caption'));
        $additionalsObj->setVar('add_desc', $additionalsObjSource->getVar('add_desc'));
        $additionalsObj->setVar('add_values', $additionalsObjSource->getVar('add_values'));
        $additionalsObj->setVar('add_placeholder', $additionalsObjSource->getVar('add_placeholder'));
        $additionalsObj->setVar('add_required', $additionalsObjSource->getVar('add_required'));
        $additionalsObj->setVar('add_print', $additionalsObjSource->getVar('add_print'));
        $additionalsObj->setVar('add_weight', $additionalsObjSource->getVar('add_weight'));
        $form = $additionalsObj->getFormAdditionals('additionals.php?op=save');
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        unset($additionalsObjSource);
        break;
    case 'delete':
        // Breadcrumbs
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_ADDITIONAL_DELETE];
        // Check params
        if (0 == $addId) {
            \redirect_header('additionals.php?op=list', 3, \_MA_WGEVENTS_INVALID_PARAM);
        }
        $additionalsObj = $additionalsHandler->get($addId);
        $addEvid = $additionalsObj->getVar('add_evid');
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                \redirect_header('additionals.php', 3, \implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($additionalsHandler->delete($additionalsObj)) {
                \redirect_header('additionals.php?list&amp;add_evid=' . $addEvid, 3, \_MA_WGEVENTS_FORM_DELETE_OK);
            } else {
                $GLOBALS['xoopsTpl']->assign('error', $additionalsObj->getHtmlErrors());
            }
        } else {
            $customConfirm = new Common\Confirm(
                ['ok' => 1, 'add_id' => $addId, 'add_evid' => $addEvid, 'op' => 'delete'],
                $_SERVER['REQUEST_URI'],
                \sprintf(\_MA_WGEVENTS_CONFIRMDELETE_ADDITIONAL, $additionalsObj->getVar('add_caption')), \_MA_WGEVENTS_CONFIRMDELETE_TITLE, \_MA_WGEVENTS_CONFIRMDELETE_LABEL);
            $form = $customConfirm->getFormConfirm();
            $GLOBALS['xoopsTpl']->assign('form', $form->render());
        }
        break;
    case 'order':
        $order = $_POST['order'];
        for ($i = 0, $iMax = \count($order); $i < $iMax; $i++) {
            $additionalsObj = $additionalsHandler->get($order[$i]);
            $additionalsObj->setVar('add_weight', $i + 1);
            $additionalsHandler->insert($additionalsObj);
        }
        break;
}

// Keywords
wgeventsMetaKeywords($helper->getConfig('keywords') . ', ' . \implode(',', $keywords));
unset($keywords);

// Description
wgeventsMetaDescription(\_MA_WGEVENTS_ADDITIONALS_DESC);
$GLOBALS['xoopsTpl']->assign('xoops_mpageurl', \WGEVENTS_URL.'/additionals.php');
$GLOBALS['xoopsTpl']->assign('wgevents_upload_url', \WGEVENTS_UPLOAD_URL);

require __DIR__ . '/footer.php';
