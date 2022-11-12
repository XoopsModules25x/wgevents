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
 * Class Object Answer
 */
class Answer extends \XoopsObject
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
        $this->initVar('regid', \XOBJ_DTYPE_INT);
        $this->initVar('queid', \XOBJ_DTYPE_INT);
        $this->initVar('evid', \XOBJ_DTYPE_INT);
        $this->initVar('text', \XOBJ_DTYPE_TXTBOX);
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
        $helper = \XoopsModules\Wgevents\Helper::getInstance();
        if (!$action) {
            $action = $_SERVER['REQUEST_URI'];
        }
        $isAdmin = (\is_object($GLOBALS['xoopsUser']) && \is_object($GLOBALS['xoopsModule'])) ? $GLOBALS['xoopsUser']->isAdmin($GLOBALS['xoopsModule']->mid()) : false;
        // Title
        $title = $this->isNew() ? \_MA_WGEVENTS_ANSWER_ADD : \_MA_WGEVENTS_ANSWER_EDIT;
        // Get Theme Form
        \xoops_load('XoopsFormLoader');
        $form = new \XoopsThemeForm($title, 'form', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        // Form Table registrations
        $registrationHandler = $helper->getHandler('Registration');
        $ansResidSelect = new \XoopsFormSelect(\_MA_WGEVENTS_ANSWER_REGID, 'regid', $this->getVar('regid'));
        $ansResidSelect->addOptionArray($registrationHandler->getList());
        $form->addElement($ansResidSelect);
        // Form Table questions
        $questionHandler = $helper->getHandler('Question');
        $ansAddidSelect = new \XoopsFormSelect(\_MA_WGEVENTS_ANSWER_QUEID, 'queid', $this->getVar('queid'));
        $crQuestion = new \CriteriaCompo();
        $crQuestion->add(new \Criteria('evid', $this->getVar('evid')));
        $questionsCount = $questionHandler->getCount($crQuestion);
        // Table view questions
        if ($questionsCount > 0) {
            $crQuestion->setSort('weight ASC, id');
            $crQuestion->setOrder('DESC');
            $questionsAll = $questionHandler->getAll($crQuestion);
            foreach (\array_keys($questionsAll) as $i) {
                $ansAddidSelect->addOption($questionsAll[$i]->getVar('id'), $questionsAll[$i]->getVar('caption'));
            }
        }
        $form->addElement($ansAddidSelect);
        // Form Table events
        $eventHandler = $helper->getHandler('Event');
        $ansEvidSelect = new \XoopsFormSelect(\_MA_WGEVENTS_ANSWER_EVID, 'evid', $this->getVar('evid'));
        $ansEvidSelect->addOptionArray($eventHandler->getList());
        $form->addElement($ansEvidSelect);
        // Form Text ansText
        $form->addElement(new \XoopsFormText(\_MA_WGEVENTS_ANSWER_TEXT, 'text', 50, 255, $this->getVar('text')));
        // Form Text Date Select ansDatecreated
        $ansDatecreated = $this->isNew() ? \time() : $this->getVar('datecreated');
        $form->addElement(new \XoopsFormTextDateSelect(\_MA_WGEVENTS_DATECREATED, 'datecreated', '', $ansDatecreated));
        // Form Select User ansSubmitter
        $uidCurrent = \is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->uid() : 0;
        $ansSubmitter = $this->isNew() ? $uidCurrent : $this->getVar('submitter');
        $form->addElement(new \XoopsFormSelectUser(\_MA_WGEVENTS_SUBMITTER, 'submitter', false, $ansSubmitter));
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
        $questionHandler = $helper->getHandler('Question');
        $questionObj = $questionHandler->get($this->getVar('queid'));
        $queCaption = '';
        if (\is_object($questionObj)) {
            $queCaption = $questionObj->getVar('caption');
        }
        $ret['quecaption']       = $queCaption;
        $eventHandler = $helper->getHandler('Event');
        $eventObj = $eventHandler->get($this->getVar('evid'));
        $evName = 'invalid event';
        if (\is_object($eventObj)) {
            $evName = $eventObj->getVar('name');
        }
        $ret['eventname']        = $evName;
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
