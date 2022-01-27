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
 * Class Object Questions
 */
class Questions extends \XoopsObject
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
        $this->initVar('que_id', \XOBJ_DTYPE_INT);
        $this->initVar('que_evid', \XOBJ_DTYPE_INT);
        $this->initVar('que_fdid', \XOBJ_DTYPE_INT);
        $this->initVar('que_type', \XOBJ_DTYPE_INT);
        $this->initVar('que_caption', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('que_desc', \XOBJ_DTYPE_OTHER);
        $this->initVar('que_values', \XOBJ_DTYPE_OTHER);
        $this->initVar('que_placeholder', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('que_required', \XOBJ_DTYPE_INT);
        $this->initVar('que_weight', \XOBJ_DTYPE_INT);
        $this->initVar('que_print', \XOBJ_DTYPE_INT);
        $this->initVar('que_datecreated', \XOBJ_DTYPE_INT);
        $this->initVar('que_submitter', \XOBJ_DTYPE_INT);
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
    public function getNewInsertedIdQuestions()
    {
        return $GLOBALS['xoopsDB']->getInsertId();
    }

    /**
     * @public function getForm
     * @param bool $action
     * @return \XoopsThemeForm
     */
    public function getFormQuestions($action = false)
    {
        $helper = \XoopsModules\Wgevents\Helper::getInstance();

        $eventsHandler = $helper->getHandler('Events');
        $questionsHandler = $helper->getHandler('Questions');

        if (!$action) {
            $action = $_SERVER['REQUEST_URI'];
        }
        $isAdmin = \is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->isAdmin($GLOBALS['xoopsModule']->mid()) : false;
        // Title
        $title = $this->isNew() ? \sprintf(\_MA_WGEVENTS_QUESTION_ADD) : \sprintf(\_MA_WGEVENTS_QUESTION_EDIT);
        // Get Theme Form
        \xoops_load('XoopsFormLoader');
        $form = new \XoopsThemeForm($title, 'form', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        // Form Table events
        $evId = $this->getVar('que_evid');
        $addEvidSelect = new \XoopsFormSelect(\_MA_WGEVENTS_QUESTION_EVID, 'que_evid', $evId);
        $addEvidSelect->addOptionArray($eventsHandler->getList());
        $form->addElement($addEvidSelect);
        // Form Select queType
        $queType = (int)$this->getVar('que_fdid') > 0 ? (int)$this->getVar('que_fdid') : 1; //set default for new as 'Infofield
        $enableValues = true;
        $enablePlaceholder = true;
        $queTypeSelect = new \XoopsFormSelect(\_MA_WGEVENTS_QUESTION_TYPE, 'que_type', $queType);
        $fieldsHandler = $helper->getHandler('Fields');
        $crFields = new \CriteriaCompo();
        $crFields->add(new \Criteria('fd_status', Constants::STATUS_ONLINE));
        $crFields->setSort('fd_weight');
        $crFields->setOrder('ASC');
        $fieldsCount = $fieldsHandler->getCount($crFields);
        if ($fieldsCount > 0) {
            $fieldsAll = $fieldsHandler->getAll($crFields);
            foreach (\array_keys($fieldsAll) as $i) {
                $queTypeSelect->addOption($i, $fieldsAll[$i]->getVar('fd_caption'));
                $form->addElement(new \XoopsFormHidden('que_caption_def[' . $i . ']', $fieldsAll[$i]->getVar('fd_caption')));
                $form->addElement(new \XoopsFormHidden('que_placeholder_def[' . $i . ']', $fieldsAll[$i]->getVar('fd_placeholder')));
                $form->addElement(new \XoopsFormHidden('que_required_def[' . $i . ']', $fieldsAll[$i]->getVar('fd_required')));
                $form->addElement(new \XoopsFormHidden('que_print_def[' . $i . ']', $fieldsAll[$i]->getVar('fd_print')));
                $form->addElement(new \XoopsFormHidden('add_display_values[' . $i . ']', $fieldsAll[$i]->getVar('fd_display_values')));
                $form->addElement(new \XoopsFormHidden('add_display_placeholder[' . $i . ']', $fieldsAll[$i]->getVar('fd_display_placeholder')));
                if ((int)$fieldsAll[$i]->getVar('fd_type') == $queType) {
                    $enableValues = (bool)$fieldsAll[$i]->getVar('fd_display_values');
                    $enablePlaceholder = (bool)$fieldsAll[$i]->getVar('fd_display_placeholder');
                }
            }
        }
        $queTypeSelect->setExtra(" onchange='fillInQuestions()' ");
        $form->addElement($queTypeSelect);
        // Form Text queCaption
        $queCaptionField = new \XoopsFormText(\_MA_WGEVENTS_QUESTION_CAPTION, 'que_caption', 50, 255, (string)$this->getVar('que_caption'));
        $queCaptionField->setDescription(\_MA_WGEVENTS_QUESTION_CAPTION_DESC);
        $form->addElement($queCaptionField, true);
        // Form Editor TextArea queDesc
        $queDescField = new \XoopsFormTextArea(\_MA_WGEVENTS_QUESTION_DESC, 'que_desc', $this->getVar('que_desc', 'e'), 3, 47);
        $queDescField->setDescription(\_MA_WGEVENTS_QUESTION_DESC_DESC);
        $form->addElement($queDescField);
        // Form Editor TextArea queValues
        $queValues = (string)$this->getVar('que_values');
        $queValuesText = '';
        if ('' != $queValues) {
            $queValuesText = \implode("\n", \unserialize($queValues));
        }
        $queValuesField = new \XoopsFormTextArea(\_MA_WGEVENTS_QUESTION_VALUE, 'que_values', $queValuesText, 5, 47);
        $queValuesField->setDescription(\_MA_WGEVENTS_QUESTION_VALUE_DESC);
        if (!$enableValues) {
            $queValuesField->setExtra('disabled="disabled"');
        }
        $form->addElement($queValuesField);
        // Form Text quePlaceholder
        $quePlaceholderField = new \XoopsFormText(\_MA_WGEVENTS_QUESTION_PLACEHOLDER, 'que_placeholder', 50, 255, $this->getVar('que_placeholder'));
        $quePlaceholderField->setDescription(\_MA_WGEVENTS_QUESTION_PLACEHOLDER_DESC);
        if (!$enablePlaceholder) {
            $quePlaceholderField->setExtra('disabled="disabled"');
        }
        $form->addElement($quePlaceholderField);
        // Form Radio Yes/No queRequired
        $queRequired = (int)$this->getVar('que_required');
        $queRequiredField = new \XoopsFormRadioYN(\_MA_WGEVENTS_QUESTION_REQUIRED, 'que_required', $queRequired);
        $queRequiredField->setDescription(\_MA_WGEVENTS_QUESTION_REQUIRED_DESC);
        $form->addElement($queRequiredField);
        // Form Radio Yes/No quePrint
        $quePrint = (int)$this->getVar('que_print');
        $quePrintField = new \XoopsFormRadioYN(\_MA_WGEVENTS_QUESTION_PRINT, 'que_print', $quePrint);
        $quePrintField->setDescription(\_MA_WGEVENTS_QUESTION_PRINT_DESC);
        $form->addElement($quePrintField);
        // Form Text queWeight
        $queWeight = $this->isNew() ? $questionsHandler->getNextWeight($evId) : $this->getVar('que_weight');
        if ($isAdmin) {
            $form->addElement(new \XoopsFormText(\_MA_WGEVENTS_WEIGHT, 'que_weight', 50, 255, $queWeight));
        } else {
            $form->addElement(new \XoopsFormHidden('que_weight', $queWeight));
        }
        // Form Text Date Select queDatecreated
        // Form Select User queSubmitter
        $queSubmitter = \is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->uid() : 0;
        if ($isAdmin) {
            // Form Text Date Select queDatecreated
            $queDatecreated = $this->isNew() ? \time() : $this->getVar('que_datecreated');
            $form->addElement(new \XoopsFormTextDateSelect(\_MA_WGEVENTS_DATECREATED, 'que_datecreated', '', $queDatecreated));
            $form->addElement(new \XoopsFormSelectUser(\_MA_WGEVENTS_SUBMITTER, 'que_submitter', false, $queSubmitter));
        } else {
            $form->addElement(new \XoopsFormHidden('que_datecreated_int', \time()));
            $form->addElement(new \XoopsFormHidden('que_submitter', $queSubmitter));
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
    public function getValuesQuestions($keys = null, $format = null, $maxDepth = null)
    {
        $helper  = \XoopsModules\Wgevents\Helper::getInstance();
        $utility = new \XoopsModules\Wgevents\Utility();
        $formelementsHandler = new \XoopsModules\Wgevents\Forms\FormelementsHandler();
        $fieldsAll = $formelementsHandler->getElementsCollection();
        $editorMaxchar = $helper->getConfig('admin_maxchar');
        $ret = $this->getValues($keys, $format, $maxDepth);
        $ret['id']             = $this->getVar('que_id');
        $eventsHandler = $helper->getHandler('Events');
        $eventsObj = $eventsHandler->get($this->getVar('que_evid'));
        $ret['evid']           = $eventsObj->getVar('ev_name');
        $ret['atid']           = $this->getVar('que_fdid');
        $type                  = $this->getVar('que_type');
        $ret['type']           = $type;
        $ret['type_text']      = $fieldsAll[$type];
        $ret['caption']        = $this->getVar('que_caption');
        $ret['desc']           = $this->getVar('que_desc', 'e');
        $ret['desc_short']     = $utility::truncateHtml($ret['desc'], $editorMaxchar);
        $ret['value']          = '';
        $ret['value_list']     = '';
        $queValues = $this->getVar('que_values');
        if ('' != $queValues) {
            $ret['value']       = \implode("\n", \unserialize($queValues));
            $ret['value_list']  = $utility::truncateHtml(\implode('<br>', \unserialize($queValues)));
        }
        $ret['placeholder']    = $this->getVar('que_placeholder');
        //$ret['values_short']   = $utility::truncateHtml($ret['value'], $editorMaxchar);
        $ret['required']       = (int)$this->getVar('que_required') > 0 ? _YES : _NO;
        $ret['print']          = (int)$this->getVar('que_print') > 0 ? _YES : _NO;
        $ret['weight']         = $this->getVar('que_weight');
        $ret['datecreated']    = \formatTimestamp($this->getVar('que_datecreated'), 's');
        $ret['submitter']      = \XoopsUser::getUnameFromId($this->getVar('que_submitter'));
        return $ret;
    }

    /**
     * Returns an array representation of the object
     *
     * @return array
     */
    public function toArrayQuestions()
    {
        $ret = [];
        $vars = $this->getVars();
        foreach (\array_keys($vars) as $var) {
            $ret[$var] = $this->getVar($var);
        }
        return $ret;
    }

}
