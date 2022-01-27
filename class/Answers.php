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
 * Class Object Answers
 */
class Answers extends \XoopsObject
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
        $this->initVar('ans_id', \XOBJ_DTYPE_INT);
        $this->initVar('ans_regid', \XOBJ_DTYPE_INT);
        $this->initVar('ans_queid', \XOBJ_DTYPE_INT);
        $this->initVar('ans_evid', \XOBJ_DTYPE_INT);
        $this->initVar('ans_text', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('ans_datecreated', \XOBJ_DTYPE_INT);
        $this->initVar('ans_submitter', \XOBJ_DTYPE_INT);
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
    public function getNewInsertedIdAnswers()
    {
        return $GLOBALS['xoopsDB']->getInsertId();
    }

    /**
     * @public function getForm
     * @param bool $action
     * @return \XoopsThemeForm
     */
    public function getFormAnswers($action = false)
    {
        $helper = \XoopsModules\Wgevents\Helper::getInstance();
        if (!$action) {
            $action = $_SERVER['REQUEST_URI'];
        }
        $isAdmin = \is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->isAdmin($GLOBALS['xoopsModule']->mid()) : false;
        // Title
        $title = $this->isNew() ? \sprintf(\_MA_WGEVENTS_ANSWER_ADD) : \sprintf(\_MA_WGEVENTS_ANSWER_EDIT);
        // Get Theme Form
        \xoops_load('XoopsFormLoader');
        $form = new \XoopsThemeForm($title, 'form', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        // Form Table registrations
        $registrationsHandler = $helper->getHandler('Registrations');
        $ansResidSelect = new \XoopsFormSelect(\_MA_WGEVENTS_ANSWER_REGID, 'ans_regid', $this->getVar('ans_regid'));
        $ansResidSelect->addOptionArray($registrationsHandler->getList());
        $form->addElement($ansResidSelect);
        // Form Table questions
        $questionsHandler = $helper->getHandler('Questions');
        $ansAddidSelect = new \XoopsFormSelect(\_MA_WGEVENTS_ANSWER_ADDID, 'ans_queid', $this->getVar('ans_queid'));
        $crQuestions = new \CriteriaCompo();
        $crQuestions->add(new \Criteria('que_evid', $this->getVar('ans_evid')));
        $questionsCount = $questionsHandler->getCount($crQuestions);
        // Table view questions
        if ($questionsCount > 0) {
            $crQuestions->setSort('que_weight ASC, que_id');
            $crQuestions->setOrder('DESC');
            $questionsAll = $questionsHandler->getAll($crQuestions);
            foreach (\array_keys($questionsAll) as $i) {
                $ansAddidSelect->addOption($questionsAll[$i]->getVar('que_id'), $questionsAll[$i]->getVar('que_caption'));
            }
        }
        $form->addElement($ansAddidSelect);
        // Form Table events
        $eventsHandler = $helper->getHandler('Events');
        $ansEvidSelect = new \XoopsFormSelect(\_MA_WGEVENTS_ANSWER_EVID, 'ans_evid', $this->getVar('ans_evid'));
        $ansEvidSelect->addOptionArray($eventsHandler->getList());
        $form->addElement($ansEvidSelect);
        // Form Text ansText
        $form->addElement(new \XoopsFormText(\_MA_WGEVENTS_ANSWER_TEXT, 'ans_text', 50, 255, $this->getVar('ans_text')));
        // Form Text Date Select ansDatecreated
        $ansDatecreated = $this->isNew() ? \time() : $this->getVar('ans_datecreated');
        $form->addElement(new \XoopsFormTextDateSelect(\_MA_WGEVENTS_DATECREATED, 'ans_datecreated', '', $ansDatecreated));
        // Form Select User ansSubmitter
        $uidCurrent = \is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->uid() : 0;
        $ansSubmitter = $this->isNew() ? $uidCurrent : $this->getVar('ans_submitter');
        $form->addElement(new \XoopsFormSelectUser(\_MA_WGEVENTS_SUBMITTER, 'ans_submitter', false, $ansSubmitter));
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
    public function getValuesAnswers($keys = null, $format = null, $maxDepth = null)
    {
        $helper  = \XoopsModules\Wgevents\Helper::getInstance();
        $ret = $this->getValues($keys, $format, $maxDepth);
        $ret['id']          = $this->getVar('ans_id');
        $ret['regid']       = $this->getVar('ans_regid');
        $questionsHandler = $helper->getHandler('Questions');
        $questionsObj = $questionsHandler->get($this->getVar('ans_queid'));
        $queCaption = '';
        if (\is_object($questionsObj)) {
            $queCaption = $questionsObj->getVar('que_caption');
        }
        $ret['addid']       = $queCaption;
        $eventsHandler = $helper->getHandler('Events');
        $eventsObj = $eventsHandler->get($this->getVar('ans_evid'));
        $evName = 'invalid event';
        if (\is_object($eventsObj)) {
            $evName = $eventsObj->getVar('ev_name');
        }
        $ret['evid']        = $evName;
        $ret['text']        = $this->getVar('ans_text');
        $ret['datecreated'] = \formatTimestamp($this->getVar('ans_datecreated'), 's');
        $ret['submitter']   = \XoopsUser::getUnameFromId($this->getVar('ans_submitter'));
        return $ret;
    }

    /**
     * Returns an array representation of the object
     *
     * @return array
     */
    public function toArrayAnswers()
    {
        $ret = [];
        $vars = $this->getVars();
        foreach (\array_keys($vars) as $var) {
            $ret[$var] = $this->getVar($var);
        }
        return $ret;
    }
}
