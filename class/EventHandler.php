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
 * Class Object Handler Event
 */
class EventHandler extends \XoopsPersistableObjectHandler
{
    /**
     * Constructor
     *
     * @param \XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, 'wgevents_events', Event::class, 'id', 'name');
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
     * Get Count Event in the database
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     * @return int
     */
    public function getCountEvents($start = 0, $limit = 0, $sort = 'id', $order = 'DESC')
    {
        $crCountEvents = new \CriteriaCompo();
        $crCountEvents = $this->getEventsCriteria($crCountEvents, $start, $limit, $sort, $order);
        return $this->getCount($crCountEvents);
    }

    /**
     * Get All Event in the database
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     * @return array
     */
    public function getAllEvents($start = 0, $limit = 0, $sort = 'id', $order = 'DESC')
    {
        $crAllEvents = new \CriteriaCompo();
        $crAllEvents = $this->getEventsCriteria($crAllEvents, $start, $limit, $sort, $order);
        return $this->getAll($crAllEvents);
    }

    /**
     * Get Criteria Event
     * @param        $crEvents
     * @param int $start
     * @param int $limit
     * @param string $sort
     * @param string $order
     * @return int
     */
    private function getEventsCriteria($crEvents, int $start, int $limit, string $sort, string $order)
    {
        $crEvents->setStart($start);
        $crEvents->setLimit($limit);
        $crEvents->setSort($sort);
        $crEvents->setOrder($order);
        return $crEvents;
    }

    /**
     * @public function getForm
     * @param bool $action
     * @return \XoopsThemeForm
     */
    public function getFormEventSelect($action = false)
    {
        $helper = Helper::getInstance();
        if (!$action) {
            $action = $_SERVER['REQUEST_URI'];
        }
        // Get Theme Form
        \xoops_load('XoopsFormLoader');
        $form = new \XoopsThemeForm(\_MA_WGEVENTS_EVENT_SELECT, 'form', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        // Form Table categories
        $eventsHandler = $helper->getHandler('Event');
        $evidSelect = new \XoopsFormSelect(\_MA_WGEVENTS_EVENT_ID, 'evid', 0);
        $evidSelect->addOption('');
        $evidSelect->addOptionArray($eventsHandler->getList());
        $evidSelect->setExtra("onchange='submit()'");
        $form->addElement($evidSelect);
        // To list
        $form->addElement(new \XoopsFormHidden('op', 'list'));

        return $form;
    }

    /**
     * @public function to get events for given params
     *
     * @param int    $uid         : select by/exclude given uid
     * @param int    $start
     * @param int    $limit
     * @param int    $from        : filter date created from (timestamp)
     * @param int    $to          : filter date created to (timestamp)
     * @param bool   $mygroups    : show events of all groups of current user
     * @param bool   $excludeuid  : exclude given uid from result
     * @param int    $groupid     : filter by given group id
     * @param int    $catid       : filter by given cat id
     * @param string $sortBy
     * @param string $orderBy
     * @return array
     */
    public function getEvents($start = 0, $limit = 0, $from = 0, $to = 0, $catid = 0, $sortBy = 'id', $orderBy = 'DESC')
    {
        $crEvents = new \CriteriaCompo();
        if ($catid >  0) {
            $crEvents->add(new \Criteria('catid', $catid));
        }
        if ($from >  0) {
            $crEvents->add(new \Criteria('datefrom', $from, '>='));
            $crEvents->add(new \Criteria('dateto', $to, '<='));
        }
        $crEvents->setSort($sortBy);
        $crEvents->setOrder($orderBy);
        $eventsCount = $this->getCount($crEvents);
        if ($eventsCount > 0) {
            if ($limit > 0) {
                $crEvents->setStart($start);
                $crEvents->setLimit($limit);
            }
            $eventsAll = $this->getAll($crEvents);
            // Get All Event
            $events = [];
            foreach (\array_keys($eventsAll) as $i) {
                $events[$i] = $eventsAll[$i]->getValuesEvents();
            }

            return $events;
        }

        return [];
    }

    /**
     * compare two versions of events
     * @param  $versionOld
     * @param  $versionNew
     * @return string
     */
    public function getEventsCompare($versionOld, $versionNew)
    {
        $infotext = '';
        // find changes in important fields of table events
        $fields = [];
        $fields[] = ['name' => 'name', 'caption' => \_MA_WGEVENTS_EVENT_NAME, 'type' => 'text'];
        $fields[] = ['name' => 'desc', 'caption' => \_MA_WGEVENTS_EVENT_DESC, 'type' => 'text'];
        $fields[] = ['name' => 'datefrom', 'caption' => \_MA_WGEVENTS_EVENT_DATEFROM, 'type' => 'datetime'];
        $fields[] = ['name' => 'dateto', 'caption' => \_MA_WGEVENTS_EVENT_DATETO, 'type' => 'datetime'];
        $fields[] = ['name' => 'location', 'caption' => \_MA_WGEVENTS_EVENT_LOCATION, 'type' => 'text'];
        $fields[] = ['name' => 'fee', 'caption' => \_MA_WGEVENTS_EVENT_FEE, 'type' => 'text'];

        foreach ($fields as $field) {
            $valueOld = $versionOld->getVar($field['name']);
            $valueNew = $versionNew->getVar($field['name']);
            if ($valueOld != $valueNew) {
                if ('datetime' == $field['type']) {
                    $infotext .= \sprintf(\_MA_WGEVENTS_MAIL_REG_MODIFICATION, $field['caption'], \formatTimestamp($valueOld, 'm'), \formatTimestamp($valueNew, 'm')) . PHP_EOL;
                } else {
                    $infotext .= \sprintf(\_MA_WGEVENTS_MAIL_REG_MODIFICATION, $field['caption'], $valueOld, $valueNew) . PHP_EOL;
                }
            }
        }

        return $infotext;
    }
}
