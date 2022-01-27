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
$atId  = Request::getInt('fd_id');
$start = Request::getInt('start');
$limit = Request::getInt('limit', $helper->getConfig('adminpager'));
$GLOBALS['xoopsTpl']->assign('start', $start);
$GLOBALS['xoopsTpl']->assign('limit', $limit);

switch ($op) {
    case 'list':
    default:
        // Define Stylesheet
        $GLOBALS['xoTheme']->addStylesheet($style, null);
        $templateMain = 'wgevents_admin_fields.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('fields.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_ADD_FIELD, 'fields.php?op=new');
        $adminObject->addItemButton(\_AM_WGEVENTS_FIELD_CREATE_DEFAULT, 'fields.php?op=default_set');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        $fieldsCount = $fieldsHandler->getCountFields();
        $fieldsAll = $fieldsHandler->getAllFields($start, $limit);
        $GLOBALS['xoopsTpl']->assign('fields_count', $fieldsCount);
        $GLOBALS['xoopsTpl']->assign('wgevents_url', \WGEVENTS_URL);
        $GLOBALS['xoopsTpl']->assign('wgevents_upload_url', \WGEVENTS_UPLOAD_URL);
        $GLOBALS['xoopsTpl']->assign('wgevents_icons_url_16', \WGEVENTS_ICONS_URL_16);
        // Table view fields
        if ($fieldsCount > 0) {
            foreach (\array_keys($fieldsAll) as $i) {
                $field = $fieldsAll[$i]->getValuesFields();
                $GLOBALS['xoopsTpl']->append('fields_list', $field);
                unset($field);
            }
            // Display Navigation
            if ($fieldsCount > $limit) {
                require_once \XOOPS_ROOT_PATH . '/class/pagenav.php';
                $pagenav = new \XoopsPageNav($fieldsCount, $limit, $start, 'start', 'op=list&limit=' . $limit);
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());
            }
        } else {
            $GLOBALS['xoopsTpl']->assign('error', \_AM_WGEVENTS_THEREARENT_FIELDS);
        }
        break;
    case 'new':
        $templateMain = 'wgevents_admin_fields.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('fields.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_FIELDS, 'fields.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Form Create
        $fieldsObj = $fieldsHandler->create();
        $form = $fieldsObj->getFormFields();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'clone':
        $templateMain = 'wgevents_admin_fields.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('fields.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_FIELDS, 'fields.php', 'list');
        $adminObject->addItemButton(\_AM_WGEVENTS_ADD_FIELD, 'fields.php?op=new');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Request source
        $atIdSource = Request::getInt('fd_id_source');
        // Get Form
        $fieldsObjSource = $fieldsHandler->get($atIdSource);
        $fieldsObj = $fieldsObjSource->xoopsClone();
        $form = $fieldsObj->getFormFields();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'save':
        // Security Check
        if (!$GLOBALS['xoopsSecurity']->check()) {
            \redirect_header('fields.php', 3, \implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if ($atId > 0) {
            $fieldsObj = $fieldsHandler->get($atId);
        } else {
            $fieldsObj = $fieldsHandler->create();
        }
        // Set Vars
        $fdType = Request::getInt('fd_type');
        $fieldsObj->setVar('fd_type', $fdType);
        $fieldsObj->setVar('fd_caption', Request::getString('fd_caption'));
        $fieldsObj->setVar('fd_desc', Request::getText('fd_desc'));
        $atValuesText = '';
        $fdValues = Request::getString('fd_values');
        if ('' != $fdValues) {
            if (Constants::FIELD_COMBOBOX == $fdType || Constants::FIELD_SELECTBOX == $fdType || Constants::FIELD_RADIO == $fdType) {
                $atValuesText = \serialize(\preg_split('/\r\n|\r|\n/', $fdValues));
            } else {
                $tmpArr = [$fdValues];
                $atValuesText = \serialize($tmpArr);
            }
        }
        $fieldsObj->setVar('fd_values', $atValuesText);
        $fieldsObj->setVar('fd_placeholder', Request::getString('fd_placeholder'));
        $fieldsObj->setVar('fd_required', Request::getInt('fd_required'));
        $fieldsObj->setVar('fd_default', Request::getInt('fd_default'));
        $fieldsObj->setVar('fd_custom', Request::getInt('fd_custom'));
        $fieldsObj->setVar('fd_print', Request::getInt('fd_print'));
        $fieldsObj->setVar('fd_display_values', Request::getInt('fd_display_values'));
        $fieldsObj->setVar('fd_display_placeholder', Request::getInt('fd_display_placeholder'));
        $fieldsObj->setVar('fd_weight', Request::getInt('fd_weight'));
        $fieldsObj->setVar('fd_status', Request::getInt('fd_status'));
        $fieldDatecreatedObj = \DateTime::createFromFormat(\_SHORTDATESTRING, Request::getString('fd_datecreated'));
        $fieldsObj->setVar('fd_datecreated', $fieldDatecreatedObj->getTimestamp());
        $fieldsObj->setVar('fd_submitter', Request::getInt('fd_submitter'));
        // Insert Data
        if ($fieldsHandler->insert($fieldsObj)) {
                \redirect_header('fields.php?op=list&amp;start=' . $start . '&amp;limit=' . $limit, 2, \_MA_WGEVENTS_FORM_OK);
        }
        // Get Form
        $GLOBALS['xoopsTpl']->assign('error', $fieldsObj->getHtmlErrors());
        $form = $fieldsObj->getFormFields();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'edit':
        $templateMain = 'wgevents_admin_fields.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('fields.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_ADD_FIELD, 'fields.php?op=new');
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_FIELDS, 'fields.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Get Form
        $fieldsObj = $fieldsHandler->get($atId);
        $fieldsObj->start = $start;
        $fieldsObj->limit = $limit;
        $form = $fieldsObj->getFormFields();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'delete':
        $templateMain = 'wgevents_admin_fields.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('fields.php'));
        $fieldsObj = $fieldsHandler->get($atId);
        $atName = $fieldsObj->getVar('fd_caption');
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                \redirect_header('fields.php', 3, \implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($fieldsHandler->delete($fieldsObj)) {
                \redirect_header('fields.php', 3, \_MA_WGEVENTS_FORM_DELETE_OK);
            } else {
                $GLOBALS['xoopsTpl']->assign('error', $fieldsObj->getHtmlErrors());
            }
        } else {
            $customConfirm = new Common\Confirm(
                ['ok' => 1, 'fd_id' => $atId, 'start' => $start, 'limit' => $limit, 'op' => 'delete'],
                $_SERVER['REQUEST_URI'],
                \sprintf(\_MA_WGEVENTS_FORM_SURE_DELETE, $fieldsObj->getVar('fd_caption')));
            $form = $customConfirm->getFormConfirm();
            $GLOBALS['xoopsTpl']->assign('form', $form->render());
        }
        break;
    case 'default_set':
        $templateMain = 'wgevents_admin_fields.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('fields.php'));
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                \redirect_header('fields.php', 3, \implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($fieldsHandler->getCount() > 0) {
                if (!$fieldsHandler->deleteAll()) {
                    $GLOBALS['xoopsTpl']->assign('error', $fieldsHandler->getHtmlErrors());
                }
            }
            $items = BuildDefaultSet();
            $uid = $GLOBALS['xoopsUser']->uid();
            foreach ($items as $key => $item) {
                $fieldsObj = $fieldsHandler->create();
                // Set Vars
                $fieldsObj->setVar('fd_id', $key + 1);
                $fieldsObj->setVar('fd_caption', $item['fd_caption']);
                $fieldsObj->setVar('fd_type', $item['fd_type']);
                $fieldsObj->setVar('fd_desc', $item['fd_desc']);
                $fdValues = (string)$item['fd_values'];
                $atValuesText = '';
                if ('' != $fdValues) {
                    $tmpArr[] = $fdValues;
                    $atValuesText = \serialize($tmpArr);
                }
                $fieldsObj->setVar('fd_values', $atValuesText);
                $fieldsObj->setVar('fd_placeholder', $item['fd_placeholder']);
                $fieldsObj->setVar('fd_required', $item['fd_required']);
                $fieldsObj->setVar('fd_default', $item['fd_default']);
                $fieldsObj->setVar('fd_print', $item['fd_print']);
                $fieldsObj->setVar('fd_display_values', $item['fd_display_values']);
                $fieldsObj->setVar('fd_display_placeholder', $item['fd_display_placeholder']);
                $fieldsObj->setVar('fd_weight', $key + 1);
                $fieldsObj->setVar('fd_custom', $item['fd_custom']);
                $fieldsObj->setVar('fd_status', Constants::STATUS_ONLINE);
                $fieldsObj->setVar('fd_datecreated', \time());
                $fieldsObj->setVar('fd_submitter', $uid);
                // Insert Data
                $fieldsHandler->insert($fieldsObj);
            }
            \redirect_header('fields.php', 3, \_MA_WGEVENTS_FORM_OK);
        } else {
            $customConfirm = new Common\Confirm(
                ['ok' => 1, 'op' => 'default_set'],
                $_SERVER['REQUEST_URI'],
                \_AM_WGEVENTS_FIELD_SURE_DELETE);
            $form = $customConfirm->getFormConfirm();
            $GLOBALS['xoopsTpl']->assign('form', $form->render());
        }
        break;
    case 'change_yn':
        if ($atId > 0) {
            $fieldsObj = $fieldsHandler->get($atId);
            $fieldsObj->setVar(Request::getString('field'), Request::getInt('value'));
            // Insert Data
            if ($fieldsHandler->insert($fieldsObj, true)) {
                \redirect_header('fields.php?op=list&amp;start=' . $start . '&amp;limit=' . $limit, 2, \_MA_WGEVENTS_FORM_OK);
            }
        }
        break;
    case 'order':
        $order = $_POST['order'];
        for ($i = 0, $iMax = \count($order); $i < $iMax; $i++) {
            $fieldsObj = $fieldsHandler->get($order[$i]);
            $fieldsObj->setVar('dir_weight', $i + 1);
            $fieldsHandler->insert($fieldsObj);
        }
        break;
}
require __DIR__ . '/footer.php';

function BuildDefaultSet () {
    $defaultSet = [];
    $i = 0;

    $defaultSet[] = [
        'fd_type' => Constants::FIELD_LABEL,
        'fd_caption' => \_MA_WGEVENTS_FIELD_LABEL,
        'fd_desc' => '',
        'fd_values' => '',
        'fd_placeholder' => '',
        'fd_required' => 0,
        'fd_default' => 0,
        'fd_print' => 1,
        'fd_display_values' => 0,
        'fd_display_placeholder' => 0,
        'fd_custom' => 0,
    ];
    $defaultSet[] = [
        'fd_type' => Constants::FIELD_TEXTBOX,
        'fd_caption' => \_MA_WGEVENTS_FIELD_TEXTBOX,
        'fd_desc' => '',
        'fd_values' => '',
        'fd_placeholder' => '',
        'fd_required' => 0,
        'fd_default' => 0,
        'fd_print' => 1,
        'fd_display_values' => 0,
        'fd_display_placeholder' => 1,
        'fd_custom' => 0,
    ];
    $defaultSet[] = [
        'fd_type' => Constants::FIELD_TEXTAREA,
        'fd_caption' => \_MA_WGEVENTS_FIELD_TEXTAREA,
        'fd_desc' => '',
        'fd_values' => '',
        'fd_placeholder' => '',
        'fd_required' => 0,
        'fd_default' => 0,
        'fd_print' => 1,
        'fd_display_values' => 0,
        'fd_display_placeholder' => 1,
        'fd_custom' => 0,
    ];
    $defaultSet[] = [
        'fd_type' => Constants::FIELD_SELECTBOX,
        'fd_caption' => \_MA_WGEVENTS_FIELD_SELECTBOX,
        'fd_desc' => '',
        'fd_values' => '',
        'fd_placeholder' => '',
        'fd_required' => 0,
        'fd_default' => 0,
        'fd_print' => 1,
        'fd_display_values' => 1,
        'fd_display_placeholder' => 0,
        'fd_custom' => 0,
    ];
    $defaultSet[] = [
        'fd_type' => Constants::FIELD_COMBOBOX,
        'fd_caption' => \_MA_WGEVENTS_FIELD_COMBOBOX,
        'fd_desc' => '',
        'fd_values' => '',
        'fd_placeholder' => '',
        'fd_required' => 0,
        'fd_default' => 0,
        'fd_print' => 1,
        'fd_display_values' => 1,
        'fd_display_placeholder' => 0,
        'fd_custom' => 0,
    ];
    $defaultSet[] = [
        'fd_type' => Constants::FIELD_CHECKBOX,
        'fd_caption' => \_MA_WGEVENTS_FIELD_CHECKBOX,
        'fd_desc' => '',
        'fd_values' => '',
        'fd_placeholder' => '',
        'fd_required' => 0,
        'fd_default' => 0,
        'fd_print' => 1,
        'fd_display_values' => 1,
        'fd_display_placeholder' => 0,
        'fd_custom' => 0,
    ];
    $defaultSet[] = [
        'fd_type' => Constants::FIELD_RADIO,
        'fd_caption' => \_MA_WGEVENTS_FIELD_RADIO,
        'fd_desc' => '',
        'fd_values' => '',
        'fd_placeholder' => '',
        'fd_required' => 0,
        'fd_default' => 0,
        'fd_print' => 1,
        'fd_display_values' => 1,
        'fd_display_placeholder' => 0,
        'fd_custom' => 0,
    ];
    $defaultSet[] = [
        'fd_type' => Constants::FIELD_RADIOYN,
        'fd_caption' => \_MA_WGEVENTS_FIELD_RADIOYN,
        'fd_desc' => '',
        'fd_values' => '',
        'fd_placeholder' => '',
        'fd_required' => 0,
        'fd_default' => 0,
        'fd_print' => 1,
        'fd_display_values' => 0,
        'fd_display_placeholder' => 0,
        'fd_custom' => 0,
    ];
    $defaultSet[] = [
        'fd_type' => Constants::FIELD_DATE,
        'fd_caption' => \_MA_WGEVENTS_FIELD_DATE,
        'fd_desc' => '',
        'fd_values' => '',
        'fd_placeholder' => '',
        'fd_required' => 0,
        'fd_default' => 0,
        'fd_print' => 1,
        'fd_display_values' => 0,
        'fd_display_placeholder' => 0,
        'fd_custom' => 0,
    ];
    /*
    $defaultSet[] = [
        'fd_type' => Constants::FIELD_DATETIME,
        'fd_caption' => \_MA_WGEVENTS_FIELD_DATETIME,
        'fd_desc' => '',
        'fd_values' => '',
        'fd_required' => 0,
        'fd_default' => 0,
        'fd_print' => 1,
        'fd_display_values' => 0,
        'fd_display_placeholder' => 0,
        'fd_custom' => 0,
    ];
    */
    $defaultSet[] = [
        'fd_type' => Constants::FIELD_TEXTBOX,
        'fd_caption' => \_AM_WGEVENTS_FIELD_PHONE,
        'fd_desc' => '',
        'fd_values' => '',
        'fd_placeholder' => \_AM_WGEVENTS_FIELD_PHONE_VALUE,
        'fd_required' => 0,
        'fd_default' => 1,
        'fd_print' => 1,
        'fd_display_values' => 0,
        'fd_display_placeholder' => 1,
        'fd_custom' => 1,
    ];
    $defaultSet[] = [
        'fd_type' => Constants::FIELD_TEXTBOX,
        'fd_caption' => \_AM_WGEVENTS_FIELD_ADDRESS,
        'fd_desc' => '',
        'fd_values' => '',
        'fd_placeholder' => \_AM_WGEVENTS_FIELD_ADDRESS_VALUE,
        'fd_required' => 0,
        'fd_default' => 0,
        'fd_print' => 1,
        'fd_display_values' => 0,
        'fd_display_placeholder' => 1,
        'fd_custom' => 1,
    ];
    $defaultSet[] = [
        'fd_type' => Constants::FIELD_TEXTBOX,
        'fd_caption' => \_AM_WGEVENTS_FIELD_POSTAL,
        'fd_desc' => '',
        'fd_values' => '',
        'fd_placeholder' => \_AM_WGEVENTS_FIELD_POSTAL_VALUE,
        'fd_required' => 0,
        'fd_default' => 0,
        'fd_print' => 1,
        'fd_display_values' => 0,
        'fd_display_placeholder' => 1,
        'fd_custom' => 1,
    ];
    $defaultSet[] = [
        'fd_type' => Constants::FIELD_TEXTBOX,
        'fd_caption' => \_AM_WGEVENTS_FIELD_CITY,
        'fd_desc' => '',
        'fd_values' => '',
        'fd_placeholder' => \_AM_WGEVENTS_FIELD_CITY_VALUE,
        'fd_required' => 0,
        'fd_default' => 0,
        'fd_print' => 1,
        'fd_display_values' => 0,
        'fd_display_placeholder' => 1,
        'fd_custom' => 1,
    ];
    $defaultSet[] = [
        'fd_type' => Constants::FIELD_COUNTRY,
        'fd_caption' => \_AM_WGEVENTS_FIELD_COUNTRY,
        'fd_desc' => '',
        'fd_values' => '',
        'fd_placeholder' => \_AM_WGEVENTS_FIELD_COUNTRY_VALUE,
        'fd_required' => 0,
        'fd_default' => 0,
        'fd_print' => 1,
        'fd_display_values' => 0,
        'fd_display_placeholder' => 0,
        'fd_custom' => 1,
    ];
    $defaultSet[] = [
        'fd_type' => Constants::FIELD_DATE,
        'fd_caption' => \_AM_WGEVENTS_FIELD_BIRTHDAY,
        'fd_desc' => '',
        'fd_values' => '',
        'fd_placeholder' => \_AM_WGEVENTS_FIELD_BIRTHDAY_VALUE,
        'fd_required' => 0,
        'fd_default' => 0,
        'fd_print' => 1,
        'fd_display_values' => 0,
        'fd_display_placeholder' => 0,
        'fd_custom' => 1,
    ];
    $defaultSet[] = [
        'fd_type' => Constants::FIELD_TEXTBOX,
        'fd_caption' => \_AM_WGEVENTS_FIELD_AGE,
        'fd_desc' => '',
        'fd_values' => '',
        'fd_placeholder' => \_AM_WGEVENTS_FIELD_AGE_VALUE,
        'fd_required' => 0,
        'fd_default' => 0,
        'fd_print' => 1,
        'fd_display_values' => 0,
        'fd_display_placeholder' => 1,
        'fd_custom' => 1,
    ];

    return $defaultSet;
}
