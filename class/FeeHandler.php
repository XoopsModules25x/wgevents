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


/**
 * Class Object Handler Fee
 */
class FeeHandler extends \XoopsPersistableObjectHandler
{
    /**
     * Constructor
     *
     * @param \XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, 'wgevents_fee', Fee::class, 'id', 'evid');
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
     * Get Count Fee in the database
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     * @return int
     */
    public function getCountFee($start = 0, $limit = 0, $sort = 'id ASC, evid', $order = 'ASC')
    {
        $crCountFee = new \CriteriaCompo();
        $crCountFee = $this->getFeeCriteria($crCountFee, $start, $limit, $sort, $order);
        return $this->getCount($crCountFee);
    }

    /**
     * Get All Fee in the database
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     * @return array
     */
    public function getAllFee($start = 0, $limit = 0, $sort = 'id ASC, evid', $order = 'ASC')
    {
        $crAllFee = new \CriteriaCompo();
        $crAllFee = $this->getFeeCriteria($crAllFee, $start, $limit, $sort, $order);
        return $this->getAll($crAllFee);
    }

    /**
     * Get Criteria Fee
     * @param        $crFee
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     * @return int
     */
    private function getFeeCriteria($crFee, $start, $limit, $sort, $order)
    {
        $crFee->setStart($start);
        $crFee->setLimit($limit);
        $crFee->setSort($sort);
        $crFee->setOrder($order);
        return $crFee;
    }
}
