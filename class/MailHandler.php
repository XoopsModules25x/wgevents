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
     * @var bool
     */
    public $isCron = false;
    
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
     * @return int
     */
    public function execute()
    {
        $helper = Helper::getInstance();
        $accountHandler = $helper->getHandler('Account');
        $useLogs = (bool)($helper->getConfig('use_logs') > Constants::LOG_NONE);
        if ($useLogs) {
            $logHandler = $helper->getHandler('Log');
        }
        $logInfo = '<br>Task ID: ' . $this->mailParams['taskId'];

        $errorCode = 0;

        $eventLink      = '';
        $eventUrl       = \WGEVENTS_URL . '/event.php?op=show&id=' . $this->mailParams['evId'];
        $eventName      = $this->getCleanParam('evName');
        $eventDate      = \formatTimestamp($this->mailParams['evDatefrom'], 'm');
        $eventLocation  = $this->getCleanParam('evLocation');
        $senderMail     = $this->getCleanParam('evRegister_sendermail');
        $senderName     = $this->getCleanParam('evRegister_sendername');
        $senderSignatur = $this->getCleanParam('evRegister_signature');
        $firstname      = $this->getCleanParam('regFirstname');
        $lastname       = $this->getCleanParam('regLastname');
        $infotext       = $this->getCleanParam('infotext');
        $mailBody       = $this->getCleanParam('mailBody');
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
            // check whether mail body contains any html tags
            if (\preg_match('/<\s?[^\>]*\/?\s?>/i', $mailBody)) {
                $this->isHtml = true;
                $logInfo .= '<br>Check isHtml: true';
                $eventLink = "<a href='" . $eventUrl . "' title='" . $eventUrl . "'>" . $eventName . '</a>';
            }
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
            if (Constants::ACCOUNT_TYPE_VAL_SMTP == $account_type
                || Constants::ACCOUNT_TYPE_VAL_GMAIL == $account_type) {

                $xoopsMailer->multimailer->isSMTP();
                $xoopsMailer->multimailer->Port       = $account_port_out; // set the SMTP port
                $xoopsMailer->multimailer->Host       = $account_server_out; //sometimes necessary to repeat
                $xoopsMailer->multimailer->SMTPAuth   = true;
                $xoopsMailer->multimailer->SMTPSecure = $account_securetype_out;
                $xoopsMailer->multimailer->Username   = $account_username; // SMTP account username
                $xoopsMailer->multimailer->Password   = $account_password; // SMTP account password
                $xoopsMailer->multimailer->SMTPDebug  = 0;
            }
            /* old version:
            if ('' != $account_securetype_out) {
                $xoopsMailer->SMTPAuth   = true;
                $xoopsMailer->SMTPSecure = $account_securetype_out; // sets the prefix to the server
            }
            */

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
            if ('' !== $eventLink) {
                $xoopsMailer->assign('EVENTURL', $eventLink);
            } else {
                $xoopsMailer->assign('EVENTURL', $eventUrl);
            }
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
                $errMsg = $xoopsMailer->getErrors();
                // check for SMTP error 554 (maximum number of mails exceeded)
                $errIds = ['SMTP','554', 'error'];
                $arrMsg = explode(' ', \preg_replace('/[^a-z0-9]/i',' ',$errMsg));
                $countMatches = count(array_intersect($arrMsg, $errIds));
                if ($countMatches > 0) {
                    $errorCode = 554;
                } else {

                    $errorCode = 900; // wgevents internal code for misc error
                }
            }
            $xoopsMailer->reset();
            unset($xoopsMailer);
        }
        catch (\Exception $e) {
            $errorCode = 999; // wgevents internal code for misc error
            if ($useLogs) {
                $logHandler->createLog('MailHandler/executeReg failed: Exception - ' . $e->getMessage());
            }
        }

        return $errorCode;
    }

    /**
     * Function to get clean mail parameter if exists
     *
     * @param string $name
     * @return string
     */
    private function getCleanParam($name) {
        $return = ' ';
        if (array_key_exists($name, $this->mailParams)) {
            $return  = '' == (string)$this->mailParams[$name] ? ' ' : $this->mailParams[$name];
        }

        return $return;
    }

    /**
     * Function to create mail parameters
     *
     * @param \XoopsObject $eventObj
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
        $mailParams['evLocation']            = $this->replaceLinebreaks((string)$eventObj->getVar('location'), ', ');
        $mailParams['evRegister_sendermail'] = $eventObj->getVar('register_sendermail');
        $mailParams['evRegister_sendername'] = $eventObj->getVar('register_sendername');
        $mailParams['evRegister_signature']  = $eventObj->getVar('register_signature');
        $mailParams['infotext']              = '';
        $mailParams['taskId']                = 0;

        return $mailParams;
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
