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
use XoopsModules\Wgevents\{
    Forms,
    Utility
};

\defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class Object Addtypes
 */
class Addtypes extends \XoopsObject
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
        $this->initVar('at_id', \XOBJ_DTYPE_INT);
        $this->initVar('at_caption', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('at_type', \XOBJ_DTYPE_INT);
        $this->initVar('at_desc', \XOBJ_DTYPE_OTHER);
        $this->initVar('at_values', \XOBJ_DTYPE_OTHER);
        $this->initVar('at_placeholder', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('at_required', \XOBJ_DTYPE_INT);
        $this->initVar('at_default', \XOBJ_DTYPE_INT);
        $this->initVar('at_print', \XOBJ_DTYPE_INT);
        $this->initVar('at_display_values', \XOBJ_DTYPE_INT);
        $this->initVar('at_display_placeholder', \XOBJ_DTYPE_INT);
        $this->initVar('at_weight', \XOBJ_DTYPE_INT);
        $this->initVar('at_custom', \XOBJ_DTYPE_INT);
        $this->initVar('at_status', \XOBJ_DTYPE_INT);
        $this->initVar('at_datecreated', \XOBJ_DTYPE_INT);
        $this->initVar('at_submitter', \XOBJ_DTYPE_INT);
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
    public function getNewInsertedIdAddtypes()
    {
        return $GLOBALS['xoopsDB']->getInsertId();
    }

    /**
     * @public function getForm
     * @param bool $action
     * @return \XoopsThemeForm
     */
    public function getFormAddtypes($action = false)
    {
        $helper = \XoopsModules\Wgevents\Helper::getInstance();

        $addtypesHandler = $helper->getHandler('Addtypes');

        if (!$action) {
            $action = $_SERVER['REQUEST_URI'];
        }
        $isAdmin = \is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->isAdmin($GLOBALS['xoopsModule']->mid()) : false;
        // Title
        $title = $this->isNew() ? \sprintf(\_AM_WGEVENTS_ADDTYPE_ADD) : \sprintf(\_AM_WGEVENTS_ADDTYPE_EDIT);
        // Get Theme Form
        \xoops_load('XoopsFormLoader');
        $form = new \XoopsThemeForm($title, 'form', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        // Form Text atCaption
        $form->addElement(new \XoopsFormText(\_AM_WGEVENTS_ADDTYPE_CAPTION, 'at_caption', 50, 255, $this->getVar('at_caption')), true);
        // Form Editor TextArea atDesc
        $form->addElement(new \XoopsFormTextArea(\_AM_WGEVENTS_ADDTYPE_DESC, 'at_desc', $this->getVar('at_desc', 'e'), 10, 47));
        // Form Select atType
        $formelementsHandler = new Forms\FormelementsHandler();
        $atTypeSelect = new \XoopsFormSelect(\_AM_WGEVENTS_ADDTYPE_TYPE, 'at_type', $this->getVar('at_type'), 5);
        $atTypeSelect->addOptionArray($formelementsHandler->getElementsCollection());
        $form->addElement($atTypeSelect);
        // Form Editor TextArea atValues
        if ($this->isNew()) {
            $atValuesText = '';
        } else {
            $atValues = (string)$this->getVar('at_values');
            if ('' != $atValues) {
                $atValuesText = \implode("\n", unserialize($atValues));
            }
        }
        $form->addElement(new \XoopsFormTextArea(\_AM_WGEVENTS_ADDTYPE_VALUE, 'at_values', $atValuesText, 5, 47));
        // Form Text atPlaceholder
        $form->addElement(new \XoopsFormText(\_AM_WGEVENTS_ADDTYPE_PLACEHOLDER, 'at_placeholder', 50, 255, $this->getVar('at_placeholder')));
        // Form Radio Yes/No atRequired
        $atRequired = $this->isNew() ? 0 : $this->getVar('at_required');
        $form->addElement(new \XoopsFormRadioYN(\_AM_WGEVENTS_ADDTYPE_REQUIRED, 'at_required', $atRequired));
        // Form Radio Yes/No atDefault
        $atDefault = $this->isNew() ? 0 : $this->getVar('at_default');
        $form->addElement(new \XoopsFormRadioYN(\_AM_WGEVENTS_ADDTYPE_DEFAULT, 'at_default', $atDefault));
        // Form Radio Yes/No atPrint
        $atPrint = $this->isNew() ? 0 : $this->getVar('at_print');
        $form->addElement(new \XoopsFormRadioYN(\_MA_WGEVENTS_PRINT, 'at_print', $atPrint));
        // Form Radio Yes/No atDisplayValues
        $atDisplayValues = $this->isNew() ? 1 : $this->getVar('at_display_values');
        $form->addElement(new \XoopsFormRadioYN(\_AM_WGEVENTS_ADDTYPE_DISPLAY_VALUES, 'at_display_values', $atDisplayValues));
        // Form Radio Yes/No atDisplayPlaceholder
        $atDisplayPlaceholder = $this->isNew() ? 1 : $this->getVar('at_display_placeholder');
        $form->addElement(new \XoopsFormRadioYN(\_AM_WGEVENTS_ADDTYPE_DISPLAY_PLACEHOLDER, 'at_display_placeholder', $atDisplayPlaceholder));
        // Form Text atWeight
        $atWeight = $this->isNew() ? $addtypesHandler->getNextWeight() : $this->getVar('at_weight');
        $form->addElement(new \XoopsFormText(\_MA_WGEVENTS_WEIGHT, 'at_weight', 50, 255, $atWeight));
        // Form Select Status atStatus
        $atStatus = $this->isNew() ? Constants::STATUS_OFFLINE : $this->getVar('at_status');
        $atStatusSelect = new \XoopsFormRadio(\_MA_WGEVENTS_STATUS, 'at_status', $atStatus);
        $atStatusSelect->addOption(Constants::STATUS_OFFLINE, \_MA_WGEVENTS_STATUS_OFFLINE);
        $atStatusSelect->addOption(Constants::STATUS_ONLINE, \_MA_WGEVENTS_STATUS_ONLINE);
        $form->addElement($atStatusSelect);
        // Form Text Date Select atypDatecreated
        $atypDatecreated = $this->isNew() ? \time() : $this->getVar('at_datecreated');
        $form->addElement(new \XoopsFormTextDateSelect(\_MA_WGEVENTS_DATECREATED, 'at_datecreated', '', $atypDatecreated));
        // Form Select User atSubmitter
        $uidCurrent = \is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->uid() : 0;
        $atSubmitter = $this->isNew() ? $uidCurrent : $this->getVar('at_submitter');
        $form->addElement(new \XoopsFormSelectUser(\_MA_WGEVENTS_SUBMITTER, 'at_submitter', false, $atSubmitter));
        // Form Text atCustom
        $atCustom = $this->isNew() ? 1 : $this->getVar('at_custom');
        $form->addElement(new \XoopsFormHidden('at_custom', $atCustom));
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
    public function getValuesAddtypes($keys = null, $format = null, $maxDepth = null)
    {
        $helper  = \XoopsModules\Wgevents\Helper::getInstance();
        $utility = new \XoopsModules\Wgevents\Utility();
        $editorMaxchar = $helper->getConfig('admin_maxchar');
        $formelementsHandler = new Forms\FormelementsHandler();
        $addtypesAll = $formelementsHandler->getElementsCollection();
        $ret = $this->getValues($keys, $format, $maxDepth);
        $ret['id']          = $this->getVar('at_id');
        $ret['name']        = $this->getVar('at_caption');
        $type               = $this->getVar('at_type');
        $ret['type']        = $type;
        $ret['type_text']   = $addtypesAll[$type];
        //$ret['desc']        = $this->getVar('at_desc', 'e');
        //$ret['desc_short']  = $utility::truncateHtml($ret['desc'], $editorMaxchar);
        $atValues = $this->getVar('at_values');
        $ret['value']       = '';
        $ret['value_list']   = '';
        if ('' != $atValues) {
            $ret['value']     = \implode("\n", unserialize($atValues));
            $ret['value_list'] = \implode('<br>', unserialize($atValues));
        }
        $ret['placeholder']         = $this->getVar('at_placeholder');
        $ret['required']            = (int)$this->getVar('at_required') > 0 ? _YES : _NO;
        $ret['default']             = (int)$this->getVar('at_default') > 0 ? _YES : _NO;
        $ret['print']               = (int)$this->getVar('at_print') > 0 ? _YES : _NO;
        $ret['display_values']      = (int)$this->getVar('at_display_values') > 0 ? _YES : _NO;
        $ret['display_placeholder'] = (int)$this->getVar('at_display_placeholder') > 0 ? _YES : _NO;
        $ret['weight']              = $this->getVar('at_weight');
        $ret['custom']              = $this->getVar('at_custom');
        $status                     = $this->getVar('at_status');
        $ret['status']              = $status;
        $ret['status_text']         = Utility::getStatusText($status);
        $ret['datecreated']         = \formatTimestamp($this->getVar('at_datecreated'), 's');
        $ret['submitter']           = \XoopsUser::getUnameFromId($this->getVar('at_submitter'));
        return $ret;
    }

    /**
     * Returns an array representation of the object
     *
     * @return array
     */
    public function toArrayAddtypes()
    {
        $ret = [];
        $vars = $this->getVars();
        foreach (\array_keys($vars) as $var) {
            $ret[$var] = $this->getVar($var);
        }
        return $ret;
    }

}
