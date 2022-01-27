<?php declare(strict_types=1);


namespace XoopsModules\Wgevents\Forms;

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
use XoopsModules\Wgevents\{
    Constants
};


/**
 * Class Object Handler Additionals
 */
class FormelementsHandler extends \XoopsPersistableObjectHandler
{
    /**
     * @var int
     */
    public $type = 0;

    /**
     * @var string
     */
    public $caption = '';

    /**
     * @var string
     */
    public $desc = '';

    /**
     * @var string
     */
    public $name = '';

    /**
     * @var mixed
     */
    public $value;

    /**
     * @var array
     */
    public $optionsArr = [];

    /**
     * @var string
     */
    public $optionsText = '';

    /**
     * @var string
     */
    public $placeholder = '';

    /**
     * @var int
     */
    public $size = 20;

    /**
     * @var int
     */
    public $maxlength = 150;

    /**
     * @var int
     */
    public $rows = 4;

    /**
     * @var int
     */
    public $cols = 30;

    /**
     * Constructor
     */
    public function __construct()
    {
    }

    public function render()
    {
        $field = null;

        switch ($this->type) {
            case Constants::ADDTYPE_LABEL:
                $field = new \XoopsFormLabel($this->caption, $this->value);
                break;
            case Constants::ADDTYPE_TEXTBOX:
            case Constants::ADDTYPE_NAME:
            case Constants::ADDTYPE_EMAIL:
                $field = new \XoopsFormText($this->caption, $this->name, $this->size, $this->maxlength, $this->value);
                $field->setExtra('placeholder="' . $this->placeholder . '"');
                $field->setDescription($this->desc);
                break;
            case Constants::ADDTYPE_TEXTAREA:
                $field = new \XoopsFormTextArea($this->caption, $this->name, $this->value, $this->rows, $this->cols);
                $field->setDescription($this->desc);
                break;
            case Constants::ADDTYPE_RADIO:
                $field = new \XoopsFormRadio($this->caption, $this->name, $this->value);
                $field->addOptionArray($this->optionsArr);
                $field->setDescription($this->desc);
                break;
            case Constants::ADDTYPE_RADIOYN:
                $field = new \XoopsFormRadioYN($this->caption, $this->name, $this->value);
                $field->setDescription($this->desc);
                break;
            case Constants::ADDTYPE_SELECTBOX:
                $field = new \XoopsFormSelect($this->caption, $this->name, $this->value);
                //$field->addOption('');
                $field->addOptionArray($this->optionsArr);
                $field->setDescription($this->desc);
                break;
            case Constants::ADDTYPE_COMBOBOX:
                $field = new \XoopsFormSelect($this->caption, $this->name, $this->value, 5);
                //$field->addOption('');
                $field->addOptionArray($this->optionsArr);
                $field->setDescription($this->desc);
                break;
            case Constants::ADDTYPE_DATE:
                $field = new \XoopsFormTextDateSelect($this->caption, $this->name, '', $this->value);
                $field->setDescription($this->desc);
                break;
            case Constants::ADDTYPE_DATETIME:
                $field = new \XoopsFormDateTime($this->caption, $this->name, '', $this->value);
                $field->setDescription($this->desc);
                break;
            case Constants::ADDTYPE_CHECKBOX:
                $field = new \XoopsFormCheckBox($this->caption, $this->name, $this->value);
                if (\count($this->optionsArr) > 0) {
                    $field->addOptionArray($this->optionsArr);
                } else {
                    $field->addOption(1, $this->optionsText);
                }
                $field->setDescription($this->desc);
                break;
            case Constants::ADDTYPE_COUNTRY:
                $field = new \XoopsFormSelectCountry($this->caption, $this->name, $this->value);
                $field->setDescription($this->desc);
                break;
            case 0:
            default:
                echo 'Error: invalid type in Formelementshandler/render';
                die;
        }

        return $field;
    }

    /**
     * Returns an array representation of the object
     *
     * @return array
     */
    public function getElementsCollection()
    {
        $ret = [];
        $ret[Constants::ADDTYPE_LABEL]     = \_MA_WGEVENTS_ADDTYPE_LABEL;
        $ret[Constants::ADDTYPE_TEXTBOX]   = \_MA_WGEVENTS_ADDTYPE_TEXTBOX;
        $ret[Constants::ADDTYPE_TEXTAREA]  = \_MA_WGEVENTS_ADDTYPE_TEXTAREA;
        $ret[Constants::ADDTYPE_RADIO]     = \_MA_WGEVENTS_ADDTYPE_RADIO;
        $ret[Constants::ADDTYPE_RADIOYN]   = \_MA_WGEVENTS_ADDTYPE_RADIOYN;
        $ret[Constants::ADDTYPE_SELECTBOX] = \_MA_WGEVENTS_ADDTYPE_SELECTBOX;
        $ret[Constants::ADDTYPE_COMBOBOX]  = \_MA_WGEVENTS_ADDTYPE_COMBOBOX;
        $ret[Constants::ADDTYPE_CHECKBOX]  = \_MA_WGEVENTS_ADDTYPE_CHECKBOX;
        $ret[Constants::ADDTYPE_DATE]      = \_MA_WGEVENTS_ADDTYPE_DATE;
        //$ret[Constants::ADDTYPE_DATETIME]  = \_MA_WGEVENTS_ADDTYPE_DATETIME;
        $ret[Constants::ADDTYPE_NAME]      = \_MA_WGEVENTS_ADDTYPE_NAME;
        $ret[Constants::ADDTYPE_EMAIL]     = \_MA_WGEVENTS_ADDTYPE_EMAIL;
        $ret[Constants::ADDTYPE_COUNTRY]   = \_MA_WGEVENTS_ADDTYPE_COUNTRY;

        return $ret;
    }
}
