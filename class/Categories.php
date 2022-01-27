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
 * Class Object Categories
 */
class Categories extends \XoopsObject
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
        $this->initVar('cat_id', \XOBJ_DTYPE_INT);
        $this->initVar('cat_pid', \XOBJ_DTYPE_INT);
        $this->initVar('cat_name', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('cat_desc', \XOBJ_DTYPE_OTHER);
        $this->initVar('cat_logo', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('cat_color', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('cat_bordercolor', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('cat_bgcolor', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('cat_othercss', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('cat_status', \XOBJ_DTYPE_INT);
        $this->initVar('cat_weight', \XOBJ_DTYPE_INT);
        $this->initVar('cat_datecreated', \XOBJ_DTYPE_INT);
        $this->initVar('cat_submitter', \XOBJ_DTYPE_INT);
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
    public function getFormCategories($action = false)
    {
        $helper = \XoopsModules\Wgevents\Helper::getInstance();
        if (!$action) {
            $action = $_SERVER['REQUEST_URI'];
        }
        $isAdmin = \is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->isAdmin($GLOBALS['xoopsModule']->mid()) : false;
        // Title
        $title = $this->isNew() ? \sprintf(\_AM_WGEVENTS_CATEGORY_ADD) : \sprintf(\_AM_WGEVENTS_CATEGORY_EDIT);
        // Get Theme Form
        \xoops_load('XoopsFormLoader');
        $form = new \XoopsThemeForm($title, 'form', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        // Form Table categories
        $categoriesHandler = $helper->getHandler('Categories');
        $catPidSelect = new \XoopsFormSelect(\_AM_WGEVENTS_CATEGORY_PID, 'cat_pid', $this->getVar('cat_pid'));
        $catPidSelect->addOption('');
        $catPidSelect->addOptionArray($categoriesHandler->getList());
        $form->addElement($catPidSelect);
        // Form Text catName
        $form->addElement(new \XoopsFormText(\_AM_WGEVENTS_CATEGORY_NAME, 'cat_name', 50, 255, $this->getVar('cat_name')), true);
        // Form Editor DhtmlTextArea catDesc
        $editorConfigs = [];
        if ($isAdmin) {
            $editor = $helper->getConfig('editor_admin');
        } else {
            $editor = $helper->getConfig('editor_user');
        }
        $editorConfigs['name'] = 'cat_desc';
        $editorConfigs['value'] = $this->getVar('cat_desc', 'e');
        $editorConfigs['rows'] = 5;
        $editorConfigs['cols'] = 40;
        $editorConfigs['width'] = '100%';
        $editorConfigs['height'] = '400px';
        $editorConfigs['editor'] = $editor;
        $form->addElement(new \XoopsFormEditor(\_AM_WGEVENTS_CATEGORY_DESC, 'cat_desc', $editorConfigs));
        // Form Image catLogo
        // Form Image catLogo: Select Uploaded Image 
        $getCatLogo = $this->getVar('cat_logo');
        $catLogo = $getCatLogo ?: 'blank.gif';
        $imageDirectory = '/uploads/wgevents/categories/logos';
        $imageTray = new \XoopsFormElementTray(\_AM_WGEVENTS_CATEGORY_LOGO, '<br>');
        $imageSelect = new \XoopsFormSelect(\sprintf(\_AM_WGEVENTS_CATEGORY_LOGO_UPLOADS, ".{$imageDirectory}/"), 'cat_logo', $catLogo, 5);
        $imageArray = \XoopsLists::getImgListAsArray( \XOOPS_ROOT_PATH . $imageDirectory );
        foreach ($imageArray as $image1) {
            $imageSelect->addOption(($image1), $image1);
        }
        $imageSelect->setExtra("onchange='showImgSelected(\"imglabel_cat_logo\", \"cat_logo\", \"" . $imageDirectory . '", "", "' . \XOOPS_URL . "\")'");
        $imageTray->addElement($imageSelect, false);
        $imageTray->addElement(new \XoopsFormLabel('', "<br><img src='" . \XOOPS_URL . '/' . $imageDirectory . '/' . $catLogo . "' id='imglabel_cat_logo' alt='' style='max-width:100px' >"));
        // Form Image catLogo: Upload new image
        $maxsize = $helper->getConfig('maxsize_image');
        $imageTray->addElement(new \XoopsFormFile('<br>' . \_AM_WGEVENTS_FORM_UPLOAD_NEW, 'cat_logo', $maxsize));
        $imageTray->addElement(new \XoopsFormLabel(\_AM_WGEVENTS_FORM_UPLOAD_SIZE, ($maxsize / 1048576) . ' '  . \_AM_WGEVENTS_FORM_UPLOAD_SIZE_MB));
        $imageTray->addElement(new \XoopsFormLabel(\_AM_WGEVENTS_FORM_UPLOAD_IMG_WIDTH, $helper->getConfig('maxwidth_image') . ' px'));
        $imageTray->addElement(new \XoopsFormLabel(\_AM_WGEVENTS_FORM_UPLOAD_IMG_HEIGHT, $helper->getConfig('maxheight_image') . ' px'));
        $form->addElement($imageTray);
        // Form Color Picker catColor
        $form->addElement(new \XoopsFormColorPicker(\_AM_WGEVENTS_CATEGORY_COLOR, 'cat_color', $this->getVar('cat_color')));
        // Form Color Picker catBorderColor
        $form->addElement(new \XoopsFormColorPicker(\_AM_WGEVENTS_CATEGORY_BORDERCOLOR, 'cat_bordercolor', $this->getVar('cat_bordercolor')));
        // Form Color Picker catColor
        $form->addElement(new \XoopsFormColorPicker(\_AM_WGEVENTS_CATEGORY_BGCOLOR, 'cat_bgcolor', $this->getVar('cat_bgcolor')));
        // Form Text catOtherstyles
        $catOtherstyles = $this->isNew() ? 'margin:1px 0;padding:8px 5px 20px 5px;border-radius:5px;' : $this->getVar('cat_othercss');
        $form->addElement(new \XoopsFormText(\_AM_WGEVENTS_CATEGORY_OTHERCSS, 'cat_othercss', 100, 255, $catOtherstyles));
        // Form Radio catStatus
        $catStatus = $this->isNew() ? Constants::STATUS_OFFLINE : $this->getVar('cat_status');
        $catStatusSelect = new \XoopsFormRadio(\_MA_WGEVENTS_STATUS, 'cat_status', $catStatus);
        $catStatusSelect->addOption(Constants::STATUS_OFFLINE, \_MA_WGEVENTS_STATUS_OFFLINE);
        $catStatusSelect->addOption(Constants::STATUS_ONLINE, \_MA_WGEVENTS_STATUS_ONLINE);
        $form->addElement($catStatusSelect);
        // Form Text catWeight
        $catWeight = $this->getVar('cat_weight');
        if ($this->isNew()) {
            $catWeight = $categoriesHandler->getNextWeight();
        }
        $form->addElement(new \XoopsFormText(\_MA_WGEVENTS_WEIGHT, 'cat_weight', 50, 255, $catWeight));
        // Form Text Date Select catDatecreated
        $catDatecreated = $this->isNew() ? \time() : $this->getVar('cat_datecreated');
        $form->addElement(new \XoopsFormTextDateSelect(\_MA_WGEVENTS_DATECREATED, 'cat_datecreated', '', $catDatecreated));
        // Form Select User catSubmitter
        $uidCurrent = \is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->uid() : 0;
        $catSubmitter = $this->isNew() ? $uidCurrent : $this->getVar('cat_submitter');
        $form->addElement(new \XoopsFormSelectUser(\_MA_WGEVENTS_SUBMITTER, 'cat_submitter', false, $catSubmitter));
        /*
        // Permissions
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
            $groupsIdsApproveEvents = $grouppermHandler->getGroupIds('wgevents_approve_cat_events', $this->getVar('cat_id'), $GLOBALS['xoopsModule']->getVar('mid'));
            $groupsIdsApproveEvents[] = \array_values($groupsIdsApproveEvents);
            $groupsCanApproveEventsCheckbox = new \XoopsFormCheckBox(\_AM_WGEVENTS_PERMISSIONS_CAT_EVENTS_APPROVE, 'groups_approve_cat_events[]', $groupsIdsApproveEvents);
            $groupsIdsSubmitEvents = $grouppermHandler->getGroupIds('wgevents_submit_cat_events', $this->getVar('cat_id'), $GLOBALS['xoopsModule']->getVar('mid'));
            $groupsIdsSubmitEvents[] = \array_values($groupsIdsSubmitEvents);
            $groupsCanSubmitEventsCheckbox = new \XoopsFormCheckBox(\_AM_WGEVENTS_PERMISSIONS_CAT_EVENTS_SUBMIT, 'groups_submit_cat_events[]', $groupsIdsSubmitEvents);
            $groupsIdsViewEvents = $grouppermHandler->getGroupIds('wgevents_view_cat_events', $this->getVar('cat_id'), $GLOBALS['xoopsModule']->getVar('mid'));
            $groupsIdsViewEvents[] = \array_values($groupsIdsViewEvents);
            $groupsCanViewEventsCheckbox = new \XoopsFormCheckBox(\_AM_WGEVENTS_PERMISSIONS_CAT_EVENTS_VIEW, 'groups_view_cat_events[]', $groupsIdsViewEvents);
            $groupsIdsApproveRegs = $grouppermHandler->getGroupIds('wgevents_approve_cat_regs', $this->getVar('cat_id'), $GLOBALS['xoopsModule']->getVar('mid'));
            $groupsIdsApproveRegs[] = \array_values($groupsIdsApproveRegs);
            $groupsCanApproveRegsCheckbox = new \XoopsFormCheckBox(\_AM_WGEVENTS_PERMISSIONS_CAT_REGS_APPROVE, 'groups_approve_cat_regs[]', $groupsIdsApproveRegs);
            $groupsIdsSubmitRegs = $grouppermHandler->getGroupIds('wgevents_submit_cat_regs', $this->getVar('cat_id'), $GLOBALS['xoopsModule']->getVar('mid'));
            $groupsIdsSubmitRegs[] = \array_values($groupsIdsSubmitRegs);
            $groupsCanSubmitRegsCheckbox = new \XoopsFormCheckBox(\_AM_WGEVENTS_PERMISSIONS_CAT_REGS_SUBMIT, 'groups_submit_cat_regs[]', $groupsIdsSubmitRegs);
            $groupsIdsViewRegs = $grouppermHandler->getGroupIds('wgevents_view_cat_regs', $this->getVar('cat_id'), $GLOBALS['xoopsModule']->getVar('mid'));
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
        $ret['id']          = $this->getVar('cat_id');
        $categoriesHandler = $helper->getHandler('Categories');
        $categoriesObj = $categoriesHandler->get($this->getVar('cat_pid'));
        $ret['pid']         = $categoriesObj->getVar('cat_name');
        $ret['name']        = $this->getVar('cat_name');
        $ret['desc']        = $this->getVar('cat_desc', 'e');
        $editorMaxchar = $helper->getConfig('admin_maxchar');
        $ret['desc_short']  = $utility::truncateHtml($ret['desc'], $editorMaxchar);
        $ret['logo']        = $this->getVar('cat_logo');
        $ret['color']       = $this->getVar('cat_color');
        $ret['bordercolor'] = $this->getVar('cat_bordercolor');
        $ret['bgcolor']     = $this->getVar('cat_bgcolor');
        $ret['othercss']    = $this->getVar('cat_othercss');
        $status             = $this->getVar('cat_status');
        $ret['status']      = $status;
        $ret['status_text'] = Utility::getStatusText($status);
        $ret['weight']      = $this->getVar('cat_weight');
        $ret['datecreated'] = \formatTimestamp($this->getVar('cat_datecreated'), 's');
        $ret['submitter']   = \XoopsUser::getUnameFromId($this->getVar('cat_submitter'));
        return $ret;
    }

    /**
     * Returns an array representation of the object
     *
     * @return array
     */
    public function toArrayCategories()
    {
        $ret = [];
        $vars = $this->getVars();
        foreach (\array_keys($vars) as $var) {
            $ret[$var] = $this->getVar($var);
        }
        return $ret;
    }
}
