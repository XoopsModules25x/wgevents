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
use XoopsModules\Wgevents\Forms\FormelementsHandler;

\defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class Object Registration
 */
class Registration extends \XoopsObject
{
    /**
     * @var int
     */
    private $start = 0;

    /**
     * @var int
     */
    private $limit = 0;

    /**
     * @var string
     */
    private $redir = 'list';

    /**
     * Constructor
     *
     * @param null
     */
    public function __construct()
    {
        $this->initVar('id', \XOBJ_DTYPE_INT);
        $this->initVar('evid', \XOBJ_DTYPE_INT);
        $this->initVar('salutation', \XOBJ_DTYPE_INT);
        $this->initVar('firstname', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('lastname', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('email', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('email_send', \XOBJ_DTYPE_INT);
        $this->initVar('gdpr', \XOBJ_DTYPE_INT);
        $this->initVar('ip', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('verifkey', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('status', \XOBJ_DTYPE_INT);
        $this->initVar('financial', \XOBJ_DTYPE_INT);
        $this->initVar('paidamount', \XOBJ_DTYPE_FLOAT);
        $this->initVar('listwait', \XOBJ_DTYPE_INT);
        $this->initVar('datecreated', \XOBJ_DTYPE_INT);
        $this->initVar('submitter', \XOBJ_DTYPE_INT);
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
    public function getNewInsertedId()
    {
        return $GLOBALS['xoopsDB']->getInsertId();
    }

    /**
     * @public function getForm
     * @param bool $action
     * @param bool $test
     * @return \XoopsThemeForm
     */
    public function getForm($action = false, $test = false)
    {
        $helper = \XoopsModules\Wgevents\Helper::getInstance();

        $eventHandler = $helper->getHandler('Event');
        $questionHandler = $helper->getHandler('Question');
        $answerHandler = $helper->getHandler('Answer');
        $permissionsHandler = $helper->getHandler('Permission');

        if (!$action) {
            $action = $_SERVER['REQUEST_URI'];
        }
        $isAdmin = \is_object($GLOBALS['xoopsUser']) && $GLOBALS['xoopsUser']->isAdmin($GLOBALS['xoopsModule']->mid());
        $answersExist = true;
        // Title
        if ($this->isNew()) {
            $title = $test ? \_MA_WGEVENTS_QUESTIONS_PREVIEW : \_MA_WGEVENTS_REGISTRATION_ADD;
            $answersExist = false;
        } else {
            $title =\_MA_WGEVENTS_REGISTRATION_EDIT;
        }

        $regEvid = $this->getVar('evid');
        $eventObj = $eventHandler->get($regEvid);
        $permRegistrationsApprove = $permissionsHandler->getPermRegistrationsApprove($eventObj->getVar('submitter'), $eventObj->getVar('status'));
        $eventFee = (float)$eventObj->getVar('fee');
        $eventRegisterMax = (int)$eventObj->getVar('register_max');
        $eventRegisterForceverif = (bool)$eventObj->getVar('register_forceverif');

        // Get Theme Form
        \xoops_load('XoopsFormLoader');
        $form = new \XoopsThemeForm($title, 'formRegistration', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        // Form Table events
        $form->addElement(new \XoopsFormLabel(\_MA_WGEVENTS_REGISTRATION_EVID, $eventObj->getVar('name')));
        $form->addElement(new \XoopsFormHidden('evid', $this->getVar('evid')));
        // Form select regSalutation
        $regSalutationSelect = new \XoopsFormSelect(\_MA_WGEVENTS_REGISTRATION_SALUTATION, 'salutation', $this->getVar('salutation'));
        $regSalutationSelect->addOption(Constants::SALUTATION_NONE, ' ');
        $regSalutationSelect->addOption(Constants::SALUTATION_MEN, \_MA_WGEVENTS_REGISTRATION_SALUTATION_MEN);
        $regSalutationSelect->addOption(Constants::SALUTATION_WOMEN, \_MA_WGEVENTS_REGISTRATION_SALUTATION_WOMEN);
        $form->addElement($regSalutationSelect);
        // Form select regFirstname
        $regFirstname = new Forms\FormText(\_MA_WGEVENTS_REGISTRATION_FIRSTNAME, 'firstname', 50, 255, $this->getVar('firstname'));
        $regFirstname->setPlaceholder(\_MA_WGEVENTS_REGISTRATION_FIRSTNAME_PLACEHOLDER);
        $form->addElement($regFirstname);
        // Form select regLastname
        $regLastname = new Forms\FormText(\_MA_WGEVENTS_REGISTRATION_LASTNAME, 'lastname', 50, 255, $this->getVar('lastname'));
        $regLastname->setPlaceholder(\_MA_WGEVENTS_REGISTRATION_LASTNAME_PLACEHOLDER);
        $form->addElement($regLastname, true);
        // Form select regEmail
        $regEmailTray = new Forms\FormElementTray(\_MA_WGEVENTS_REGISTRATION_EMAIL, '<br>');
        $regEmail = new Forms\FormText('', 'email', 50, 255, $this->getVar('email'));
        $regEmail->setPlaceholder(\_MA_WGEVENTS_REGISTRATION_EMAIL_PLACEHOLDER);
        if ($eventRegisterForceverif) {
            $regEmail->setDescription(_MA_WGEVENTS_EVENT_REGISTER_FORCEVERIF_INFO);
        }
        $regEmailTray->addElement($regEmail, $eventRegisterForceverif);
        // Form select regEmailSend
        $regEmailSend = $this->isNew() ? 1 : $this->getVar('email_send');
        $regEmailRadio = new \XoopsFormRadioYN(\_MA_WGEVENTS_REGISTRATION_EMAIL_CONFIRM, 'email_send', $regEmailSend);
        $regEmailTray->addElement($regEmailRadio);
        $form->addElement($regEmailTray);
        // get all questions
        $crQuestion = new \CriteriaCompo();
        $crQuestion->add(new \Criteria('evid', $regEvid));
        $crQuestion->setSort('weight ASC, id');
        $crQuestion->setOrder('DESC');
        $questionsCount = $questionHandler->getCount($crQuestion);
        if ($questionsCount > 0) {
            $questionsAll = $questionHandler->getAll($crQuestion);
            foreach (\array_keys($questionsAll) as $queId) {
                $formelementsHandler = new FormelementsHandler();
                $formelementsHandler->name = 'ans_id[' . $queId . ']';
                $queType = (int)$questionsAll[$queId]->getVar('type');
                $addValue = (string)$questionsAll[$queId]->getVar('values');
                $formelementsHandler->type = $queType;
                $formelementsHandler->caption = $questionsAll[$queId]->getVar('caption');
                if ($answersExist) {
                    $value = '';
                    // get answers for this questions
                    $crAnswer = new \CriteriaCompo();
                    $crAnswer->add(new \Criteria('regid', $this->getVar('id')));
                    $crAnswer->add(new \Criteria('queid', $queId));
                    $answersCount = $answerHandler->getCount($crAnswer);
                    if ($answersCount > 0) {
                        $answersAll = $answerHandler->getAll($crAnswer);
                        foreach (\array_keys($answersAll) as $ansId) {
                            switch ($queType) {
                                case Constants::FIELD_DATE:
                                    $answerDateObj = \DateTime::createFromFormat(\_SHORTDATESTRING, $answersAll[$ansId]->getVar('text'));
                                    $value = $answerDateObj->getTimestamp();
                                    break;
                                case Constants::FIELD_COMBOBOX:
                                case Constants::FIELD_CHECKBOX:
                                    $ansText = $answersAll[$ansId]->getVar('text', 'n');
                                    $value = \unserialize($ansText);
                                    break;
                                case Constants::FIELD_SELECTBOX:
                                    $ansText = $answersAll[$ansId]->getVar('text', 'n');
                                    $value = (string)\unserialize($ansText);
                                    break;
                                case 0:
                                default:
                                    $value = $answersAll[$ansId]->getVar('text');
                                    break;
                            }
                        }
                    }
                    $formelementsHandler->value = $value;
                //} else {
                    //TODO
                }
                if (Constants::FIELD_RADIO == $queType ||
                    Constants::FIELD_SELECTBOX == $queType ||
                    Constants::FIELD_CHECKBOX == $queType ||
                    Constants::FIELD_COMBOBOX == $queType) {
                        //$formelementsHandler->options = preg_split('/\r\n|\r|\n/', $questionsAll[$queId]->getVar('values'));
                        $formelementsHandler->optionsArr = \unserialize($addValue);
                }
                /*
                if (Constants::FIELD_CHECKBOX == $queType) {
                    //$addValueArr = \unserialize($addValue);
                    //$formelementsHandler->optionsText = $addValueArr[0];
                    $formelementsHandler->optionsArr = \unserialize($addValue);
                }
                */
                if (Constants::FIELD_LABEL == $queType) {
                    $desc = \preg_replace('/\r\n|\r|\n/', '<br>', $questionsAll[$queId]->getVar('desc', 'e'));
                    $formelementsHandler->value = $desc;
                }
                $formelementsHandler->placeholder = $questionsAll[$queId]->getVar('placeholder');
                $formelementsHandler->desc = $questionsAll[$queId]->getVar('desc');
                $required = $questionsAll[$queId]->getVar('required');
                $form->addElement($formelementsHandler->render(), $required);
                $form->addElement(new \XoopsFormHidden('type[' . $questionsAll[$queId]->getVar('id') . ']', $questionsAll[$queId]->getVar('type')));
            }
            unset($questions);
        }
        // Form checkbox regGdpr
        $valueGdpr = $permRegistrationsApprove ? 1 : '';
        $regGdpr = new \XoopsFormCheckBox(\_MA_WGEVENTS_REGISTRATION_GDPR, 'gdpr', $valueGdpr);
        $regGdpr->addOption(1, \_MA_WGEVENTS_REGISTRATION_GDPR_VALUE);
        $form->addElement($regGdpr, true);
        // Form Text Date Select regFinancial
        // Form Text Date Select regPaidamount
        $regFinancial = $this->isNew() ? Constants::FINANCIAL_UNPAID : $this->getVar('financial');
        $default0 = '0' . $helper->getConfig('sep_comma') . '00';
        $regPaidamount = $this->isNew() ? $default0 : Utility::FloatToString($this->getVar('paidamount'));
        if ($eventFee > 0 && $permRegistrationsApprove && !$test) {
            $regFinancialRadio = new \XoopsFormRadio(\_MA_WGEVENTS_REGISTRATION_FINANCIAL, 'financial', $regFinancial);
            $regFinancialRadio->addOption(Constants::FINANCIAL_UNPAID, \_MA_WGEVENTS_REGISTRATION_FINANCIAL_UNPAID);
            $regFinancialRadio->addOption(Constants::FINANCIAL_PAID, \_MA_WGEVENTS_REGISTRATION_FINANCIAL_PAID);
            $form->addElement($regFinancialRadio, true);
            $form->addElement(new \XoopsFormText(\_MA_WGEVENTS_REGISTRATION_PAIDAMOUNT, 'paidamount', 20, 150, $regPaidamount));
        } else {
            if (!$this->isNew() && $eventFee > 0  && $test) {
                $form->addElement(new \XoopsFormLabel(\_MA_WGEVENTS_REGISTRATION_FINANCIAL, Utility::getFinancialText($regFinancial)));
                $form->addElement(new \XoopsFormLabel(\_MA_WGEVENTS_REGISTRATION_PAIDAMOUNT, $regPaidamount));
            }
            $form->addElement(new \XoopsFormHidden('financial', $regFinancial));
            $form->addElement(new \XoopsFormHidden('paidamount', $regPaidamount));
        }
        // Form Radio Yes/No regListwait
        $regListwait = $this->isNew() ? 0 : (int)$this->getVar('listwait');
        if ($eventRegisterMax > 0 && $permRegistrationsApprove && !$test) {
            $form->addElement(new \XoopsFormRadioYN(\_MA_WGEVENTS_REGISTRATION_LISTWAIT, 'listwait', $regListwait));
        } else {
            if ($eventRegisterMax > 0 && $regListwait > 0 && $test) {
                $form->addElement(new \XoopsFormLabel(\_MA_WGEVENTS_REGISTRATION_LISTWAIT, \_YES));
            }
            $form->addElement(new \XoopsFormHidden('listwait', $regListwait));
        }
        // Form Text IP resIp
        $regIp = $_SERVER['REMOTE_ADDR'];
        // Form Text Select resStatus
        if ($this->isNew()) {
            $regStatus = Constants::STATUS_SUBMITTED;
            if ($permissionsHandler->getPermRegistrationsVerif()) {
                $regStatus = Constants::STATUS_VERIFIED;
            }
            if ($permRegistrationsApprove) {
                $regStatus = Constants::STATUS_APPROVED;
            }
        } else {
            $regStatus = $this->getVar('status');
        }
        // Form Text resVerifcode
        $resVerifkey = $this->getVar('verifkey');
        // Form Text Date Select regDatecreated
        $regDatecreated = $this->isNew() ? \time() : $this->getVar('datecreated');
        // Form Select User resSubmitter
        $regSubmitter = \is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->uid() : 0;
        if ($permRegistrationsApprove && !$test) {
            $regStatusSelect = new \XoopsFormSelect(\_MA_WGEVENTS_STATUS, 'status', $regStatus);
            $regStatusSelect->addOption(Constants::STATUS_NONE, \_MA_WGEVENTS_STATUS_NONE);
            $regStatusSelect->addOption(Constants::STATUS_OFFLINE, \_MA_WGEVENTS_STATUS_OFFLINE);
            $regStatusSelect->addOption(Constants::STATUS_SUBMITTED, \_MA_WGEVENTS_STATUS_SUBMITTED);
            $regStatusSelect->addOption(Constants::STATUS_VERIFIED, \_MA_WGEVENTS_STATUS_VERIFIED);
            $regStatusSelect->addOption(Constants::STATUS_APPROVED, \_MA_WGEVENTS_STATUS_APPROVED);
            $form->addElement($regStatusSelect, true);
        } else {
            if (!$this->isNew() && !$test) {
                $form->addElement(new \XoopsFormLabel(\_MA_WGEVENTS_STATUS, Utility::getStatusText($regStatus)));
            }
            $form->addElement(new \XoopsFormHidden('status', $regStatus));
        }
        if ($isAdmin) {
            $form->addElement(new \XoopsFormText(\_MA_WGEVENTS_REGISTRATION_IP, 'ip', 20, 150, $regIp));
            $form->addElement(new \XoopsFormText(\_MA_WGEVENTS_REGISTRATION_VERIFKEY, 'verifkey', 20, 150, $resVerifkey));
            // Form Text Date Select queDatecreated
            $form->addElement(new \XoopsFormTextDateSelect(\_MA_WGEVENTS_DATECREATED, 'datecreated', '', $regDatecreated));
            $form->addElement(new \XoopsFormSelectUser(\_MA_WGEVENTS_SUBMITTER, 'submitter', false, $regSubmitter));
        } else {
            $form->addElement(new \XoopsFormHidden('ip', $regIp));
            $form->addElement(new \XoopsFormHidden('verifkey', $resVerifkey));
            $form->addElement(new \XoopsFormHidden('datecreated_int', \time()));
            $form->addElement(new \XoopsFormHidden('submitter', $regSubmitter));
            if (!$this->isNew() && !$test) {
                $form->addElement(new \XoopsFormLabel(\_MA_WGEVENTS_DATECREATED, \formatTimestamp($regDatecreated, 's')));
                $form->addElement(new \XoopsFormLabel(\_MA_WGEVENTS_SUBMITTER, \XoopsUser::getUnameFromId($regSubmitter)));
            }
        }
        // To Save
        $form->addElement(new \XoopsFormHidden('id', $this->getVar('id')));
        $form->addElement(new \XoopsFormHidden('op', 'save'));
        $form->addElement(new \XoopsFormHidden('start', $this->start));
        $form->addElement(new \XoopsFormHidden('limit', $this->limit));
        $form->addElement(new \XoopsFormHidden('redir', $this->redir));
        // button tray
        $buttonTray = new \XoopsFormElementTray('');
        $buttonBack = new Forms\FormButton('', 'cancel', \_CANCEL, 'button');
        $buttonBack->setExtra('onclick="history.go(-1);return true;"');
        $buttonBack->setClass('btn-danger');
        $buttonTray->addElement($buttonBack);
        if (!$test) {
            $buttonReset = new Forms\FormButton('', 'reset', \_RESET, 'reset');
            $buttonReset->setClass('btn-warning');
            $buttonTray->addElement($buttonReset);
            $buttonSubmit = new Forms\FormButton('', '_submit', \_MA_WGEVENTS_SAVE, 'submit');
            $buttonSubmit->setClass('btn-primary');
            $buttonTray->addElement($buttonSubmit);
        }
        $form->addElement($buttonTray);
        return $form;
    }

    /**
     * Get Values
     * @param null $keys
     * @param null $format
     * @param null $maxDepth
     * @return array
     */
    public function getValuesRegistrations($keys = null, $format = null, $maxDepth = null)
    {
        $helper  = \XoopsModules\Wgevents\Helper::getInstance();
        $ret = $this->getValues($keys, $format, $maxDepth);
        $eventHandler = $helper->getHandler('Event');
        $eventObj = $eventHandler->get($this->getVar('evid'));
        $ret['eventname']        = $eventObj->getVar('name');
        $ret['salutation_text']  = Utility::getSalutationText($this->getVar('salutation'));
        $ret['status_text']      = Utility::getStatusText($this->getVar('status'));
        $ret['financial_text']   = Utility::getFinancialText($this->getVar('financial'));
        $ret['paidamount_text']  = Utility::FloatToString($this->getVar('paidamount'));
        $ret['listwait_text']    = (int)$this->getVar('listwait') > 0 ? \_YES : \_NO;
        $ret['datecreated_text'] = \formatTimestamp($this->getVar('datecreated'), 'm');
        $ret['submitter_text']   = \XoopsUser::getUnameFromId($this->getVar('submitter'));
        return $ret;
    }

    /**
     * Set start
     * @param $start
     * @return void
     */
    public function setStart($start)
    {
        $this->start = $start;
    }

    /**
     * Set start
     * @param $limit
     * @return void
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    /**
     * Set redir
     * @param $redir
     * @return void
     */
    public function setRedir($redir)
    {
        $this->redir = $redir;
    }

    /**
     * Returns an array representation of the object
     *
     * @return array
     */
    /*
    public function toArray()
    {
        $ret = [];
        $vars = $this->getVars();
        foreach (\array_keys($vars) as $var) {
            $ret[$var] = $this->getVar($var);
        }
        return $ret;
    }
    */
}
