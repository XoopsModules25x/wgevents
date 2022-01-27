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


/**
 * Class Object Handler Questions
 */
class QuestionsHandler extends \XoopsPersistableObjectHandler
{
    /**
     * Constructor
     *
     * @param \XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, 'wgevents_questions', Questions::class, 'que_id', 'que_id');
    }

    /**
     * @param bool $isNew
     *
     * @return object
     */
    public function create($isNew = true)
    {
        return parent::create($isNew);
    }

    /**
     * retrieve a field
     *
     * @param int $i field id
     * @param null fields
     * @return \XoopsObject|null reference to the {@link Get} object
     */
    public function get($i = null, $fields = null)
    {
        return parent::get($i, $fields);
    }

    /**
     * get inserted id
     *
     * @param null
     * @return int reference to the {@link Get} object
     */
    public function getInsertId()
    {
        return $this->db->getInsertId();
    }

    /**
     * Get Count Questions in the database
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     * @return int
     */
    public function getCountQuestions($start = 0, $limit = 0, $sort = 'que_id ASC, que_evid', $order = 'ASC')
    {
        $crCountQuestions = new \CriteriaCompo();
        $crCountQuestions = $this->getQuestionsCriteria($crCountQuestions, $start, $limit, $sort, $order);
        return $this->getCount($crCountQuestions);
    }

    /**
     * Get All Questions in the database
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     * @return array
     */
    public function getAllQuestions($start = 0, $limit = 0, $sort = 'que_id ASC, que_evid', $order = 'ASC')
    {
        $crAllQuestions = new \CriteriaCompo();
        $crAllQuestions = $this->getQuestionsCriteria($crAllQuestions, $start, $limit, $sort, $order);
        return $this->getAll($crAllQuestions);
    }

    /**
     * Get Criteria Questions
     * @param        $crQuestions
     * @param int $start
     * @param int $limit
     * @param string $sort
     * @param string $order
     * @return \CriteriaCompo
     */
    private function getQuestionsCriteria($crQuestions, int $start, int $limit, string $sort, string $order)
    {
        $crQuestions->setStart($start);
        $crQuestions->setLimit($limit);
        $crQuestions->setSort($sort);
        $crQuestions->setOrder($order);
        return $crQuestions;
    }

    /**
     * Function create Questions Defaultset
     * @param  $addEvid
     * @return bool
     */
    public function createQuestionsDefaultset ($addEvid) {

        $helper = \XoopsModules\Wgevents\Helper::getInstance();

        $uidCurrent = \is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->uid() : 0;
        $fieldsHandler = $helper->getHandler('Fields');
        $questionsHandler = $helper->getHandler('Questions');

        $crAddTypes = new \CriteriaCompo();
        $crAddTypes->add(new \Criteria('fd_default', 1));
        $fieldsCount = $fieldsHandler->getCount($crAddTypes);
        if ($fieldsCount > 0) {
            $crAddTypes->setSort('fd_weight asc, fd_id');
            $crAddTypes->setOrder('ASC');
            $fieldsAll = $fieldsHandler->getAll($crAddTypes);
            // Get All AddTypes
            foreach (\array_keys($fieldsAll) as $i) {
                $questionsObj = $questionsHandler->create();
                $questionsObj->setVar('que_evid', $addEvid);
                $questionsObj->setVar('que_type', $fieldsAll[$i]->getVar('fd_type'));
                $questionsObj->setVar('que_caption', $fieldsAll[$i]->getVar('fd_caption'));
                $questionsObj->setVar('que_desc', $fieldsAll[$i]->getVar('fd_desc'));
                $questionsObj->setVar('que_values', (string)$fieldsAll[$i]->getVar('fd_values'));
                $questionsObj->setVar('que_placeholder', $fieldsAll[$i]->getVar('fd_placeholder'));
                $questionsObj->setVar('que_required', $fieldsAll[$i]->getVar('fd_required'));
                $questionsObj->setVar('que_print', $fieldsAll[$i]->getVar('fd_print'));
                $questionsObj->setVar('que_weight', $i);
                $questionsObj->setVar('que_datecreated', \time());
                $questionsObj->setVar('que_submitter', $uidCurrent);
                // Insert Data
                $questionsHandler->insert($questionsObj);
            }
        }

        return true;
    }

    /**
     * Delete all questions for given event
     * @param int $evId
     * @return bool
     */
    public function cleanupQuestions(int $evId)
    {
        if ($evId > 0) {
            $crQuestions = new \CriteriaCompo();
            $crQuestions->add(new \Criteria('que_evid', $evId));
            $questionsCount = $this->getCount($crQuestions);
            if ($questionsCount > 0) {
                return $this->deleteAll($crQuestions, true);
            }
        }
        return true;
    }

    /**
     * get all questions for given event
     * @param int $evId
     * @param bool $onlyPrintable
     * @return array
     */
    public function getQuestionsByEvent(int $evId, $onlyPrintable = true)
    {
        $questionsArr = [];
        if ($evId > 0) {
            $crQuestions = new \CriteriaCompo();
            $crQuestions->add(new \Criteria('que_evid', $evId));
            $crQuestions->setSort('que_weight ASC, que_id');
            $crQuestions->setOrder('DESC');
            if ($onlyPrintable) {
                $crQuestions->add(new \Criteria('que_print', 1));
            }
            $questionsCount = $this->getCount($crQuestions);
            if ($questionsCount > 0) {
                $questionsAll = $this->getAll($crQuestions);
                foreach (\array_keys($questionsAll) as $queId) {
                    $questionsArr[$queId] = [
                        'caption' => $questionsAll[$queId]->getVar('que_caption'),
                        'type' => $questionsAll[$queId]->getVar('que_type'),
                        'values' => $questionsAll[$queId]->getVar('que_values')
                    ];
                }
            }
        }

        return $questionsArr;

    }

    /**
     * @public function to get next value for sorting
     * @param int $evId
     * @return int
     */
    public function getNextWeight(int $evId)
    {
        $nextValue = 0;

        $crQuestions = new \CriteriaCompo();
        $crQuestions->add(new \Criteria('que_evid', $evId));
        $crQuestions->setSort('que_weight');
        $crQuestions->setOrder('DESC');
        $crQuestions->setLimit(1);
        $questionsCount = $this->getCount($crQuestions);
        if ($questionsCount > 0) {
            $questionsAll = $this->getAll($crQuestions);
            foreach (\array_keys($questionsAll) as $queId) {
                $nextValue = $questionsAll[$queId]->getVar('que_weight');
            }
        }

        return $nextValue + 1;

    }

}
