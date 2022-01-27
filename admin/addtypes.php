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
$atId  = Request::getInt('at_id');
$start = Request::getInt('start');
$limit = Request::getInt('limit', $helper->getConfig('adminpager'));
$GLOBALS['xoopsTpl']->assign('start', $start);
$GLOBALS['xoopsTpl']->assign('limit', $limit);

switch ($op) {
    case 'list':
    default:
        // Define Stylesheet
        $GLOBALS['xoTheme']->addStylesheet($style, null);
        $templateMain = 'wgevents_admin_addtypes.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('addtypes.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_ADD_ADDTYPE, 'addtypes.php?op=new');
        $adminObject->addItemButton(\_AM_WGEVENTS_ADDTYPE_CREATE_DEFAULT, 'addtypes.php?op=default_set');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        $addtypesCount = $addtypesHandler->getCountAddtypes();
        $addtypesAll = $addtypesHandler->getAllAddtypes($start, $limit);
        $GLOBALS['xoopsTpl']->assign('addtypes_count', $addtypesCount);
        $GLOBALS['xoopsTpl']->assign('wgevents_url', \WGEVENTS_URL);
        $GLOBALS['xoopsTpl']->assign('wgevents_upload_url', \WGEVENTS_UPLOAD_URL);
        $GLOBALS['xoopsTpl']->assign('wgevents_icons_url_16', \WGEVENTS_ICONS_URL_16);
        // Table view addtypes
        if ($addtypesCount > 0) {
            foreach (\array_keys($addtypesAll) as $i) {
                $addtype = $addtypesAll[$i]->getValuesAddtypes();
                $GLOBALS['xoopsTpl']->append('addtypes_list', $addtype);
                unset($addtype);
            }
            // Display Navigation
            if ($addtypesCount > $limit) {
                require_once \XOOPS_ROOT_PATH . '/class/pagenav.php';
                $pagenav = new \XoopsPageNav($addtypesCount, $limit, $start, 'start', 'op=list&limit=' . $limit);
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());
            }
        } else {
            $GLOBALS['xoopsTpl']->assign('error', \_AM_WGEVENTS_THEREARENT_ADDTYPES);
        }
        break;
    case 'new':
        $templateMain = 'wgevents_admin_addtypes.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('addtypes.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_ADDTYPES, 'addtypes.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Form Create
        $addtypesObj = $addtypesHandler->create();
        $form = $addtypesObj->getFormAddtypes();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'clone':
        $templateMain = 'wgevents_admin_addtypes.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('addtypes.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_ADDTYPES, 'addtypes.php', 'list');
        $adminObject->addItemButton(\_AM_WGEVENTS_ADD_ADDTYPE, 'addtypes.php?op=new');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Request source
        $atIdSource = Request::getInt('at_id_source');
        // Get Form
        $addtypesObjSource = $addtypesHandler->get($atIdSource);
        $addtypesObj = $addtypesObjSource->xoopsClone();
        $form = $addtypesObj->getFormAddtypes();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'save':
        // Security Check
        if (!$GLOBALS['xoopsSecurity']->check()) {
            \redirect_header('addtypes.php', 3, \implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if ($atId > 0) {
            $addtypesObj = $addtypesHandler->get($atId);
        } else {
            $addtypesObj = $addtypesHandler->create();
        }
        // Set Vars
        $atType = Request::getInt('at_type');
        $addtypesObj->setVar('at_type', $atType);
        $addtypesObj->setVar('at_caption', Request::getString('at_caption'));
        $addtypesObj->setVar('at_desc', Request::getText('at_desc'));
        $atValuesText = '';
        $atValues = Request::getString('at_values');
        if ('' != $atValues) {
            if (Constants::ADDTYPE_COMBOBOX == $atType || Constants::ADDTYPE_SELECTBOX == $atType || Constants::ADDTYPE_RADIO == $atType) {
                $atValuesText = \serialize(\preg_split('/\r\n|\r|\n/', $atValues));
            } else {
                $tmpArr = [$atValues];
                $atValuesText = \serialize($tmpArr);
            }
        }
        $addtypesObj->setVar('at_values', $atValuesText);
        $addtypesObj->setVar('at_placeholder', Request::getString('at_placeholder'));
        $addtypesObj->setVar('at_required', Request::getInt('at_required'));
        $addtypesObj->setVar('at_default', Request::getInt('at_default'));
        $addtypesObj->setVar('at_custom', Request::getInt('at_custom'));
        $addtypesObj->setVar('at_print', Request::getInt('at_print'));
        $addtypesObj->setVar('at_display_values', Request::getInt('at_display_values'));
        $addtypesObj->setVar('at_display_placeholder', Request::getInt('at_display_placeholder'));
        $addtypesObj->setVar('at_weight', Request::getInt('at_weight'));
        $addtypesObj->setVar('at_status', Request::getInt('at_status'));
        $addtypeDatecreatedObj = \DateTime::createFromFormat(\_SHORTDATESTRING, Request::getString('at_datecreated'));
        $addtypesObj->setVar('at_datecreated', $addtypeDatecreatedObj->getTimestamp());
        $addtypesObj->setVar('at_submitter', Request::getInt('at_submitter'));
        // Insert Data
        if ($addtypesHandler->insert($addtypesObj)) {
                \redirect_header('addtypes.php?op=list&amp;start=' . $start . '&amp;limit=' . $limit, 2, \_MA_WGEVENTS_FORM_OK);
        }
        // Get Form
        $GLOBALS['xoopsTpl']->assign('error', $addtypesObj->getHtmlErrors());
        $form = $addtypesObj->getFormAddtypes();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'edit':
        $templateMain = 'wgevents_admin_addtypes.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('addtypes.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_ADD_ADDTYPE, 'addtypes.php?op=new');
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_ADDTYPES, 'addtypes.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Get Form
        $addtypesObj = $addtypesHandler->get($atId);
        $addtypesObj->start = $start;
        $addtypesObj->limit = $limit;
        $form = $addtypesObj->getFormAddtypes();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'delete':
        $templateMain = 'wgevents_admin_addtypes.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('addtypes.php'));
        $addtypesObj = $addtypesHandler->get($atId);
        $atName = $addtypesObj->getVar('at_caption');
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                \redirect_header('addtypes.php', 3, \implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($addtypesHandler->delete($addtypesObj)) {
                \redirect_header('addtypes.php', 3, \_MA_WGEVENTS_FORM_DELETE_OK);
            } else {
                $GLOBALS['xoopsTpl']->assign('error', $addtypesObj->getHtmlErrors());
            }
        } else {
            $customConfirm = new Common\Confirm(
                ['ok' => 1, 'at_id' => $atId, 'start' => $start, 'limit' => $limit, 'op' => 'delete'],
                $_SERVER['REQUEST_URI'],
                \sprintf(\_MA_WGEVENTS_FORM_SURE_DELETE, $addtypesObj->getVar('at_caption')));
            $form = $customConfirm->getFormConfirm();
            $GLOBALS['xoopsTpl']->assign('form', $form->render());
        }
        break;
    case 'default_set':
        $templateMain = 'wgevents_admin_addtypes.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('addtypes.php'));
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                \redirect_header('addtypes.php', 3, \implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($addtypesHandler->getCount() > 0) {
                if (!$addtypesHandler->deleteAll()) {
                    $GLOBALS['xoopsTpl']->assign('error', $addtypesHandler->getHtmlErrors());
                }
            }
            $items = BuildDefaultSet();
            $uid = $GLOBALS['xoopsUser']->uid();
            foreach ($items as $key => $item) {
                $addtypesObj = $addtypesHandler->create();
                // Set Vars
                $addtypesObj->setVar('at_id', $key + 1);
                $addtypesObj->setVar('at_caption', $item['at_caption']);
                $addtypesObj->setVar('at_type', $item['at_type']);
                $addtypesObj->setVar('at_desc', $item['at_desc']);
                $atValues = (string)$item['at_values'];
                $atValuesText = '';
                if ('' != $atValues) {
                    $tmpArr[] = $atValues;
                    $atValuesText = \serialize($tmpArr);
                }
                $addtypesObj->setVar('at_values', $atValuesText);
                $addtypesObj->setVar('at_placeholder', $item['at_placeholder']);
                $addtypesObj->setVar('at_required', $item['at_required']);
                $addtypesObj->setVar('at_default', $item['at_default']);
                $addtypesObj->setVar('at_print', $item['at_print']);
                $addtypesObj->setVar('at_display_values', $item['at_display_values']);
                $addtypesObj->setVar('at_display_placeholder', $item['at_display_placeholder']);
                $addtypesObj->setVar('at_weight', $key + 1);
                $addtypesObj->setVar('at_custom', $item['at_custom']);
                $addtypesObj->setVar('at_status', Constants::STATUS_ONLINE);
                $addtypesObj->setVar('at_datecreated', \time());
                $addtypesObj->setVar('at_submitter', $uid);
                // Insert Data
                $addtypesHandler->insert($addtypesObj);
            }
            \redirect_header('addtypes.php', 3, \_MA_WGEVENTS_FORM_OK);
        } else {
            $customConfirm = new Common\Confirm(
                ['ok' => 1, 'op' => 'default_set'],
                $_SERVER['REQUEST_URI'],
                \_AM_WGEVENTS_ADDTYPE_SURE_DELETE);
            $form = $customConfirm->getFormConfirm();
            $GLOBALS['xoopsTpl']->assign('form', $form->render());
        }
        break;
    case 'change_yn':
        if ($atId > 0) {
            $addtypesObj = $addtypesHandler->get($atId);
            $addtypesObj->setVar(Request::getString('field'), Request::getInt('value'));
            // Insert Data
            if ($addtypesHandler->insert($addtypesObj, true)) {
                \redirect_header('addtypes.php?op=list&amp;start=' . $start . '&amp;limit=' . $limit, 2, \_MA_WGEVENTS_FORM_OK);
            }
        }
        break;
    case 'order':
        $order = $_POST['order'];
        for ($i = 0, $iMax = \count($order); $i < $iMax; $i++) {
            $addtypesObj = $addtypesHandler->get($order[$i]);
            $addtypesObj->setVar('dir_weight', $i + 1);
            $addtypesHandler->insert($addtypesObj);
        }
        break;
}
require __DIR__ . '/footer.php';

function BuildDefaultSet () {
    $defaultSet = [];
    $i = 0;

    $defaultSet[] = [
        'at_type' => Constants::ADDTYPE_LABEL,
        'at_caption' => \_MA_WGEVENTS_ADDTYPE_LABEL,
        'at_desc' => '',
        'at_values' => '',
        'at_placeholder' => '',
        'at_required' => 0,
        'at_default' => 0,
        'at_print' => 1,
        'at_display_values' => 0,
        'at_display_placeholder' => 0,
        'at_custom' => 0,
    ];
    $defaultSet[] = [
        'at_type' => Constants::ADDTYPE_TEXTBOX,
        'at_caption' => \_MA_WGEVENTS_ADDTYPE_TEXTBOX,
        'at_desc' => '',
        'at_values' => '',
        'at_placeholder' => '',
        'at_required' => 0,
        'at_default' => 0,
        'at_print' => 1,
        'at_display_values' => 0,
        'at_display_placeholder' => 1,
        'at_custom' => 0,
    ];
    $defaultSet[] = [
        'at_type' => Constants::ADDTYPE_TEXTAREA,
        'at_caption' => \_MA_WGEVENTS_ADDTYPE_TEXTAREA,
        'at_desc' => '',
        'at_values' => '',
        'at_placeholder' => '',
        'at_required' => 0,
        'at_default' => 0,
        'at_print' => 1,
        'at_display_values' => 0,
        'at_display_placeholder' => 1,
        'at_custom' => 0,
    ];
    $defaultSet[] = [
        'at_type' => Constants::ADDTYPE_SELECTBOX,
        'at_caption' => \_MA_WGEVENTS_ADDTYPE_SELECTBOX,
        'at_desc' => '',
        'at_values' => '',
        'at_placeholder' => '',
        'at_required' => 0,
        'at_default' => 0,
        'at_print' => 1,
        'at_display_values' => 1,
        'at_display_placeholder' => 0,
        'at_custom' => 0,
    ];
    $defaultSet[] = [
        'at_type' => Constants::ADDTYPE_COMBOBOX,
        'at_caption' => \_MA_WGEVENTS_ADDTYPE_COMBOBOX,
        'at_desc' => '',
        'at_values' => '',
        'at_placeholder' => '',
        'at_required' => 0,
        'at_default' => 0,
        'at_print' => 1,
        'at_display_values' => 1,
        'at_display_placeholder' => 0,
        'at_custom' => 0,
    ];
    $defaultSet[] = [
        'at_type' => Constants::ADDTYPE_CHECKBOX,
        'at_caption' => \_MA_WGEVENTS_ADDTYPE_CHECKBOX,
        'at_desc' => '',
        'at_values' => '',
        'at_placeholder' => '',
        'at_required' => 0,
        'at_default' => 0,
        'at_print' => 1,
        'at_display_values' => 1,
        'at_display_placeholder' => 0,
        'at_custom' => 0,
    ];
    $defaultSet[] = [
        'at_type' => Constants::ADDTYPE_RADIO,
        'at_caption' => \_MA_WGEVENTS_ADDTYPE_RADIO,
        'at_desc' => '',
        'at_values' => '',
        'at_placeholder' => '',
        'at_required' => 0,
        'at_default' => 0,
        'at_print' => 1,
        'at_display_values' => 1,
        'at_display_placeholder' => 0,
        'at_custom' => 0,
    ];
    $defaultSet[] = [
        'at_type' => Constants::ADDTYPE_RADIOYN,
        'at_caption' => \_MA_WGEVENTS_ADDTYPE_RADIOYN,
        'at_desc' => '',
        'at_values' => '',
        'at_placeholder' => '',
        'at_required' => 0,
        'at_default' => 0,
        'at_print' => 1,
        'at_display_values' => 0,
        'at_display_placeholder' => 0,
        'at_custom' => 0,
    ];
    $defaultSet[] = [
        'at_type' => Constants::ADDTYPE_DATE,
        'at_caption' => \_MA_WGEVENTS_ADDTYPE_DATE,
        'at_desc' => '',
        'at_values' => '',
        'at_placeholder' => '',
        'at_required' => 0,
        'at_default' => 0,
        'at_print' => 1,
        'at_display_values' => 0,
        'at_display_placeholder' => 0,
        'at_custom' => 0,
    ];
    /*
    $defaultSet[] = [
        'at_type' => Constants::ADDTYPE_DATETIME,
        'at_caption' => \_MA_WGEVENTS_ADDTYPE_DATETIME,
        'at_desc' => '',
        'at_values' => '',
        'at_required' => 0,
        'at_default' => 0,
        'at_print' => 1,
        'at_display_values' => 0,
        'at_display_placeholder' => 0,
        'at_custom' => 0,
    ];
    */
    $defaultSet[] = [
        'at_type' => Constants::ADDTYPE_TEXTBOX,
        'at_caption' => \_AM_WGEVENTS_ADDTYPE_PHONE,
        'at_desc' => '',
        'at_values' => '',
        'at_placeholder' => \_AM_WGEVENTS_ADDTYPE_PHONE_VALUE,
        'at_required' => 0,
        'at_default' => 1,
        'at_print' => 1,
        'at_display_values' => 0,
        'at_display_placeholder' => 1,
        'at_custom' => 1,
    ];
    $defaultSet[] = [
        'at_type' => Constants::ADDTYPE_TEXTBOX,
        'at_caption' => \_AM_WGEVENTS_ADDTYPE_ADDRESS,
        'at_desc' => '',
        'at_values' => '',
        'at_placeholder' => \_AM_WGEVENTS_ADDTYPE_ADDRESS_VALUE,
        'at_required' => 0,
        'at_default' => 0,
        'at_print' => 1,
        'at_display_values' => 0,
        'at_display_placeholder' => 1,
        'at_custom' => 1,
    ];
    $defaultSet[] = [
        'at_type' => Constants::ADDTYPE_TEXTBOX,
        'at_caption' => \_AM_WGEVENTS_ADDTYPE_POSTAL,
        'at_desc' => '',
        'at_values' => '',
        'at_placeholder' => \_AM_WGEVENTS_ADDTYPE_POSTAL_VALUE,
        'at_required' => 0,
        'at_default' => 0,
        'at_print' => 1,
        'at_display_values' => 0,
        'at_display_placeholder' => 1,
        'at_custom' => 1,
    ];
    $defaultSet[] = [
        'at_type' => Constants::ADDTYPE_TEXTBOX,
        'at_caption' => \_AM_WGEVENTS_ADDTYPE_CITY,
        'at_desc' => '',
        'at_values' => '',
        'at_placeholder' => \_AM_WGEVENTS_ADDTYPE_CITY_VALUE,
        'at_required' => 0,
        'at_default' => 0,
        'at_print' => 1,
        'at_display_values' => 0,
        'at_display_placeholder' => 1,
        'at_custom' => 1,
    ];
    $defaultSet[] = [
        'at_type' => Constants::ADDTYPE_COUNTRY,
        'at_caption' => \_AM_WGEVENTS_ADDTYPE_COUNTRY,
        'at_desc' => '',
        'at_values' => '',
        'at_placeholder' => \_AM_WGEVENTS_ADDTYPE_COUNTRY_VALUE,
        'at_required' => 0,
        'at_default' => 0,
        'at_print' => 1,
        'at_display_values' => 0,
        'at_display_placeholder' => 0,
        'at_custom' => 1,
    ];
    $defaultSet[] = [
        'at_type' => Constants::ADDTYPE_DATE,
        'at_caption' => \_AM_WGEVENTS_ADDTYPE_BIRTHDAY,
        'at_desc' => '',
        'at_values' => '',
        'at_placeholder' => \_AM_WGEVENTS_ADDTYPE_BIRTHDAY_VALUE,
        'at_required' => 0,
        'at_default' => 0,
        'at_print' => 1,
        'at_display_values' => 0,
        'at_display_placeholder' => 0,
        'at_custom' => 1,
    ];
    $defaultSet[] = [
        'at_type' => Constants::ADDTYPE_TEXTBOX,
        'at_caption' => \_AM_WGEVENTS_ADDTYPE_AGE,
        'at_desc' => '',
        'at_values' => '',
        'at_placeholder' => \_AM_WGEVENTS_ADDTYPE_AGE_VALUE,
        'at_required' => 0,
        'at_default' => 0,
        'at_print' => 1,
        'at_display_values' => 0,
        'at_display_placeholder' => 1,
        'at_custom' => 1,
    ];

    return $defaultSet;
}
