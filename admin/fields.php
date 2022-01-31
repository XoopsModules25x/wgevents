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
$atId  = Request::getInt('id');
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
        $GLOBALS['xoopsTpl']->assign('fields_count', $fieldsCount);
        $GLOBALS['xoopsTpl']->assign('wgevents_url', \WGEVENTS_URL);
        $GLOBALS['xoopsTpl']->assign('wgevents_upload_url', \WGEVENTS_UPLOAD_URL);
        $GLOBALS['xoopsTpl']->assign('wgevents_icons_url_16', \WGEVENTS_ICONS_URL_16);
        // Table view fields
        if ($fieldsCount > 0) {
            $fieldsAll = $fieldsHandler->getAllFields($start, $limit);
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
        $atIdSource = Request::getInt('id_source');
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
        $fdType = Request::getInt('type');
        $fieldsObj->setVar('type', $fdType);
        $fieldsObj->setVar('caption', Request::getString('caption'));
        $fieldsObj->setVar('desc', Request::getText('desc'));
        $atValuesText = '';
        $fdValues = Request::getString('values');
        if ('' != $fdValues) {
            if (Constants::FIELD_COMBOBOX == $fdType || Constants::FIELD_SELECTBOX == $fdType || Constants::FIELD_RADIO == $fdType) {
                $atValuesText = \serialize(\preg_split('/\r\n|\r|\n/', $fdValues));
            } else {
                $tmpArr = [$fdValues];
                $atValuesText = \serialize($tmpArr);
            }
        }
        $fieldsObj->setVar('values', $atValuesText);
        $fieldsObj->setVar('placeholder', Request::getString('placeholder'));
        $fieldsObj->setVar('required', Request::getInt('required'));
        $fieldsObj->setVar('default', Request::getInt('default'));
        $fieldsObj->setVar('custom', Request::getInt('custom'));
        $fieldsObj->setVar('print', Request::getInt('print'));
        $fieldsObj->setVar('display_values', Request::getInt('display_values'));
        $fieldsObj->setVar('display_placeholder', Request::getInt('display_placeholder'));
        $fieldsObj->setVar('weight', Request::getInt('weight'));
        $fieldsObj->setVar('status', Request::getInt('status'));
        $fieldDatecreatedObj = \DateTime::createFromFormat(\_SHORTDATESTRING, Request::getString('datecreated'));
        $fieldsObj->setVar('datecreated', $fieldDatecreatedObj->getTimestamp());
        $fieldsObj->setVar('submitter', Request::getInt('submitter'));
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
        $atName = $fieldsObj->getVar('caption');
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
                ['ok' => 1, 'id' => $atId, 'start' => $start, 'limit' => $limit, 'op' => 'delete'],
                $_SERVER['REQUEST_URI'],
                \sprintf(\_MA_WGEVENTS_FORM_SURE_DELETE, $fieldsObj->getVar('caption')));
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
                $fieldsObj->setVar('id', $key + 1);
                $fieldsObj->setVar('caption', $item['caption']);
                $fieldsObj->setVar('type', $item['type']);
                $fieldsObj->setVar('desc', $item['desc']);
                $fdValues = (string)$item['values'];
                $atValuesText = '';
                if ('' != $fdValues) {
                    $tmpArr[] = $fdValues;
                    $atValuesText = \serialize($tmpArr);
                }
                $fieldsObj->setVar('values', $atValuesText);
                $fieldsObj->setVar('placeholder', $item['placeholder']);
                $fieldsObj->setVar('required', $item['required']);
                $fieldsObj->setVar('default', $item['default']);
                $fieldsObj->setVar('print', $item['print']);
                $fieldsObj->setVar('display_values', $item['display_values']);
                $fieldsObj->setVar('display_placeholder', $item['display_placeholder']);
                $fieldsObj->setVar('weight', $key + 1);
                $fieldsObj->setVar('custom', $item['custom']);
                $fieldsObj->setVar('status', Constants::STATUS_ONLINE);
                $fieldsObj->setVar('datecreated', \time());
                $fieldsObj->setVar('submitter', $uid);
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
        'type' => Constants::FIELD_LABEL,
        'caption' => \_MA_WGEVENTS_FIELD_LABEL,
        'desc' => '',
        'values' => '',
        'placeholder' => '',
        'required' => 0,
        'default' => 0,
        'print' => 1,
        'display_values' => 0,
        'display_placeholder' => 0,
        'custom' => 0,
    ];
    $defaultSet[] = [
        'type' => Constants::FIELD_TEXTBOX,
        'caption' => \_MA_WGEVENTS_FIELD_TEXTBOX,
        'desc' => '',
        'values' => '',
        'placeholder' => '',
        'required' => 0,
        'default' => 0,
        'print' => 1,
        'display_values' => 0,
        'display_placeholder' => 1,
        'custom' => 0,
    ];
    $defaultSet[] = [
        'type' => Constants::FIELD_TEXTAREA,
        'caption' => \_MA_WGEVENTS_FIELD_TEXTAREA,
        'desc' => '',
        'values' => '',
        'placeholder' => '',
        'required' => 0,
        'default' => 0,
        'print' => 1,
        'display_values' => 0,
        'display_placeholder' => 1,
        'custom' => 0,
    ];
    $defaultSet[] = [
        'type' => Constants::FIELD_SELECTBOX,
        'caption' => \_MA_WGEVENTS_FIELD_SELECTBOX,
        'desc' => '',
        'values' => '',
        'placeholder' => '',
        'required' => 0,
        'default' => 0,
        'print' => 1,
        'display_values' => 1,
        'display_placeholder' => 0,
        'custom' => 0,
    ];
    $defaultSet[] = [
        'type' => Constants::FIELD_COMBOBOX,
        'caption' => \_MA_WGEVENTS_FIELD_COMBOBOX,
        'desc' => '',
        'values' => '',
        'placeholder' => '',
        'required' => 0,
        'default' => 0,
        'print' => 1,
        'display_values' => 1,
        'display_placeholder' => 0,
        'custom' => 0,
    ];
    $defaultSet[] = [
        'type' => Constants::FIELD_CHECKBOX,
        'caption' => \_MA_WGEVENTS_FIELD_CHECKBOX,
        'desc' => '',
        'values' => '',
        'placeholder' => '',
        'required' => 0,
        'default' => 0,
        'print' => 1,
        'display_values' => 1,
        'display_placeholder' => 0,
        'custom' => 0,
    ];
    $defaultSet[] = [
        'type' => Constants::FIELD_RADIO,
        'caption' => \_MA_WGEVENTS_FIELD_RADIO,
        'desc' => '',
        'values' => '',
        'placeholder' => '',
        'required' => 0,
        'default' => 0,
        'print' => 1,
        'display_values' => 1,
        'display_placeholder' => 0,
        'custom' => 0,
    ];
    $defaultSet[] = [
        'type' => Constants::FIELD_RADIOYN,
        'caption' => \_MA_WGEVENTS_FIELD_RADIOYN,
        'desc' => '',
        'values' => '',
        'placeholder' => '',
        'required' => 0,
        'default' => 0,
        'print' => 1,
        'display_values' => 0,
        'display_placeholder' => 0,
        'custom' => 0,
    ];
    $defaultSet[] = [
        'type' => Constants::FIELD_DATE,
        'caption' => \_MA_WGEVENTS_FIELD_DATE,
        'desc' => '',
        'values' => '',
        'placeholder' => '',
        'required' => 0,
        'default' => 0,
        'print' => 1,
        'display_values' => 0,
        'display_placeholder' => 0,
        'custom' => 0,
    ];
    /*
    $defaultSet[] = [
        'type' => Constants::FIELD_DATETIME,
        'caption' => \_MA_WGEVENTS_FIELD_DATETIME,
        'desc' => '',
        'values' => '',
        'required' => 0,
        'default' => 0,
        'print' => 1,
        'display_values' => 0,
        'display_placeholder' => 0,
        'custom' => 0,
    ];
    */
    $defaultSet[] = [
        'type' => Constants::FIELD_TEXTBOX,
        'caption' => \_AM_WGEVENTS_FIELD_PHONE,
        'desc' => '',
        'values' => '',
        'placeholder' => \_AM_WGEVENTS_FIELD_PHONE_VALUE,
        'required' => 0,
        'default' => 1,
        'print' => 1,
        'display_values' => 0,
        'display_placeholder' => 1,
        'custom' => 1,
    ];
    $defaultSet[] = [
        'type' => Constants::FIELD_TEXTBOX,
        'caption' => \_AM_WGEVENTS_FIELD_ADDRESS,
        'desc' => '',
        'values' => '',
        'placeholder' => \_AM_WGEVENTS_FIELD_ADDRESS_VALUE,
        'required' => 0,
        'default' => 0,
        'print' => 1,
        'display_values' => 0,
        'display_placeholder' => 1,
        'custom' => 1,
    ];
    $defaultSet[] = [
        'type' => Constants::FIELD_TEXTBOX,
        'caption' => \_AM_WGEVENTS_FIELD_POSTAL,
        'desc' => '',
        'values' => '',
        'placeholder' => \_AM_WGEVENTS_FIELD_POSTAL_VALUE,
        'required' => 0,
        'default' => 0,
        'print' => 1,
        'display_values' => 0,
        'display_placeholder' => 1,
        'custom' => 1,
    ];
    $defaultSet[] = [
        'type' => Constants::FIELD_TEXTBOX,
        'caption' => \_AM_WGEVENTS_FIELD_CITY,
        'desc' => '',
        'values' => '',
        'placeholder' => \_AM_WGEVENTS_FIELD_CITY_VALUE,
        'required' => 0,
        'default' => 0,
        'print' => 1,
        'display_values' => 0,
        'display_placeholder' => 1,
        'custom' => 1,
    ];
    $defaultSet[] = [
        'type' => Constants::FIELD_COUNTRY,
        'caption' => \_AM_WGEVENTS_FIELD_COUNTRY,
        'desc' => '',
        'values' => '',
        'placeholder' => \_AM_WGEVENTS_FIELD_COUNTRY_VALUE,
        'required' => 0,
        'default' => 0,
        'print' => 1,
        'display_values' => 0,
        'display_placeholder' => 0,
        'custom' => 1,
    ];
    $defaultSet[] = [
        'type' => Constants::FIELD_DATE,
        'caption' => \_AM_WGEVENTS_FIELD_BIRTHDAY,
        'desc' => '',
        'values' => '',
        'placeholder' => \_AM_WGEVENTS_FIELD_BIRTHDAY_VALUE,
        'required' => 0,
        'default' => 0,
        'print' => 1,
        'display_values' => 0,
        'display_placeholder' => 0,
        'custom' => 1,
    ];
    $defaultSet[] = [
        'type' => Constants::FIELD_TEXTBOX,
        'caption' => \_AM_WGEVENTS_FIELD_AGE,
        'desc' => '',
        'values' => '',
        'placeholder' => \_AM_WGEVENTS_FIELD_AGE_VALUE,
        'required' => 0,
        'default' => 0,
        'print' => 1,
        'display_values' => 0,
        'display_placeholder' => 1,
        'custom' => 1,
    ];

    return $defaultSet;
}
