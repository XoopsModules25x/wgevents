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
 * Class Object Handler Categories
 */
class CategoriesHandler extends \XoopsPersistableObjectHandler
{
    /**
     * Constructor
     *
     * @param \XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, 'wgevents_categories', Categories::class, 'cfd_id', 'cat_name');
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
     * Get Count Categories in the database
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     * @return int
     */
    public function getCountCategories($start = 0, $limit = 0, $sort = 'cat_weight ASC, cfd_id', $order = 'ASC')
    {
        $crCountCategories = new \CriteriaCompo();
        $crCountCategories = $this->getCategoriesCriteria($crCountCategories, $start, $limit, $sort, $order);
        return $this->getCount($crCountCategories);
    }

    /**
     * Get All Categories in the database
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     * @return array
     */
    public function getAllCategories($start = 0, $limit = 0, $sort = 'cat_weight ASC, cfd_id', $order = 'ASC')
    {
        $crAllCategories = new \CriteriaCompo();
        $crAllCategories = $this->getCategoriesCriteria($crAllCategories, $start, $limit, $sort, $order);
        return $this->getAll($crAllCategories);
    }

    /**
     * Get Criteria Categories
     * @param        $crCategories
     * @param int $start
     * @param int $limit
     * @param string $sort
     * @param string $order
     * @return int
     */
    private function getCategoriesCriteria($crCategories, int $start, int $limit, string $sort, string $order)
    {
        $crCategories->setStart($start);
        $crCategories->setLimit($limit);
        $crCategories->setSort($sort);
        $crCategories->setOrder($order);
        return $crCategories;
    }

    /**
     * @public function to get next value for sorting
     * @param null
     * @return int
     */
    public function getNextWeight()
    {
        $nextValue = 0;

        $crCategories = new \CriteriaCompo();
        $crCategories->setSort('cat_weight');
        $crCategories->setOrder('DESC');
        $crCategories->setLimit(1);
        $categoriesCount = $this->getCount($crCategories);
        if ($categoriesCount > 0) {
            $categoriesAll = $this->getAll($crCategories);
            foreach (\array_keys($categoriesAll) as $i) {
                $nextValue = $categoriesAll[$i]->getVar('cat_weight');
            }
        }

        return $nextValue + 1;

    }

    /**
     * @public function to get a collection of all existing categories
     * @param null
     * @return array
     */
    public function getCategoriesCollection()
    {
        $categories = [];
        $categoriesCount = $this->getCount();
        if ($categoriesCount > 0) {
            $categoriesAll = $this->getAll();
            foreach (\array_keys($categoriesAll) as $i) {
                $categories[$i] = [
                    'id' => $i,
                    'name' => $categoriesAll[$i]->getVar('cat_name'),
                    'color' => $categoriesAll[$i]->getVar('cat_color'),
                    'bordercolor' => $categoriesAll[$i]->getVar('cat_bordercolor'),
                    'bgcolor' => $categoriesAll[$i]->getVar('cat_bgcolor'),
                    'othercss' => $categoriesAll[$i]->getVar('cat_othercss')
                ];
            }
        }

        return $categories;

    }
}
