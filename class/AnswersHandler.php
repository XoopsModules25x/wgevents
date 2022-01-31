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
use XoopsModules\Wgevents\Helper;

/**
 * Class Object Handler Answers
 */
class AnswersHandler extends \XoopsPersistableObjectHandler
{
    /**
     * Constructor
     *
     * @param \XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, 'wgevents_answers', Answers::class, 'id', 'evid');
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
     * Get Count Answers in the database
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     * @return int
     */
    public function getCountAnswers($start = 0, $limit = 0, $sort = 'id ASC, evid', $order = 'ASC')
    {
        $crCountAnswers = new \CriteriaCompo();
        $crCountAnswers = $this->getAnswersCriteria($crCountAnswers, $start, $limit, $sort, $order);
        return $this->getCount($crCountAnswers);
    }

    /**
     * Get All Answers in the database
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     * @return array
     */
    public function getAllAnswers($start = 0, $limit = 0, $sort = 'id ASC, evid', $order = 'ASC')
    {
        $crAllAnswers = new \CriteriaCompo();
        $crAllAnswers = $this->getAnswersCriteria($crAllAnswers, $start, $limit, $sort, $order);
        return $this->getAll($crAllAnswers);
    }

    /**
     * Get Criteria Answers
     * @param        $crAnswers
     * @param int $start
     * @param int $limit
     * @param string $sort
     * @param string $order
     * @return \CriteriaCompo
     */
    private function getAnswersCriteria($crAnswers, int $start, int $limit, string $sort, string $order)
    {
        $crAnswers->setStart($start);
        $crAnswers->setLimit($limit);
        $crAnswers->setSort($sort);
        $crAnswers->setOrder($order);
        return $crAnswers;
    }

    /**
     * Delete all answers for given event
     * @param int    $evId
     * @param int    $regId
     * @return bool
     */
    public function cleanupAnswers($evId, $regId = 0)
    {
        if ($evId > 0) {
            $crAnswers = new \CriteriaCompo();
            $crAnswers->add(new \Criteria('evid', $evId));
            if ($regId > 0) {
                $crAnswers->add(new \Criteria('regid', $regId));
            }
            $answersCount = $this->getCount($crAnswers);
            if ($answersCount > 0) {
                return $this->deleteAll($crAnswers, true);
            }
        }
        return true;
    }

    /**
     * get all answers of given registration
     * @param int    $regId            // id of registration
     * @param array  $questionsArr   // array with all questions for this event
     * @return array
     */
    public function getAnswersDetailsByRegistration($regId, $questionsArr)
    {
        $answers = [];
        foreach ($questionsArr as $queId => $addItem) {
            // get answers for this questions
            $crAnswers = new \CriteriaCompo();
            $crAnswers->add(new \Criteria('regid', $regId));
            $crAnswers->add(new \Criteria('queid', $queId));
            $answersCount = $this->getCount($crAnswers);
            if ($answersCount > 0) {
                $answersAll = $this->getAll($crAnswers);
                foreach (\array_keys($answersAll) as $i) {
                    $ansText = $answersAll[$i]->getVar('text');
                    if (Constants::FIELD_RADIOYN == $addItem['type'] ||
                        Constants::FIELD_CHECKBOX == $addItem['type']) {
                        if ((bool)$ansText) {
                            $ansText = \_YES;
                        } else {
                            $ansText = \_NO;
                        }
                    }
                    if (Constants::FIELD_RADIO == $addItem['type'] ||
                        Constants::FIELD_COMBOBOX == $addItem['type'] ||
                        Constants::FIELD_SELECTBOX == $addItem['type']) {
                        $queValues = \unserialize($addItem['values']);
                        $ansText = $queValues[$ansText];
                    }
                    $answers[$queId] = $ansText;
                }
            } else {
                $answers[$queId] = '';
            }
        }

        return $answers;
    }

    /**
     * compare two versions of answers
     * @param  $versionOld
     * @param  $versionNew
     * @return string
     */
    public function getAnswersCompare($versionOld, $versionNew)
    {
        $helper  = Helper::getInstance();
        $questionsHandler = $helper->getHandler('Questions');

        $infotext = '';
        foreach(\array_keys($versionNew) as $key) {
            $caption = '';
            $questionsObj = $questionsHandler->get($key);
            if (\is_object($questionsObj)) {
                $caption = $questionsObj->getVar('caption');
            }
            $valueOld = $versionOld[$key];
            $valueNew = $versionNew[$key];
            if ($valueOld != $valueNew) {
                if ('' == $valueNew) {
                    $infotext .= \sprintf(\_MA_WGEVENTS_MAIL_REG_MODIFICATION_DEL, $caption) . PHP_EOL;
                } else {
                    $infotext .= \sprintf(\_MA_WGEVENTS_MAIL_REG_MODIFICATION, $caption, $valueOld, $valueNew) . PHP_EOL;
                }
            }
            unset($questionsObj);
        }

        return $infotext;
    }
}
