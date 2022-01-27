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

use XoopsModules\Wgevents\{
    Helper,
    Constants
};

/**
 * Class Object Handler Mails
 */
class MailsHandler
{
    /**
     * @var string
     */
    private $info = '';

    /**
     * @var array|string
     */
    private $notifyEmails = '';

    /**
     * Constructor
     *
     * @param null
     */
    public function __construct()
    {
    }

    /**
     * Function to send mails for new/update registrations
     *
     * @param int $regId
     * @param int $type
     * @return bool
     */
    public function executeReg(int $regId, int $type)
    {
        $helper = Helper::getInstance();
        $eventsHandler = $helper->getHandler('Events');
        $registrationsHandler = $helper->getHandler('Registrations');
        $permissionsHandler = $helper->getHandler('Permissions');
        $logsHandler = $helper->getHandler('Logs');

        $logsHandler->createLog('Start MailsHandler/executeReg');


        $registrationsObj = $registrationsHandler->get($regId, true);
        $regEvid = $registrationsObj->getVar('reg_evid');
        $eventsObj = $eventsHandler->get($regEvid);
        if (!$permissionsHandler->getPermRegistrationsEdit(
                $registrationsObj->getVar('reg_ip'),
                $registrationsObj->getVar('reg_submitter'),
                $eventsObj->getVar('ev_submitter'),
                $eventsObj->getVar('ev_status'),
            )) {
                return false;
        }

        $eventUrl       = \WGEVENTS_URL . '/events.php?op=show&ev_id=' . $regEvid;
        $eventName      = $eventsObj->getVar('ev_name');
        $eventDate      = \formatTimestamp($eventsObj->getVar('ev_datefrom'), 'm');
        $eventLocation  = $eventsObj->getVar('ev_location');
        $senderMail     = $eventsObj->getVar('ev_register_sendermail');
        $senderName     = $eventsObj->getVar('ev_register_sendername');
        $senderSignatur = $eventsObj->getVar('ev_register_signature');
        $firstname      = $registrationsObj->getVar('reg_firstname');
        $lastname       = $registrationsObj->getVar('reg_lastname');
        $userName       = $GLOBALS['xoopsConfig']['anonymous'];
        if (\is_object($GLOBALS['xoopsUser'])) {
            $userName  = ('' != (string)$GLOBALS['xoopsUser']->name()) ? $GLOBALS['xoopsUser']->name() : $GLOBALS['xoopsUser']->uname();
        }
        $infotext = (string)$this->info;
        if ('' == $infotext) {
            //must have minimum one blank space in order to replace it in the template
            $infotext = ' ';
        }

        switch ($type) {
            case 0:
            default:
                /***** handled with executeRegDelete *****
                 * case Constants::MAIL_REG_CONFIRM_OUT:
                 * case Constants::MAIL_REG_NOTIFY_OUT:
                 */
                return false;
            case Constants::MAIL_REG_CONFIRM_IN:
                $template = 'mail_reg_confirm_in.tpl';
                $subject = \_MA_WGEVENTS_MAIL_REG_IN_SUBJECT;
                break;
            case Constants::MAIL_REG_CONFIRM_MODIFY:
                $template = 'mail_reg_confirm_modify.tpl';
                $subject = \_MA_WGEVENTS_MAIL_REG_MODIFY_SUBJECT;
                break;
            case Constants::MAIL_REG_NOTIFY_IN:
                $template = 'mail_reg_notify_in.tpl';
                $subject = \_MA_WGEVENTS_MAIL_REG_IN_SUBJECT;
                break;
            case Constants::MAIL_REG_NOTIFY_MODIFY:
                $template = 'mail_reg_notify_modify.tpl';
                $subject = \_MA_WGEVENTS_MAIL_REG_MODIFY_SUBJECT;
                break;
            case Constants::MAIL_EVENT_NOTIFY_MODIFY:
                $template = 'mail_event_notify_modify.tpl';
                $subject = \_MA_WGEVENTS_MAIL_EVENT_NOTIFY_MODIFY_SUBJECT;
                break;
        }

        $xoopsMailer = xoops_getMailer();
        $xoopsMailer->useMail();
        //set template path
        if (\file_exists(\WGEVENTS_PATH . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/')) {
            $xoopsMailer->setTemplateDir(\WGEVENTS_PATH . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/mail_template/');
        } else {
            $xoopsMailer->setTemplateDir(\WGEVENTS_PATH . '/language/english/mail_template/');
        }
        //set template name
        $xoopsMailer->setTemplate($template);
        //set sender
        $xoopsMailer->setFromEmail($senderMail);
        //set sender name
        $xoopsMailer->setFromName($senderName);
        //set subject
        $xoopsMailer->setSubject($subject);
        //assign vars
        $xoopsMailer->assign('UNAME', $userName);
        $xoopsMailer->assign('NAME', \trim($firstname . ' ' . $lastname));
        $xoopsMailer->assign('EVENTNAME', $eventName);
        $xoopsMailer->assign('EVENTDATEFROM', $eventDate);
        $xoopsMailer->assign('EVENTLOCATION', $eventLocation);
        $xoopsMailer->assign('INFOTEXT', $infotext);
        $xoopsMailer->assign('EVENTURL', $eventUrl);
        if ('' == $senderSignatur) {
            $senderSignatur = ' ';
        }
        $xoopsMailer->assign('SIGNATURE', $senderSignatur);
        //set recipient
        $xoopsMailer->setToEmails($this->notifyEmails);

        //send mail
        if ($xoopsMailer->send()) {
            $logsHandler->createLog('Result MailsHandler/executeReg: success');
        } else {
            $logsHandler->createLog('Result MailsHandler/executeReg: failed' .$xoopsMailer->getErrors());
        }
        $xoopsMailer->reset();

        return true;
    }

    /**
     * Function to send mails after deleting registration
     *
     * @param array $regParams
     * @param int $type
     * @return bool
     */
    public function executeRegDelete(array $regParams, int $type)
    {
        $helper = Helper::getInstance();
        $eventsHandler = $helper->getHandler('Events');

        $logsHandler = $helper->getHandler('Logs');
        $logsHandler->createLog('Start MailsHandler/executeRegDelete');

        $eventsObj = $eventsHandler->get($regParams['reg_evid']);

        $eventUrl       = \WGEVENTS_URL . '/events.php?op=show&ev_id=' . $regParams['reg_evid'];
        $eventName      = $eventsObj->getVar('ev_name');
        $eventDate      = \formatTimestamp($eventsObj->getVar('ev_datefrom'), 'm');
        $eventLocation  = $eventsObj->getVar('ev_location');
        $senderMail     = $eventsObj->getVar('ev_register_sendermail');
        $senderName     = $eventsObj->getVar('ev_register_sendername');
        $senderSignatur = $eventsObj->getVar('ev_register_signature');
        $firstname      = $regParams['reg_firstname'];
        $lastname       = $regParams['reg_lastname'];
        $userName       = $GLOBALS['xoopsConfig']['anonymous'];
        if (\is_object($GLOBALS['xoopsUser'])) {
            $userName  = ('' != (string)$GLOBALS['xoopsUser']->name()) ? $GLOBALS['xoopsUser']->name() : $GLOBALS['xoopsUser']->uname();
        }
        switch ($type) {
            case Constants::MAIL_REG_CONFIRM_OUT:
                $template = 'mail_reg_confirm_out.tpl';
                $subject = \_MA_WGEVENTS_MAIL_REG_OUT_SUBJECT;
                break;
            case Constants::MAIL_REG_NOTIFY_OUT:
                $template = 'mail_reg_notify_out.tpl';
                $subject = \_MA_WGEVENTS_MAIL_REG_OUT_SUBJECT;
                break;
        }

        $xoopsMailer = xoops_getMailer();
        $xoopsMailer->useMail();
        //set template path
        //echo 'template:' . $template . '<br>';
        if (\file_exists(\WGEVENTS_PATH . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/')) {
            $xoopsMailer->setTemplateDir(\WGEVENTS_PATH . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/mail_template/');
            //echo \WGEVENTS_PATH . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/mail_template/';
        } else {
            $xoopsMailer->setTemplateDir(\WGEVENTS_PATH . '/language/english/mail_template/');
            //echo \WGEVENTS_PATH . '/language/english/mail_template/';
        }
        //set template name
        $xoopsMailer->setTemplate($template);
        //set sender
        $xoopsMailer->setFromEmail($senderMail);
        //set sender name
        $xoopsMailer->setFromName($senderName);
        //set subject
        $xoopsMailer->setSubject($subject);
        //assign vars
        $xoopsMailer->assign('UNAME', $userName);
        $xoopsMailer->assign('NAME', \trim($firstname . ' ' . $lastname));
        $xoopsMailer->assign('EVENTNAME', $eventName);
        $xoopsMailer->assign('EVENTDATEFROM', $eventDate);
        $xoopsMailer->assign('EVENTLOCATION', $eventLocation);
        if ($this->info) {
            $xoopsMailer->assign('INFOTEXT', $this->info);
        }
        $xoopsMailer->assign('EVENTURL', $eventUrl);
        if ('' == $senderSignatur) {
            $senderSignatur = ' ';
        }
        $xoopsMailer->assign('SIGNATURE', $senderSignatur);
        //set recipient
        $xoopsMailer->setToEmails($this->notifyEmails);

        //send mail
        if ($xoopsMailer->send()) {
            $logsHandler->createLog('Result MailsHandler/executeRegDelete: success');
        } else {
            $logsHandler->createLog('Result MailsHandler/executeRegDelete: failed');
        }
        $xoopsMailer->reset();

        return true;
    }

    /**
     * Set info text
     * @param string $text
     * @return void
     */
    public function setInfo(string $text)
    {
        $this->info = $text;
    }

    /**
     * Set recipients
     * @param string $notifyEmails
     * @return void
     */
    public function setNotifyEmails(string $notifyEmails)
    {
        $this->notifyEmails = $notifyEmails;
    }

}
