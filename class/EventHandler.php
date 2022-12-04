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
     * TODO: not in use currently
     * @public function getForm
     * @param array  $params
     * @param string $action
     * @return Forms\FormInline
     */
    public function getFormPageNavCounter($params = [], $action = '')
    {
        if (!$action) {
            $action = $_SERVER['REQUEST_URI'];
        }
        // Get Theme Form
        \xoops_load('XoopsFormLoader');
        $form = new Forms\FormInline('', 'formPageNavCounter', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        // Form Table categories
        $pageNavTray = new Forms\FormElementTray('', '');
        $evidSelect = new \XoopsFormSelect(\_MA_WGEVENTS_EVENT_ID, 'limit', $params['limit']);
        $evidSelect->addOption(10);
        $evidSelect->addOption(20);
        $evidSelect->addOption(30);
        $evidSelect->addOption(40);
        $evidSelect->addOption(50);
        $evidSelect->addOption(100);
        $evidSelect->addOption(0, _ALL);
        $evidSelect->setExtra("onchange='submit()'");
        $pageNavTray->addElement($evidSelect);
        $form->addElement($pageNavTray);
        // To list
        $form->addElement(new \XoopsFormHidden('op',         $params['op']));
        $form->addElement(new \XoopsFormHidden('start', 0));
        $form->addElement(new \XoopsFormHidden('cat_id',     $params['cat_id']));
        $form->addElement(new \XoopsFormHidden('filterCats', $params['filterCats']));

        return $form;
    }

    /**
     * @public function to get events for given params
     *
     * @param int    $start
     * @param int    $limit
     * @param int    $dateFrom      // filter date created from (timestamp)
     * @param int    $dateTo        // filter date created to (timestamp)
     * @param string $sortBy
     * @param string $orderBy
     * @param string $op
     * @param int    $evId
     * @param string $filter
     * @param array  $filterCats
     * @param int    $dateCreated
     * @return array
     */
    public function getEvents($start = 0, $limit = 0, $dateFrom = 0, $dateTo = 0, $sortBy = 'datefrom', $orderBy = 'ASC', $op = 'list', $evId = 0, $filter = '', $filterCats = [], $dateCreated = 0)
    {
        $helper = Helper::getInstance();

        /*
        echo '<br>start:'.$start;
        echo '<br>limit:'.$limit;
        echo '<br>datefrom:'.\formatTimestamp($dateFrom, 'm').'('.$dateFrom.')';
        echo '<br>dateto:'.\formatTimestamp($dateTo, 'm').'('.$dateTo.')';
        echo '<br>sortBy:'.$sortBy;
        echo '<br>orderBy:'.$orderBy;
        echo '<br>op:'.$op;
        echo '<br>evId:'.$evId;
        echo '<br>filter:'.$filter;
        foreach ($filterCats as $filterCat) {
            echo '<br>filterCat:'.$filterCat;
        }
        */

        $showItem = ($evId > 0);
        $uidCurrent  = 0;
        $userIsAdmin = false;
        if (\is_object($GLOBALS['xoopsUser'])) {
            $uidCurrent  = $GLOBALS['xoopsUser']->uid();
            $userIsAdmin = $GLOBALS['xoopsUser']->isAdmin();
        }
        $useGroups = (bool)$helper->getConfig('use_groups');

        //apply criteria for events
        $crEvent = new \CriteriaCompo();
        if ($showItem) {
            $crEvent->add(new \Criteria('id', $evId));
        } else {
            if ('me' == $filter && $uidCurrent > 0) {
                $crEvent->add(new \Criteria('submitter', $uidCurrent));
            }
        }
        //get only events which are online or from me
        $crEventOnline = new \CriteriaCompo();
        $crEventOnline->add(new \Criteria('status', Constants::STATUS_OFFLINE, '>'));
        $crEventOnline->add(new \Criteria('submitter', $uidCurrent), 'OR');
        $crEvent->add($crEventOnline);

        if ($dateCreated > 0) {
            $crEvent->add(new \Criteria('datecreated', $dateCreated, '>='));
        }
        if ($useGroups) {
            // current user
            // - must have perm to see event or
            // - must be event owner
            // - is admin
            if (!$userIsAdmin) {
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
        }
        if (!$showItem) {
            if ('past' == $op) {
                // list events before now
                $crEvent->add(new \Criteria('datefrom', $dateFrom, '<'));
                $sortBy  = 'datefrom';
                $orderBy = 'DESC';
            } else {
                // calendar view:
                // - event start is between dateFrom and dateTo
                // - event end is between dateFrom and dateTo
                // ==> dateFrom and dateTo needed

                // index/event/block view:
                // - event start or event end is greater than dateFrom
                // ==> dateFrom needed, dateTo must be 0
                $crEventFromTo = new \CriteriaCompo();
                $crEventStart = new \CriteriaCompo();
                $crEventStart->add(new \Criteria('datefrom', $dateFrom, '>='));
                if ($dateTo > 0) {
                    $crEventStart->add(new \Criteria('datefrom', $dateTo, '<='));
                }
                $crEventFromTo->add($crEventStart);
                $crEventEnd = new \CriteriaCompo();
                $crEventEnd->add(new \Criteria('dateto', $dateFrom, '>='));
                if ($dateTo > 0) {
                    $crEventEnd->add(new \Criteria('dateto', $dateTo, '<='));
                }
                $crEventFromTo->add($crEventEnd, 'OR');
                $crEvent->add($crEventFromTo);

                unset($crEventStart, $crEventEnd, $crEventFromTo);
                $sortBy  = 'datefrom';
                $orderBy = 'ASC';
            }
            if (\count($filterCats) > 0) {
                $crEventCats = new \CriteriaCompo();
                $crEventCats->add(new \Criteria('catid', '(' . \implode(',', $filterCats) . ')', 'IN'));
                foreach ($filterCats as $filterCat) {
                    $crEventCats->add(new \Criteria('subcats', '%"' . $filterCat . '"%', 'LIKE'), 'OR');
                }
                $crEvent->add($crEventCats);
            }
        }
        $crEvent->setSort($sortBy);
        $crEvent->setOrder($orderBy);
        $eventsCount = $this->getCount($crEvent);
        if ($eventsCount > 0) {
            if ($limit > 0 && !$showItem) {
                $crEvent->setStart($start);
                $crEvent->setLimit($limit);
            }
            // Get All Event
            $eventsAll = $this->getAll($crEvent);

            return ['count' => $eventsCount, 'eventsAll' => $eventsAll];
        }

        return ['count' => 0, 'eventsAll' => []];
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
    /**
     * get clean date from/to for displaying
     * @param  int $datefrom
     * @param  int $dateto
     * @param  bool $allday
     * @return string
     */
    public function getDateFromToText($datefrom, $dateto, $allday)
    {
        $text = '';
        $today = date('d.m.Y', time()) === date('d.m.Y', $datefrom);
        $multiday = (int)date('j', $dateto) > (int)date('j', $datefrom);

        // currently only used by blocks
        //if (\defined(\_MB_WGEVENTS_EVENT_TODAY)) {
            $lng_today = \_MA_WGEVENTS_EVENT_TODAY;
            $lng_allday = \_MA_WGEVENTS_EVENT_ALLDAY;
        //}

        if ($today) {
            // get all types of today
            if ($allday && !$multiday) {
                // today, allday, no multiday
                $text = $lng_today . ' ' . $lng_allday;
            } else if ($today && !$allday && !$multiday) {
                // today, no allday, no multiday
                $text = $lng_today . ' ' . date('H:i', $datefrom) . ' - ' . date('H:i', $dateto);
            } else {
                // today, no allday, multiday
                $text = $lng_today . ' ' . date('H:i', $datefrom) . ' - ' . \formatTimestamp($dateto, 'm');
            }
        } else {
            // not today
            if ($allday && $multiday) {
                // allday, multiday
                $text =  \formatTimestamp($datefrom, 's') . $lng_allday . ' - ' . \formatTimestamp($dateto, 'm') . $lng_allday;
            } else if (!$allday && !$multiday) {
                // no allday, no multiday
                $text = \formatTimestamp($datefrom, 's') . ' ' . date('H:i', $datefrom) . ' - ' . date('H:i', $dateto);
            } else {
                // no allday, multiday
                $text = \formatTimestamp($datefrom, 'm') . ' - ' . \formatTimestamp($dateto, 'm');
            }
        }
        /*
        echo '<br>today:'.$today;
        echo '<br>datefrom:'.\formatTimestamp($datefrom, 'm');
        echo '<br>dateto:'.\formatTimestamp($dateto, 'm');
        echo '<br>multiday:'.$multiday;
        echo '<br>return:'.$text;
        */
        return $text;
    }


    /**
     * @public function getFormFilterExport: form for selecting cats and number of lines for export of events
     * @param bool  $eventDisplayCats
     * @param array $filterCats
     * @return \XoopsThemeForm
     */
    public function getFormFilterExport($limit, $dateFrom, $dateTo, $eventDisplayCats = false, $filterCats = [])
    {
        $helper = Helper::getInstance();

        $categoryHandler = $helper->getHandler('Category');
        // Get Theme Form
        \xoops_load('XoopsFormLoader');
        $form = new \XoopsThemeForm('', 'formFilterExport', $_SERVER['REQUEST_URI'], 'post', true);
        $form->setExtra('enctype="multipart/form-data"');

        if ($eventDisplayCats) {
            $cbAll = 1;
            // Form Select categories
            $catsOnline = $categoryHandler->getAllCatsOnline();
            if (0 == \count($filterCats)) {
                foreach (\array_keys($catsOnline) as $i) {
                    $filterCats[] = $i;
                }
            } elseif (\count($filterCats) < \count($catsOnline)) {
                $cbAll = 0;
            }
            $catTray = new \XoopsFormElementTray(\_MA_WGEVENTS_CATEGORY_FILTER);
            // checkbox for(de)select all
            $catAllSelect = new Forms\FormCheckboxInline('', 'all_cats', $cbAll);
            $catAllSelect->addOption(1, _ALL);
            $catAllSelect->setExtra(" onclick='toggleAllCats()' ");
            // checkboxes for all existing categories
            $catTray->addElement($catAllSelect);
            $catSelect = new Forms\FormCheckboxInline('', 'filter_cats', $filterCats);
            $catSelect->addOptionArray($catsOnline);
            $catTray->addElement($catSelect);
            $form->addElement($catTray);
        }
        // Form Text Date Select evDateto
        $form->addElement(new \XoopsFormDateTime(\_MA_WGEVENTS_EVENT_DATEFROM, 'datefrom', 15, $dateFrom), true);
        // Form Text Date Select evDateto
        $form->addElement(new \XoopsFormDateTime(\_MA_WGEVENTS_EVENT_DATETO, 'dateto', 15, $dateTo));
        // Form Select for setting limit of events
        $eventCountSelect = new \XoopsFormSelect(\_MA_WGEVENTS_EVENTS_FILTER_NB, 'limit', $limit);
        $eventCountSelect->addOption(10, 10);
        $eventCountSelect->addOption(20, 20);
        $eventCountSelect->addOption(30, 30);
        $eventCountSelect->addOption(40, 40);
        $eventCountSelect->addOption(50, 50);
        $eventCountSelect->addOption(0, _ALL);
        $form->addElement($eventCountSelect);
        $btnFilter = new \XoopsFormButton('', 'submit', \_MA_WGEVENTS_APPLY_FILTER, 'submit');
        $btnFilter->setClass('btn btn-success');
        $form->addElement($btnFilter);

        // To Save
        $form->addElement(new \XoopsFormHidden('op', 'list'));
        $form->addElement(new \XoopsFormHidden('start', '0'));
        $form->addElement(new \XoopsFormHidden('new', '1'));
        //$form->addElement(new \XoopsFormHidden('filter', $filter));
        return $form;
    }
}
