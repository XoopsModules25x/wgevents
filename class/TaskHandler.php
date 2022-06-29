<?php

declare(strict_types=1);


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
    Constants,
    MailHandler
};

/**
 * Class Object Handler Task
 */
class TaskHandler extends \XoopsPersistableObjectHandler
{
    /**
     * Constructor
     *
     * @param \XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, 'wgevents_task', Task::class, 'id', 'id');
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
     * Get Count Task in the database
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     * @return int
     */
    public function getCountTasks($start = 0, $limit = 0, $sort = 'id', $order = 'ASC')
    {
        $crCountTasks = new \CriteriaCompo();
        $crCountTasks = $this->getTasksCriteria($crCountTasks, $start, $limit, $sort, $order);
        return $this->getCount($crCountTasks);
    }

    /**
     * Get All Task in the database
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     * @return array
     */
    public function getAllTasks($start = 0, $limit = 0, $sort = 'id', $order = 'ASC')
    {
        $crAllTasks = new \CriteriaCompo();
        $crAllTasks = $this->getTasksCriteria($crAllTasks, $start, $limit, $sort, $order);
        return $this->getAll($crAllTasks);
    }

    /**
     * Get Criteria Task
     * @param        $crTasks
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     * @return int
     */
    private function getTasksCriteria($crTasks, $start, $limit, $sort, $order)
    {
        $crTasks->setStart($start);
        $crTasks->setLimit($limit);
        $crTasks->setSort($sort);
        $crTasks->setOrder($order);
        return $crTasks;
    }

    /**
     * Create a task
     * @param $type
     * @param $recipient
     * @param $params
     * @return bool
     */
    public function createTask($type, $recipient, $params)
    {
        $uidCurrent = \is_object($GLOBALS['xoopsUser']) ? (int)$GLOBALS['xoopsUser']->uid() : 0;

        $taskObj = $this->create();
        // Set Vars
        $taskObj->setVar('type', $type);
        $taskObj->setVar('params', $params);
        $taskObj->setVar('recipient', $recipient);
        $taskObj->setVar('datecreated', time());
        $taskObj->setVar('submitter', $uidCurrent);
        $taskObj->setVar('status', Constants::STATUS_PENDING);

        // Insert Data
        return (bool)$this->insert($taskObj);
    }

    /**
     * process all task if limit is not exceeded
     * @return bool
     */
    public function processTasks()
    {
        $helper = \XoopsModules\Wgevents\Helper::getInstance();

        // get limit_hour from primary account
        $accountHandler = $helper->getHandler('Account');
        $limitHour = $accountHandler->getLimitHour();

        $crTaskPending = new \CriteriaCompo();
        $crTaskPending->add(new \Criteria('status', Constants::STATUS_PENDING));
        $crTaskDone = new \CriteriaCompo();
        $crTaskDone->add(new \Criteria('status', Constants::STATUS_DONE));
        $crTaskDone->add(new \Criteria('datedone', time() - 3600, '>'));
        $tasksCountPending = $this->getCount($crTaskPending);
        $tasksCountDone = $this->getCount($crTaskDone);
        if (($tasksCountPending > 0) && ($tasksCountDone < $limitHour || 0 == $limitHour)) {
            $tasksAll = $this->getAll($crTaskPending);
            foreach (\array_keys($tasksAll) as $i) {
                // check whether task is still pending
                // ignore it if meanwhile another one started to process the task
                if ((Constants::STATUS_PENDING == (int)$tasksAll[$i]->getVar('status'))
                    && ($tasksCountDone < $limitHour || 0 == $limitHour)) {
                    $taskProcessObj = $this->get($i);
                    $taskProcessObj->setVar('status', Constants::STATUS_PROCESSING);
                    if ($this->insert($taskProcessObj)) {
                        $mailsHandler = new MailHandler();
                        $mailParams = json_decode($tasksAll[$i]->getVar('params', 'n'), true);
                        $mailParams['recipients'] = $tasksAll[$i]->getVar('recipient');
                        $mailParams['taskId'] = $i;
                        $mailsHandler->setParams($mailParams);
                        $mailsHandler->setType($tasksAll[$i]->getVar('type'));
                        // send mails
                        $result = $mailsHandler->execute();
                        unset($mailsHandler);
                        //update task list corresponding the result
                        if ($result) {
                            $taskProcessObj->setVar('status', Constants::STATUS_DONE);
                            $taskProcessObj->setVar('done', time());
                        } else {
                            $taskProcessObj->setVar('status', Constants::STATUS_PENDING);
                        }
                        $this->insert($taskProcessObj);
                    }
                }
                // check once more number of done
                $tasksCountDone = $this->getCount($crTaskDone);
            }
        }

        return true;
    }
}
