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
 * Class Object Fields
 */
class Fields extends \XoopsObject
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
        $this->initVar('caption', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('type', \XOBJ_DTYPE_INT);
        $this->initVar('desc', \XOBJ_DTYPE_OTHER);
        $this->initVar('values', \XOBJ_DTYPE_OTHER);
        $this->initVar('placeholder', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('required', \XOBJ_DTYPE_INT);
        $this->initVar('default', \XOBJ_DTYPE_INT);
        $this->initVar('print', \XOBJ_DTYPE_INT);
        $this->initVar('display_values', \XOBJ_DTYPE_INT);
        $this->initVar('display_placeholder', \XOBJ_DTYPE_INT);
        $this->initVar('weight', \XOBJ_DTYPE_INT);
        $this->initVar('custom', \XOBJ_DTYPE_INT);
        $this->initVar('status', \XOBJ_DTYPE_INT);
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
    public function getNewInsertedIdFields()
    {
        return $GLOBALS['xoopsDB']->getInsertId();
    }

    /**
     * @public function getForm
     * @param bool $action
     * @return \XoopsThemeForm
     */
    public function getFormFields($action = false)
    {
        $helper = \XoopsModules\Wgevents\Helper::getInstance();

        $fieldsHandler = $helper->getHandler('Fields');

        if (!$action) {
            $action = $_SERVER['REQUEST_URI'];
        }
        $isAdmin = \is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->isAdmin($GLOBALS['xoopsModule']->mid()) : false;
        // Title
        $title = $this->isNew() ? \sprintf(\_AM_WGEVENTS_FIELD_ADD) : \sprintf(\_AM_WGEVENTS_FIELD_EDIT);
        // Get Theme Form
        \xoops_load('XoopsFormLoader');
        $form = new \XoopsThemeForm($title, 'form', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        // Form Text fdCaption
        $form->addElement(new \XoopsFormText(\_AM_WGEVENTS_FIELD_CAPTION, 'caption', 50, 255, $this->getVar('caption')), true);
        // Form Editor TextArea fdDesc
        $form->addElement(new \XoopsFormTextArea(\_AM_WGEVENTS_FIELD_DESC, 'desc', $this->getVar('desc', 'e'), 10, 47));
        // Form Select fdType
        $formelementsHandler = new Forms\FormelementsHandler();
        $atTypeSelect = new \XoopsFormSelect(\_AM_WGEVENTS_FIELD_TYPE, 'type', $this->getVar('type'), 5);
        $atTypeSelect->addOptionArray($formelementsHandler->getElementsCollection());
        $form->addElement($atTypeSelect);
        // Form Editor TextArea fdValues
        $atValuesText = '';
        if (!$this->isNew()) {
            $fdValues = (string)$this->getVar('values');
            if ('' != $fdValues) {
                $atValuesText = \implode("\n", unserialize($fdValues));
            }
        }
        $form->addElement(new \XoopsFormTextArea(\_AM_WGEVENTS_FIELD_VALUE, 'values', $atValuesText, 5, 47));
        // Form Text fdPlaceholder
        $form->addElement(new \XoopsFormText(\_AM_WGEVENTS_FIELD_PLACEHOLDER, 'placeholder', 50, 255, $this->getVar('placeholder')));
        // Form Radio Yes/No fdRequired
        $fdRequired = $this->isNew() ? 0 : $this->getVar('required');
        $form->addElement(new \XoopsFormRadioYN(\_AM_WGEVENTS_FIELD_REQUIRED, 'required', $fdRequired));
        // Form Radio Yes/No fdDefault
        $fdDefault = $this->isNew() ? 0 : $this->getVar('default');
        $form->addElement(new \XoopsFormRadioYN(\_AM_WGEVENTS_FIELD_DEFAULT, 'default', $fdDefault));
        // Form Radio Yes/No fdPrint
        $fdPrint = $this->isNew() ? 0 : $this->getVar('print');
        $form->addElement(new \XoopsFormRadioYN(\_MA_WGEVENTS_PRINT, 'print', $fdPrint));
        // Form Radio Yes/No fdDisplayValues
        $fdDisplayValues = $this->isNew() ? 1 : $this->getVar('display_values');
        $form->addElement(new \XoopsFormRadioYN(\_AM_WGEVENTS_FIELD_DISPLAY_VALUES, 'display_values', $fdDisplayValues));
        // Form Radio Yes/No fdDisplayPlaceholder
        $fdDisplayPlaceholder = $this->isNew() ? 1 : $this->getVar('display_placeholder');
        $form->addElement(new \XoopsFormRadioYN(\_AM_WGEVENTS_FIELD_DISPLAY_PLACEHOLDER, 'display_placeholder', $fdDisplayPlaceholder));
        // Form Text fdWeight
        $fdWeight = $this->isNew() ? $fieldsHandler->getNextWeight() : $this->getVar('weight');
        $form->addElement(new \XoopsFormText(\_MA_WGEVENTS_WEIGHT, 'weight', 50, 255, $fdWeight));
        // Form Select Status fdStatus
        $fdStatus = $this->isNew() ? Constants::STATUS_OFFLINE : $this->getVar('status');
        $atStatusSelect = new \XoopsFormRadio(\_MA_WGEVENTS_STATUS, 'status', $fdStatus);
        $atStatusSelect->addOption(Constants::STATUS_OFFLINE, \_MA_WGEVENTS_STATUS_OFFLINE);
        $atStatusSelect->addOption(Constants::STATUS_ONLINE, \_MA_WGEVENTS_STATUS_ONLINE);
        $form->addElement($atStatusSelect);
        // Form Text Date Select fdDatecreated
        $fdDatecreated = $this->isNew() ? \time() : $this->getVar('datecreated');
        $form->addElement(new \XoopsFormTextDateSelect(\_MA_WGEVENTS_DATECREATED, 'datecreated', '', $fdDatecreated));
        // Form Select User fdSubmitter
        $uidCurrent = \is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->uid() : 0;
        $fdSubmitter = $this->isNew() ? $uidCurrent : $this->getVar('submitter');
        $form->addElement(new \XoopsFormSelectUser(\_MA_WGEVENTS_SUBMITTER, 'submitter', false, $fdSubmitter));
        // Form Text fdCustom
        $fdCustom = $this->isNew() ? 1 : $this->getVar('custom');
        $form->addElement(new \XoopsFormHidden('custom', $fdCustom));
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
    public function getValuesFields($keys = null, $format = null, $maxDepth = null)
    {
        $helper  = \XoopsModules\Wgevents\Helper::getInstance();
        $utility = new \XoopsModules\Wgevents\Utility();
        $editorMaxchar = $helper->getConfig('admin_maxchar');
        $formelementsHandler = new Forms\FormelementsHandler();
        $fieldsAll = $formelementsHandler->getElementsCollection();
        $ret = $this->getValues($keys, $format, $maxDepth);
        $ret['type_text']   = $fieldsAll[$this->getVar('type')];
        $fdValues = $this->getVar('values');
        $ret['value_text']       = '';
        $ret['value_list']   = '';
        if ('' != $fdValues) {
            $ret['value_text']     = \implode("\n", unserialize($fdValues));
            $ret['value_list'] = \implode('<br>', unserialize($fdValues));
        }
        $ret['required_text']            = (int)$this->getVar('required') > 0 ? _YES : _NO;
        $ret['default_text']             = (int)$this->getVar('default') > 0 ? _YES : _NO;
        $ret['print_text']               = (int)$this->getVar('print') > 0 ? _YES : _NO;
        $ret['display_values_text']      = (int)$this->getVar('display_values') > 0 ? _YES : _NO;
        $ret['display_placeholder_text'] = (int)$this->getVar('display_placeholder') > 0 ? _YES : _NO;
        $ret['status_text']              = Utility::getStatusText($this->getVar('status'));
        $ret['datecreated_text']         = \formatTimestamp($this->getVar('datecreated'), 's');
        $ret['submitter_text']           = \XoopsUser::getUnameFromId($this->getVar('submitter'));
        return $ret;
    }

    /**
     * Returns an array representation of the object
     *
     * @return array
     */
    public function toArrayFields()
    {
        $ret = [];
        $vars = $this->getVars();
        foreach (\array_keys($vars) as $var) {
            $ret[$var] = $this->getVar($var);
        }
        return $ret;
    }

}
