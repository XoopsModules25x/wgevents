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
 * Class Object Question
 */
class Question extends \XoopsObject
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
     */
    public function __construct()
    {
        $this->initVar('id', \XOBJ_DTYPE_INT);
        $this->initVar('evid', \XOBJ_DTYPE_INT);
        $this->initVar('fdid', \XOBJ_DTYPE_INT);
        $this->initVar('type', \XOBJ_DTYPE_INT);
        $this->initVar('caption', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('desc', \XOBJ_DTYPE_OTHER);
        $this->initVar('values', \XOBJ_DTYPE_OTHER);
        $this->initVar('placeholder', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('required', \XOBJ_DTYPE_INT);
        $this->initVar('weight', \XOBJ_DTYPE_INT);
        $this->initVar('print', \XOBJ_DTYPE_INT);
        $this->initVar('datecreated', \XOBJ_DTYPE_INT);
        $this->initVar('submitter', \XOBJ_DTYPE_INT);
    }

    /**
     * @static function &getInstance
     *
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
        $helper = \XoopsModules\Wgevents\Helper::getInstance();

        $eventHandler = $helper->getHandler('Event');
        $questionHandler = $helper->getHandler('Question');

        if (!$action) {
            $action = $_SERVER['REQUEST_URI'];
        }
        $isAdmin = (\is_object($GLOBALS['xoopsUser']) && \is_object($GLOBALS['xoopsModule'])) && $GLOBALS['xoopsUser']->isAdmin($GLOBALS['xoopsModule']->mid());
        // Title
        $title = $this->isNew() ? \_MA_WGEVENTS_QUESTION_ADD : \_MA_WGEVENTS_QUESTION_EDIT;
        // Get Theme Form
        \xoops_load('XoopsFormLoader');
        $form = new \XoopsThemeForm($title, 'formQuestion', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        // Form Table events
        $evId = ($this->getVar('evid')) ?? 0;
        $addEvidSelect = new \XoopsFormSelect(\_MA_WGEVENTS_QUESTION_EVID, 'evid', $evId);
        $addEvidSelect->addOptionArray($eventHandler->getList());
        $form->addElement($addEvidSelect);
        // Form Select queType
        $queType = (int)$this->getVar('fdid') > 0 ? (int)$this->getVar('fdid') : 1; //set default for new as 'Infofield
        $enableValues = true;
        $enablePlaceholder = true;
        $queTypeSelect = new \XoopsFormSelect(\_MA_WGEVENTS_QUESTION_TYPE, 'type', $queType);
        $fieldHandler = $helper->getHandler('Field');
        $fieldObj = $fieldHandler->get($queType);
        $fieldType = $fieldObj->getVar('type');

        $crField = new \CriteriaCompo();
        $crField->add(new \Criteria('status', Constants::STATUS_ONLINE));
        $crField->setSort('weight');
        $crField->setOrder('ASC');
        $fieldsCount = $fieldHandler->getCount($crField);
        if ($fieldsCount > 0) {
            $fieldsAll = $fieldHandler->getAll($crField);
            foreach (\array_keys($fieldsAll) as $i) {
                $queTypeSelect->addOption($i, $fieldsAll[$i]->getVar('caption'));
                $form->addElement(new \XoopsFormHidden('caption_def[' . $i . ']', $fieldsAll[$i]->getVar('caption')));
                $form->addElement(new \XoopsFormHidden('placeholder_def[' . $i . ']', $fieldsAll[$i]->getVar('placeholder')));
                $form->addElement(new \XoopsFormHidden('required_def[' . $i . ']', $fieldsAll[$i]->getVar('required')));
                $form->addElement(new \XoopsFormHidden('print_def[' . $i . ']', $fieldsAll[$i]->getVar('print')));
                $form->addElement(new \XoopsFormHidden('display_desc[' . $i . ']', $fieldsAll[$i]->getVar('display_desc')));
                $form->addElement(new \XoopsFormHidden('display_values[' . $i . ']', $fieldsAll[$i]->getVar('display_values')));
                $form->addElement(new \XoopsFormHidden('display_placeholder[' . $i . ']', $fieldsAll[$i]->getVar('display_placeholder')));
                if ((int)$fieldsAll[$i]->getVar('type') == $fieldType) {
                    $enableDesc        = (bool)$fieldsAll[$i]->getVar('display_desc');
                    $enableValues      = (bool)$fieldsAll[$i]->getVar('display_values');
                    $enablePlaceholder = (bool)$fieldsAll[$i]->getVar('display_placeholder');
                }
            }
        }
        $queTypeSelect->setExtra(" onchange='fillInQuestions()' ");
        $form->addElement($queTypeSelect);
        // Form Text queCaption
        $queCaptionField = new \XoopsFormText(\_MA_WGEVENTS_QUESTION_CAPTION, 'caption', 50, 255, (string)$this->getVar('caption'));
        $queCaptionField->setDescription(\_MA_WGEVENTS_QUESTION_CAPTION_DESC);
        $form->addElement($queCaptionField, true);
        // Form Editor TextArea queDesc
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
        $queDescField = new \XoopsFormEditor(\_MA_WGEVENTS_QUESTION_DESC, 'desc', $editorConfigs);
        //$queDescField = new \XoopsFormTextArea(\_MA_WGEVENTS_QUESTION_DESC, 'desc', $this->getVar('desc', 'e'), 3, 47);
        $queDescField->setDescription(\_MA_WGEVENTS_QUESTION_DESC_DESC);
        if (!$enableDesc) {
            $queDescField->setExtra('disabled="disabled"');
        }
        $form->addElement($queDescField);
        // Form Editor TextArea queValues
        $queValues = (string)$this->getVar('values');
        $queValuesText = '';
        if ('' !== $queValues) {
            $queValuesText = \implode("\n", \unserialize($queValues, ['allowed_classes' => false]));
        }
        $queValuesField = new \XoopsFormTextArea(\_MA_WGEVENTS_QUESTION_VALUE, 'values', $queValuesText, 5, 47);
        $queValuesField->setDescription(\_MA_WGEVENTS_QUESTION_VALUE_DESC);
        if (!$enableValues) {
            $queValuesField->setExtra('disabled="disabled"');
        }
        $form->addElement($queValuesField);
        // Form Text quePlaceholder
        $quePlaceholderField = new \XoopsFormText(\_MA_WGEVENTS_QUESTION_PLACEHOLDER, 'placeholder', 50, 255, $this->getVar('placeholder'));
        $quePlaceholderField->setDescription(\_MA_WGEVENTS_QUESTION_PLACEHOLDER_DESC);
        if (!$enablePlaceholder) {
            $quePlaceholderField->setExtra('disabled="disabled"');
        }
        $form->addElement($quePlaceholderField);
        // Form Radio Yes/No queRequired
        $queRequired = (int)$this->getVar('required');
        $queRequiredField = new \XoopsFormRadioYN(\_MA_WGEVENTS_QUESTION_REQUIRED, 'required', $queRequired);
        $queRequiredField->setDescription(\_MA_WGEVENTS_QUESTION_REQUIRED_DESC);
        $form->addElement($queRequiredField);
        // Form Radio Yes/No quePrint
        $quePrint = (int)$this->getVar('print');
        $quePrintField = new \XoopsFormRadioYN(\_MA_WGEVENTS_QUESTION_PRINT, 'print', $quePrint);
        $quePrintField->setDescription(\_MA_WGEVENTS_QUESTION_PRINT_DESC);
        $form->addElement($quePrintField);
        // Form Text queWeight
        $queWeight = $this->isNew() ? $questionHandler->getNextWeight($evId) : $this->getVar('weight');
        if ($isAdmin) {
            $form->addElement(new \XoopsFormText(\_MA_WGEVENTS_WEIGHT, 'weight', 50, 255, $queWeight));
        } else {
            $form->addElement(new \XoopsFormHidden('weight', $queWeight));
        }
        // Form Text Date Select queDatecreated
        // Form Select User queSubmitter
        $queSubmitter = \is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->uid() : 0;
        if ($isAdmin) {
            // Form Text Date Select queDatecreated
            $queDatecreated = $this->isNew() ? \time() : $this->getVar('datecreated');
            $form->addElement(new \XoopsFormTextDateSelect(\_MA_WGEVENTS_DATECREATED, 'datecreated', '', $queDatecreated));
            $form->addElement(new \XoopsFormSelectUser(\_MA_WGEVENTS_SUBMITTER, 'submitter', false, $queSubmitter));
        } else {
            $form->addElement(new \XoopsFormHidden('datecreated_int', \time()));
            $form->addElement(new \XoopsFormHidden('submitter', $queSubmitter));
        }
        // To Save
        $form->addElement(new \XoopsFormHidden('op', 'save'));
        $form->addElement(new \XoopsFormHidden('start', $this->start));
        $form->addElement(new \XoopsFormHidden('limit', $this->limit));
        $form->addElement(new \XoopsFormButtonTray('submit', \_SUBMIT, 'submit', '', false));
        return $form;
    }

    /**
     * Get Values
     * @param null $keys
     * @param null $format
     * @param null $maxDepth
     * @return array
     */
    public function getValuesQuestions($keys = null, $format = null, $maxDepth = null)
    {
        $helper  = \XoopsModules\Wgevents\Helper::getInstance();
        $utility = new \XoopsModules\Wgevents\Utility();
        $formelementsHandler = new \XoopsModules\Wgevents\Forms\FormelementsHandler();
        $fieldsAll = $formelementsHandler->getElementsCollection();
        $editorMaxchar = $helper->getConfig('admin_maxchar');
        $ret = $this->getValues($keys, $format, $maxDepth);
        $eventHandler = $helper->getHandler('Event');
        $eventObj = $eventHandler->get($this->getVar('evid'));
        $ret['eventname']  = $eventObj->getVar('name');
        $ret['type_text']  = $fieldsAll[$this->getVar('type')];
        $ret['desc_text']  = $this->getVar('desc', 'e');
        $ret['desc_short'] = $utility::truncateHtml($ret['desc_text'], $editorMaxchar);
        $ret['value_text'] = '';
        $ret['value_list'] = '';
        $queValues = (string)$this->getVar('values');
        if ('' !== $queValues) {
            $ret['value_text'] = \implode("\n", \unserialize($queValues, ['allowed_classes' => false]));
            $ret['value_list'] = $utility::truncateHtml(\implode('<br>', \unserialize($queValues, ['allowed_classes' => false])));
        }
        $ret['required_text']    = (int)$this->getVar('required') > 0 ? _YES : _NO;
        $ret['print_text']       = (int)$this->getVar('print') > 0 ? _YES : _NO;
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
