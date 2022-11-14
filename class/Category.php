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
use XoopsModules\Wgevents\Utility;

\defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class Object Category
 */
class Category extends \XoopsObject
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
        $this->initVar('pid', \XOBJ_DTYPE_INT);
        $this->initVar('name', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('desc', \XOBJ_DTYPE_OTHER);
        $this->initVar('logo', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('color', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('bordercolor', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('bgcolor', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('othercss', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('type', \XOBJ_DTYPE_INT);
        $this->initVar('status', \XOBJ_DTYPE_INT);
        $this->initVar('weight', \XOBJ_DTYPE_INT);
        $this->initVar('identifier', \XOBJ_DTYPE_TXTBOX);
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
    public function getNewInsertedIdCategories()
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
        $helper = \XoopsModules\Wgevents\Helper::getInstance();
        if (!$action) {
            $action = $_SERVER['REQUEST_URI'];
        }
        $isAdmin = \is_object($GLOBALS['xoopsUser']) && $GLOBALS['xoopsUser']->isAdmin($GLOBALS['xoopsModule']->mid());
        // Title
        $title = $this->isNew() ? \_AM_WGEVENTS_CATEGORY_ADD : \_AM_WGEVENTS_CATEGORY_EDIT;
        // Get Theme Form
        \xoops_load('XoopsFormLoader');
        $form = new \XoopsThemeForm($title, 'form', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        // Form Table categories
        $categoryHandler = $helper->getHandler('Category');
        $catPidSelect = new \XoopsFormSelect(\_AM_WGEVENTS_CATEGORY_PID, 'pid', $this->getVar('pid'));
        $catPidSelect->addOption('');
        $catPidSelect->addOptionArray($categoryHandler->getList());
        $form->addElement($catPidSelect);
        // Form Text catName
        $form->addElement(new \XoopsFormText(\_AM_WGEVENTS_CATEGORY_NAME, 'name', 50, 255, $this->getVar('name')), true);
        // Form Editor DhtmlTextArea catDesc
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
        $form->addElement(new \XoopsFormEditor(\_AM_WGEVENTS_CATEGORY_DESC, 'desc', $editorConfigs));
        // Form Image catLogo
        // Form Image catLogo: Select Uploaded Image 
        $getCatLogo = $this->getVar('logo');
        $catLogo = $getCatLogo ?: 'blank.gif';
        $imageDirectory = '/uploads/wgevents/categories/logos';
        $imageTray = new \XoopsFormElementTray(\_AM_WGEVENTS_CATEGORY_LOGO, '<br>');
        $imageSelect = new \XoopsFormSelect(\sprintf(\_AM_WGEVENTS_CATEGORY_LOGO_UPLOADS, ".{$imageDirectory}/"), 'logo', $catLogo, 5);
        $imageArray = \XoopsLists::getImgListAsArray( \XOOPS_ROOT_PATH . $imageDirectory );
        foreach ($imageArray as $image1) {
            $imageSelect->addOption(($image1), $image1);
        }
        $imageSelect->setExtra("onchange='showImgSelected(\"imglabel_cat_logo\", \"logo\", \"" . $imageDirectory . '", "", "' . \XOOPS_URL . "\")'");
        $imageTray->addElement($imageSelect, false);
        $imageTray->addElement(new \XoopsFormLabel('', "<br><img src='" . \XOOPS_URL . '/' . $imageDirectory . '/' . $catLogo . "' id='imglabel_cat_logo' alt='' style='max-width:100px' >"));
        // Form Image catLogo: Upload new image
        $maxsize = $helper->getConfig('maxsize_image');
        $imageTray->addElement(new \XoopsFormFile('<br>' . \_AM_WGEVENTS_FORM_UPLOAD_NEW, 'logo', $maxsize));
        $imageTray->addElement(new \XoopsFormLabel(\_AM_WGEVENTS_FORM_UPLOAD_SIZE, ($maxsize / 1048576) . ' '  . \_AM_WGEVENTS_FORM_UPLOAD_SIZE_MB));
        $imageTray->addElement(new \XoopsFormLabel(\_AM_WGEVENTS_FORM_UPLOAD_IMG_WIDTH, $helper->getConfig('maxwidth_image') . ' px'));
        $imageTray->addElement(new \XoopsFormLabel(\_AM_WGEVENTS_FORM_UPLOAD_IMG_HEIGHT, $helper->getConfig('maxheight_image') . ' px'));
        $form->addElement($imageTray);
        // Form Color Picker catColor
        $form->addElement(new \XoopsFormColorPicker(\_AM_WGEVENTS_CATEGORY_COLOR, 'color', $this->getVar('color')));
        // Form Color Picker catBorderColor
        $form->addElement(new \XoopsFormColorPicker(\_AM_WGEVENTS_CATEGORY_BORDERCOLOR, 'bordercolor', $this->getVar('bordercolor')));
        // Form Color Picker catColor
        $form->addElement(new \XoopsFormColorPicker(\_AM_WGEVENTS_CATEGORY_BGCOLOR, 'bgcolor', $this->getVar('bgcolor')));
        // Form Text catOtherstyles
        $catOtherstyles = $this->isNew() ? 'margin:1px 0;padding:8px 5px 20px 5px;border-radius:5px;' : $this->getVar('othercss');
        $form->addElement(new \XoopsFormText(\_AM_WGEVENTS_CATEGORY_OTHERCSS, 'othercss', 100, 255, $catOtherstyles));
        // Form Text catIdentifier
        $catIdentifier = new \XoopsFormText(\_AM_WGEVENTS_CATEGORY_IDENTIFIER, 'identifier', 100, 255, $this->getVar('identifier'));
        $catIdentifier->setDescription(\_AM_WGEVENTS_CATEGORY_IDENTIFIER_DESC);
        $form->addElement($catIdentifier);
        // Form Radio catStatus
        $catStatus = $this->isNew() ? Constants::STATUS_OFFLINE : $this->getVar('status');
        $catStatusRadio = new \XoopsFormRadio(\_MA_WGEVENTS_STATUS, 'status', $catStatus);
        $catStatusRadio->addOption(Constants::STATUS_OFFLINE, \_MA_WGEVENTS_STATUS_OFFLINE);
        $catStatusRadio->addOption(Constants::STATUS_ONLINE, \_MA_WGEVENTS_STATUS_ONLINE);
        $form->addElement($catStatusRadio);
        // Form Radio catType
        $catType = $this->isNew() ? Constants::CATEGORY_TYPE_BOTH : (int)$this->getVar('type');
        $catTypeRadio = new \XoopsFormRadio(\_AM_WGEVENTS_CATEGORY_TYPE, 'type', $catType);
        $catTypeRadio->addOption(Constants::CATEGORY_TYPE_MAIN, \_AM_WGEVENTS_CATEGORY_TYPE_MAIN);
        $catTypeRadio->addOption(Constants::CATEGORY_TYPE_SUB, \_AM_WGEVENTS_CATEGORY_TYPE_SUB);
        $catTypeRadio->addOption(Constants::CATEGORY_TYPE_BOTH, \_AM_WGEVENTS_CATEGORY_TYPE_BOTH);
        $form->addElement($catTypeRadio);
        // Form Text catWeight
        $catWeight = $this->getVar('weight');
        if ($this->isNew()) {
            $catWeight = $categoryHandler->getNextWeight();
        }
        $form->addElement(new \XoopsFormText(\_MA_WGEVENTS_WEIGHT, 'weight', 50, 255, $catWeight));
        // Form Text Date Select catDatecreated
        $catDatecreated = $this->isNew() ? \time() : $this->getVar('datecreated');
        $form->addElement(new \XoopsFormTextDateSelect(\_MA_WGEVENTS_DATECREATED, 'datecreated', '', $catDatecreated));
        // Form Select User catSubmitter
        $uidCurrent = \is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->uid() : 0;
        $catSubmitter = $this->isNew() ? $uidCurrent : $this->getVar('submitter');
        $form->addElement(new \XoopsFormSelectUser(\_MA_WGEVENTS_SUBMITTER, 'submitter', false, $catSubmitter));
        /*
        // Permission
        $memberHandler = \xoops_getHandler('member');
        $groupList = $memberHandler->getGroupList();
        $grouppermHandler = \xoops_getHandler('groupperm');
        $fullList[] = \array_keys($groupList);
        if ($this->isNew()) {
            $groupsCanApproveEventsCheckbox = new \XoopsFormCheckBox(\_AM_WGEVENTS_PERMISSIONS_CAT_EVENTS_APPROVE, 'groups_approve_cat_events[]', $fullList);
            $groupsCanSubmitEventsCheckbox = new \XoopsFormCheckBox(\_AM_WGEVENTS_PERMISSIONS_CAT_EVENTS_SUBMIT, 'groups_submit_cat_events[]', $fullList);
            $groupsCanViewEventsCheckbox = new \XoopsFormCheckBox(\_AM_WGEVENTS_PERMISSIONS_CAT_EVENTS_VIEW, 'groups_view_cat_events[]', $fullList);
            $groupsCanApproveRegsCheckbox = new \XoopsFormCheckBox(\_AM_WGEVENTS_PERMISSIONS_CAT_REGS_APPROVE, 'groups_approve_cat_regs[]', $fullList);
            $groupsCanSubmitRegsCheckbox = new \XoopsFormCheckBox(\_AM_WGEVENTS_PERMISSIONS_CAT_REGS_SUBMIT, 'groups_submit_cat_regs[]', $fullList);
            $groupsCanViewRegsCheckbox = new \XoopsFormCheckBox(\_AM_WGEVENTS_PERMISSIONS_CAT_REGS_VIEW, 'groups_view_cat_regs[]', $fullList);
        } else {
            $groupsIdsApproveEvents = $grouppermHandler->getGroupIds('wgevents_approve_cat_events', $this->getVar('id'), $GLOBALS['xoopsModule']->getVar('mid'));
            $groupsIdsApproveEvents[] = \array_values($groupsIdsApproveEvents);
            $groupsCanApproveEventsCheckbox = new \XoopsFormCheckBox(\_AM_WGEVENTS_PERMISSIONS_CAT_EVENTS_APPROVE, 'groups_approve_cat_events[]', $groupsIdsApproveEvents);
            $groupsIdsSubmitEvents = $grouppermHandler->getGroupIds('wgevents_submit_cat_events', $this->getVar('id'), $GLOBALS['xoopsModule']->getVar('mid'));
            $groupsIdsSubmitEvents[] = \array_values($groupsIdsSubmitEvents);
            $groupsCanSubmitEventsCheckbox = new \XoopsFormCheckBox(\_AM_WGEVENTS_PERMISSIONS_CAT_EVENTS_SUBMIT, 'groups_submit_cat_events[]', $groupsIdsSubmitEvents);
            $groupsIdsViewEvents = $grouppermHandler->getGroupIds('wgevents_view_cat_events', $this->getVar('id'), $GLOBALS['xoopsModule']->getVar('mid'));
            $groupsIdsViewEvents[] = \array_values($groupsIdsViewEvents);
            $groupsCanViewEventsCheckbox = new \XoopsFormCheckBox(\_AM_WGEVENTS_PERMISSIONS_CAT_EVENTS_VIEW, 'groups_view_cat_events[]', $groupsIdsViewEvents);
            $groupsIdsApproveRegs = $grouppermHandler->getGroupIds('wgevents_approve_cat_regs', $this->getVar('id'), $GLOBALS['xoopsModule']->getVar('mid'));
            $groupsIdsApproveRegs[] = \array_values($groupsIdsApproveRegs);
            $groupsCanApproveRegsCheckbox = new \XoopsFormCheckBox(\_AM_WGEVENTS_PERMISSIONS_CAT_REGS_APPROVE, 'groups_approve_cat_regs[]', $groupsIdsApproveRegs);
            $groupsIdsSubmitRegs = $grouppermHandler->getGroupIds('wgevents_submit_cat_regs', $this->getVar('id'), $GLOBALS['xoopsModule']->getVar('mid'));
            $groupsIdsSubmitRegs[] = \array_values($groupsIdsSubmitRegs);
            $groupsCanSubmitRegsCheckbox = new \XoopsFormCheckBox(\_AM_WGEVENTS_PERMISSIONS_CAT_REGS_SUBMIT, 'groups_submit_cat_regs[]', $groupsIdsSubmitRegs);
            $groupsIdsViewRegs = $grouppermHandler->getGroupIds('wgevents_view_cat_regs', $this->getVar('id'), $GLOBALS['xoopsModule']->getVar('mid'));
            $groupsIdsViewRegs[] = \array_values($groupsIdsViewRegs);
            $groupsCanViewRegsCheckbox = new \XoopsFormCheckBox(\_AM_WGEVENTS_PERMISSIONS_CAT_REGS_VIEW, 'groups_view_cat_regs[]', $groupsIdsViewRegs);
        }
        // To Approve
        $groupsCanApproveEventsCheckbox->addOptionArray($groupList);
        $form->addElement($groupsCanApproveEventsCheckbox);
        // To Submit
        $groupsCanSubmitEventsCheckbox->addOptionArray($groupList);
        $form->addElement($groupsCanSubmitEventsCheckbox);
        // To View
        $groupsCanViewEventsCheckbox->addOptionArray($groupList);
        $form->addElement($groupsCanViewEventsCheckbox);
        // To Approve
        $groupsCanApproveRegsCheckbox->addOptionArray($groupList);
        $form->addElement($groupsCanApproveRegsCheckbox);
        // To Submit
        $groupsCanSubmitRegsCheckbox->addOptionArray($groupList);
        $form->addElement($groupsCanSubmitRegsCheckbox);
        // To View
        $groupsCanViewRegsCheckbox->addOptionArray($groupList);
        $form->addElement($groupsCanViewRegsCheckbox);
        */
        // To Save
        $form->addElement(new \XoopsFormHidden('op', 'save'));
        $form->addElement(new \XoopsFormHidden('start', $this->start));
        $form->addElement(new \XoopsFormHidden('limit', $this->limit));
        $form->addElement(new \XoopsFormButtonTray('', \_SUBMIT, 'submit', '', false));
        return $form;
    }

    /**
     * Get Values
     * @param null $keys
     * @param null $format
     * @param null $maxDepth
     * @return array
     */
    public function getValuesCategories($keys = null, $format = null, $maxDepth = null)
    {
        $helper  = \XoopsModules\Wgevents\Helper::getInstance();
        $utility = new \XoopsModules\Wgevents\Utility();
        $ret = $this->getValues($keys, $format, $maxDepth);
        $categoryHandler = $helper->getHandler('Category');
        $categoryObj = $categoryHandler->get($this->getVar('pid'));
        $pidText = '(' . $this->getVar('pid') . ') ';
        if (is_object($categoryObj)) {
            $pidText .= $categoryObj->getVar('name');
        }
        $ret['pid_text']         = $pidText;
        $ret['desc_text']        = $this->getVar('desc', 'e');
        $editorMaxchar = $helper->getConfig('admin_maxchar');
        $ret['desc_short']       = $utility::truncateHtml($ret['desc_text'], $editorMaxchar);
        $ret['status_text']      = Utility::getStatusText($this->getVar('status'));
        $catType = (int)$this->getVar('type');
        switch ($catType) {
            case Constants::CATEGORY_TYPE_MAIN:
                $catTypeText = \_AM_WGEVENTS_CATEGORY_TYPE_MAIN;
                break;
            case Constants::CATEGORY_TYPE_SUB:
                $catTypeText = \_AM_WGEVENTS_CATEGORY_TYPE_SUB;
                break;
            case Constants::CATEGORY_TYPE_BOTH:
            default:
                $catTypeText = \_AM_WGEVENTS_CATEGORY_TYPE_BOTH;
                break;
        }
        $ret['type_text']        = $catTypeText;
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
    public function toArrayCategories()
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
