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
 * Class Object Event
 */
class Event extends \XoopsObject
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
     * @var int
     */
    public $idSource = 0;

    /**
     * Constructor
     *
     * @param null
     */
    public function __construct()
    {
        $this->initVar('id', \XOBJ_DTYPE_INT);
        $this->initVar('catid', \XOBJ_DTYPE_INT);
        $this->initVar('subcats', \XOBJ_DTYPE_OTHER);
        $this->initVar('name', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('logo', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('desc', \XOBJ_DTYPE_OTHER);
        $this->initVar('datefrom', \XOBJ_DTYPE_INT);
        $this->initVar('dateto', \XOBJ_DTYPE_INT);
        $this->initVar('allday', \XOBJ_DTYPE_INT);
        $this->initVar('contact', \XOBJ_DTYPE_TXTAREA);
        $this->initVar('email', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('url', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('location', \XOBJ_DTYPE_TXTAREA);
        $this->initVar('locgmlat', \XOBJ_DTYPE_FLOAT);
        $this->initVar('locgmlon', \XOBJ_DTYPE_FLOAT);
        $this->initVar('locgmzoom', \XOBJ_DTYPE_INT);
        $this->initVar('fee', \XOBJ_DTYPE_OTHER);
        $this->initVar('paymentinfo', \XOBJ_DTYPE_OTHER);
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
        $this->initVar('register_forceverif', \XOBJ_DTYPE_INT);
        $this->initVar('status', \XOBJ_DTYPE_INT);
        $this->initVar('galid', \XOBJ_DTYPE_INT);
        $this->initVar('identifier', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('groups', \XOBJ_DTYPE_TXTBOX);
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
    public function getNewInsertedId()
    {
        return $GLOBALS['xoopsDB']->getInsertId();
    }

    /**
     * @public function getForm
     * @param bool $action
     * @return \XoopsThemeForm
     */
    public function getForm($action = false)
    {
        $helper = Helper::getInstance();

        $categoryHandler = $helper->getHandler('Category');
        $permissionsHandler = $helper->getHandler('Permission');

        $utility = new Utility();

        if (!$action) {
            $action = $_SERVER['REQUEST_URI'];
        }
        $isAdmin   = false;
        $userUid   = 0;
        $userEmail = '';
        $userName  = '';
        if (\is_object($GLOBALS['xoopsUser'])) {
            if (\is_object($GLOBALS['xoopsModule'])) {
                $isAdmin   = $GLOBALS['xoopsUser']->isAdmin($GLOBALS['xoopsModule']->mid());
            }
            $userUid   = $GLOBALS['xoopsUser']->uid();
            $userEmail = $GLOBALS['xoopsUser']->email();
            $userName  = ('' != (string)$GLOBALS['xoopsUser']->name()) ? $GLOBALS['xoopsUser']->name() : $GLOBALS['xoopsUser']->uname();
        }

        $imgInfo = '<img class="wge-img-info" src="' . \WGEVENTS_ICONS_URL_24 . '/info.png" alt="img-info" title="%s">';

        // Title
        $title = $this->isNew() ? \_MA_WGEVENTS_EVENT_ADD : \_MA_WGEVENTS_EVENT_EDIT;
        if ($this->idSource > 0) {
            $this->unsetNew();
        }

        // Get Theme Form
        \xoops_load('XoopsFormLoader');
        $form = new \XoopsThemeForm($title, 'formEvent', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        // Form Text identifier
        if (!$this->isNew()) {
            if ($isAdmin) {
                $form->addElement(new \XoopsFormText(\_MA_WGEVENTS_EVENT_IDENTIFIER, 'identifier', 50, 255, $this->getVar('identifier')));
            } else {
                $form->addElement(new \XoopsFormLabel(\_MA_WGEVENTS_EVENT_IDENTIFIER, $this->getVar('identifier')));
                $form->addElement(new \XoopsFormHidden('identifier', (string)$this->getVar('identifier')));
            }
        }
        // Form Table categories
        $evCatidSelect = new \XoopsFormSelect(\_MA_WGEVENTS_EVENT_CATID, 'catid', $this->getVar('catid'));
        $evCatidSelect->addOptionArray($categoryHandler->getAllCatsOnline(Constants::CATEGORY_TYPE_MAIN));
        $form->addElement($evCatidSelect);
        // Form Table sub categories
        // count sub categories
        $catsSubOnline = $categoryHandler->getAllCatsOnline(Constants::CATEGORY_TYPE_SUB);
        if (\count($catsSubOnline) > 0) {
            $evSubCats = $this->isNew() ? [] : \unserialize($this->getVar('subcats'));
            $evSubCatsSelect = new \XoopsFormCheckBox(\_MA_WGEVENTS_EVENT_SUBCATS, 'subcats', $evSubCats);
            $evSubCatsSelect->addOptionArray($catsSubOnline);
            $form->addElement($evSubCatsSelect);
        }
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
        // Form Tray Datefrom
        $evDatefromTray = new Forms\FormElementTray(\_MA_WGEVENTS_EVENT_DATEFROM, '&nbsp;');
        // Text Date Select evDatefrom
        $evDatefrom = ($this->isNew() && 0 == (int)$this->getVar('datefrom')) ? \time() : $this->getVar('datefrom');
        $evDatefromTray->addElement(new \XoopsFormDateTime('', 'datefrom', '', $evDatefrom), true);
        // Text Date Checkbox evAllday
        $evAllday = $this->isNew() ? 0 : (int)$this->getVar('allday');
        $checkAllday = new \XoopsFormCheckBox('', 'allday', $evAllday);
        $checkAllday->addOption(1, \_MA_WGEVENTS_EVENT_ALLDAY);
        $checkAllday->setExtra(" onclick='toogleAllday()' ");
        $evDatefromTray->addElement($checkAllday);
        $form->addElement($evDatefromTray);
        // Form Text Date Select evDateto
        $evDateto = ($this->isNew() && 0 == (int)$this->getVar('dateto')) ? \time() : $this->getVar('dateto');
        $form->addElement(new \XoopsFormDateTime(\_MA_WGEVENTS_EVENT_DATETO, 'dateto', '', $evDateto));
        // Form Text evContact
        $form->addElement(new \XoopsFormTextArea(\_MA_WGEVENTS_EVENT_CONTACT, 'contact', $this->getVar('contact', 'e'), 4, 30));
        // Form Text evEmail
        $form->addElement(new \XoopsFormText(\_MA_WGEVENTS_EVENT_EMAIL, 'email', 50, 255, $this->getVar('email')));
        // Form Text evUrl
        $form->addElement(new \XoopsFormText(\_MA_WGEVENTS_EVENT_URL, 'url', 50, 255, $this->getVar('url')));
        // Location
        // Form Editor TextArea evLocation
        $evLocation = $this->isNew() ? '' : $this->getVar('location', 'e');
        $form->addElement(new \XoopsFormTextArea(\_MA_WGEVENTS_EVENT_LOCATION, 'location', $evLocation, 4, 50));

        if ($helper->getConfig('use_gmaps')) {
            $gmapsTray = new Forms\FormElementTray('', '<br>');
            // Form Text evLocgmlat
            $evLocgmlat = $this->isNew() ? '0.00' : $this->getVar('locgmlat');
            $gmapsTray->addElement(new \XoopsFormText(\_MA_WGEVENTS_EVENT_LOCGMLAT, 'locgmlat', 20, 50, $evLocgmlat));
            // Form Text evLocgmlon
            $evLocgmlon = $this->isNew() ? '0.00' : $this->getVar('locgmlon');
            $gmapsTray->addElement(new \XoopsFormText(\_MA_WGEVENTS_EVENT_LOCGMLON, 'locgmlon', 20, 50, $evLocgmlon));
            // Form Text evLocgmzoom
            $evLocgmzoom = $this->isNew() ? '10' : $this->getVar('locgmzoom');
            $gmapsTray->addElement(new \XoopsFormText(\_MA_WGEVENTS_EVENT_LOCGMZOOM, 'locgmzoom', 5, 50, $evLocgmzoom));
            // Form label
            $locLabel = '<div class="row"><div class="col-sm-6">';
            $locLabel .= $gmapsTray->render();
            $locLabel .= '</div>';
            $locLabel .= '<div class="col-sm-6">';
            $locLabel .= "<button id='btnGetCoords' type='button' class='btn btn-primary' data-toggle='modal' data-target='#modalCoordsPicker'>" . \_MA_WGEVENTS_EVENT_GM_GETCOORDS . '</button>';
            $locLabel .= '</div></div>';
            $form->addElement(new \XoopsFormLabel('', $locLabel));
        }
        // Form Text evFee
        $default0 = '0' . $helper->getConfig('sep_comma') . '00';
        $evFeeArr = [];
        if ($this->isNew()) {
            $evFeeArr = [[$default0, '', 'placeholder' => \_MA_WGEVENTS_EVENT_FEE_DESC_PH]];
        } else {
            $evFee = \json_decode($this->getVar('fee'), true);
            foreach($evFee as $fee) {
                $evFeeArr[] = [Utility::FloatToString((float)$fee[0]), $fee[1], 'placeholder' => \_MA_WGEVENTS_EVENT_FEE_DESC_PH];
            }
        }
        $evFeeTray = new Forms\FormElementTray(\_MA_WGEVENTS_EVENT_FEE, '<br>');
        $evFeeGroup = new Forms\FormTextDouble('', 'fee', 0, 0, '');
        $evFeeGroup->setElements($evFeeArr);
        $evFeeGroup->setPlaceholder1(\_MA_WGEVENTS_EVENT_FEE_VAL_PH);
        $evFeeGroup->setPlaceholder2(\_MA_WGEVENTS_EVENT_FEE_DESC_PH);
        $evFeeTray->addElement($evFeeGroup);

        $form->addElement($evFeeTray);
        // Form TextArea evPaymentinfo
        $editorConfigs2 = [];
        $editorConfigs2['name'] = 'paymentinfo';
        $editorConfigs2['value'] = $this->getVar('paymentinfo', 'e');
        $editorConfigs2['rows'] = 5;
        $editorConfigs2['cols'] = 40;
        $editorConfigs2['width'] = '100%';
        $editorConfigs2['height'] = '400px';
        $editorConfigs2['editor'] = $editor;
        $form->addElement(new \XoopsFormEditor(\_MA_WGEVENTS_EVENT_PAYMENTINFO, 'paymentinfo', $editorConfigs2));

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
            // Form Radio Yes/No evRegisterListwait
            $evRegisterForceverif = $this->isNew() ? 1 : $this->getVar('register_forceverif');
            $captionRegisterForceverif = \_MA_WGEVENTS_EVENT_REGISTER_FORCEVERIF . \sprintf($imgInfo, \_MA_WGEVENTS_EVENT_REGISTER_FORCEVERIF_DESC);
            $evRegisterOptsTray->addElement(new \XoopsFormRadioYN($captionRegisterForceverif, 'register_forceverif', $evRegisterForceverif));

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
        // Form Select evGroups
        if ($helper->getConfig('use_groups')) {
            if ($this->isNew()) {
                $groups = ['00000'];
            } else {
                $groups = \explode("|", $this->getVar('groups'));
            }
            $evGroupsSelect = new \XoopsFormSelect(\_MA_WGEVENTS_EVENT_GROUPS, 'groups', $groups, 5, true);
            $evGroupsSelect->addOption('00000', \_MA_WGEVENTS_EVENT_GROUPS_ALL);
            // Get groups
            $memberHandler = \xoops_getHandler('member');
            $xoopsGroups = $memberHandler->getGroupList();
            foreach ($xoopsGroups as $key => $group) {
                if (3 !== (int)$key) {
                    $evGroupsSelect->addOption(substr('00000' . $key,  -5), $group);
                }
            }
            $form->addElement($evGroupsSelect, true);
        } else {
            $form->addElement(new \XoopsFormHidden('groups', '00000'));
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
        $evDatecreated = $this->isNew() ? \time() : (int)$this->getVar('datecreated');
        $evSubmitter = $this->isNew() ? $userUid : (int)$this->getVar('submitter');
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
            if (!$this->isNew() && $permEventsApprove) {
                $evStatusSelect = new \XoopsFormSelect(\_MA_WGEVENTS_STATUS, 'status', $evStatus);
                $evStatusSelect->addOption(Constants::STATUS_NONE, \_MA_WGEVENTS_STATUS_NONE);
                $evStatusSelect->addOption(Constants::STATUS_OFFLINE, \_MA_WGEVENTS_STATUS_OFFLINE);
                $evStatusSelect->addOption(Constants::STATUS_SUBMITTED, \_MA_WGEVENTS_STATUS_SUBMITTED);
                $evStatusSelect->addOption(Constants::STATUS_APPROVED, \_MA_WGEVENTS_STATUS_APPROVED);
                $form->addElement($evStatusSelect, true);
                $form->addElement(new \XoopsFormLabel(\_MA_WGEVENTS_DATECREATED, \formatTimestamp($evDatecreated, 's')));
                $form->addElement(new \XoopsFormLabel(\_MA_WGEVENTS_SUBMITTER, \XoopsUser::getUnameFromId($evSubmitter)));
            }
        }
        if ($this->idSource > 0 && $helper->getConfig('use_register')) {
            $form->addElement(new \XoopsFormRadioYN(\_MA_WGEVENTS_EVENT_CLONE_QUESTION, 'clone_question', 1));
            $form->addElement(new \XoopsFormHidden('id_source', $this->idSource));
        }
        if (!$this->isNew() && 0 === $this->idSource) {
            $informModif = new \XoopsFormRadioYN(\_MA_WGEVENTS_EVENT_INFORM_MODIF, 'informModif', 0);
            $informModif->setDescription(\_MA_WGEVENTS_EVENT_INFORM_MODIF_DESC);
            $form->addElement($informModif);
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
     * @public function getFormContactAll
     * @param bool $action
     * @return \XoopsThemeForm
     */
    public function getFormContactAll($action = false)
    {
        $helper = \XoopsModules\Wgevents\Helper::getInstance();
        $registrationHandler = $helper->getHandler('Registration');
        if (!$action) {
            $action = $_SERVER['REQUEST_URI'];
        }
        // Get Theme Form
        \xoops_load('XoopsFormLoader');
        $form = new \XoopsThemeForm(\_MA_WGEVENTS_CONTACT_ALL, 'formContactAll', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        // Form Text mailFrom
        $emailTray = new Forms\FormElementTray(\_MA_WGEVENTS_CONTACT_MAILFROM, '<br>');
        $email = new Forms\FormText('', 'mail_from', 50, 255, $this->getVar('email'));
        $email->setPlaceholder(\_MA_WGEVENTS_REGISTRATION_EMAIL_PLACEHOLDER);
        $emailTray->addElement($email, true);
        // Form select mailCopy
        $emailRadio = new \XoopsFormRadioYN(\_MA_WGEVENTS_CONTACT_MAILCOPY, 'mail_copy', 1);
        $emailTray->addElement($emailRadio);
        $form->addElement($emailTray);
        // Form Editor TextArea mailTo
        $crRegistration = new \CriteriaCompo();
        $crRegistration->add(new \Criteria('evid', $this->getVar('id')));
        $numberRegCurr = $registrationHandler->getCount($crRegistration);
        $mailToArr = [];
        $mailTo = '';
        if ($numberRegCurr > 0) {
            $registrationsAll = $registrationHandler->getAll($crRegistration);
            foreach (\array_keys($registrationsAll) as $i) {
                $mailToArr[$registrationsAll[$i]->getVar('email')] = $registrationsAll[$i]->getVar('email');
            }
        }
        foreach ($mailToArr as $mail) {
            $mailTo .= $mail . PHP_EOL;
        }
        $mailToTextarea = new \XoopsFormTextArea(\_MA_WGEVENTS_CONTACT_MAILTO, 'mail_to', $mailTo, 5, 47);
        $mailToTextarea->setExtra(" disabled='disabled'");
        $form->addElement($mailToTextarea);
        // From Text Subject
        $subject = \sprintf(\_MA_WGEVENTS_CONTACT_ALL_MAILSUBJECT_TEXT, $this->getVar('name'));
        $form->addElement(new \XoopsFormText(\_MA_WGEVENTS_CONTACT_MAILSUBJECT, 'mail_subject', 50, 255, $subject), true);
        // Form Editor DhtmlTextArea mailBody
        $editorConfigs = [];
        $editor = $helper->getConfig('editor_user');
        $editorConfigs['name'] = 'mail_body';
        $editorConfigs['value'] = '';
        $editorConfigs['rows'] = 10;
        $editorConfigs['cols'] = 40;
        $editorConfigs['width'] = '100%';
        $editorConfigs['height'] = '400px';
        $editorConfigs['editor'] = $editor;
        $form->addElement(new \XoopsFormEditor(\_MA_WGEVENTS_CONTACT_MAILBODY, 'mail_body', $editorConfigs));
        // To Save
        $form->addElement(new \XoopsFormHidden('evid', $this->getVar('id')));
        $form->addElement(new \XoopsFormHidden('op', 'exec_contactall'));
        $form->addElement(new \XoopsFormButtonTray('', \_MA_WGEVENTS_SEND_ALL, 'submit', '', false));
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
        $adminMaxchar    = $helper->getConfig('admin_maxchar');
        $userMaxchar     = $helper->getConfig('user_maxchar');
        $categoryHandler = $helper->getHandler('Category');
        $catId       = (int)$this->getVar('catid');
        $categoryObj = $categoryHandler->get($catId);
        $catName     = '';
        $catLogo     = '';
        if (\is_object($categoryObj)) {
            $catName = $categoryObj->getVar('name');
            if ('blank.gif' !== (string)$categoryObj->getVar('logo')) {
                $catLogo = $categoryObj->getVar('logo');
            }
        }
        $ret['catname'] = $catName;
        $ret['catlogo'] = $catLogo;
        $subcatsArr = [];
        $subcats = \unserialize($this->getVar('subcats'));
        if (\is_array($subcats) && \count($subcats) > 0) {
            foreach ($subcats as $subcat) {
                $subcategoryObj = $categoryHandler->get($subcat);
                // do not repeat main cat in sub cats even if it is checked there once more
                if (\is_object($subcategoryObj) && $catId !== (int)$subcat) {
                    //if (\is_object($subcategoryObj)) {
                    $subcatsArr[$subcat]['id'] = $subcat;
                    $subcatsArr[$subcat]['name'] = $subcategoryObj->getVar('name');
                    $subcatsArr[$subcat]['logo'] = $subcat;
                    if ('blank.gif' !== (string)$subcategoryObj->getVar('logo')) {
                        $subcatsArr[$subcat]['logo'] = $subcategoryObj->getVar('logo');
                    }
                }
            }
        }
        $ret['subcats_arr']      = $subcatsArr;
        $ret['name_clean']       = \htmlspecialchars($this->getVar('name'), ENT_QUOTES | ENT_HTML5);
        $ret['desc_text']        = $this->getVar('desc', 'e');
        $ret['desc_short_admin'] = $utility::truncateHtml($ret['desc_text'], $adminMaxchar);
        $ret['desc_short_user']  = $utility::truncateHtml($ret['desc_text'], $userMaxchar);
        $evAllday                = (int)$this->getVar('allday');
        $ret['allday_single']    = 0;
        if ($evAllday > 0) {
            $datefrom_text = \formatTimestamp($this->getVar('datefrom'), 's');
            $dateto_text   = \formatTimestamp($this->getVar('dateto'), 's');
            $ret['datefrom_text'] = $datefrom_text . ' ' . \_MA_WGEVENTS_EVENT_ALLDAY;
            if ($datefrom_text === $dateto_text) {
                //single allday
                $ret['allday_single'] = 1;
                $ret['dateto_text']   = ' ';
            } else {
                $ret['dateto_text']   = $dateto_text . ' ' . \_MA_WGEVENTS_EVENT_ALLDAY;
            }
        } else {
            $ret['datefrom_text']    = \formatTimestamp($this->getVar('datefrom'), 'm');
            $ret['dateto_text']      = \formatTimestamp($this->getVar('dateto'), 'm');
        }
        $evLocation              = $this->getVar('location', 'e');
        $ret['location_text']    = $evLocation;
        if ($evLocation) {
            $loc   = preg_split("/\r\n|\n|\r/", $evLocation);
            $ret['location_text_user']  = \implode('<br>', $loc);
        }
        $evContact               = $this->getVar('contact', 'e');
        $ret['contact_text']     = $evContact;
        if ($evContact) {
            $contactLines   = preg_split("/\r\n|\n|\r/", $evContact);
            $ret['contact_text_user']  = \implode('<br>', $contactLines);
        }
        $evFee = \json_decode($this->getVar('fee'), true);
        $evFeeText = '';
        foreach($evFee as $fee) {
            $evFeeText .= Utility::FloatToString((float)$fee[0]) . ' ' . $fee[1] . '<br>';
        }
        $ret['fee_text']           = $evFeeText;
        $ret['paymentinfo_text']   = $this->getVar('paymentinfo', 'e');
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
        $ret['register_forceverif_text'] = (int)$this->getVar('register_forceverif') > 0 ? \_YES : \_NO;
        $evGroups                        = $this->getVar('groups', 'e');
        $groups_text                     = '';
        if (0 == (int)$evGroups) {
            $groups_text = \_MA_WGEVENTS_EVENT_GROUPS_ALL;
        } else {
            // Get groups
            $groups_text .= '<ul>';
            $memberHandler = \xoops_getHandler('member');
            $xoopsGroups  = $memberHandler->getGroupList();
            $groups   = explode("|", $evGroups);
            foreach ($groups as $group) {
                $groups_text .= '<li>' . $xoopsGroups[(int)$group] .  '</li>' ;
            }
            $groups_text .= '</ul>';
        }
        $ret['groups_text']      = $groups_text;
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
    /*
    public function toArray()
    {
        $ret = [];
        $vars = $this->getVars();
        foreach (\array_keys($vars) as $var) {
            $ret[$var] = $this->getVar($var);
        }
        return $ret;
    }
    */
}
