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
    Constants,
    Helper
};


/**
 * Class Object Handler Question
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
            case Constants::FIELD_LABEL:
                $field = new \XoopsFormLabel($this->caption, $this->value);
                break;
            case Constants::FIELD_TEXTBOX:
            case Constants::FIELD_NAME:
            case Constants::FIELD_EMAIL:
                $field = new \XoopsFormText($this->caption, $this->name, $this->size, $this->maxlength, $this->value);
                $field->setExtra('placeholder="' . $this->placeholder . '"');
                $field->setDescription($this->desc);
                break;
            case Constants::FIELD_TEXTAREA:
                $field = new \XoopsFormTextArea($this->caption, $this->name, $this->value, $this->rows, $this->cols);
                $field->setDescription($this->desc);
                break;
            case Constants::FIELD_TEXTEDITOR:
                $editorConfigs = [];
                $helper  = Helper::getInstance();
                $editor = $helper->getConfig('editor_user');
                $editorConfigs['name'] = $this->name;
                $editorConfigs['value'] = $this->value;
                $editorConfigs['rows'] = $this->rows;
                $editorConfigs['cols'] = $this->cols;
                $editorConfigs['width'] = '100%';
                $editorConfigs['height'] = '400px';
                $editorConfigs['editor'] = $editor;
                $field = new \XoopsFormEditor($this->caption, $this->name, $editorConfigs);
                $field->setDescription($this->desc);
                break;
            case Constants::FIELD_RADIO:
                $field = new \XoopsFormRadio($this->caption, $this->name, $this->value);
                $field->addOptionArray($this->optionsArr);
                $field->setDescription($this->desc);
                break;
            case Constants::FIELD_RADIOYN:
                $field = new \XoopsFormRadioYN($this->caption, $this->name, $this->value);
                $field->setDescription($this->desc);
                break;
            case Constants::FIELD_SELECTBOX:
                $field = new \XoopsFormSelect($this->caption, $this->name, $this->value);
                //$field->addOption('');
                $field->addOptionArray($this->optionsArr);
                $field->setDescription($this->desc);
                break;
            case Constants::FIELD_COMBOBOX:
                $field = new \XoopsFormSelect($this->caption, $this->name, $this->value, 5, true);
                //$field->addOption('');
                $field->addOptionArray($this->optionsArr);
                $field->setDescription($this->desc);
                break;
            case Constants::FIELD_DATE:
                $field = new \XoopsFormTextDateSelect($this->caption, $this->name, '', $this->value);
                $field->setDescription($this->desc);
                break;
            case Constants::FIELD_DATETIME:
                $field = new \XoopsFormDateTime($this->caption, $this->name, '', $this->value);
                $field->setDescription($this->desc);
                break;
            case Constants::FIELD_CHECKBOX:
                $field = new \XoopsFormCheckBox($this->caption, $this->name, $this->value);
                if (\count($this->optionsArr) > 0) {
                    $field->addOptionArray($this->optionsArr);
                } else {
                    $field->addOption(1, $this->optionsText);
                }
                $field->setDescription($this->desc);
                break;
            case Constants::FIELD_COUNTRY:
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
        $ret[Constants::FIELD_LABEL]      = \_MA_WGEVENTS_FIELD_LABEL;
        $ret[Constants::FIELD_TEXTBOX]    = \_MA_WGEVENTS_FIELD_TEXTBOX;
        $ret[Constants::FIELD_TEXTAREA]   = \_MA_WGEVENTS_FIELD_TEXTAREA;
        $ret[Constants::FIELD_TEXTEDITOR] = \_MA_WGEVENTS_FIELD_TEXTEDITOR;
        $ret[Constants::FIELD_RADIO]      = \_MA_WGEVENTS_FIELD_RADIO;
        $ret[Constants::FIELD_RADIOYN]    = \_MA_WGEVENTS_FIELD_RADIOYN;
        $ret[Constants::FIELD_SELECTBOX]  = \_MA_WGEVENTS_FIELD_SELECTBOX;
        $ret[Constants::FIELD_COMBOBOX]   = \_MA_WGEVENTS_FIELD_COMBOBOX;
        $ret[Constants::FIELD_CHECKBOX]   = \_MA_WGEVENTS_FIELD_CHECKBOX;
        $ret[Constants::FIELD_DATE]       = \_MA_WGEVENTS_FIELD_DATE;
        //$ret[Constants::FIELD_DATETIME]  = \_MA_WGEVENTS_FIELD_DATETIME;
        $ret[Constants::FIELD_NAME]       = \_MA_WGEVENTS_FIELD_NAME;
        $ret[Constants::FIELD_EMAIL]      = \_MA_WGEVENTS_FIELD_EMAIL;
        $ret[Constants::FIELD_COUNTRY]    = \_MA_WGEVENTS_FIELD_COUNTRY;

        return $ret;
    }
}
