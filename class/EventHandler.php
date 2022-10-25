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
use XoopsModules\Wgevents\MailHandler;


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
        parent::__construct($db, 'wgevents_event', Event::class, 'id', 'name');
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
     * @param        $crEvent
     * @param int $start
     * @param int $limit
     * @param string $sort
     * @param string $order
     * @return int
     */
    private function getEventsCriteria($crEvent, int $start, int $limit, string $sort, string $order)
    {
        $crEvent->setStart($start);
        $crEvent->setLimit($limit);
        $crEvent->setSort($sort);
        $crEvent->setOrder($order);
        return $crEvent;
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
        $eventHandler = $helper->getHandler('Event');
        $evidSelect = new \XoopsFormSelect(\_MA_WGEVENTS_EVENT_ID, 'evid', 0);
        $evidSelect->addOption('');
        $evidSelect->addOptionArray($eventHandler->getList());
        $evidSelect->setExtra("onchange='submit()'");
        $form->addElement($evidSelect);
        // To list
        $form->addElement(new \XoopsFormHidden('op', 'list'));

        return $form;
    }

    /**
     * @public function to get events for given params
     *
     * @param int $start
     * @param int $limit
     * @param int $from      // filter date created from (timestamp)
     * @param int $to        // filter date created to (timestamp)
     * @param int $catid     // filter by given cat id
     * @param string $sortBy
     * @param string $orderBy
     * @return array
     */
    public function getEvents($start = 0, $limit = 0, $from = 0, $to = 0, $catid = 0, $sortBy = 'id', $orderBy = 'DESC')
    {
        $crEvent = new \CriteriaCompo();
        if ($catid >  0) {
            $crEvent->add(new \Criteria('catid', $catid));
        }
        if ($from >  0) {
            //event start is between from and to
            $crEventStart = new \CriteriaCompo();
            $crEventStart->add(new \Criteria('datefrom', $from, '>='));
            $crEventStart->add(new \Criteria('datefrom', $to, '<='));
            $crEvent->add($crEventStart);
            //event end is between from and to
            $crEventEnd = new \CriteriaCompo();
            $crEventEnd->add(new \Criteria('dateto', $from, '>='));
            $crEventEnd->add(new \Criteria('dateto', $to, '<='));
            $crEvent->add($crEventEnd, 'OR');
            unset($crEventStart, $crEventEnd);
        }
        $crEvent->setSort($sortBy);
        $crEvent->setOrder($orderBy);
        $eventsCount = $this->getCount($crEvent);
        if ($eventsCount > 0) {
            if ($limit > 0) {
                $crEvent->setStart($start);
                $crEvent->setLimit($limit);
            }
            $eventsAll = $this->getAll($crEvent);
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
        $changedValues = [];
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
                if ('' == $valueNew) {
                    $valueNew = _MA_WGEVENTS_MAIL_REG_MODIFICATION_DELETED;
                }
                switch ($field['type']) {
                    case 'datetime':
                        $changedValues[] = [
                            'caption' => $field['caption'],
                            'valueOld' => \formatTimestamp($valueOld, 'm'),
                            'valueNew' => \formatTimestamp($valueNew, 'm')
                        ];
                        break;
                    case'default':
                    default:
                       $changedValues[] = [
                            'caption' => $field['caption'],
                            'valueOld' => $valueOld,
                            'valueNew' => $valueNew
                        ];
                        break;
                }
            }
        }
        if (\count($changedValues) > 0) {
            $mailHandler = new MailHandler();
            return $mailHandler->array2table ($changedValues);
        }

        return '';
    }
    /**
     * get email recipients for noticiations
     * @param  $registerNotify
     * @return array|false|string[]
     */
    public function getRecipientsNotify($registerNotify)
    {
        $notifyEmails   = preg_split("/\r\n|\n|\r/", $registerNotify);
        // no notification to myself
        if (\is_object($GLOBALS['xoopsUser'])) {
            $email = $GLOBALS['xoopsUser']->email();
            if ('' != $email) {
                foreach ($notifyEmails as $key => $value) {
                    if ($value == $email) {
                        unset($notifyEmails[$key]);
                    }
                }
            }
       }

        return $notifyEmails;
    }
}
