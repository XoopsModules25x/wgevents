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

\defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class Object Additionals
 */
class Additionals extends \XoopsObject
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
        $this->initVar('add_id', \XOBJ_DTYPE_INT);
        $this->initVar('add_evid', \XOBJ_DTYPE_INT);
        $this->initVar('add_atid', \XOBJ_DTYPE_INT);
        $this->initVar('add_type', \XOBJ_DTYPE_INT);
        $this->initVar('add_caption', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('add_desc', \XOBJ_DTYPE_OTHER);
        $this->initVar('add_values', \XOBJ_DTYPE_OTHER);
        $this->initVar('add_placeholder', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('add_required', \XOBJ_DTYPE_INT);
        $this->initVar('add_weight', \XOBJ_DTYPE_INT);
        $this->initVar('add_print', \XOBJ_DTYPE_INT);
        $this->initVar('add_datecreated', \XOBJ_DTYPE_INT);
        $this->initVar('add_submitter', \XOBJ_DTYPE_INT);
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
    public function getNewInsertedIdAdditionals()
    {
        return $GLOBALS['xoopsDB']->getInsertId();
    }

    /**
     * @public function getForm
     * @param bool $action
     * @return \XoopsThemeForm
     */
    public function getFormAdditionals($action = false)
    {
        $helper = \XoopsModules\Wgevents\Helper::getInstance();

        $eventsHandler = $helper->getHandler('Events');
        $additionalsHandler = $helper->getHandler('Additionals');

        if (!$action) {
            $action = $_SERVER['REQUEST_URI'];
        }
        $isAdmin = \is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->isAdmin($GLOBALS['xoopsModule']->mid()) : false;
        // Title
        $title = $this->isNew() ? \sprintf(\_MA_WGEVENTS_ADDITIONAL_ADD) : \sprintf(\_MA_WGEVENTS_ADDITIONAL_EDIT);
        // Get Theme Form
        \xoops_load('XoopsFormLoader');
        $form = new \XoopsThemeForm($title, 'form', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        // Form Table events
        $evId = $this->getVar('add_evid');
        $addEvidSelect = new \XoopsFormSelect(\_MA_WGEVENTS_ADDITIONAL_EVID, 'add_evid', $evId);
        $addEvidSelect->addOptionArray($eventsHandler->getList());
        $form->addElement($addEvidSelect);
        // Form Select addType
        $addType = (int)$this->getVar('add_atid') > 0 ? (int)$this->getVar('add_atid') : 1; //set default for new as 'Infofield
        $enableValues = true;
        $enablePlaceholder = true;
        $addTypeSelect = new \XoopsFormSelect(\_MA_WGEVENTS_ADDITIONAL_TYPE, 'add_type', $addType);
        $addtypesHandler = $helper->getHandler('Addtypes');
        $crAddtypes = new \CriteriaCompo();
        $crAddtypes->add(new \Criteria('at_status', Constants::STATUS_ONLINE));
        $crAddtypes->setSort('at_weight');
        $crAddtypes->setOrder('ASC');
        $addtypesCount = $addtypesHandler->getCount($crAddtypes);
        if ($addtypesCount > 0) {
            $addtypesAll = $addtypesHandler->getAll($crAddtypes);
            foreach (\array_keys($addtypesAll) as $i) {
                $addTypeSelect->addOption($i, $addtypesAll[$i]->getVar('at_caption'));
                $form->addElement(new \XoopsFormHidden('add_caption_def[' . $i . ']', $addtypesAll[$i]->getVar('at_caption')));
                $form->addElement(new \XoopsFormHidden('add_placeholder_def[' . $i . ']', $addtypesAll[$i]->getVar('at_placeholder')));
                $form->addElement(new \XoopsFormHidden('add_required_def[' . $i . ']', $addtypesAll[$i]->getVar('at_required')));
                $form->addElement(new \XoopsFormHidden('add_print_def[' . $i . ']', $addtypesAll[$i]->getVar('at_print')));
                $form->addElement(new \XoopsFormHidden('add_display_values[' . $i . ']', $addtypesAll[$i]->getVar('at_display_values')));
                $form->addElement(new \XoopsFormHidden('add_display_placeholder[' . $i . ']', $addtypesAll[$i]->getVar('at_display_placeholder')));
                if ((int)$addtypesAll[$i]->getVar('at_type') == $addType) {
                    $enableValues = (bool)$addtypesAll[$i]->getVar('at_display_values');
                    $enablePlaceholder = (bool)$addtypesAll[$i]->getVar('at_display_placeholder');
                }
            }
        }
        $addTypeSelect->setExtra(" onchange='fillInAdditionals()' ");
        $form->addElement($addTypeSelect);
        // Form Text addCaption
        $addCaptionField = new \XoopsFormText(\_MA_WGEVENTS_ADDITIONAL_CAPTION, 'add_caption', 50, 255, (string)$this->getVar('add_caption'));
        $addCaptionField->setDescription(\_MA_WGEVENTS_ADDITIONAL_CAPTION_DESC);
        $form->addElement($addCaptionField, true);
        // Form Editor TextArea addDesc
        $addDescField = new \XoopsFormTextArea(\_MA_WGEVENTS_ADDITIONAL_DESC, 'add_desc', $this->getVar('add_desc', 'e'), 3, 47);
        $addDescField->setDescription(\_MA_WGEVENTS_ADDITIONAL_DESC_DESC);
        $form->addElement($addDescField);
        // Form Editor TextArea $addValues
        $addValues = (string)$this->getVar('add_values');
        $addValuesText = '';
        if ('' != $addValues) {
            $addValuesText = \implode("\n", \unserialize($addValues));
        }
        $addValuesField = new \XoopsFormTextArea(\_MA_WGEVENTS_ADDITIONAL_VALUE, 'add_values', $addValuesText, 5, 47);
        $addValuesField->setDescription(\_MA_WGEVENTS_ADDITIONAL_VALUE_DESC);
        if (!$enableValues) {
            $addValuesField->setExtra('disabled="disabled"');
        }
        $form->addElement($addValuesField);
        // Form Text addPlaceholder
        $addPlaceholderField = new \XoopsFormText(\_MA_WGEVENTS_ADDITIONAL_PLACEHOLDER, 'add_placeholder', 50, 255, $this->getVar('add_placeholder'));
        $addPlaceholderField->setDescription(\_MA_WGEVENTS_ADDITIONAL_PLACEHOLDER_DESC);
        if (!$enablePlaceholder) {
            $addPlaceholderField->setExtra('disabled="disabled"');
        }
        $form->addElement($addPlaceholderField);
        // Form Radio Yes/No addRequired
        $addRequired = (int)$this->getVar('add_required');
        $addRequiredField = new \XoopsFormRadioYN(\_MA_WGEVENTS_ADDITIONAL_REQUIRED, 'add_required', $addRequired);
        $addRequiredField->setDescription(\_MA_WGEVENTS_ADDITIONAL_REQUIRED_DESC);
        $form->addElement($addRequiredField);
        // Form Radio Yes/No addPrint
        $addPrint = (int)$this->getVar('add_print');
        $addPrintField = new \XoopsFormRadioYN(\_MA_WGEVENTS_ADDITIONAL_PRINT, 'add_print', $addPrint);
        $addPrintField->setDescription(\_MA_WGEVENTS_ADDITIONAL_PRINT_DESC);
        $form->addElement($addPrintField);
        // Form Text addWeight
        $addWeight = $this->isNew() ? $additionalsHandler->getNextWeight($evId) : $this->getVar('add_weight');
        if ($isAdmin) {
            $form->addElement(new \XoopsFormText(\_MA_WGEVENTS_WEIGHT, 'add_weight', 50, 255, $addWeight));
        } else {
            $form->addElement(new \XoopsFormHidden('add_weight', $addWeight));
        }
        // Form Text Date Select addDatecreated
        // Form Select User addSubmitter
        $addSubmitter = \is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->uid() : 0;
        if ($isAdmin) {
            // Form Text Date Select addDatecreated
            $addDatecreated = $this->isNew() ? \time() : $this->getVar('add_datecreated');
            $form->addElement(new \XoopsFormTextDateSelect(\_MA_WGEVENTS_DATECREATED, 'add_datecreated', '', $addDatecreated));
            $form->addElement(new \XoopsFormSelectUser(\_MA_WGEVENTS_SUBMITTER, 'add_submitter', false, $addSubmitter));
        } else {
            $form->addElement(new \XoopsFormHidden('add_datecreated_int', \time()));
            $form->addElement(new \XoopsFormHidden('add_submitter', $addSubmitter));
        }
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
    public function getValuesAdditionals($keys = null, $format = null, $maxDepth = null)
    {
        $helper  = \XoopsModules\Wgevents\Helper::getInstance();
        $utility = new \XoopsModules\Wgevents\Utility();
        $formelementsHandler = new \XoopsModules\Wgevents\Forms\FormelementsHandler();
        $addtypesAll = $formelementsHandler->getElementsCollection();
        $editorMaxchar = $helper->getConfig('admin_maxchar');
        $ret = $this->getValues($keys, $format, $maxDepth);
        $ret['id']             = $this->getVar('add_id');
        $eventsHandler = $helper->getHandler('Events');
        $eventsObj = $eventsHandler->get($this->getVar('add_evid'));
        $ret['evid']           = $eventsObj->getVar('ev_name');
        $ret['atid']           = $this->getVar('add_atid');
        $type                  = $this->getVar('add_type');
        $ret['type']           = $type;
        $ret['type_text']      = $addtypesAll[$type];
        $ret['caption']        = $this->getVar('add_caption');
        $ret['desc']           = $this->getVar('add_desc', 'e');
        $ret['desc_short']     = $utility::truncateHtml($ret['desc'], $editorMaxchar);
        $ret['value']          = '';
        $ret['value_list']     = '';
        $addValues = $this->getVar('add_values');
        if ('' != $addValues) {
            $ret['value']       = \implode("\n", unserialize($addValues));
            $ret['value_list']  = $utility::truncateHtml(\implode('<br>', unserialize($addValues)));
        }
        $ret['placeholder']    = $this->getVar('add_placeholder');
        //$ret['values_short']   = $utility::truncateHtml($ret['value'], $editorMaxchar);
        $ret['required']       = (int)$this->getVar('add_required') > 0 ? _YES : _NO;
        $ret['print']          = (int)$this->getVar('add_print') > 0 ? _YES : _NO;
        $ret['weight']         = $this->getVar('add_weight');
        $ret['datecreated']    = \formatTimestamp($this->getVar('add_datecreated'), 's');
        $ret['submitter']      = \XoopsUser::getUnameFromId($this->getVar('add_submitter'));
        return $ret;
    }

    /**
     * Returns an array representation of the object
     *
     * @return array
     */
    public function toArrayAdditionals()
    {
        $ret = [];
        $vars = $this->getVars();
        foreach (\array_keys($vars) as $var) {
            $ret[$var] = $this->getVar($var);
        }
        return $ret;
    }

}
