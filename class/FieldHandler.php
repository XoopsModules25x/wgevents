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
 * Class Object Handler Field
 */
class FieldHandler extends \XoopsPersistableObjectHandler
{
    /**
     * Constructor
     *
     * @param \XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, 'wgevents_field', Field::class, 'id', 'caption');
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
     * Get Count Field in the database
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     * @return int
     */
    public function getCountFields($start = 0, $limit = 0, $sort = 'id ASC, caption', $order = 'ASC')
    {
        $crCountFields = new \CriteriaCompo();
        $crCountFields = $this->getFieldsCriteria($crCountFields, $start, $limit, $sort, $order);
        return $this->getCount($crCountFields);
    }

    /**
     * Get All Field in the database
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     * @return array
     */
    public function getAllFields($start = 0, $limit = 0, $sort = 'id ASC, caption', $order = 'ASC')
    {
        $crAllFields = new \CriteriaCompo();
        $crAllFields = $this->getFieldsCriteria($crAllFields, $start, $limit, $sort, $order);
        return $this->getAll($crAllFields);
    }

    /**
     * Get Criteria Field
     * @param        $crField
     * @param int $start
     * @param int $limit
     * @param string $sort
     * @param string $order
     * @return int
     */
    private function getFieldsCriteria($crField, int $start, int $limit, string $sort, string $order)
    {
        $crField->setStart($start);
        $crField->setLimit($limit);
        $crField->setSort($sort);
        $crField->setOrder($order);
        return $crField;
    }

    /**
     * @public function to get next value for sorting
     * @param null
     * @return int
     */
    public function getNextWeight()
    {
        $nextValue = 0;

        $crField = new \CriteriaCompo();
        $crField->setSort('weight');
        $crField->setOrder('DESC');
        $crField->setLimit(1);
        $fieldsCount = $this->getCount($crField);
        if ($fieldsCount > 0) {
            $fieldsAll = $this->getAll($crField);
            foreach (\array_keys($fieldsAll) as $i) {
                $nextValue = $fieldsAll[$i]->getVar('weight');
            }
        }

        return $nextValue + 1;

    }
}
