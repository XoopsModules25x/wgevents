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
        $this->initVar('ev_id', \XOBJ_DTYPE_INT);
        $this->initVar('ev_catid', \XOBJ_DTYPE_INT);
        $this->initVar('ev_name', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('ev_logo', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('ev_desc', \XOBJ_DTYPE_OTHER);
        $this->initVar('ev_datefrom', \XOBJ_DTYPE_INT);
        $this->initVar('ev_dateto', \XOBJ_DTYPE_INT);
        $this->initVar('ev_contact', \XOBJ_DTYPE_TXTAREA);
        $this->initVar('ev_email', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('ev_location', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('ev_locgmlat', \XOBJ_DTYPE_FLOAT);
        $this->initVar('ev_locgmlon', \XOBJ_DTYPE_FLOAT);
        $this->initVar('ev_locgmzoom', \XOBJ_DTYPE_INT);
        $this->initVar('ev_fee', \XOBJ_DTYPE_FLOAT);
        $this->initVar('ev_register_use', \XOBJ_DTYPE_INT);
        $this->initVar('ev_register_from', \XOBJ_DTYPE_INT);
        $this->initVar('ev_register_to', \XOBJ_DTYPE_INT);
        $this->initVar('ev_register_max', \XOBJ_DTYPE_INT);
        $this->initVar('ev_register_listwait', \XOBJ_DTYPE_INT);
        $this->initVar('ev_register_autoaccept', \XOBJ_DTYPE_INT);
        $this->initVar('ev_register_notify', \XOBJ_DTYPE_TXTAREA);
        $this->initVar('ev_register_sendermail', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('ev_register_sendername', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('ev_register_signature', \XOBJ_DTYPE_TXTAREA);
        $this->initVar('ev_status', \XOBJ_DTYPE_INT);
        $this->initVar('ev_galid', \XOBJ_DTYPE_INT);
        $this->initVar('ev_datecreated', \XOBJ_DTYPE_INT);
        $this->initVar('ev_submitter', \XOBJ_DTYPE_INT);
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
        $evCatidSelect = new \XoopsFormSelect(\_MA_WGEVENTS_EVENT_CATID, 'ev_catid', $this->getVar('ev_catid'));
        $evCatidSelect->addOptionArray($categoriesHandler->getList());
        $form->addElement($evCatidSelect);
        // Form Text evName
        $form->addElement(new \XoopsFormText(\_MA_WGEVENTS_EVENT_NAME, 'ev_name', 50, 255, $this->getVar('ev_name')), true);
        // Form Editor DhtmlTextArea evDesc
        $editorConfigs = [];
        if ($isAdmin) {
            $editor = $helper->getConfig('editor_admin');
        } else {
            $editor = $helper->getConfig('editor_user');
        }
        $editorConfigs['name'] = 'ev_desc';
        $editorConfigs['value'] = $this->getVar('ev_desc', 'e');
        $editorConfigs['rows'] = 5;
        $editorConfigs['cols'] = 40;
        $editorConfigs['width'] = '100%';
        $editorConfigs['height'] = '400px';
        $editorConfigs['editor'] = $editor;
        $form->addElement(new \XoopsFormEditor(\_MA_WGEVENTS_EVENT_DESC, 'ev_desc', $editorConfigs));
        // Form Image evLogo
        // Form Image evLogo: Select Uploaded Image
        $getEvLogo = $this->getVar('ev_logo');
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
        $imageSelect = new \XoopsFormSelect(\_MA_WGEVENTS_EVENT_LOGO_UPLOADS, 'ev_logo', $evLogo, 5);
        $imageArray = \XoopsLists::getImgListAsArray(\XOOPS_ROOT_PATH . $imageDirectory);
        foreach ($imageArray as $image1) {
            $imageSelect->addOption(($image1), $image1);
        }
        $imageSelect->setExtra("onchange='showImgSelected(\"imglabel_ev_logo\", \"ev_logo\", \"" . $imageDirectory . '", "", "' . \XOOPS_URL . "\")'");
        $imageTray->addElement($imageSelect, false);
        $imageTray->addElement(new \XoopsFormLabel('', "<br><img src='" . \XOOPS_URL . '/' . $imageDirectory . '/' . $evLogo . "' id='imglabel_ev_logo' alt='' style='max-width:100px' >"));
        // Form Image evLogo: Upload new image
        $maxsize = $helper->getConfig('maxsize_image');
        $imageTray->addElement(new \XoopsFormFile('<br>' . \_AM_WGEVENTS_FORM_UPLOAD_NEW, 'ev_logo', $maxsize));
        $imageTray->addElement(new \XoopsFormLabel(\_AM_WGEVENTS_FORM_UPLOAD_SIZE, ($maxsize / 1048576) . ' ' . \_AM_WGEVENTS_FORM_UPLOAD_SIZE_MB));
        $imageTray->addElement(new \XoopsFormLabel(\_AM_WGEVENTS_FORM_UPLOAD_IMG_WIDTH, $helper->getConfig('maxwidth_image') . ' px'));
        $imageTray->addElement(new \XoopsFormLabel(\_AM_WGEVENTS_FORM_UPLOAD_IMG_HEIGHT, $helper->getConfig('maxheight_image') . ' px'));
        $form->addElement($imageTray);
        // Form Text Date Select evDatefrom
        $evDatefrom = ($this->isNew() && 0 == (int)$this->getVar('ev_datefrom')) ? \time() : $this->getVar('ev_datefrom');
        $form->addElement(new \XoopsFormDateTime(\_MA_WGEVENTS_EVENT_DATEFROM, 'ev_datefrom', '', $evDatefrom), true);
        // Form Text Date Select evDateto
        $evDateto = ($this->isNew() && 0 == (int)$this->getVar('ev_dateto')) ? \time() : $this->getVar('ev_dateto');
        $form->addElement(new \XoopsFormDateTime(\_MA_WGEVENTS_EVENT_DATETO, 'ev_dateto', '', $evDateto));
        // Form Text evContact
        $form->addElement(new \XoopsFormTextArea(\_MA_WGEVENTS_EVENT_CONTACT, 'ev_contact', $this->getVar('ev_contact', 'e'), 4, 30));
        // Form Text evEmail
        $form->addElement(new \XoopsFormText(\_MA_WGEVENTS_EVENT_EMAIL, 'ev_email', 50, 255, $this->getVar('ev_email')));
        // Form Text evLocation
        $evLocationTray = new \XoopsFormElementTray(\_MA_WGEVENTS_EVENT_LOCATION, '<br>');
        $evLocationTray->addElement(new \XoopsFormText('', 'ev_location', 50, 255, $this->getVar('ev_location')));
        if ($helper->getConfig('use_gm')) {
            $evLocationGmTray = new \XoopsFormElementTray('', '&nbsp;');
            // Form Text evLocgmlat
            $evLocgmlat = $this->isNew() ? '0' : $this->getVar('ev_locgmlat');
            $evLocationGmTray->addElement(new \XoopsFormText(\_MA_WGEVENTS_EVENT_LOCGMLAT, 'ev_locgmlat', 15, 150, $evLocgmlat));
            // Form Text evLocgmlon
            $evLocgmlon = $this->isNew() ? '0' : $this->getVar('ev_locgmlon');
            $evLocationGmTray->addElement(new \XoopsFormText(\_MA_WGEVENTS_EVENT_LOCGMLON, 'ev_locgmlon', 15, 150, $evLocgmlon));
            // Form Text evLocgmzoom
            $evLocationGmTray->addElement(new \XoopsFormText(\_MA_WGEVENTS_EVENT_LOCGMZOOM, 'ev_locgmzoom', 5, 255, $this->getVar('ev_locgmzoom')));
            $evLocationTray->addElement($evLocationGmTray);
        }
        $form->addElement($evLocationTray);
        // Form Text evFee
        $default0 = '0' . $helper->getConfig('sep_comma') . '00';
        $evFee = $this->isNew() ? $default0 : Utility::FloatToString($this->getVar('ev_fee'));
        $form->addElement(new \XoopsFormText(\_MA_WGEVENTS_EVENT_FEE, 'ev_fee', 20, 150, $evFee));
        // Start block registration options
        if ($helper->getConfig('use_register')) {
            // Form Radio Yes/No evRegister_use
            $evRegister_use = $this->isNew() ? 0 : $this->getVar('ev_register_use');
            $evRegisterUseRadio = new \XoopsFormRadioYN(\_MA_WGEVENTS_EVENT_REGISTER_USE, 'ev_register_use', $evRegister_use);
            $evRegisterUseRadio->setExtra(" onclick='toogleRegistrationOpts()' ");
            $form->addElement($evRegisterUseRadio);
            $evReservUseTray = new \XoopsFormElementTray('', '<br>'); //double element tray is necessary for proper toogle
            $evRegisterOptsTray = new Forms\FormElementTray('', '<br>', 'registeropttray');
            $evRegisterOptsTray->setClass('col-xs-12 col-sm-5 col-lg-5');
            if (!$evRegister_use) {
                $evRegisterOptsTray->setHidden();
            }
            // Form Text Date Select evRegisterfrom
            $evRegisterfrom = $this->isNew() ? \time() : $this->getVar('ev_register_from');
            $evRegisterOptsTray->addElement(new \XoopsFormDateTime(\_MA_WGEVENTS_EVENT_REGISTER_FROM, 'ev_register_from', '', $evRegisterfrom));
            // Form Text Date Select evRegisterto
            $evRegisterto = $this->isNew() ? \time() : $this->getVar('ev_register_to');
            $evRegisterOptsTray->addElement(new \XoopsFormDateTime(\_MA_WGEVENTS_EVENT_REGISTER_TO, 'ev_register_to', '', $evRegisterto));
            // Form Text evRegisterMax
            $evRegisterMax = $this->isNew() ? 0 : $this->getVar('ev_register_max');
            $captionRegisterMax = \_MA_WGEVENTS_EVENT_REGISTER_MAX . \sprintf($imgInfo, \_MA_WGEVENTS_EVENT_REGISTER_MAX_DESC);
            $evRegisterOptsTray->addElement(new \XoopsFormText($captionRegisterMax, 'ev_register_max', 5, 50, $evRegisterMax));
            // Form Radio Yes/No evRegisterListwait
            $evRegisterListwait = $this->isNew() ? 0 : $this->getVar('ev_register_listwait');
            $captionRegisterListwait = \_MA_WGEVENTS_EVENT_REGISTER_LISTWAIT . \sprintf($imgInfo, \_MA_WGEVENTS_EVENT_REGISTER_LISTWAIT_DESC);
            $evRegisterOptsTray->addElement(new \XoopsFormRadioYN($captionRegisterListwait, 'ev_register_listwait', $evRegisterListwait));
            // Form Select User evRegisterAutoaccept
            $evRegisterAutoaccept = $this->isNew() ? 0 : $this->getVar('ev_register_autoaccept');
            $captionRegisterAutoaccept = \_MA_WGEVENTS_EVENT_REGISTER_AUTOACCEPT . \sprintf($imgInfo, \_MA_WGEVENTS_EVENT_REGISTER_AUTOACCEPT_DESC);
            $evRegisterOptsTray->addElement(new \XoopsFormRadioYN($captionRegisterAutoaccept, 'ev_register_autoaccept', $evRegisterAutoaccept));
            // Form Editor TextArea evRegisterNotify
            $evRegisterNotify = $this->isNew() ? $userEmail : $this->getVar('ev_register_notify', 'e');
            $captionRegisterNotify = \_MA_WGEVENTS_EVENT_REGISTER_NOTIFY . \sprintf($imgInfo, \_MA_WGEVENTS_EVENT_REGISTER_NOTIFY_DESC);
            $evRegisterOptsTray->addElement(new \XoopsFormTextArea($captionRegisterNotify, 'ev_register_notify', $evRegisterNotify, 4, 30));
            // Form Text evRegisterSendermail
            $evRegisterSendermail = $this->isNew() ? $userEmail : $this->getVar('ev_register_sendermail');
            $captionRegisterSendermail = \_MA_WGEVENTS_EVENT_REGISTER_SENDERMAIL . \sprintf($imgInfo, \_MA_WGEVENTS_EVENT_REGISTER_SENDERMAIL_DESC);
            $evRegisterOptsTray->addElement(new \XoopsFormText($captionRegisterSendermail, 'ev_register_sendermail', 35, 255, $evRegisterSendermail));
            // Form Text evRegisterSendername
            $evRegisterSendername = $this->isNew() ? $userName : $this->getVar('ev_register_sendername');
            $captionRegisterSendername = \_MA_WGEVENTS_EVENT_REGISTER_SENDERNAME . \sprintf($imgInfo, \_MA_WGEVENTS_EVENT_REGISTER_SENDERNAME_DESC);
            $evRegisterOptsTray->addElement(new \XoopsFormText($captionRegisterSendername, 'ev_register_sendername', 35, 255, $evRegisterSendername));
            // Form Editor TextArea evRegisterSignature
            $evRegisterSignature = $this->isNew() ? '' : $this->getVar('ev_register_signature', 'e');
            $captionRegisterSignature = \_MA_WGEVENTS_EVENT_REGISTER_SIGNATURE . \sprintf($imgInfo, \_MA_WGEVENTS_EVENT_REGISTER_SIGNATURE_DESC);
            $evRegisterOptsTray->addElement(new \XoopsFormTextArea($captionRegisterSignature, 'ev_register_signature', $evRegisterSignature, 4, 30));

            $evReservUseTray->addElement($evRegisterOptsTray);
            $form->addElement($evReservUseTray);
            //$form->addElement(new \XoopsFormLabel('', $evReservUseTray));
            // End block registration options
        }
        // Form Select evGalid
        if ($helper->getConfig('use_wggallery')) {
            /*TODO */
            /*
            $evGalidSelect = new \XoopsFormSelect(\_MA_WGEVENTS_EVENT_GALID, 'ev_galid', $this->getVar('ev_galid'));
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
            $evStatus = $this->getVar('ev_status');
        }
        $evDatecreated = $this->isNew() ? \time() : $this->getVar('ev_datecreated');
        $evSubmitter = $this->isNew() ? $userUid : $this->getVar('ev_submitter');
        if ($isAdmin) {
            $evStatusSelect = new \XoopsFormSelect(\_MA_WGEVENTS_STATUS, 'ev_status', $evStatus);
            $evStatusSelect->addOption(Constants::STATUS_NONE, \_MA_WGEVENTS_STATUS_NONE);
            $evStatusSelect->addOption(Constants::STATUS_OFFLINE, \_MA_WGEVENTS_STATUS_OFFLINE);
            $evStatusSelect->addOption(Constants::STATUS_SUBMITTED, \_MA_WGEVENTS_STATUS_SUBMITTED);
            $evStatusSelect->addOption(Constants::STATUS_APPROVED, \_MA_WGEVENTS_STATUS_APPROVED);
            $evStatusSelect->addOption(Constants::STATUS_LOCKED, \_MA_WGEVENTS_STATUS_LOCKED);
            $evStatusSelect->addOption(Constants::STATUS_CANCELED, \_MA_WGEVENTS_STATUS_CANCELED);
            $form->addElement($evStatusSelect, true);
            $form->addElement(new \XoopsFormTextDateSelect(\_MA_WGEVENTS_DATECREATED, 'ev_datecreated', '', $evDatecreated));
            $form->addElement(new \XoopsFormSelectUser(\_MA_WGEVENTS_SUBMITTER, 'ev_submitter', false, $evSubmitter));
        } else {
            $form->addElement(new \XoopsFormHidden('ev_status', $evStatus));
            $form->addElement(new \XoopsFormHidden('ev_datecreated_int', $evDatecreated));
            $form->addElement(new \XoopsFormHidden('ev_submitter', $evSubmitter));
            if (!$this->isNew()) {
                if ($permEventsApprove) {
                    $evStatusSelect = new \XoopsFormSelect(\_MA_WGEVENTS_STATUS, 'ev_status', $evStatus);
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

        $buttonNext = new Forms\FormButton('', 'continue_additionals', \_MA_WGEVENTS_CONTINUE_ADDITIONALY, 'submit');
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
        $categoriesObj = $categoriesHandler->get($this->getVar('ev_catid'));
        $adminMaxchar = $helper->getConfig('admin_maxchar');
        $userMaxchar = $helper->getConfig('user_maxchar');
        $ret['id']               = $this->getVar('ev_id');
        $ret['catid']            = $categoriesObj->getVar('cat_name');
        $ret['name']             = $this->getVar('ev_name');
        $ret['logo']             = $this->getVar('ev_logo');
        $ret['desc']             = $this->getVar('ev_desc', 'e');
        $ret['desc_short_admin'] = $utility::truncateHtml($ret['desc'], $adminMaxchar);
        $ret['desc_short_user']  = $utility::truncateHtml($ret['desc'], $userMaxchar);
        $ret['datefrom']         = \formatTimestamp($this->getVar('ev_datefrom'), 'm');
        $ret['dateto']           = \formatTimestamp($this->getVar('ev_dateto'), 'm');
        $ret['contact']          = $this->getVar('ev_contact');
        $ret['email']            = $this->getVar('ev_email');
        $ret['location']         = $this->getVar('ev_location');
        $ret['locgmlat']         = $this->getVar('ev_locgmlat');
        $ret['locgmlon']         = $this->getVar('ev_locgmlon');
        $ret['locgmzoom']        = $this->getVar('ev_locgmzoom');
        $ret['fee']               = $this->getVar('ev_fee');
        $ret['fee_text']         = Utility::FloatToString($this->getVar('ev_fee'));
        $ret['register_use']     = (int)$this->getVar('ev_register_use') > 0 ? \_YES : \_NO;
        $ret['register_from']    = '';
        if ($this->getVar('ev_register_from') > 0) {
            $ret['register_from'] = \formatTimestamp($this->getVar('ev_register_from'), 'm');
        }
        $ret['register_to'] = '';
        if ($this->getVar('ev_register_to') > 0) {
            $ret['register_to']       = \formatTimestamp($this->getVar('ev_register_to'), 'm');
        }
        $regMax = $this->getVar('ev_register_max');
        $ret['register_max']        = $regMax;
        $ret['register_max_text']   = $regMax > 0 ? $this->getVar('ev_register_max') : \_MA_WGEVENTS_EVENT_REGISTER_MAX_UNLIMITED;
        $ret['register_listwait']   = (int)$this->getVar('ev_register_listwait') > 0 ? \_YES : \_NO;
        $ret['register_autoaccept'] = (int)$this->getVar('ev_register_autoaccept') > 0 ? \_YES : \_NO;
        $evRegisterNotify           = $this->getVar('ev_register_notify', 'e');
        $ret['register_notify']     = $evRegisterNotify;
        if ($evRegisterNotify) {
            $notifyEmails   = preg_split("/\r\n|\n|\r/", $evRegisterNotify);
            $ret['register_notify_user']  = \implode('<br>', $notifyEmails);
        }
        $ret['register_sendermail'] = $this->getVar('ev_register_sendermail');
        $ret['register_sendername'] = $this->getVar('ev_register_sendername');
        $ret['register_signature']  = $this->getVar('ev_register_signature');
        $status                     = $this->getVar('ev_status');
        $ret['status']              = $status;
        $ret['status_text']         = Utility::getStatusText($status);
        $ret['galid']               = $this->getVar('ev_galid');
        $ret['datecreated']         = \formatTimestamp($this->getVar('ev_datecreated'), 's');
        $ret['submitter']           = \XoopsUser::getUnameFromId($this->getVar('ev_submitter'));
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
