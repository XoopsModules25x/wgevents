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

\defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class Object PermissionHandler
 */
class PermissionHandler extends \XoopsPersistableObjectHandler
{
    /**
     * Constructor
     *
     * @param null
     */
    public function __construct()
    {
    }


    /**
     * @private function getPermSubmit
     * returns right to submit for given perm
     * @param $constantPerm
     * @return bool
     */
    private function getPerm($constantPerm)
    {
        global $xoopsUser, $xoopsModule;

        $currentuid = 0;
        if (isset($xoopsUser) && \is_object($xoopsUser)) {
            if ($xoopsUser->isAdmin($xoopsModule->mid())) {
                return true;
            }
            $currentuid = $xoopsUser->uid();
        }
        $grouppermHandler = \xoops_getHandler('groupperm');
        $mid = $xoopsModule->mid();
        $memberHandler = \xoops_getHandler('member');
        if (0 == $currentuid) {
            $my_group_ids = [\XOOPS_GROUP_ANONYMOUS];
        } else {
            $my_group_ids = $memberHandler->getGroupsByUser($currentuid);
        }
        if ($grouppermHandler->checkRight('wgevents_ac', $constantPerm, $my_group_ids, $mid)) {
            return true;
        }

        return false;
    }

    /**
     * @private function getPermSubmit
     * returns right to submit for given perm
     * @param $constantPerm
     * @return bool
     */
    private function getPermApprove($constantPerm)
    {
        if ($this->getPermGlobalApprove()) {
            return true;
        }

        return $this->getPerm($constantPerm);
    }
    /**
     * @private function getPermSubmit
     * returns right to submit for given perm
     * @param $constantPerm
     * @return bool
     */
    private function getPermSubmit($constantPerm)
    {
        if ($this->getPermGlobalSubmit()) {
            return true;
        }

        return $this->getPerm($constantPerm);
    }

    /**
     * @private function getPermView
     * returns right for view of given perm
     * @param $constantPerm
     * @return bool
     */
    private function getPermView($constantPerm)
    {
        if ($this->getPermGlobalView()) {
            return true;
        }

        return $this->getPerm($constantPerm);
    }
    /**
     * @public function permGlobalApprove
     * returns right for global approve
     *
     * @param null
     * @return bool
     */
    public function getPermGlobalApprove()
    {
        return $this->getPerm(Constants::PERM_GLOBAL_APPROVE);
    }

    /**
     * @public function permGlobalSubmit
     * returns right for global submit
     *
     * @param null
     * @return bool
     */
    public function getPermGlobalSubmit()
    {
        if ($this->getPermGlobalApprove()) {
            return true;
        }
        return $this->getPerm(Constants::PERM_GLOBAL_SUBMIT);
    }

    /**
     * @public function permGlobalView
     * returns right for global view
     *
     * @param null
     * @return bool
     */
    public function getPermGlobalView()
    {
        if ($this->getPermGlobalApprove() || $this->getPermGlobalSubmit()) {
            return true;
        }
        return $this->getPerm(Constants::PERM_GLOBAL_APPROVE);
    }

    /**
     * @public function getPermEventsApprove
     * returns right for approve events
     * @param null
     * @return bool
     */
    public function getPermEventsApprove()
    {
        if ($this->getPermGlobalApprove()) {
            return true;
        }
        return $this->getPermApprove(Constants::PERM_EVENTS_APPROVE);
    }

    /**
     * @public function getPermEventsApproveAuto
     * returns right for approve events
     * @param null
     * @return bool
     */
    public function getPermEventsApproveAuto()
    {
        if ($this->getPermEventsApprove()) {
            return true;
        }
        return $this->getPermApprove(Constants::PERM_EVENTS_APPROVE_AUTO);
    }

    /**
     * @public function getPermEventsSubmit
     * returns right for submit events
     * @param null
     * @return bool
     */
    public function getPermEventsSubmit()
    {
        if ($this->getPermGlobalSubmit()) {
            return true;
        }
        return $this->getPermSubmit(Constants::PERM_EVENTS_SUBMIT);
    }

    /**
     * @public function getPermEventsEdit
     * returns right for edit/delete events
     *  - User must have perm to submit and must be owner
     *  - transaction is not closed
     * @param $evSubmitter
     * @param $evStatus
     * @return bool
     */
    public function getPermEventsEdit($evSubmitter, $evStatus)
    {
        global $xoopsUser, $xoopsModule;

        if ($this->getPermEventsApprove()) {
            return true;
        }

        if (Constants::STATUS_LOCKED == $evStatus) {
            return false;
        }

        $currentuid = 0;
        if (isset($xoopsUser) && \is_object($xoopsUser)) {
            if ($xoopsUser->isAdmin($xoopsModule->mid())) {
                return true;
            }
            $currentuid = $xoopsUser->uid();
        }
        if ($this->getPermEventsSubmit() && $currentuid == $evSubmitter) {
            return true;
        }

        return false;
    }

    /**
     * @public function getPermEventsView
     * returns right for view Event
     * @param null
     * @return bool
     */
    public function getPermEventsView()
    {
        return $this->getPermView(Constants::PERM_EVENTS_VIEW);
    }

    /**
     * @public function getPermQuestionsAdmin
     * returns right for submit/edit/delete questions
     *  - User must have perm edit the event
     * @param $evSubmitter
     * @param $evStatus
     * @return bool
     */
    public function getPermQuestionsAdmin($evSubmitter, $evStatus)
    {
        if ($this->getPermEventsEdit($evSubmitter, $evStatus)) {
            return true;
        }

        return false;
    }

    /**
     * @public function getPermRegistrationsApprove
     * returns right for approve registrations
     *  - User must have perm edit the event (owner or approver)
     * @param null
     * @return bool
     */
    public function getPermRegistrationsApprove($evSubmitter, $evStatus)
    {
        if ($this->getPermEventsEdit($evSubmitter, $evStatus)) {
            return true;
        }

        return false;
    }

    /**
     * @public function getPermRegistrationsVerif
     * returns right for submit registrations without question verification by mail
     *  - User must have perm to submit registrations
     * @param null
     * @return bool
     */
    public function getPermRegistrationsVerif()
    {
        return $this->getPerm(Constants::PERM_REGISTRATIONS_AUTOVERIF);
    }

    /**
     * @public function getPermRegistrationsSubmit
     * returns right for submit registrations
     * @param null
     * @return bool
     */
    public function getPermRegistrationsSubmit()
    {
        if ($this->getPermRegistrationsVerif()) {
            return true;
        }
        return $this->getPermSubmit(Constants::PERM_REGISTRATIONS_SUBMIT);
    }

    /**
     * @public function getPermRegistrationsEdit
     * returns right for edit/delete registrations
     *  - User have permission to edit the event
     *  - User must have perm to submit and must be owner
     * @param $regIp
     * @param $regSubmitter
     * @param $evSubmitter
     * @param $evStatus
     * @return bool
     */
    public function getPermRegistrationsEdit($regIp, $regSubmitter, $evSubmitter, $evStatus)
    {
        if ($this->getPermEventsEdit($evSubmitter, $evStatus)) {
            return true;
        }

        if ($this->getPermRegistrationsSubmit() && $this->getPermRegistrationsView($regIp, $regSubmitter)) {
            return true;
        }

        return false;
    }

    /**
     * @public function getPermRegistrationsView
     * returns right for view Registration
     *  - user must be the submitter_text of registration or same IP must be used
     * @param $regIp
     * @param $regSubmitter
     * @return bool
     */
    public function getPermRegistrationsView($regIp, $regSubmitter)
    {
        $uidCurrent = \is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->uid() : 0;
        if ($regSubmitter == $uidCurrent) {
            return true;
        }
        $ipCurrent = $_SERVER['REMOTE_ADDR'];
        if ($regIp == $ipCurrent) {
            return true;
        }

        return false;
    }

    /**
     * @public function getPermTextblocksSubmit
     * returns right for submit textblocks
     * @param null
     * @return bool
     */
    public function getPermTextblocksSubmit()
    {
        return $this->getPermEventsSubmit();
    }

    /**
     * @public function getPermTextblocksAdmin
     * returns right for edit/delete textblocks
     *  - User must have perm to submit and must be owner
     * @param $tbSubmitter
     * @return bool
     */
    public function getPermTextblocksEdit($tbSubmitter)
    {
        global $xoopsUser, $xoopsModule;

        if ($this->getPermGlobalApprove()) {
            return true;
        }

        $currentuid = 0;
        if (isset($xoopsUser) && \is_object($xoopsUser)) {
            if ($xoopsUser->isAdmin($xoopsModule->mid())) {
                return true;
            }
            $currentuid = $xoopsUser->uid();
        }
        if ($this->getPermTextblocksSubmit() && $currentuid == $tbSubmitter) {
            return true;
        }

        return false;
    }

    /**
     * @public function getPermTextblocksAdmin
     * returns right for edit/delete textblocks
     *  - User must have perm to submit and must be owner
     * @param $tbSubmitter
     * @return bool
     */
    public function getPermTextblocksAdmin($tbSubmitter)
    {
        global $xoopsUser, $xoopsModule;

        if ($this->getPermGlobalApprove()) {
            return true;
        }

        $currentuid = 0;
        if (isset($xoopsUser) && \is_object($xoopsUser)) {
            if ($xoopsUser->isAdmin($xoopsModule->mid())) {
                return true;
            }
            $currentuid = $xoopsUser->uid();
        }
        if ($this->getPermTextblocksSubmit() && $currentuid == $tbSubmitter) {
            return true;
        }

        return false;
    }









    /** Check permission for categories
     * @param string $permName
     * @param int $catId
     * @return int

    private function permPermCats($permName, $catId)
    {
        global $xoopsUser, $xoopsModule;

        $currentuid = 0;
        if (isset($xoopsUser) && \is_object($xoopsUser)) {
            if ($xoopsUser->isAdmin()) {
                return 1;
            }
            $currentuid = $xoopsUser->uid();
        }
        $grouppermHandler  = \xoops_getHandler('groupperm');
        $mid           = $xoopsModule->mid();
        $memberHandler = \xoops_getHandler('member');
        if (0 == $currentuid) {
            $my_group_ids = [\XOOPS_GROUP_ANONYMOUS];
        } else {
            $my_group_ids = $memberHandler->getGroupsByUser($currentuid);
        }

        return $grouppermHandler->checkRight($permName, $catId, $my_group_ids, $mid);
    }*/

    /**
     * @public function getPermCatEventsView
     * returns right for view events of this category
     * @param int $catId
     * @return bool

    public function getPermCatEventsView($catId)
    {
        return $this->permPermCats('wgevents_view_cat_events',  $catId);
    }*/

    /**
     * @public function getPermCatEventsSubmit
     * returns right for submit events to this category
     * @param int $catId
     * @return bool

    public function getPermCatEventsSubmit($catId)
    {
        return $this->permPermCats('wgevents_submit_cat_events',  $catId);
    }*/

    /**
     * @public function getPermCatEventsApprove
     * returns right for approve events of this category
     * @param int $catId
     * @return bool

    public function getPermCatEventsApprove($catId)
    {
        return $this->permPermCats('wgevents_approve_cat_events',  $catId);
    }*/

    /**
     * @public function getPermCatRegsView
     * returns right for view registrations of events of this category
     * @param int $catId
     * @return bool

    public function getPermCatRegsView($catId)
    {
        return $this->permPermCats('wgevents_view_cat_regs',  $catId);
    }*/

    /**
     * @public function getPermCatRegsSubmit
     * returns right for submit registrations to events to this category
     * @param int $catId
     * @return bool

    public function getPermCatRegsSubmit($catId)
    {
        return $this->permPermCats('wgevents_submit_cat_regs',  $catId);
    }*/

    /**
     * @public function getPermCatRegsApprove
     * returns right for approve registrations of events of this category
     * @param int $catId
     * @return bool

    public function getPermCatRegsApprove($catId)
    {
        return $this->permPermCats('wgevents_approve_cat_regs',  $catId);
    }*/

}
