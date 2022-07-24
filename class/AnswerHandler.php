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
 * Class Object Handler Answer
 */
class AnswerHandler extends \XoopsPersistableObjectHandler
{
    /**
     * Constructor
     *
     * @param \XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, 'wgevents_answer', Answer::class, 'id', 'evid');
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
     * Get Count Answer in the database
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
     * Get All Answer in the database
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
     * Get Criteria Answer
     * @param        $crAnswer
     * @param int $start
     * @param int $limit
     * @param string $sort
     * @param string $order
     * @return \CriteriaCompo
     */
    private function getAnswersCriteria($crAnswer, int $start, int $limit, string $sort, string $order)
    {
        $crAnswer->setStart($start);
        $crAnswer->setLimit($limit);
        $crAnswer->setSort($sort);
        $crAnswer->setOrder($order);
        return $crAnswer;
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
            $crAnswer = new \CriteriaCompo();
            $crAnswer->add(new \Criteria('evid', $evId));
            if ($regId > 0) {
                $crAnswer->add(new \Criteria('regid', $regId));
            }
            $answersCount = $this->getCount($crAnswer);
            if ($answersCount > 0) {
                return $this->deleteAll($crAnswer, true);
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
        foreach ($questionsArr as $queId => $queItem) {
            // get answers for this questions
            $crAnswer = new \CriteriaCompo();
            $crAnswer->add(new \Criteria('regid', $regId));
            $crAnswer->add(new \Criteria('queid', $queId));
            $answersCount = $this->getCount($crAnswer);
            if ($answersCount > 0) {
                $answersAll = $this->getAll($crAnswer);
                foreach (\array_keys($answersAll) as $i) {
                    $ansText = $answersAll[$i]->getVar('text', 'n');
                    if (Constants::FIELD_RADIOYN == $queItem['type']) {
                        if ((bool)$ansText) {
                            $ansText = \_YES;
                        } else {
                            $ansText = \_NO;
                        }
                    }
                    if (Constants::FIELD_CHECKBOX == $queItem['type'] ||
                        Constants::FIELD_COMBOBOX == $queItem['type']) {
                        $queValues = \unserialize($queItem['values']);
                        $ansItems = \unserialize($ansText);
                        $ansText = '';
                        foreach ($ansItems as $ansItem) {
                            $ansText .= $queValues[(int)$ansItem] . ' <br>';
                        }
                    }
                    if (Constants::FIELD_SELECTBOX == $queItem['type']) {
                        $queValues = \unserialize($queItem['values']);
                        $ansItem = (string)\unserialize($ansText);
                        $ansText = $queValues[(int)$ansItem];
                    }
                    if (Constants::FIELD_RADIO == $queItem['type']) {
                        $queValues = \unserialize($queItem['values']);
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
        $questionHandler = $helper->getHandler('Question');

        $infotext = '';
        foreach(\array_keys($versionNew) as $key) {
            $caption = '';
            $questionObj = $questionHandler->get($key);
            if (\is_object($questionObj)) {
                $caption = $questionObj->getVar('caption');
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
            unset($questionObj);
        }

        return $infotext;
    }
}
