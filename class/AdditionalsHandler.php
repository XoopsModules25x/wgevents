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
 * Class Object Handler Additionals
 */
class AdditionalsHandler extends \XoopsPersistableObjectHandler
{
    /**
     * Constructor
     *
     * @param \XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, 'wgevents_additionals', Additionals::class, 'add_id', 'add_id');
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
     * Get Count Additionals in the database
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     * @return int
     */
    public function getCountAdditionals($start = 0, $limit = 0, $sort = 'add_id ASC, add_evid', $order = 'ASC')
    {
        $crCountAdditionals = new \CriteriaCompo();
        $crCountAdditionals = $this->getAdditionalsCriteria($crCountAdditionals, $start, $limit, $sort, $order);
        return $this->getCount($crCountAdditionals);
    }

    /**
     * Get All Additionals in the database
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     * @return array
     */
    public function getAllAdditionals($start = 0, $limit = 0, $sort = 'add_id ASC, add_evid', $order = 'ASC')
    {
        $crAllAdditionals = new \CriteriaCompo();
        $crAllAdditionals = $this->getAdditionalsCriteria($crAllAdditionals, $start, $limit, $sort, $order);
        return $this->getAll($crAllAdditionals);
    }

    /**
     * Get Criteria Additionals
     * @param        $crAdditionals
     * @param int $start
     * @param int $limit
     * @param string $sort
     * @param string $order
     * @return \CriteriaCompo
     */
    private function getAdditionalsCriteria($crAdditionals, int $start, int $limit, string $sort, string $order)
    {
        $crAdditionals->setStart($start);
        $crAdditionals->setLimit($limit);
        $crAdditionals->setSort($sort);
        $crAdditionals->setOrder($order);
        return $crAdditionals;
    }

    /**
     * Function create Additionals Defaultset
     * @param  $addEvid
     * @return bool
     */
    public function createAdditionalsDefaultset ($addEvid) {

        $helper = \XoopsModules\Wgevents\Helper::getInstance();

        $uidCurrent = \is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->uid() : 0;
        $addtypesHandler = $helper->getHandler('Addtypes');
        $additionalsHandler = $helper->getHandler('Additionals');

        $crAddTypes = new \CriteriaCompo();
        $crAddTypes->add(new \Criteria('at_default', 1));
        $addtypesCount = $addtypesHandler->getCount($crAddTypes);
        if ($addtypesCount > 0) {
            $crAddTypes->setSort('at_weight asc, at_id');
            $crAddTypes->setOrder('ASC');
            $addtypesAll = $addtypesHandler->getAll($crAddTypes);
            // Get All AddTypes
            foreach (\array_keys($addtypesAll) as $i) {
                $additionalsObj = $additionalsHandler->create();
                $additionalsObj->setVar('add_evid', $addEvid);
                $additionalsObj->setVar('add_type', $addtypesAll[$i]->getVar('at_type'));
                $additionalsObj->setVar('add_caption', $addtypesAll[$i]->getVar('at_caption'));
                $additionalsObj->setVar('add_desc', $addtypesAll[$i]->getVar('at_desc'));
                $additionalsObj->setVar('add_values', (string)$addtypesAll[$i]->getVar('at_values'));
                $additionalsObj->setVar('add_placeholder', $addtypesAll[$i]->getVar('at_placeholder'));
                $additionalsObj->setVar('add_required', $addtypesAll[$i]->getVar('at_required'));
                $additionalsObj->setVar('add_print', $addtypesAll[$i]->getVar('at_print'));
                $additionalsObj->setVar('add_weight', $i);
                $additionalsObj->setVar('add_datecreated', \time());
                $additionalsObj->setVar('add_submitter', $uidCurrent);
                // Insert Data
                $additionalsHandler->insert($additionalsObj);
            }
        }

        return true;
    }

    /**
     * Delete all additionals for given event
     * @param int $evId
     * @return bool
     */
    public function cleanupAdditionals(int $evId)
    {
        if ($evId > 0) {
            $crAdditionals = new \CriteriaCompo();
            $crAdditionals->add(new \Criteria('add_evid', $evId));
            $additionalsCount = $this->getCount($crAdditionals);
            if ($additionalsCount > 0) {
                return $this->deleteAll($crAdditionals, true);
            }
        }
        return true;
    }

    /**
     * get all additionals for given event
     * @param int $evId
     * @param bool $onlyPrintable
     * @return array
     */
    public function getAdditionalsByEvent(int $evId, $onlyPrintable = true)
    {
        $additionalsArr = [];
        if ($evId > 0) {
            $crAdditionals = new \CriteriaCompo();
            $crAdditionals->add(new \Criteria('add_evid', $evId));
            $crAdditionals->setSort('add_weight ASC, add_id');
            $crAdditionals->setOrder('DESC');
            if ($onlyPrintable) {
                $crAdditionals->add(new \Criteria('add_print', 1));
            }
            $additionalsCount = $this->getCount($crAdditionals);
            if ($additionalsCount > 0) {
                $additionalsAll = $this->getAll($crAdditionals);
                foreach (\array_keys($additionalsAll) as $addId) {
                    $additionalsArr[$addId] = [
                        'caption' => $additionalsAll[$addId]->getVar('add_caption'),
                        'type' => $additionalsAll[$addId]->getVar('add_type'),
                        'values' => $additionalsAll[$addId]->getVar('add_values')
                    ];
                }
            }
        }

        return $additionalsArr;

    }

    /**
     * @public function to get next value for sorting
     * @param int $evId
     * @return int
     */
    public function getNextWeight(int $evId)
    {
        $nextValue = 0;

        $crAdditionals = new \CriteriaCompo();
        $crAdditionals->add(new \Criteria('add_evid', $evId));
        $crAdditionals->setSort('add_weight');
        $crAdditionals->setOrder('DESC');
        $crAdditionals->setLimit(1);
        $additionalsCount = $this->getCount($crAdditionals);
        if ($additionalsCount > 0) {
            $additionalsAll = $this->getAll($crAdditionals);
            foreach (\array_keys($additionalsAll) as $addId) {
                $nextValue = $additionalsAll[$addId]->getVar('add_weight');
            }
        }

        return $nextValue + 1;

    }

}
