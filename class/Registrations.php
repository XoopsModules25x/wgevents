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
 * Class Object Registrations
 */
class Registrations extends \XoopsObject
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
        $this->initVar('reg_id', \XOBJ_DTYPE_INT);
        $this->initVar('reg_evid', \XOBJ_DTYPE_INT);
        $this->initVar('reg_salutation', \XOBJ_DTYPE_INT);
        $this->initVar('reg_firstname', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('reg_lastname', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('reg_email', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('reg_email_send', \XOBJ_DTYPE_INT);
        $this->initVar('reg_gdpr', \XOBJ_DTYPE_INT);
        $this->initVar('reg_ip', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('reg_verifkey', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('reg_status', \XOBJ_DTYPE_INT);
        $this->initVar('reg_financial', \XOBJ_DTYPE_INT);
        $this->initVar('reg_listwait', \XOBJ_DTYPE_INT);
        $this->initVar('reg_datecreated', \XOBJ_DTYPE_INT);
        $this->initVar('reg_submitter', \XOBJ_DTYPE_INT);
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
    public function getNewInsertedIdRegistrations()
    {
        return $GLOBALS['xoopsDB']->getInsertId();
    }

    /**
     * @public function getForm
     * @param bool $action
     * @param bool $test
     * @return \XoopsThemeForm
     */
    public function getFormRegistrations($action = false, $test = false)
    {
        $helper = \XoopsModules\Wgevents\Helper::getInstance();

        $eventsHandler = $helper->getHandler('Events');
        $additionalsHandler = $helper->getHandler('Additionals');
        $answersHandler = $helper->getHandler('Answers');
        $permissionsHandler = $helper->getHandler('Permissions');

        if (!$action) {
            $action = $_SERVER['REQUEST_URI'];
        }
        $isAdmin = \is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->isAdmin($GLOBALS['xoopsModule']->mid()) : false;
        $answersExist = true;
        // Title
        if ($this->isNew()) {
            $title = $test ? \sprintf(\_MA_WGEVENTS_ADDITIONALS_PREVIEW) : \sprintf(\_MA_WGEVENTS_REGISTRATION_ADD);
            $answersExist = false;
        } else {
            $title =\_MA_WGEVENTS_REGISTRATION_EDIT;
        }

        $regEvid = $this->getVar('reg_evid');
        $eventsObj = $eventsHandler->get($regEvid);
        $permRegistrationsApprove = $permissionsHandler->getPermRegistrationsApprove($eventsObj->getVAr('ev_submitter'), $eventsObj->getVar('ev_status'));
        $eventFee = (float)$eventsObj->getVar('ev_fee');
        $eventRegisterMax = (int)$eventsObj->getVar('ev_register_max');

        // Get Theme Form
        \xoops_load('XoopsFormLoader');
        $form = new \XoopsThemeForm($title, 'form', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        // Form Table events
        $form->addElement(new \XoopsFormLabel(\_MA_WGEVENTS_REGISTRATION_EVID, $eventsObj->getVAr('ev_name')));
        $form->addElement(new \XoopsFormHidden('reg_evid', $this->getVar('reg_evid')));
        // Form select regSalutation
        $regSalutationSelect = new \XoopsFormSelect(\_MA_WGEVENTS_REGISTRATION_SALUTATION, 'reg_salutation', $this->getVar('reg_salutation'));
        $regSalutationSelect->addOption(Constants::SALUTATION_NONE, ' ');
        $regSalutationSelect->addOption(Constants::SALUTATION_MEN, \_MA_WGEVENTS_REGISTRATION_SALUTATION_MEN);
        $regSalutationSelect->addOption(Constants::SALUTATION_WOMEN, \_MA_WGEVENTS_REGISTRATION_SALUTATION_WOMEN);
        $form->addElement($regSalutationSelect);
        // Form select regFirstname
        $regFirstname = new Forms\FormText(\_MA_WGEVENTS_REGISTRATION_FIRSTNAME, 'reg_firstname', 50, 255, $this->getVar('reg_firstname'));
        $regFirstname->setPlaceholder(\_MA_WGEVENTS_REGISTRATION_FIRSTNAME_PLACEHOLDER);
        $form->addElement($regFirstname);
        // Form select regLastname
        $regLastname = new Forms\FormText(\_MA_WGEVENTS_REGISTRATION_LASTNAME, 'reg_lastname', 50, 255, $this->getVar('reg_lastname'));
        $regLastname->setPlaceholder(\_MA_WGEVENTS_REGISTRATION_LASTNAME_PLACEHOLDER);
        $form->addElement($regLastname, true);
        // Form select regEmail
        $regEmailTray = new Forms\FormElementTray(\_MA_WGEVENTS_REGISTRATION_EMAIL, '<br>');
        $regEmail = new Forms\FormText('', 'reg_email', 50, 255, $this->getVar('reg_email'));
        $regEmail->setPlaceholder(\_MA_WGEVENTS_REGISTRATION_EMAIL_PLACEHOLDER);
        $regEmailTray->addElement($regEmail);
        // Form select regEmailSend
        $regEmailSend = $this->isNew() ? 0 : $this->getVar('reg_email_send');
        $regEmailRadio = new \XoopsFormRadioYN(\_MA_WGEVENTS_REGISTRATION_EMAIL_CONFIRM, 'reg_email_send', $regEmailSend);
        $regEmailTray->addElement($regEmailRadio);
        $form->addElement($regEmailTray);
        // get all additionals
        $crAdditionals = new \CriteriaCompo();
        $crAdditionals->add(new \Criteria('add_evid', $regEvid));
        $crAdditionals->setSort('add_weight ASC, add_id');
        $crAdditionals->setOrder('DESC');
        $additionalsCount = $additionalsHandler->getCount($crAdditionals);
        if ($additionalsCount > 0) {
            $additionalsAll = $additionalsHandler->getAll($crAdditionals);
            foreach (\array_keys($additionalsAll) as $addId) {
                $formelementsHandler = new FormelementsHandler();
                $formelementsHandler->name = 'add_id[' . $addId . ']';
                $addType = (int)$additionalsAll[$addId]->getVar('add_type');
                $addValue = (string)$additionalsAll[$addId]->getVar('add_values');
                $formelementsHandler->type = $addType;
                $formelementsHandler->caption = $additionalsAll[$addId]->getVar('add_caption');
                if ($answersExist) {
                    $value = '';
                    // get answers for this additionals
                    $crAnswers = new \CriteriaCompo();
                    $crAnswers->add(new \Criteria('ans_regid', $this->getVar('reg_id')));
                    $crAnswers->add(new \Criteria('ans_addid', $addId));
                    $answersCount = $answersHandler->getCount($crAnswers);
                    if ($answersCount > 0) {
                        $answersAll = $answersHandler->getAll($crAnswers);
                        foreach (\array_keys($answersAll) as $ansId) {
                            if (Constants::ADDTYPE_DATE == $addType) {
                                $answerDateObj = \DateTime::createFromFormat(\_SHORTDATESTRING, $answersAll[$ansId]->getVar('ans_text'));
                                $value = $answerDateObj->getTimestamp();
                            } else {
                                $value = $answersAll[$ansId]->getVar('ans_text');
                            }
                        }
                    }
                    $formelementsHandler->value = $value;
                //} else {
                    //TODO
                }
                if (Constants::ADDTYPE_RADIO == $addType ||
                    Constants::ADDTYPE_SELECTBOX == $addType ||
                    Constants::ADDTYPE_COMBOBOX == $addType) {
                        //$formelementsHandler->options = preg_split('/\r\n|\r|\n/', $additionalsAll[$addId]->getVar('add_values'));
                        $formelementsHandler->optionsArr = \unserialize($addValue);
                }
                if (Constants::ADDTYPE_CHECKBOX == $addType) {
                    $addValueArr = \unserialize($addValue);
                    $formelementsHandler->optionsText = $addValueArr[0];
                }
                $formelementsHandler->placeholder = $additionalsAll[$addId]->getVar('add_placeholder');
                $formelementsHandler->desc = $additionalsAll[$addId]->getVar('add_desc');
                $required = $additionalsAll[$addId]->getVar('add_required');
                $form->addElement($formelementsHandler->render(), $required);
                $form->addElement(new \XoopsFormHidden('add_type[' . $additionalsAll[$addId]->getVar('add_id') . ']', $additionalsAll[$addId]->getVar('add_type')));
            }
            unset($additionals);
        }
        // Form checkbox regGdpr
        $regGdpr = new \XoopsFormCheckBox(\_MA_WGEVENTS_REGISTRATION_GDPR, 'reg_gdpr', '');
        $regGdpr->addOption(1, \_MA_WGEVENTS_REGISTRATION_GDPR_VALUE);
        $form->addElement($regGdpr, true);
        // Form Text Date Select regFinancial
        $regFinancial = $this->isNew() ? Constants::FINANCIAL_UNPAID : $this->getVar('reg_financial');
        if ($eventFee > 0 && $permRegistrationsApprove) {
            $regFinancialRadio = new \XoopsFormRadio(\_MA_WGEVENTS_REGISTRATION_FINANCIAL, 'reg_financial', $regFinancial);
            $regFinancialRadio->addOption(Constants::FINANCIAL_UNPAID, \_MA_WGEVENTS_REGISTRATION_FINANCIAL_UNPAID);
            $regFinancialRadio->addOption(Constants::FINANCIAL_PAID, \_MA_WGEVENTS_REGISTRATION_FINANCIAL_PAID);
            $form->addElement($regFinancialRadio, true);
        } else {
            if (!$this->isNew() && $eventFee > 0) {
                $form->addElement(new \XoopsFormLabel(\_MA_WGEVENTS_REGISTRATION_FINANCIAL, Utility::getFinancialText($regFinancial)));
            }
            $form->addElement(new \XoopsFormHidden('reg_financial', $regFinancial));
        }
        // Form Radio Yes/No regListwait
        $regListwait = $this->isNew() ? 0 : (int)$this->getVar('reg_listwait');
        if ($eventRegisterMax > 0 && $permRegistrationsApprove) {
            $form->addElement(new \XoopsFormRadioYN(\_MA_WGEVENTS_REGISTRATION_LISTWAIT, 'reg_listwait', $regListwait));
        } else {
            if ($eventRegisterMax > 0 && $regListwait > 0) {
                $form->addElement(new \XoopsFormLabel(\_MA_WGEVENTS_REGISTRATION_LISTWAIT, \_YES));
            }
            $form->addElement(new \XoopsFormHidden('reg_listwait', $regListwait));
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
            $regStatus = $this->getVar('reg_status');
        }
        // Form Text resVerifcode
        $resVerifkey = $this->getVar('reg_verifkey');
        // Form Text Date Select regDatecreated
        $regDatecreated = $this->isNew() ? \time() : $this->getVar('reg_datecreated');
        // Form Select User resSubmitter
        $regSubmitter = \is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->uid() : 0;
        if ($permRegistrationsApprove) {
            $regStatusSelect = new \XoopsFormSelect(\_MA_WGEVENTS_STATUS, 'reg_status', $regStatus);
            $regStatusSelect->addOption(Constants::STATUS_NONE, \_MA_WGEVENTS_STATUS_NONE);
            $regStatusSelect->addOption(Constants::STATUS_OFFLINE, \_MA_WGEVENTS_STATUS_OFFLINE);
            $regStatusSelect->addOption(Constants::STATUS_SUBMITTED, \_MA_WGEVENTS_STATUS_SUBMITTED);
            $regStatusSelect->addOption(Constants::STATUS_VERIFIED, \_MA_WGEVENTS_STATUS_VERIFIED);
            $regStatusSelect->addOption(Constants::STATUS_APPROVED, \_MA_WGEVENTS_STATUS_APPROVED);
            $form->addElement($regStatusSelect, true);
        } else {
            if (!$this->isNew()) {
                $form->addElement(new \XoopsFormLabel(\_MA_WGEVENTS_STATUS, Utility::getStatusText($regStatus)));
            }
            $form->addElement(new \XoopsFormHidden('reg_status', $regStatus));
        }
        if ($isAdmin) {
            $form->addElement(new \XoopsFormText(\_MA_WGEVENTS_REGISTRATION_IP, 'reg_ip', 20, 150, $regIp));
            $form->addElement(new \XoopsFormText(\_MA_WGEVENTS_REGISTRATION_VERIFKEY, 'reg_verifkey', 20, 150, $resVerifkey));
            // Form Text Date Select addDatecreated
            $form->addElement(new \XoopsFormTextDateSelect(\_MA_WGEVENTS_DATECREATED, 'reg_datecreated', '', $regDatecreated));
            $form->addElement(new \XoopsFormSelectUser(\_MA_WGEVENTS_SUBMITTER, 'reg_submitter', false, $regSubmitter));
        } else {
            $form->addElement(new \XoopsFormHidden('reg_ip', $regIp));
            $form->addElement(new \XoopsFormHidden('reg_verifkey', $resVerifkey));
            $form->addElement(new \XoopsFormHidden('reg_datecreated_int', \time()));
            $form->addElement(new \XoopsFormHidden('reg_submitter', $regSubmitter));
            if (!$this->isNew()) {
                $form->addElement(new \XoopsFormLabel(\_MA_WGEVENTS_DATECREATED, \formatTimestamp($regDatecreated, 's')));
                $form->addElement(new \XoopsFormLabel(\_MA_WGEVENTS_SUBMITTER, \XoopsUser::getUnameFromId($regSubmitter)));
            }
        }
        // To Save
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
        $ret['id']              = $this->getVar('reg_id');
        $eventsHandler = $helper->getHandler('Events');
        $eventsObj = $eventsHandler->get($this->getVar('reg_evid'));
        $ret['evid']            = $eventsObj->getVar('ev_name');
        $salutation             = $this->getVar('reg_salutation');
        $ret['salutation']      = $salutation;
        $ret['salutation_text'] = Utility::getSalutationText($salutation);
        $ret['firstname']       = $this->getVar('reg_firstname');
        $ret['lastname']        = $this->getVar('reg_lastname');
        $ret['email']           = $this->getVar('reg_email');
        $ret['gdpr']            = $this->getVar('reg_gdpr');
        $ret['ip']              = $this->getVar('reg_ip');
        $ret['verifkey']        = $this->getVar('reg_verifkey');
        $status                 = $this->getVar('reg_status');
        $ret['status']          = $status;
        $ret['status_text']     = Utility::getStatusText($status);
        if ((int)$this->getVar('reg_listwait') > 0) {
            $ret['status_listwait'] = '(' . \_MA_WGEVENTS_REGISTRATION_LISTWAIT  . ')';
        }
        $financial              = $this->getVar('reg_financial');
        $ret['financial']       = $financial;
        $ret['financial_text']  = Utility::getFinancialText($financial);
        $ret['listwait']        = (int)$this->getVar('reg_listwait') > 0 ? \_YES : \_NO;
        $ret['datecreated']     = \formatTimestamp($this->getVar('reg_datecreated'), 'm');
        $ret['submitter']       = \XoopsUser::getUnameFromId($this->getVar('reg_submitter'));
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
    public function toArrayRegistrations()
    {
        $ret = [];
        $vars = $this->getVars();
        foreach (\array_keys($vars) as $var) {
            $ret[$var] = $this->getVar($var);
        }
        return $ret;
    }
}
