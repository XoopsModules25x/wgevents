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
 * Class Object Handler Log
 */
class LogHandler extends \XoopsPersistableObjectHandler
{
    /**
     * Constructor
     *
     * @param \XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, 'wgevents_logs', Log::class, 'id', 'text');
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
     * Get Count Log in the database
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     * @return int
     */
    public function getCountLogs($start = 0, $limit = 0, $sort = 'id ASC, text', $order = 'ASC')
    {
        $crCountLogs = new \CriteriaCompo();
        $crCountLogs = $this->getLogsCriteria($crCountLogs, $start, $limit, $sort, $order);
        return $this->getCount($crCountLogs);
    }

    /**
     * Get All Log in the database
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     * @return array
     */
    public function getAllLogs($start = 0, $limit = 0, $sort = 'id ASC, text', $order = 'ASC')
    {
        $crAllLogs = new \CriteriaCompo();
        $crAllLogs = $this->getLogsCriteria($crAllLogs, $start, $limit, $sort, $order);
        return $this->getAll($crAllLogs);
    }

    /**
     * Get Criteria Log
     * @param        $crLogs
     * @param int $start
     * @param int $limit
     * @param string $sort
     * @param string $order
     * @return int
     */
    private function getLogsCriteria($crLogs, int $start, int $limit, string $sort, string $order)
    {
        $crLogs->setStart($start);
        $crLogs->setLimit($limit);
        $crLogs->setSort($sort);
        $crLogs->setOrder($order);
        return $crLogs;
    }

    /**
     * Create new Log
     * @param string $text
     * @return void
     */
    public function createLog(string $text)
    {
        $helper = \XoopsModules\Wgevents\Helper::getInstance();

        if ($helper->getConfig('use_logs')) {
            $logSubmitter = \is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->uid() : 0;
            $logsObj = $this->create();
            // Set Vars
            $logsObj->setVar('text', $text);
            $logsObj->setVar('datecreated', \time());
            $logsObj->setVar('submitter', $logSubmitter);
            // Insert Data
            $this->insert($logsObj);
        }
    }
}
