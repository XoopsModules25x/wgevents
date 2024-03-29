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
use XoopsModules\Wgevents\Forms;
use XoopsModules\Wgevents\Forms\FormInline;

/**
 * Class Object Handler Category
 */
class CategoryHandler extends \XoopsPersistableObjectHandler
{
    /**
     * Constructor
     *
     * @param \XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, 'wgevents_category', Category::class, 'id', 'name');
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
     * @param $fields
     * @return \XoopsObject|null reference to the {@link Get} object
     */
    public function get($id = null, $fields = null)
    {
        return parent::get($id, $fields);
    }

    /**
     * get inserted id
     *
     * @return int reference to the {@link Get} object
     */
    public function getInsertId()
    {
        return $this->db->getInsertId();
    }

    /**
     * Get Count Category in the database
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     * @return int
     */
    public function getCountCategories($start = 0, $limit = 0, $sort = 'weight ASC, id', $order = 'ASC')
    {
        $crCountCategories = new \CriteriaCompo();
        $crCountCategories = $this->getCategoriesCriteria($crCountCategories, $start, $limit, $sort, $order);
        return $this->getCount($crCountCategories);
    }

    /**
     * Get All Category in the database
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     * @return array
     */
    public function getAllCategories($start = 0, $limit = 0, $sort = 'weight ASC, id', $order = 'ASC')
    {
        $crAllCategories = new \CriteriaCompo();
        $crAllCategories = $this->getCategoriesCriteria($crAllCategories, $start, $limit, $sort, $order);
        return $this->getAll($crAllCategories);
    }

    /**
     * Get All online categories in the database
     * @param int    $type
     * @param string $sort
     * @param string $order
     * @return array
     */
    public function getAllCatsOnline($type = 0, $sort = 'weight ASC, id', $order = 'ASC')
    {
        $cats = [];
        $crCategory = new \CriteriaCompo();
        $crCategory->add(new \Criteria('status', Constants::STATUS_ONLINE));
        if (Constants::CATEGORY_TYPE_MAIN == $type) {
            $crCategory->add(new \Criteria('type', Constants::CATEGORY_TYPE_SUB, '<'));
        }
        if (Constants::CATEGORY_TYPE_SUB == $type) {
            $crCategory->add(new \Criteria('type', Constants::CATEGORY_TYPE_MAIN, '<>'));
        }
        $crCategory->setSort($sort);
        $crCategory->setOrder($order);
        $categoriesCount = $this->getCount($crCategory);
        if ($categoriesCount > 0) {
            $categoriesAll = $this->getAll($crCategory);
            foreach (\array_keys($categoriesAll) as $i) {
                $cats[$i] = $categoriesAll[$i]->getVar('name');
            }
        }
        return $cats;
    }

    /**
     * Get Criteria Category
     * @param        $crCategory
     * @param int $start
     * @param int $limit
     * @param string $sort
     * @param string $order
     * @return int
     */
    private function getCategoriesCriteria($crCategory, int $start, int $limit, string $sort, string $order)
    {
        $crCategory->setStart($start);
        $crCategory->setLimit($limit);
        $crCategory->setSort($sort);
        $crCategory->setOrder($order);
        return $crCategory;
    }

    /**
     * @public function to get next value for sorting
     *
     * @return int
     */
    public function getNextWeight()
    {
        $nextValue = 0;

        $crCategory = new \CriteriaCompo();
        $crCategory->setSort('weight');
        $crCategory->setOrder('DESC');
        $crCategory->setLimit(1);
        $categoriesCount = $this->getCount($crCategory);
        if ($categoriesCount > 0) {
            $categoriesAll = $this->getAll($crCategory);
            foreach (\array_keys($categoriesAll) as $i) {
                $nextValue = $categoriesAll[$i]->getVar('weight');
            }
        }

        return $nextValue + 1;

    }

    /**
     * @public function to get a collection of all existing categories
     *
     * @return array
     */
    public function getCollection()
    {
        $categories = [];
        $categoriesCount = $this->getCount();
        if ($categoriesCount > 0) {
            $categoriesAll = $this->getAll();
            foreach (\array_keys($categoriesAll) as $i) {
                $categories[$i] = [
                    'id' => $i,
                    'name' => $categoriesAll[$i]->getVar('name'),
                    'color' => $categoriesAll[$i]->getVar('color'),
                    'bordercolor' => $categoriesAll[$i]->getVar('bordercolor'),
                    'bgcolor' => $categoriesAll[$i]->getVar('bgcolor'),
                    'othercss' => $categoriesAll[$i]->getVar('othercss')
                ];
            }
        }

        return $categories;

    }

    /**
     * @public function getFormCatsCb: form with checkboxes of cats with events
     * @param array $filterCats
     * @param string $op
     * @param string $filter
     * @return FormInline
     */
    public function getFormCatsCb($filterCats = [], $op = 'list', $filter = '')
    {
        // Get Theme Form
        \xoops_load('XoopsFormLoader');
        $form = new Forms\FormInline('', 'formCatsCb', $_SERVER['REQUEST_URI'], 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        $cbAll = 1;
        // Form Select categories
        $catsOnline = $this->getAllCatsOnline();
        if (0 === \count($filterCats)) {
            foreach (\array_keys($catsOnline) as $i) {
                $filterCats[] = $i;
            }
        } elseif (\count($filterCats) < \count($catsOnline)) {
            $cbAll = 0;
        }
        $catAllSelect = new Forms\FormCheckboxInline(\_MA_WGEVENTS_CATEGORY_FILTER, 'all_cats', $cbAll);
        $catAllSelect->addOption(1, _ALL);
        $catAllSelect->setExtra(" onclick='toggleAllCats()' ");
        $form->addElement($catAllSelect);
        $catSelect = new Forms\FormCheckboxInline('', 'filter_cats', $filterCats);
        $catSelect->addOptionArray($catsOnline);
        $form->addElement($catSelect);
        $btnFilter = new \XoopsFormButton('', 'submit', \_MA_WGEVENTS_APPLY_FILTER, 'submit');
        $btnFilter->setClass('btn btn-success');
        $form->addElement($btnFilter);

        // To Save
        $form->addElement(new \XoopsFormHidden('op', $op));
        $form->addElement(new \XoopsFormHidden('start', '0'));
        $form->addElement(new \XoopsFormHidden('filter', $filter));
        return $form;
    }

    /**
     * @public function getCategoriesForFilter: get all cats wtih number of events for displying as filter
     * @param string $eventDisplayCats
     * @param array  $filterCats
     * @param string $op
     * @param bool   $useGroups
     * @param string $filter
     * @return array
     */
    public function getCategoriesForFilter ($eventDisplayCats, $filterCats, $op, $useGroups, $filter = '') {

        $helper = Helper::getInstance();
        $eventHandler = $helper->getHandler('Event');

        $uidCurrent  = 0;
        $userIsAdmin = false;
        if (\is_object($GLOBALS['xoopsUser'])) {
            $uidCurrent  = $GLOBALS['xoopsUser']->uid();
            $userIsAdmin = $GLOBALS['xoopsUser']->isAdmin();
        }

        $crCategory = new \CriteriaCompo();
        $crCategory->add(new \Criteria('status',Constants::STATUS_ONLINE));
        $crCategory->setSort('weight');
        $crCategory->setOrder('ASC');
        $categoriesCount = $this->getCount($crCategory);
        $GLOBALS['xoopsTpl']->assign('categoriesCount', $categoriesCount);
        if ($categoriesCount > 0) {
            if ('form' === $eventDisplayCats) {
                $formCatsCb = $this->getFormCatsCb($filterCats, $op, $filter);
                $GLOBALS['xoopsTpl']->assign('formCatsCb', $formCatsCb->render());
            } else {
                //$crCategory->setStart($start);
                //$crCategory->setLimit($limit);
                $categoriesAll = $this->getAll($crCategory);
                $categories = [];
                if ('button' === $eventDisplayCats) {
                    $categories[0] = ['id' => 0, 'logo' => 'blank.gif', 'name' => 'alle', 'eventsCount' => 0];
                }
                // Get All Event
                foreach (\array_keys($categoriesAll) as $i) {
                    $categories[$i] = $categoriesAll[$i]->getValuesCategories();
                    $crEvent = new \CriteriaCompo();
                    $crEvent->add(new \Criteria('catid',$i));
                    // current user
                    // - must have perm to see event or
                    // - must be event owner
                    // - is admin
                    if ($useGroups && !$userIsAdmin) {
                        $crEventGroup = new \CriteriaCompo();
                        $crEventGroup->add(new \Criteria('groups', '%00000%', 'LIKE')); //all users
                        if ($uidCurrent > 0) {
                            // Get groups
                            $memberHandler = \xoops_getHandler('member');
                            $xoopsGroups = $memberHandler->getGroupsByUser($uidCurrent);
                            foreach ($xoopsGroups as $group) {
                                $crEventGroup->add(new \Criteria('groups', '%' . substr('00000' . $group, -5) . '%', 'LIKE'), 'OR');
                            }
                        }
                        $crEventGroup->add(new \Criteria('submitter', $uidCurrent), 'OR');
                        $crEvent->add($crEventGroup);
                        unset($crEventGroup);
                    }
                    $eventsCount = $eventHandler->getCount($crEvent);
                    $nbEventsText = \_MA_WGEVENTS_CATEGORY_NOEVENTS;
                    if ($eventsCount > 0) {
                        if ($eventsCount > 1) {
                            $nbEventsText = \sprintf(\_MA_WGEVENTS_CATEGORY_EVENTS, $eventsCount);
                        } else {
                            $nbEventsText = \_MA_WGEVENTS_CATEGORY_EVENT;
                        }
                    }
                    $categories[$i]['nbeventsText'] = $nbEventsText;
                    $categories[$i]['eventsCount'] = $eventsCount;
                }

                return $categories;
            }
        }

        return [];
    }
}
