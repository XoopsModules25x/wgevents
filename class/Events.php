<?php declare(strict_types=1);

namespace XoopsModules\Wgevents;

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

use XoopsModules\Wgevents;
use XoopsModules\Wgevents\ {
    Helper,
    Utility,
    Forms
};


\defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class Object Events
 */
class Events extends \XoopsObject
{
    /**
     * @var int
     */
    public $start = 0;

    /**
     * @var int
     */
    public $limit = 0;

    /**
     * Constructor
     *
     * @param null
     */
    public function __construct()
    {
        $this->initVar('id', \XOBJ_DTYPE_INT);
        $this->initVar('catid', \XOBJ_DTYPE_INT);
        $this->initVar('name', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('logo', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('desc', \XOBJ_DTYPE_OTHER);
        $this->initVar('datefrom', \XOBJ_DTYPE_INT);
        $this->initVar('dateto', \XOBJ_DTYPE_INT);
        $this->initVar('contact', \XOBJ_DTYPE_TXTAREA);
        $this->initVar('email', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('location', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('locgmlat', \XOBJ_DTYPE_FLOAT);
        $this->initVar('locgmlon', \XOBJ_DTYPE_FLOAT);
        $this->initVar('locgmzoom', \XOBJ_DTYPE_INT);
        $this->initVar('fee', \XOBJ_DTYPE_FLOAT);
        $this->initVar('register_use', \XOBJ_DTYPE_INT);
        $this->initVar('register_from', \XOBJ_DTYPE_INT);
        $this->initVar('register_to', \XOBJ_DTYPE_INT);
        $this->initVar('register_max', \XOBJ_DTYPE_INT);
        $this->initVar('register_listwait', \XOBJ_DTYPE_INT);
        $this->initVar('register_autoaccept', \XOBJ_DTYPE_INT);
        $this->initVar('register_notify', \XOBJ_DTYPE_TXTAREA);
        $this->initVar('register_sendermail', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('register_sendername', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('register_signature', \XOBJ_DTYPE_TXTAREA);
        $this->initVar('status', \XOBJ_DTYPE_INT);
        $this->initVar('galid', \XOBJ_DTYPE_INT);
        $this->initVar('datecreated', \XOBJ_DTYPE_INT);
        $this->initVar('submitter', \XOBJ_DTYPE_INT);
    }

    /**
     * @static function &getInstance
     *
     * @param null
     */
    public static function getInstance()
    {
        static $instance = false;
        if (!$instance) {
            $instance = new self();
        }
    }

    /**
     * The new inserted $Id
     * @return inserted id
     */
    public function getNewInsertedIdEvents()
    {
        return $GLOBALS['xoopsDB']->getInsertId();
    }

    /**
     * @public function getForm
     * @param bool $action
     * @return \XoopsThemeForm
     */
    public function getFormEvents($action = false)
    {
        $helper = Helper::getInstance();

        $categoriesHandler = $helper->getHandler('Categories');
        $permissionsHandler = $helper->getHandler('Permissions');

        $utility = new Utility();

        if (!$action) {
            $action = $_SERVER['REQUEST_URI'];
        }
        $isAdmin   = false;
        $userUid   = 0;
        $userEmail = '';
        $userName  = '';
        if (\is_object($GLOBALS['xoopsUser'])) {
            $isAdmin   = $GLOBALS['xoopsUser']->isAdmin($GLOBALS['xoopsModule']->mid());
            $userUid   = $GLOBALS['xoopsUser']->uid();
            $userEmail = $GLOBALS['xoopsUser']->email();
            $userName  = ('' != (string)$GLOBALS['xoopsUser']->name()) ? $GLOBALS['xoopsUser']->name() : $GLOBALS['xoopsUser']->uname();
        }

        $imgInfo = '<img class="wge-img-info" src="' . \WGEVENTS_ICONS_URL_24 . '/info.png" alt="img-info" title="%s">';

        // Title
        $title = $this->isNew() ? \sprintf(\_MA_WGEVENTS_EVENT_ADD) : \sprintf(\_MA_WGEVENTS_EVENT_EDIT);
        // Get Theme Form
        \xoops_load('XoopsFormLoader');
        $form = new \XoopsThemeForm($title, 'form', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        // Form Table categories
        $evCatidSelect = new \XoopsFormSelect(\_MA_WGEVENTS_EVENT_CATID, 'catid', $this->getVar('catid'));
        $evCatidSelect->addOptionArray($categoriesHandler->getList());
        $form->addElement($evCatidSelect);
        // Form Text evName
        $form->addElement(new \XoopsFormText(\_MA_WGEVENTS_EVENT_NAME, 'name', 50, 255, $this->getVar('name')), true);
        // Form Editor DhtmlTextArea evDesc
        $editorConfigs = [];
        if ($isAdmin) {
            $editor = $helper->getConfig('editor_admin');
        } else {
            $editor = $helper->getConfig('editor_user');
        }
        $editorConfigs['name'] = 'desc';
        $editorConfigs['value'] = $this->getVar('desc', 'e');
        $editorConfigs['rows'] = 5;
        $editorConfigs['cols'] = 40;
        $editorConfigs['width'] = '100%';
        $editorConfigs['height'] = '400px';
        $editorConfigs['editor'] = $editor;
        $form->addElement(new \XoopsFormEditor(\_MA_WGEVENTS_EVENT_DESC, 'desc', $editorConfigs));
        // Form Image evLogo
        // Form Image evLogo: Select Uploaded Image
        $getEvLogo = $this->getVar('logo');
        $evLogo = $getEvLogo ?: 'blank.gif';
        $imageDirectory = '/uploads/wgevents/events/logos/' . $userUid;
        $folderUid = \XOOPS_ROOT_PATH . $imageDirectory;
        if (!\file_exists($folderUid)) {
            $utility::createFolder($folderUid);
            \chmod($folderUid, 0777);
            $file = \WGEVENTS_PATH . '/assets/images/blank.gif';
            $dest = $folderUid . '/blank.gif';
            $utility::copyFile($file, $dest);
        }
        $imageTray = new Forms\FormElementTray(\_MA_WGEVENTS_EVENT_LOGO, '<br>');
        $imageSelect = new \XoopsFormSelect(\_MA_WGEVENTS_EVENT_LOGO_UPLOADS, 'logo', $evLogo, 5);
        $imageArray = \XoopsLists::getImgListAsArray(\XOOPS_ROOT_PATH . $imageDirectory);
        foreach ($imageArray as $image1) {
            $imageSelect->addOption(($image1), $image1);
        }
        $imageSelect->setExtra("onchange='showImgSelected(\"imglabel_logo\", \"logo\", \"" . $imageDirectory . '", "", "' . \XOOPS_URL . "\")'");
        $imageTray->addElement($imageSelect, false);
        $imageTray->addElement(new \XoopsFormLabel('', "<br><img src='" . \XOOPS_URL . '/' . $imageDirectory . '/' . $evLogo . "' id='imglabel_logo' alt='' style='max-width:100px' >"));
        // Form Image evLogo: Upload new image
        $maxsize = $helper->getConfig('maxsize_image');
        $imageTray->addElement(new \XoopsFormFile('<br>' . \_AM_WGEVENTS_FORM_UPLOAD_NEW, 'logo', $maxsize));
        $imageTray->addElement(new \XoopsFormLabel(\_AM_WGEVENTS_FORM_UPLOAD_SIZE, ($maxsize / 1048576) . ' ' . \_AM_WGEVENTS_FORM_UPLOAD_SIZE_MB));
        $imageTray->addElement(new \XoopsFormLabel(\_AM_WGEVENTS_FORM_UPLOAD_IMG_WIDTH, $helper->getConfig('maxwidth_image') . ' px'));
        $imageTray->addElement(new \XoopsFormLabel(\_AM_WGEVENTS_FORM_UPLOAD_IMG_HEIGHT, $helper->getConfig('maxheight_image') . ' px'));
        $form->addElement($imageTray);
        // Form Text Date Select evDatefrom
        $evDatefrom = ($this->isNew() && 0 == (int)$this->getVar('datefrom')) ? \time() : $this->getVar('datefrom');
        $form->addElement(new \XoopsFormDateTime(\_MA_WGEVENTS_EVENT_DATEFROM, 'datefrom', '', $evDatefrom), true);
        // Form Text Date Select evDateto
        $evDateto = ($this->isNew() && 0 == (int)$this->getVar('dateto')) ? \time() : $this->getVar('dateto');
        $form->addElement(new \XoopsFormDateTime(\_MA_WGEVENTS_EVENT_DATETO, 'dateto', '', $evDateto));
        // Form Text evContact
        $form->addElement(new \XoopsFormTextArea(\_MA_WGEVENTS_EVENT_CONTACT, 'contact', $this->getVar('contact', 'e'), 4, 30));
        // Form Text evEmail
        $form->addElement(new \XoopsFormText(\_MA_WGEVENTS_EVENT_EMAIL, 'email', 50, 255, $this->getVar('email')));
        // Form Text evLocation
        $evLocationTray = new \XoopsFormElementTray(\_MA_WGEVENTS_EVENT_LOCATION, '<br>');
        $evLocationTray->addElement(new \XoopsFormText('', 'location', 50, 255, $this->getVar('location')));
        if ($helper->getConfig('use_gm')) {
            $evLocationGmTray = new \XoopsFormElementTray('', '&nbsp;');
            // Form Text evLocgmlat
            $evLocgmlat = $this->isNew() ? '0' : $this->getVar('locgmlat');
            $evLocationGmTray->addElement(new \XoopsFormText(\_MA_WGEVENTS_EVENT_LOCGMLAT, 'locgmlat', 15, 150, $evLocgmlat));
            // Form Text evLocgmlon
            $evLocgmlon = $this->isNew() ? '0' : $this->getVar('locgmlon');
            $evLocationGmTray->addElement(new \XoopsFormText(\_MA_WGEVENTS_EVENT_LOCGMLON, 'locgmlon', 15, 150, $evLocgmlon));
            // Form Text evLocgmzoom
            $evLocationGmTray->addElement(new \XoopsFormText(\_MA_WGEVENTS_EVENT_LOCGMZOOM, 'locgmzoom', 5, 255, $this->getVar('locgmzoom')));
            $evLocationTray->addElement($evLocationGmTray);
        }
        $form->addElement($evLocationTray);
        // Form Text evFee
        $default0 = '0' . $helper->getConfig('sep_comma') . '00';
        $evFee = $this->isNew() ? $default0 : Utility::FloatToString($this->getVar('fee'));
        $form->addElement(new \XoopsFormText(\_MA_WGEVENTS_EVENT_FEE, 'fee', 20, 150, $evFee));
        // Start block registration options
        if ($helper->getConfig('use_register')) {
            // Form Radio Yes/No evRegister_use
            $evRegister_use = $this->isNew() ? 0 : $this->getVar('register_use');
            $evRegisterUseRadio = new \XoopsFormRadioYN(\_MA_WGEVENTS_EVENT_REGISTER_USE, 'register_use', $evRegister_use);
            $evRegisterUseRadio->setExtra(" onclick='toogleRegistrationOpts()' ");
            $form->addElement($evRegisterUseRadio);
            $evReservUseTray = new \XoopsFormElementTray('', '<br>'); //double element tray is necessary for proper toogle
            $evRegisterOptsTray = new Forms\FormElementTray('', '<br>', 'registeropttray');
            $evRegisterOptsTray->setClass('col-xs-12 col-sm-5 col-lg-5');
            if (!$evRegister_use) {
                $evRegisterOptsTray->setHidden();
            }
            // Form Text Date Select evRegisterfrom
            $evRegisterfrom = $this->isNew() ? \time() : $this->getVar('register_from');
            $evRegisterOptsTray->addElement(new \XoopsFormDateTime(\_MA_WGEVENTS_EVENT_REGISTER_FROM, 'register_from', '', $evRegisterfrom));
            // Form Text Date Select evRegisterto
            $evRegisterto = $this->isNew() ? \time() : $this->getVar('register_to');
            $evRegisterOptsTray->addElement(new \XoopsFormDateTime(\_MA_WGEVENTS_EVENT_REGISTER_TO, 'register_to', '', $evRegisterto));
            // Form Text evRegisterMax
            $evRegisterMax = $this->isNew() ? 0 : $this->getVar('register_max');
            $captionRegisterMax = \_MA_WGEVENTS_EVENT_REGISTER_MAX . \sprintf($imgInfo, \_MA_WGEVENTS_EVENT_REGISTER_MAX_DESC);
            $evRegisterOptsTray->addElement(new \XoopsFormText($captionRegisterMax, 'register_max', 5, 50, $evRegisterMax));
            // Form Radio Yes/No evRegisterListwait
            $evRegisterListwait = $this->isNew() ? 0 : $this->getVar('register_listwait');
            $captionRegisterListwait = \_MA_WGEVENTS_EVENT_REGISTER_LISTWAIT . \sprintf($imgInfo, \_MA_WGEVENTS_EVENT_REGISTER_LISTWAIT_DESC);
            $evRegisterOptsTray->addElement(new \XoopsFormRadioYN($captionRegisterListwait, 'register_listwait', $evRegisterListwait));
            // Form Select User evRegisterAutoaccept
            $evRegisterAutoaccept = $this->isNew() ? 0 : $this->getVar('register_autoaccept');
            $captionRegisterAutoaccept = \_MA_WGEVENTS_EVENT_REGISTER_AUTOACCEPT . \sprintf($imgInfo, \_MA_WGEVENTS_EVENT_REGISTER_AUTOACCEPT_DESC);
            $evRegisterOptsTray->addElement(new \XoopsFormRadioYN($captionRegisterAutoaccept, 'register_autoaccept', $evRegisterAutoaccept));
            // Form Editor TextArea evRegisterNotify
            $evRegisterNotify = $this->isNew() ? $userEmail : $this->getVar('register_notify', 'e');
            $captionRegisterNotify = \_MA_WGEVENTS_EVENT_REGISTER_NOTIFY . \sprintf($imgInfo, \_MA_WGEVENTS_EVENT_REGISTER_NOTIFY_DESC);
            $evRegisterOptsTray->addElement(new \XoopsFormTextArea($captionRegisterNotify, 'register_notify', $evRegisterNotify, 4, 30));
            // Form Text evRegisterSendermail
            $evRegisterSendermail = $this->isNew() ? $userEmail : $this->getVar('register_sendermail');
            $captionRegisterSendermail = \_MA_WGEVENTS_EVENT_REGISTER_SENDERMAIL . \sprintf($imgInfo, \_MA_WGEVENTS_EVENT_REGISTER_SENDERMAIL_DESC);
            $evRegisterOptsTray->addElement(new \XoopsFormText($captionRegisterSendermail, 'register_sendermail', 35, 255, $evRegisterSendermail));
            // Form Text evRegisterSendername
            $evRegisterSendername = $this->isNew() ? $userName : $this->getVar('register_sendername');
            $captionRegisterSendername = \_MA_WGEVENTS_EVENT_REGISTER_SENDERNAME . \sprintf($imgInfo, \_MA_WGEVENTS_EVENT_REGISTER_SENDERNAME_DESC);
            $evRegisterOptsTray->addElement(new \XoopsFormText($captionRegisterSendername, 'register_sendername', 35, 255, $evRegisterSendername));
            // Form Editor TextArea evRegisterSignature
            $evRegisterSignature = $this->isNew() ? '' : $this->getVar('register_signature', 'e');
            $captionRegisterSignature = \_MA_WGEVENTS_EVENT_REGISTER_SIGNATURE . \sprintf($imgInfo, \_MA_WGEVENTS_EVENT_REGISTER_SIGNATURE_DESC);
            $evRegisterOptsTray->addElement(new \XoopsFormTextArea($captionRegisterSignature, 'register_signature', $evRegisterSignature, 4, 30));

            $evReservUseTray->addElement($evRegisterOptsTray);
            $form->addElement($evReservUseTray);
            //$form->addElement(new \XoopsFormLabel('', $evReservUseTray));
            // End block registration options
        }
        // Form Select evGalid
        if ($helper->getConfig('use_wggallery')) {
            /*TODO */
            /*
            $evGalidSelect = new \XoopsFormSelect(\_MA_WGEVENTS_EVENT_GALID, 'galid', $this->getVar('galid'));
            $evGalidSelect->addOption('Empty');
            $evGalidSelect->addOptionArray($albumHandler->getList());
            $form->addElement($evGalidSelect);
            */
        }
        // Form Select Status evStatus
        // Form Text Date Select evDatecreated
        // Form Select User evSubmitter
        $permEventsApprove = $permissionsHandler->getPermEventsApprove();
        $permEventsApproveAuto = $permissionsHandler->getPermEventsApproveAuto();
        if ($this->isNew()) {
            $evStatus = $permEventsApproveAuto ? Constants::STATUS_APPROVED : Constants::STATUS_SUBMITTED;
        } else {
            $evStatus = $this->getVar('status');
        }
        $evDatecreated = $this->isNew() ? \time() : $this->getVar('datecreated');
        $evSubmitter = $this->isNew() ? $userUid : $this->getVar('submitter');
        if ($isAdmin) {
            $evStatusSelect = new \XoopsFormSelect(\_MA_WGEVENTS_STATUS, 'status', $evStatus);
            $evStatusSelect->addOption(Constants::STATUS_NONE, \_MA_WGEVENTS_STATUS_NONE);
            $evStatusSelect->addOption(Constants::STATUS_OFFLINE, \_MA_WGEVENTS_STATUS_OFFLINE);
            $evStatusSelect->addOption(Constants::STATUS_SUBMITTED, \_MA_WGEVENTS_STATUS_SUBMITTED);
            $evStatusSelect->addOption(Constants::STATUS_APPROVED, \_MA_WGEVENTS_STATUS_APPROVED);
            $evStatusSelect->addOption(Constants::STATUS_LOCKED, \_MA_WGEVENTS_STATUS_LOCKED);
            $evStatusSelect->addOption(Constants::STATUS_CANCELED, \_MA_WGEVENTS_STATUS_CANCELED);
            $form->addElement($evStatusSelect, true);
            $form->addElement(new \XoopsFormTextDateSelect(\_MA_WGEVENTS_DATECREATED, 'datecreated', '', $evDatecreated));
            $form->addElement(new \XoopsFormSelectUser(\_MA_WGEVENTS_SUBMITTER, 'submitter', false, $evSubmitter));
        } else {
            $form->addElement(new \XoopsFormHidden('status', $evStatus));
            $form->addElement(new \XoopsFormHidden('datecreated_int', $evDatecreated));
            $form->addElement(new \XoopsFormHidden('submitter', $evSubmitter));
            if (!$this->isNew()) {
                if ($permEventsApprove) {
                    $evStatusSelect = new \XoopsFormSelect(\_MA_WGEVENTS_STATUS, 'status', $evStatus);
                    $evStatusSelect->addOption(Constants::STATUS_NONE, \_MA_WGEVENTS_STATUS_NONE);
                    $evStatusSelect->addOption(Constants::STATUS_OFFLINE, \_MA_WGEVENTS_STATUS_OFFLINE);
                    $evStatusSelect->addOption(Constants::STATUS_SUBMITTED, \_MA_WGEVENTS_STATUS_SUBMITTED);
                    $evStatusSelect->addOption(Constants::STATUS_APPROVED, \_MA_WGEVENTS_STATUS_APPROVED);
                    $form->addElement($evStatusSelect, true);
                } else {
                    $form->addElement(new \XoopsFormLabel(\_MA_WGEVENTS_STATUS, Utility::getStatusText($evStatus)));
                }
                $form->addElement(new \XoopsFormLabel(\_MA_WGEVENTS_DATECREATED, \formatTimestamp($evDatecreated, 's')));
                $form->addElement(new \XoopsFormLabel(\_MA_WGEVENTS_SUBMITTER, \XoopsUser::getUnameFromId($evSubmitter)));
            }
        }
        // To Save
        $form->addElement(new \XoopsFormHidden('op', 'save'));
        $form->addElement(new \XoopsFormHidden('start', $this->start));
        $form->addElement(new \XoopsFormHidden('limit', $this->limit));
        // button tray
        $buttonTray = new \XoopsFormElementTray('');
        $buttonBack = new Forms\FormButton('', 'confirm_back', \_CANCEL, 'button');
        $buttonBack->setExtra('onclick="history.go(-1);return true;"');
        $buttonBack->setClass('btn-danger');
        $buttonTray->addElement($buttonBack);

        $buttonReset = new Forms\FormButton('', 'reset', \_RESET, 'reset');
        $buttonReset->setClass('btn-warning');
        $buttonTray->addElement($buttonReset);

        $buttonSubmit = new Forms\FormButton('', '_submit', \_MA_WGEVENTS_SAVE, 'submit');
        $buttonSubmit->setClass('btn-primary');
        $buttonTray->addElement($buttonSubmit);

        $buttonNext = new Forms\FormButton('', 'continue_questions', \_MA_WGEVENTS_CONTINUE_QUESTIONY, 'submit');
        $buttonNext->setClass('btn-primary');
        if (!$evRegister_use) {
            $buttonNext->setHidden();
        }
        $buttonTray->addElement($buttonNext);
        $form->addElement($buttonTray);

        return $form;
    }

    /**
     * Get Values
     * @param null $keys
     * @param null $format
     * @param null $maxDepth
     * @return array
     */
    public function getValuesEvents($keys = null, $format = null, $maxDepth = null)
    {
        $helper  = \XoopsModules\Wgevents\Helper::getInstance();
        $utility = new \XoopsModules\Wgevents\Utility();
        $ret = $this->getValues($keys, $format, $maxDepth);
        $categoriesHandler = $helper->getHandler('Categories');
        $categoriesObj = $categoriesHandler->get($this->getVar('catid'));
        $adminMaxchar = $helper->getConfig('admin_maxchar');
        $userMaxchar = $helper->getConfig('user_maxchar');
        $catName = '';
        if (\is_object($categoriesObj)) {
            $catName = $categoriesObj->getVar('name');
        }
        $ret['catname']            = $catName;
        $ret['desc_text']          = $this->getVar('desc', 'e');
        $ret['desc_short_admin']   = $utility::truncateHtml($ret['desc_text'], $adminMaxchar);
        $ret['desc_short_user']    = $utility::truncateHtml($ret['desc_text'], $userMaxchar);
        $ret['datefrom_text']      = \formatTimestamp($this->getVar('datefrom'), 'm');
        $ret['dateto_text']        = \formatTimestamp($this->getVar('dateto'), 'm');
        $ret['fee_text']           = Utility::FloatToString($this->getVar('fee'));
        $ret['register_use_text']  = (int)$this->getVar('register_use') > 0 ? \_YES : \_NO;
        $ret['register_from_text'] = '';
        if ($this->getVar('register_from') > 0) {
            $ret['register_from_text'] = \formatTimestamp($this->getVar('register_from'), 'm');
        }
        $ret['register_to_text'] = '';
        if ($this->getVar('register_to') > 0) {
            $ret['register_to_text']       = \formatTimestamp($this->getVar('register_to'), 'm');
        }
        $regMax = $this->getVar('register_max');
        $ret['register_max_text']        = $regMax > 0 ? $this->getVar('register_max') : \_MA_WGEVENTS_EVENT_REGISTER_MAX_UNLIMITED;
        $ret['register_listwait_text']   = (int)$this->getVar('register_listwait') > 0 ? \_YES : \_NO;
        $ret['register_autoaccept_text'] = (int)$this->getVar('register_autoaccept') > 0 ? \_YES : \_NO;
        $evRegisterNotify                = $this->getVar('register_notify', 'e');
        $ret['register_notify_text']     = $evRegisterNotify;
        if ($evRegisterNotify) {
            $notifyEmails   = preg_split("/\r\n|\n|\r/", $evRegisterNotify);
            $ret['register_notify_user']  = \implode('<br>', $notifyEmails);
        }
        $ret['status_text']      = Utility::getStatusText($this->getVar('status'));
        $ret['datecreated_text'] = \formatTimestamp($this->getVar('datecreated'), 's');
        $ret['submitter_text']   = \XoopsUser::getUnameFromId($this->getVar('submitter'));
        return $ret;
    }

    /**
     * Returns an array representation of the object
     *
     * @return array
     */
    public function toArrayEvents()
    {
        $ret = [];
        $vars = $this->getVars();
        foreach (\array_keys($vars) as $var) {
            $ret[$var] = $this->getVar($var);
        }
        return $ret;
    }
}
