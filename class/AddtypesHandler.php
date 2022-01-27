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
 * Class Object Handler Addtypes
 */
class AddtypesHandler extends \XoopsPersistableObjectHandler
{
    /**
     * Constructor
     *
     * @param \XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, 'wgevents_addtypes', Addtypes::class, 'at_id', 'at_caption');
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
     * Get Count Addtypes in the database
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     * @return int
     */
    public function getCountAddtypes($start = 0, $limit = 0, $sort = 'at_id ASC, at_caption', $order = 'ASC')
    {
        $crCountAddtypes = new \CriteriaCompo();
        $crCountAddtypes = $this->getAddtypesCriteria($crCountAddtypes, $start, $limit, $sort, $order);
        return $this->getCount($crCountAddtypes);
    }

    /**
     * Get All Addtypes in the database
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     * @return array
     */
    public function getAllAddtypes($start = 0, $limit = 0, $sort = 'at_id ASC, at_caption', $order = 'ASC')
    {
        $crAllAddtypes = new \CriteriaCompo();
        $crAllAddtypes = $this->getAddtypesCriteria($crAllAddtypes, $start, $limit, $sort, $order);
        return $this->getAll($crAllAddtypes);
    }

    /**
     * Get Criteria Addtypes
     * @param        $crAddtypes
     * @param int $start
     * @param int $limit
     * @param string $sort
     * @param string $order
     * @return int
     */
    private function getAddtypesCriteria($crAddtypes, int $start, int $limit, string $sort, string $order)
    {
        $crAddtypes->setStart($start);
        $crAddtypes->setLimit($limit);
        $crAddtypes->setSort($sort);
        $crAddtypes->setOrder($order);
        return $crAddtypes;
    }

    /**
     * @public function to get next value for sorting
     * @param null
     * @return int
     */
    public function getNextWeight()
    {
        $nextValue = 0;

        $crAddtypes = new \CriteriaCompo();
        $crAddtypes->setSort('at_weight');
        $crAddtypes->setOrder('DESC');
        $crAddtypes->setLimit(1);
        $addtypesCount = $this->getCount($crAddtypes);
        if ($addtypesCount > 0) {
            $addtypesAll = $this->getAll($crAddtypes);
            foreach (\array_keys($addtypesAll) as $i) {
                $nextValue = $addtypesAll[$i]->getVar('at_weight');
            }
        }

        return $nextValue + 1;

    }
}
