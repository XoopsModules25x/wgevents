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
use XoopsModules\Wgevents\Helper;

/**
 * Class Object Handler Registration
 */
class RegistrationHandler extends \XoopsPersistableObjectHandler
{
    /**
     * Constructor
     *
     * @param \XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, 'wgevents_registrations', Registration::class, 'id', 'evid');
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
     * Get Count Registration in the database
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     * @return int
     */
    public function getCountRegistrations($start = 0, $limit = 0, $sort = 'id ASC, evid', $order = 'ASC')
    {
        $crCountRegistrations = new \CriteriaCompo();
        $crCountRegistrations = $this->getRegistrationsCriteria($crCountRegistrations, $start, $limit, $sort, $order);
        return $this->getCount($crCountRegistrations);
    }

    /**
     * Get All Registration in the database
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     * @return array
     */
    public function getAllRegistrations($start = 0, $limit = 0, $sort = 'id ASC, evid', $order = 'ASC')
    {
        $crAllRegistrations = new \CriteriaCompo();
        $crAllRegistrations = $this->getRegistrationsCriteria($crAllRegistrations, $start, $limit, $sort, $order);
        return $this->getAll($crAllRegistrations);
    }

    /**
     * Get Criteria Registration
     * @param        $crRegistrations
     * @param int $start
     * @param int $limit
     * @param string $sort
     * @param string $order
     * @return \CriteriaCompo
     */
    private function getRegistrationsCriteria($crRegistrations, int $start, int $limit, string $sort, string $order)
    {
        $crRegistrations->setStart($start);
        $crRegistrations->setLimit($limit);
        $crRegistrations->setSort($sort);
        $crRegistrations->setOrder($order);
        return $crRegistrations;
    }

    /**
     * Delete all registrations for given event
     * @param int $evId
     * @return bool
     */
    public function cleanupRegistrations(int $evId)
    {
        if ($evId > 0) {
            $helper = Helper::getInstance();
            $eventsHandler = $helper->getHandler('Event');

            $crRegistrations = new \CriteriaCompo();
            $crRegistrations->add(new \Criteria('evid', $evId));
            $registrationsCount = $this->getCount($crRegistrations);
            if ($registrationsCount > 0) {
                // declare types
                $typeNotify = Constants::MAIL_REG_NOTIFY_OUT;
                $typeConfirm = Constants::MAIL_REG_CONFIRM_OUT;
                // get mail addresses from register_notify
                $eventsObj = $eventsHandler->get($evId);
                $registerNotify = (string)$eventsObj->getVar('register_notify', 'e');
                //get all registrations
                $registrationsAll = $this->getAll($crRegistrations);
                foreach (\array_keys($registrationsAll) as $i) {
                    $regParams['evid'] = $registrationsAll[$i]->getVar('evid');
                    $regParams['firstname'] = $registrationsAll[$i]->getVar('firstname');
                    $regParams['lastname'] = $registrationsAll[$i]->getVar('lastname');
                    $regParams['email'] = $registrationsAll[$i]->getVar('email');
                    if ($this->delete($registrationsAll[$i])) {
                        // Event delete notification
                        /*
                        $tags = [];
                        $tags['ITEM_NAME'] = $regEvid;
                        $notificationHandler = \xoops_getHandler('notification');
                        $notificationHandler->triggerEvent('global', 0, 'global_delete', $tags);
                        $notificationHandler->triggerEvent('registrations', $regId, 'registration_delete', $tags);
                        */
                        // send notifications/confirmation emails
                        if ('' != $registerNotify) {
                            // send notifications to emails of register_notify
                            $notifyEmails = \pregsplit("/\r\n|\n|\r/", $registerNotify);
                            $mailsHandler = new MailHandler();
                            $mailsHandler->setNotifyEmails($notifyEmails);
                            $mailsHandler->executeRegDelete($regParams, $typeNotify);
                            unset($mailsHandler);
                        }
                        $regEmail = $regParams['email'];
                        if ('' != $regEmail) {
                            // send confirmation, if radio is checked
                            $mailsHandler = new MailHandler();
                            $mailsHandler->setNotifyEmails($regEmail);
                            $mailsHandler->executeRegDelete($regParams, $typeConfirm);
                            unset($mailsHandler);
                        }
                    } else {
                        $GLOBALS['xoopsTpl']->assign('error', $registrationsAll[$i]->getHtmlErrors());
                    }
                }
            }
        }
        return true;
    }

    /**
     * get all registrations/answers of given event
     * @param int $evId // id of event
     * @param array $questionsArr // array with all questions for this event
     * @param bool $currentUserOnly // true: filter result for current user - false: return all for given event
     * @return bool|array
     */
    public function getRegistrationDetailsByEvent(int $evId, array $questionsArr, $currentUserOnly = true)
    {
        if ($evId > 0) {
            $helper  = \XoopsModules\Wgevents\Helper::getInstance();
            $eventsHandler     = $helper->getHandler('Event');
            $answersHandler     = $helper->getHandler('Answer');
            $permissionsHandler = $helper->getHandler('Permission');

            $eventsObj = $eventsHandler->get($evId);
            $evSubmitter = $eventsObj->getVar('submitter');
            $evStatus = $eventsObj->getVar('status');

            $registrations = [];
            $crRegistrations = new \CriteriaCompo();
            $crRegistrations->add(new \Criteria('evid', $evId));
            if ($currentUserOnly) {
                $uidCurrent = \is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->uid() : 0;
                if ($uidCurrent > 0) {
                    $crRegistrations->add(new \Criteria('submitter', $uidCurrent));
                } else {
                    $regIp = $_SERVER['REMOTE_ADDR'];
                    $crRegistrations->add(new \Criteria('ip', $regIp));
                }
            }
            $registrationsCount = $this->getCount($crRegistrations);
            $GLOBALS['xoopsTpl']->assign('registrationsCount', $registrationsCount);
            $registrationsAll = $this->getAll($crRegistrations);
            if ($registrationsCount > 0) {
                // Get All Registration
                foreach (\array_keys($registrationsAll) as $regId) {
                    $registerValues = $registrationsAll[$regId]->getValuesRegistrations();
                    $registrations[$regId] = $registrationsAll[$regId]->getValuesRegistrations();
                    $registrations[$regId]['id'] = $regId;
                    $registrations[$regId]['evid'] = $evId;
                    if ($registerValues['submitter'] > 0) {
                        $registrations[$regId]['submitter_text'] = $registerValues['submitter_text'];
                    } else {
                        $registrations[$regId]['submitter_text'] = $registerValues['ip'];
                    }
                    $registrations[$regId]['permRegistrationEdit'] = $permissionsHandler->getPermRegistrationsEdit(
                                                                            $registerValues['ip'],
                                                                            $registerValues['submitter'],
                                                                            $evSubmitter,
                                                                            $evStatus,
                                                                        );
                    $registrations[$regId]['permRegistrationApprove'] = $permissionsHandler->getPermRegistrationsApprove($evSubmitter, $evStatus);
                    // get all answers for this event
                    $answers = $answersHandler->getAnswersDetailsByRegistration($regId, $questionsArr);
                    $registrations[$regId]['answers'] = $answers;
                }
                return $registrations;

            }
        }
        return false;
    }

    /**
     * compare two versions of registration
     * @param  $versionOld
     * @param  $versionNew
     * @return string
     */
    public function getRegistrationsCompare($versionOld, $versionNew)
    {
        $infotext = '';
        $fields = [];
        $fields[] = ['name' => 'firstname', 'caption' => \_MA_WGEVENTS_REGISTRATION_FIRSTNAME, 'type' => 'text'];
        $fields[] = ['name' => 'lastname', 'caption' => \_MA_WGEVENTS_REGISTRATION_LASTNAME, 'type' => 'text'];
        $fields[] = ['name' => 'email', 'caption' => \_MA_WGEVENTS_REGISTRATION_EMAIL, 'type' => 'text'];
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
        unset($fields, $field);

        $field = 'listwait';
        $valueOld = $versionOld->getVar($field);
        $valueNew = $versionNew->getVar($field);
        if ($valueOld != $valueNew) {
            $infotext .= \sprintf(\_MA_WGEVENTS_MAIL_REG_MODIFICATION, \_MA_WGEVENTS_REGISTRATION_LISTWAIT, Utility::getListWaitText($valueOld), Utility::getListWaitText($valueNew)) . PHP_EOL;
        }
        $field = 'status';
        $valueOld = $versionOld->getVar($field);
        $valueNew = $versionNew->getVar($field);
        if ($valueOld != $valueNew) {
            $infotext .= \sprintf(\_MA_WGEVENTS_MAIL_REG_MODIFICATION, \_MA_WGEVENTS_STATUS, Utility::getStatusText($valueOld), Utility::getStatusText($valueNew)) . PHP_EOL;
        }
        $field = 'financial';
        $valueOld = $versionOld->getVar($field);
        $valueNew = $versionNew->getVar($field);
        if ($valueOld != $valueNew) {
            $infotext .= \sprintf(\_MA_WGEVENTS_MAIL_REG_MODIFICATION, \_MA_WGEVENTS_REGISTRATION_FINANCIAL, Utility::getFinancialText($valueOld), Utility::getFinancialText($valueNew)) . PHP_EOL;
        }

        return $infotext;
    }

}
