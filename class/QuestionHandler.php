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
 * Class Object Handler Question
 */
class QuestionHandler extends \XoopsPersistableObjectHandler
{
    /**
     * Constructor
     *
     * @param \XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, 'wgevents_question', Question::class, 'id', 'id');
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
     * @param int $id field id
     * @param null fields
     * @return \XoopsObject|null reference to the {@link Get} object
     */
    public function get($id = null, $fields = null)
    {
        return parent::get($id, $fields);
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
     * Get Count Question in the database
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     * @return int
     */
    public function getCountQuestions($start = 0, $limit = 0, $sort = 'weight ASC, id', $order = 'ASC')
    {
        $crCountQuestions = new \CriteriaCompo();
        $crCountQuestions = $this->getQuestionsCriteria($crCountQuestions, $start, $limit, $sort, $order);
        return $this->getCount($crCountQuestions);
    }

    /**
     * Get All Question in the database
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     * @return array
     */
    public function getAllQuestions($start = 0, $limit = 0, $sort = 'weight ASC, id', $order = 'ASC')
    {
        $crAllQuestions = new \CriteriaCompo();
        $crAllQuestions = $this->getQuestionsCriteria($crAllQuestions, $start, $limit, $sort, $order);
        return $this->getAll($crAllQuestions);
    }

    /**
     * Get Criteria Question
     * @param        $crQuestion
     * @param int $start
     * @param int $limit
     * @param string $sort
     * @param string $order
     * @return \CriteriaCompo
     */
    private function getQuestionsCriteria($crQuestion, int $start, int $limit, string $sort, string $order)
    {
        $crQuestion->setStart($start);
        $crQuestion->setLimit($limit);
        $crQuestion->setSort($sort);
        $crQuestion->setOrder($order);
        return $crQuestion;
    }

    /**
     * Function create Question Defaultset
     * @param  $addEvid
     * @return bool
     */
    public function createQuestionsDefaultset ($addEvid) {

        $helper = \XoopsModules\Wgevents\Helper::getInstance();

        $uidCurrent = \is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->uid() : 0;
        $fieldHandler = $helper->getHandler('Field');
        $questionHandler = $helper->getHandler('Question');

        $crAddTypes = new \CriteriaCompo();
        $crAddTypes->add(new \Criteria('default', 1));
        $fieldsCount = $fieldHandler->getCount($crAddTypes);
        if ($fieldsCount > 0) {
            $crAddTypes->setSort('weight asc, id');
            $crAddTypes->setOrder('ASC');
            $fieldsAll = $fieldHandler->getAll($crAddTypes);
            // Get All AddTypes
            foreach (\array_keys($fieldsAll) as $i) {
                $questionObj = $questionHandler->create();
                $questionObj->setVar('evid', $addEvid);
                $questionObj->setVar('type', $fieldsAll[$i]->getVar('type'));
                $questionObj->setVar('caption', $fieldsAll[$i]->getVar('caption'));
                $questionObj->setVar('desc', $fieldsAll[$i]->getVar('desc'));
                $questionObj->setVar('values', (string)$fieldsAll[$i]->getVar('values'));
                $questionObj->setVar('placeholder', $fieldsAll[$i]->getVar('placeholder'));
                $questionObj->setVar('required', $fieldsAll[$i]->getVar('required'));
                $questionObj->setVar('print', $fieldsAll[$i]->getVar('print'));
                $questionObj->setVar('weight', $i);
                $questionObj->setVar('datecreated', \time());
                $questionObj->setVar('submitter', $uidCurrent);
                // Insert Data
                $questionHandler->insert($questionObj);
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
            $crQuestion = new \CriteriaCompo();
            $crQuestion->add(new \Criteria('evid', $evId));
            $questionsCount = $this->getCount($crQuestion);
            if ($questionsCount > 0) {
                return $this->deleteAll($crQuestion, true);
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
            $crQuestion = new \CriteriaCompo();
            $crQuestion->add(new \Criteria('evid', $evId));
            $crQuestion->setSort('weight ASC, id');
            $crQuestion->setOrder('DESC');
            if ($onlyPrintable) {
                $crQuestion->add(new \Criteria('print', 1));
            }
            $questionsCount = $this->getCount($crQuestion);
            if ($questionsCount > 0) {
                $questionsAll = $this->getAll($crQuestion);
                foreach (\array_keys($questionsAll) as $queId) {
                    $questionsArr[$queId] = [
                        'caption' => $questionsAll[$queId]->getVar('caption'),
                        'type' => $questionsAll[$queId]->getVar('type'),
                        'values' => $questionsAll[$queId]->getVar('values')
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

        $crQuestion = new \CriteriaCompo();
        $crQuestion->add(new \Criteria('evid', $evId));
        $crQuestion->setSort('weight');
        $crQuestion->setOrder('DESC');
        $crQuestion->setLimit(1);
        $questionsCount = $this->getCount($crQuestion);
        if ($questionsCount > 0) {
            $questionsAll = $this->getAll($crQuestion);
            foreach (\array_keys($questionsAll) as $queId) {
                $nextValue = $questionsAll[$queId]->getVar('weight');
            }
        }

        return $nextValue + 1;

    }

}
