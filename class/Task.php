<?php

declare(strict_types=1);


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
 * Class Object Task
 */
class Task extends \XoopsObject
{
    /**
     * Constructor
     *
     * @param null
     */
    public function __construct()
    {
        $this->initVar('id', \XOBJ_DTYPE_INT);
        $this->initVar('type', \XOBJ_DTYPE_INT);
        $this->initVar('params', \XOBJ_DTYPE_TXTAREA);
        $this->initVar('recipient', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('datecreated', \XOBJ_DTYPE_INT);
        $this->initVar('submitter', \XOBJ_DTYPE_INT);
        $this->initVar('status', \XOBJ_DTYPE_INT);
        $this->initVar('datedone', \XOBJ_DTYPE_INT);
    }

    /**
     * @static function &getInstance
     *
     * @param null
     */
    public static function getInstance()
    {
        static $instance = false;
        if (!$instance) {
            $instance = new self();
        }
    }

    /**
     * The new inserted $Id
     * @return inserted id
     */
    public function getNewInsertedIdTasks()
    {
        $newInsertedId = $GLOBALS['xoopsDB']->getInsertId();
        return $newInsertedId;
    }

    /**
     * @public function getForm
     * @param bool $action
     * @return \XoopsThemeForm
     */
    public function getFormTasks($action = false)
    {
        if (!$action) {
            $action = $_SERVER['REQUEST_URI'];
        }
        // Title
        $title = $this->isNew() ? \_AM_WGEVENTS_ADD_TASK : \_AM_WGEVENTS_EDIT_TASK;
        // Get Theme Form
        \xoops_load('XoopsFormLoader');
        $form = new \XoopsThemeForm($title, 'form', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        // Form Select taskType
        $form->addElement(new \XoopsFormText(\_AM_WGEVENTS_TASK_TYPE, 'type', 50, 255, $this->getVar('type')));
        // Form Editor TextArea taskParams
        $form->addElement(new \XoopsFormTextArea(\_AM_WGEVENTS_TASK_PARAMS, 'params', $this->getVar('params', 'e'), 10, 47), false);
        // Form Editor TextArea taskRecipient
        $form->addElement(new \XoopsFormText(\_AM_WGEVENTS_TASK_RECIPIENT, 'recipient', 50, 255, $this->getVar('recipient')));
        // Form Text Date Select taskDatecreated
        $taskDatecreated = $this->isNew() ? \time() : $this->getVar('datecreated');
        $form->addElement(new \XoopsFormDateTime(\_MA_WGEVENTS_DATECREATED, 'datecreated', '', $taskDatecreated), true);
        // Form Select User taskSubmitter
        $uidCurrent = \is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->uid() : 0;
        $taskSubmitter = $this->isNew() ? $uidCurrent : $this->getVar('submitter');
        $form->addElement(new \XoopsFormSelectUser(\_MA_WGEVENTS_SUBMITTER, 'submitter', false, $taskSubmitter), true);
        // Form Select Status taskStatus
        $taskStatusSelect = new \XoopsFormSelect(\_MA_WGEVENTS_STATUS, 'status', $this->getVar('status'));
        $taskStatusSelect->addOption(Constants::STATUS_NONE, \_MA_WGEVENTS_STATUS_NONE);
        $taskStatusSelect->addOption(Constants::STATUS_PENDING, \_MA_WGEVENTS_STATUS_PENDING);
        $taskStatusSelect->addOption(Constants::STATUS_PROCESSING, \_MA_WGEVENTS_STATUS_PROCESSING);
        $taskStatusSelect->addOption(Constants::STATUS_DONE, \_MA_WGEVENTS_STATUS_DONE);
        $form->addElement($taskStatusSelect, true);
        // Form Text Date Select taskDatedone
        $taskDatedone = $this->isNew() ? \time() : $this->getVar('datedone');
        $form->addElement(new \XoopsFormDateTime(\_AM_WGEVENTS_TASK_DATEDONE, 'datedone', '', $taskDatedone), true);
        // To Save
        $form->addElement(new \XoopsFormHidden('op', 'save'));
        $form->addElement(new \XoopsFormButtonTray('', \_SUBMIT, 'submit', '', false));
        return $form;
    }

    /**
     * Get Values
     * @param null $keys
     * @param null $format
     * @param null $maxDepth
     * @return array
     */
    public function getValuesTasks($keys = null, $format = null, $maxDepth = null)
    {
        $helper  = \XoopsModules\Wgevents\Helper::getInstance();
        $utility = new \XoopsModules\Wgevents\Utility();
        $editorMaxchar = $helper->getConfig('editor_maxchar');
        $ret = $this->getValues($keys, $format, $maxDepth);
        $ret['type_text']    = $this->getMailNotificationText($this->getVar('type'));
        $ret['params_text']    = $this->getVar('params', 'e');
        $ret['params_short']   = $utility::truncateHtml($ret['params'], $editorMaxchar);
        $ret['datecreated_text'] = \formatTimestamp($this->getVar('datecreated'), 'm');
        $ret['submitter_text']   = \XoopsUser::getUnameFromId($this->getVar('submitter'));
        $ret['status_text']      = Utility::getStatusText($this->getVar('status'));
        $ret['datedone_text']    = \formatTimestamp($this->getVar('datedone'), 'm');
        return $ret;
    }

    /**
     * @private function to get text constants mail notification
     * @param $const
     * @return string
     */
    private function getMailNotificationText($const)
    {
        switch ($const) {
            case Constants::MAIL_REG_CONFIRM_IN:
                $const_text = 'MAIL_REG_CONFIRM_IN';
                break;
            case Constants::MAIL_REG_CONFIRM_OUT:
                $const_text = 'MAIL_REG_CONFIRM_OUT';
                break;
            case Constants::MAIL_REG_CONFIRM_MODIFY:
                $const_text = 'MAIL_REG_CONFIRM_MODIFY';
                break;
            case Constants::MAIL_REG_NOTIFY_IN:
                $const_text = 'MAIL_REG_NOTIFY_IN';
                break;
            case Constants::MAIL_REG_NOTIFY_OUT:
                $const_text = 'MAIL_REG_NOTIFY_OUT';
                break;
            case Constants::MAIL_REG_NOTIFY_MODIFY:
                $const_text = 'MAIL_REG_NOTIFY_MODIFY';
                break;
            case Constants::MAIL_EVENT_NOTIFY_MODIFY:
                $const_text = 'MAIL_EVENT_NOTIFY_MODIFY';
                break;
            case Constants::MAIL_EVENT_NOTIFY_ALL:
                $const_text = 'MAIL_EVENT_NOTIFY_ALL';
                break;
            case 0:
            default:
                $const_text = 'invalid constant text';
                break;
        }

        return $const_text;
    }

    /**
     * Returns an array representation of the object
     *
     * @return array
     */
    public function toArrayTasks()
    {
        $ret = [];
        $vars = $this->getVars();
        foreach (\array_keys($vars) as $var) {
            $ret[$var] = $this->getVar($var);
        }
        return $ret;
    }
}
