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
class MailHandler
{
    /**
     * @var array
     */
    public $mailParams = [];

    /**
     * @var int
     */
    public $type = 0;

    /**
     * @var bool
     */
    public $isHtml = false;
    
    /**
     * Constructor
     *
     * @param null
     */
    public function __construct()
    {
    }

    public function setParams($value)
    {
        $this->mailParams = $value;
    }

    public function setType($value)
    {
        $this->type = $value;
    }

    public function setHtml($value)
    {
        $this->isHtml = $value;
    }

    /**
     * Function to send mails for new/update registrations
     * 
     * @return bool
     */
    public function execute()
    {
        $helper = Helper::getInstance();
        $permissionsHandler = $helper->getHandler('Permission');
        $accountHandler = $helper->getHandler('Account');
        $useLogs = (bool)$helper->getConfig('use_logs');
        if ($useLogs) {
            $logHandler = $helper->getHandler('Log');
        }

        if (Constants::MAIL_EVENT_NOTIFY_ALL == $this->type){
            //current user must have perm to edit event
            if (!$permissionsHandler->getPermEventsEdit(
                $this->mailParams['evSubmitter'],
                $this->mailParams['evStatus'],
            )) {
                return false;
            }
        } else {
            //current user must have perm to edit registration
            if (!$permissionsHandler->getPermRegistrationsEdit(
                $this->mailParams['regIp'],
                $this->mailParams['regSubmitter'],
                $this->mailParams['evSubmitter'],
                $this->mailParams['evStatus'],
            )) {
                return false;
            }
        }

        $logInfo = '<br>Task ID: ' . $this->mailParams['taskId'];

        $errors = 0;

        $eventUrl       = \WGEVENTS_URL . '/event.php?op=show&id=' . $this->mailParams['evId'];
        $eventName      = $this->mailParams['evName'];
        $eventDate      = \formatTimestamp($this->mailParams['evDatefrom'], 'm');
        $eventLocation  = '' == (string)$this->mailParams['evLocation'] ? ' ' : $this->mailParams['evLocation'];
        $senderMail     = '' == (string)$this->mailParams['evRegister_sendermail'] ? ' ' : $this->mailParams['evRegister_sendermail'];
        $senderName     = '' == (string)$this->mailParams['evRegister_sendername'] ? ' ' : $this->mailParams['evRegister_sendername'];
        $senderSignatur = '' == (string)$this->mailParams['evRegister_signature'] ? ' ' : $this->mailParams['evRegister_signature'];
        $firstname      = '' == (string)$this->mailParams['regFirstname'] ? ' ' : $this->mailParams['regFirstname'];
        $lastname       = '' == (string)$this->mailParams['regLastname'] ? ' ' : $this->mailParams['regLastname'];
        $infotext       = '' == (string)$this->mailParams['infotext'] ? ' ' : $this->mailParams['infotext'];
        $mailBody       = '' == (string)$this->mailParams['mailBody'] ? ' ' : $this->mailParams['mailBody'];
        $recipients     = $this->mailParams['recipients'];
        $userName       = $GLOBALS['xoopsConfig']['anonymous'];
        if (\is_object($GLOBALS['xoopsUser'])) {
            $userName  = ('' != (string)$GLOBALS['xoopsUser']->name()) ? $GLOBALS['xoopsUser']->name() : $GLOBALS['xoopsUser']->uname();
        }

        switch ($this->type) {
            case 0:
            default:
                return false;
            case Constants::MAIL_REG_CONFIRM_OUT:
                $template = 'mail_reg_confirm_out.tpl';
                $subject = \_MA_WGEVENTS_MAIL_REG_OUT_SUBJECT;
                break;
            case Constants::MAIL_REG_NOTIFY_OUT:
                $template = 'mail_reg_notify_out.tpl';
                $subject = \_MA_WGEVENTS_MAIL_REG_OUT_SUBJECT;
                break;
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
            case Constants::MAIL_EVENT_NOTIFY_ALL:
                $template = 'mail_event_notify_all.tpl';
                $subject = $this->mailParams['mailSubject'];
                break;
        }

        // get settings of primary account
        $primaryAcc = $accountHandler->getPrimary();
        $account_type           = (int)$primaryAcc['type'];
        //$account_yourname       = (string)$primaryAcc['yourname'];
        $account_username       = (string)$primaryAcc['yourmail'];
        $account_password       = (string)$primaryAcc['password'];
        $account_server_out     = (string)$primaryAcc['server_out'];
        $account_port_out       = (int)$primaryAcc['port_out'];
        $account_securetype_out = (string)$primaryAcc['securetype_out'];

        // create info for log
        if ($useLogs) {
            switch ($account_type) {
                case '':
                default:
                    $accountTypeDesc = 'XOOPS system';
                    break;
                case Constants::ACCOUNT_TYPE_VAL_GMAIL:
                    $accountTypeDesc = 'gmail';
                    break;
                case Constants::ACCOUNT_TYPE_VAL_PHP_MAIL:
                    $accountTypeDesc = 'php mail';
                    break;
                case Constants::ACCOUNT_TYPE_VAL_PHP_SENDMAIL:
                    $accountTypeDesc = 'sendmail';
                    break;
                case Constants::ACCOUNT_TYPE_VAL_POP3:
                    $accountTypeDesc = 'pop3';
                    break;
                case Constants::ACCOUNT_TYPE_VAL_SMTP:
                    $accountTypeDesc = 'smtp';
                    break;
            }
            $logInfo .= '<br>Mailer: ' . $accountTypeDesc;
            $logInfo .= '<br>Template: ' . $template;
        }

        try {
            /*
            if ($account_type == Constants::ACCOUNT_TYPE_VAL_PHP_SENDMAIL) {
                $pop = new POP3();
                $pop->authorise($account_server_out, $account_port_out, 30, $account_username, $account_password, 1);
            }
            */
            $xoopsMailer = xoops_getMailer();
            $xoopsMailer->useMail();
            $xoopsMailer->setHTML($this->isHtml);
            //set template path
            if (\file_exists(\WGEVENTS_PATH . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/')) {
                $xoopsMailer->setTemplateDir(\WGEVENTS_PATH . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/mail_template/');
            } else {
                $xoopsMailer->setTemplateDir(\WGEVENTS_PATH . '/language/english/mail_template/');
            }
            //set template name
            $xoopsMailer->setTemplate($template);
            $xoopsMailer->CharSet = _CHARSET; //use xoops default character set
            //set account settings
            if ('' != $account_username) {
                $xoopsMailer->Username = $account_username; // SMTP account username
            }
            if ('' != $account_password) {
                $xoopsMailer->Password = $account_password; // SMTP account password
            }
            if (Constants::ACCOUNT_TYPE_VAL_POP3 == $account_type) {
                //xoopsMailer->isSMTP();
                //$xoopsMailer->SMTPDebug = 2;
                $xoopsMailer->Host = $account_server_out;
            }
            if (Constants::ACCOUNT_TYPE_VAL_SMTP == $account_type
                || Constants::ACCOUNT_TYPE_VAL_GMAIL == $account_type) {
                $xoopsMailer->Port = $account_port_out; // set the SMTP port
                $xoopsMailer->Host = $account_server_out; //sometimes necessary to repeat
            }
            if ('' != $account_securetype_out) {
                $xoopsMailer->SMTPAuth   = true;
                $xoopsMailer->SMTPSecure = $account_securetype_out; // sets the prefix to the server
            }
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
            $xoopsMailer->assign('BODY', $mailBody);
            $xoopsMailer->assign('SIGNATURE', $senderSignatur);
            //set recipient
            $xoopsMailer->setToEmails($recipients);
            //execute sending
            if ($xoopsMailer->send()) {
                if ($useLogs) {
                    $logHandler->createLog('Result MailHandler/executeReg: success' . $logInfo);
                }
            } else {
                if ($useLogs) {
                    $logHandler->createLog('Result MailHandler/executeReg: failed' .$xoopsMailer->getErrors() . $logInfo);
                }
                $errors++;
            }
            $xoopsMailer->reset();
            unset($xoopsMailer);
        }
        catch (\Exception $e) {
            if ($useLogs) {
                $logHandler->createLog('MailHandler/executeReg failed: Exception - ' . $e->getMessage());
            }
            $errors++;
        }

        return (0 == $errors);
    }

    /**
     * Function to create mail parameters
     *
     * @param     $eventObj
     * @param int $regId
     * @return array
     */
    public function getMailParam($eventObj, $regId) {
        $helper = Helper::getInstance();
        $registrationHandler = $helper->getHandler('Registration');
        $registrationObj = $registrationHandler->get($regId);

        $mailParams = [];
        $mailParams['regIp']                 = $registrationObj->getVar('ip');
        $mailParams['regFirstname']          = $registrationObj->getVar('firstname');
        $mailParams['regLastname']           = $registrationObj->getVar('lastname');
        $mailParams['regEmail']              = $registrationObj->getVar('email');
        $mailParams['regSubmitter']          = $registrationObj->getVar('submitter');
        $mailParams['evId']                  = $eventObj->getVar('id');
        $mailParams['evSubmitter']           = $eventObj->getVar('submitter');
        $mailParams['evStatus']              = $eventObj->getVar('status');
        $mailParams['evName']                = $eventObj->getVar('name');
        $mailParams['evDatefrom']            = $eventObj->getVar('datefrom');
        $mailParams['evLocation']            = $this->replaceLinebreaks($eventObj->getVar('location'), ', ');
        $mailParams['evRegister_sendermail'] = $eventObj->getVar('register_sendermail');
        $mailParams['evRegister_sendername'] = $eventObj->getVar('register_sendername');
        $mailParams['evRegister_signature']  = $eventObj->getVar('register_signature');
        $mailParams['infotext']              = '';
        $mailParams['taskId']                = 0;

        return $mailParams;
    }

    /**
     * Function to send mails to all participants
     *
     * @return bool
     */
    public function executeContactAll()
    {
        $helper = Helper::getInstance();
        $accountHandler = $helper->getHandler('Account');
        $useLogs = (bool)$helper->getConfig('use_logs');
        if ($useLogs) {
            $logHandler = $helper->getHandler('Log');
        }

        $errors = 0;

        $template       = $this->mailParams['template'];
        $eventUrl       = \WGEVENTS_URL . '/event.php?op=show&id=' . $this->mailParams['evId'];
        $eventName      = $this->mailParams['evName'];
        $eventDate      = \formatTimestamp($this->mailParams['evDatefrom'], 'm');
        $eventLocation  = '' == (string)$this->mailParams['evLocation'] ? ' ' : $this->mailParams['evLocation'];
        $mailFrom       = $this->mailParams['mailFrom'];
        $mailSubject    = '' == (string)$this->mailParams['mailSubject'] ? ' ' : $this->mailParams['mailSubject'];
        $mailBody       = '' == (string)$this->mailParams['mailBody'] ? ' ' : $this->mailParams['mailBody'];
        $recipients     = $this->mailParams['recipients'];

         // get settings of primary account
        $primaryAcc = $accountHandler->getPrimary();
        $account_type           = (int)$primaryAcc['type'];
        //$account_yourname       = (string)$primaryAcc['yourname'];
        $account_username       = (string)$primaryAcc['yourmail'];
        $account_password       = (string)$primaryAcc['password'];
        $account_server_out     = (string)$primaryAcc['server_out'];
        $account_port_out       = (int)$primaryAcc['port_out'];
        $account_securetype_out = (string)$primaryAcc['securetype_out'];

        // create info for log
        if ($useLogs) {
            switch ($account_type) {
                case '':
                default:
                    $accountTypeDesc = 'XOOPS system';
                    break;
                case Constants::ACCOUNT_TYPE_VAL_GMAIL:
                    $accountTypeDesc = 'gmail';
                    break;
                case Constants::ACCOUNT_TYPE_VAL_PHP_MAIL:
                    $accountTypeDesc = 'php mail';
                    break;
                case Constants::ACCOUNT_TYPE_VAL_PHP_SENDMAIL:
                    $accountTypeDesc = 'sendmail';
                    break;
                case Constants::ACCOUNT_TYPE_VAL_POP3:
                    $accountTypeDesc = 'pop3';
                    break;
                case Constants::ACCOUNT_TYPE_VAL_SMTP:
                    $accountTypeDesc = 'smtp';
                    break;
            }
            $logInfo = '<br>Mailer: ' . $accountTypeDesc;
            $logInfo .= '<br>Template: ' . $template;
        }

        try {
            /*
            if ($account_type == Constants::ACCOUNT_TYPE_VAL_PHP_SENDMAIL) {
                $pop = new POP3();
                $pop->authorise($account_server_out, $account_port_out, 30, $account_username, $account_password, 1);
            }
            */
            $xoopsMailer = xoops_getMailer();

            $xoopsMailer->useMail();

            $xoopsMailer->setHTML($this->isHtml);

            //set template path
            if (\file_exists(\WGEVENTS_PATH . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/')) {
                $xoopsMailer->setTemplateDir(\WGEVENTS_PATH . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/mail_template/');
            } else {
                $xoopsMailer->setTemplateDir(\WGEVENTS_PATH . '/language/english/mail_template/');
            }
            //set template name
            $xoopsMailer->setTemplate($template);

            $xoopsMailer->CharSet = _CHARSET; //use xoops default character set

            if ('' != $account_username) {
                $xoopsMailer->Username = $account_username; // SMTP account username
            }
            if ('' != $account_password) {
                $xoopsMailer->Password = $account_password; // SMTP account password
            }
            if (Constants::ACCOUNT_TYPE_VAL_POP3 == $account_type) {
                //xoopsMailer->isSMTP();
                //$xoopsMailer->SMTPDebug = 2;
                $xoopsMailer->Host = $account_server_out;
            }
            if (Constants::ACCOUNT_TYPE_VAL_SMTP == $account_type
                || Constants::ACCOUNT_TYPE_VAL_GMAIL == $account_type) {
                $xoopsMailer->Port = $account_port_out; // set the SMTP port
                $xoopsMailer->Host = $account_server_out; //sometimes necessary to repeat
            }
            if ('' != $account_securetype_out) {
                $xoopsMailer->SMTPAuth   = true;
                $xoopsMailer->SMTPSecure = $account_securetype_out; // sets the prefix to the server
            }

            //set sender
            $xoopsMailer->setFromEmail($mailFrom);
            //set subject
            $xoopsMailer->setSubject($mailSubject);
            //assign vars
            $xoopsMailer->assign('EVENTNAME', $eventName);
            $xoopsMailer->assign('EVENTDATEFROM', $eventDate);
            $xoopsMailer->assign('EVENTLOCATION', $eventLocation);
            $xoopsMailer->assign('EVENTURL', $eventUrl);
            $xoopsMailer->assign('BODY', $mailBody);
            //set recipient
            $xoopsMailer->setToEmails($recipients);
            //execute sending
            if ($xoopsMailer->send()) {
                if ($useLogs) {
                    $logHandler->createLog('Result MailHandler/executeContactAll: success' . $logInfo);
                }
            } else {
                if ($useLogs) {
                    $logHandler->createLog('Result MailHandler/executeContactAll: failed' .$xoopsMailer->getErrors() . $logInfo);
                }
                $errors++;
            }
            $xoopsMailer->reset();
            unset($xoopsMailer);
        }
        catch (\Exception $e) {
            if ($useLogs) {
                $logHandler->createLog('MailHandler/executeContactAll failed: Exception - ' . $e->getMessage());
            }
            $errors++;
        }

        return (0 == $errors);
    }

    /**
    * Function to create a table of modifications
     *
     * @param array $changedValues
     * @return string
     */
    public function array2table ($changedValues) {
        // create object
        $xoopsTpl = new \XoopsTpl;
        // assign array
        $xoopsTpl->assign('changedValues', $changedValues);
        // display it
        return $xoopsTpl->fetch('db:wgevents_mail_table.tpl');
    }

    /**
     * Function to replace line breaks
     *
     * @param string $string
     * @param string $replaceBy
     * @return string
     */
    public function replaceLinebreaks($string, $replaceBy) {

        // replace
        return str_replace(["\r\n", "\r", "\n", "<br />", "<br>"], $replaceBy, $string);

    }
}
