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
     * @param array $mailParams
     * @param int $type
     * @return bool
     */
    public function executeReg(array $mailParams, int $type)
    {
        $helper = Helper::getInstance();
        $permissionsHandler = $helper->getHandler('Permission');
        $accountHandler = $helper->getHandler('Account');
        $useLogs = (bool)$helper->getConfig('use_logs');
        if ($useLogs) {
            $logHandler = $helper->getHandler('Log');
        }

        if (!$permissionsHandler->getPermRegistrationsEdit(
            $mailParams['regIp'],
            $mailParams['regSubmitter'],
            $mailParams['evSubmitter'],
            $mailParams['evStatus'],
            )) {
                return false;
        }

        $errors = 0;

        $eventUrl       = \WGEVENTS_URL . '/event.php?op=show&id=' . $mailParams['evId'];
        $eventName      = $mailParams['evName'];
        $eventDate      = \formatTimestamp($mailParams['evDatefrom'], 'm');
        $eventLocation  = '' == (string)$mailParams['evLocation'] ? ' ' : $mailParams['evLocation'];
        $senderMail     = '' == (string)$mailParams['evRegister_sendermail'] ? ' ' : $mailParams['evRegister_sendermail'];
        $senderName     = '' == (string)$mailParams['evRegister_sendername'] ? ' ' : $mailParams['evRegister_sendername'];
        $senderSignatur = '' == (string)$mailParams['evRegister_signature'] ? ' ' : $mailParams['evRegister_signature'];
        $firstname      = '' == (string)$mailParams['regFirstname'] ? ' ' : $mailParams['regFirstname'];
        $lastname       = '' == (string)$mailParams['regLastname'] ? ' ' : $mailParams['regLastname'];
        $infotext       = '' == (string)$mailParams['infotext'] ? ' ' : $mailParams['infotext'];
        $recipients     = $mailParams['recipients'];
        $userName       = $GLOBALS['xoopsConfig']['anonymous'];
        if (\is_object($GLOBALS['xoopsUser'])) {
            $userName  = ('' != (string)$GLOBALS['xoopsUser']->name()) ? $GLOBALS['xoopsUser']->name() : $GLOBALS['xoopsUser']->uname();
        }

        switch ($type) {
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
            unset($mail);
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
     * @param int $evId
     * @param int $regId
     * @return array
     */
    public function getMailParam($evId, $regId) {
        $helper = Helper::getInstance();
        $registrationHandler = $helper->getHandler('Registration');
        $eventHandler = $helper->getHandler('Event');

        $registrationObj = $registrationHandler->get($regId);
        $eventObj = $eventHandler->get($evId);

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
        $mailParams['evLocation']            = $eventObj->getVar('location');
        $mailParams['evRegister_sendermail'] = $eventObj->getVar('register_sendermail');
        $mailParams['evRegister_sendername'] = $eventObj->getVar('register_sendername');
        $mailParams['evRegister_signature']  = $eventObj->getVar('register_signature');
        $mailParams['infotext']              = '';

        return $mailParams;
    }

    /**
     * Function to send mails to all participants
     *
     * @param array $mailParams
     * @return bool
     */
    public function executeContactAll(array $mailParams)
    {
        $helper = Helper::getInstance();
        $accountHandler = $helper->getHandler('Account');
        $useLogs = (bool)$helper->getConfig('use_logs');
        if ($useLogs) {
            $logHandler = $helper->getHandler('Log');
        }

        $errors = 0;

        $template       = $mailParams['template'];
        $eventUrl       = \WGEVENTS_URL . '/event.php?op=show&id=' . $mailParams['evId'];
        $eventName      = $mailParams['evName'];
        $eventDate      = \formatTimestamp($mailParams['evDatefrom'], 'm');
        $eventLocation  = '' == (string)$mailParams['evLocation'] ? ' ' : $mailParams['evLocation'];
        $mailFrom       = $mailParams['mailFrom'];
        $mailSubject    = '' == (string)$mailParams['mailSubject'] ? ' ' : $mailParams['mailSubject'];
        $mailBody       = '' == (string)$mailParams['mailBody'] ? ' ' : $mailParams['mailBody'];
        $recipients     = $mailParams['recipients'];

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
            unset($mail);
        }
        catch (\Exception $e) {
            if ($useLogs) {
                $logHandler->createLog('MailHandler/executeContactAll failed: Exception - ' . $e->getMessage());
            }
            $errors++;
        }

        return (0 == $errors);
    }
}
