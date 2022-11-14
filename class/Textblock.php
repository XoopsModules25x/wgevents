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
use XoopsModules\Wgevents\Helper;

\defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class Object Textblock
 */
class Textblock extends \XoopsObject
{
    /**
     * @var int
     */
    public $start = 0;

    /**
     * @var int
     */
    public $limit = 0;

    /**
     * Constructor
     *
     * @param null
     */
    public function __construct()
    {
        $this->initVar('id', \XOBJ_DTYPE_INT);
        $this->initVar('catid', \XOBJ_DTYPE_INT);
        $this->initVar('name', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('text', \XOBJ_DTYPE_OTHER);
        $this->initVar('class', \XOBJ_DTYPE_INT);
        $this->initVar('weight', \XOBJ_DTYPE_INT);
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
     * @return \XoopsThemeForm
     */
    public function getForm($action = false)
    {
        $helper = Helper::getInstance();
        //$categoryHandler = $helper->getHandler('Category');
        $textblockHandler = $helper->getHandler('Textblock');

        if (!$action) {
            $action = $_SERVER['REQUEST_URI'];
        }
        $isAdmin = \is_object($GLOBALS['xoopsUser']) && $GLOBALS['xoopsUser']->isAdmin($GLOBALS['xoopsModule']->mid());
        // Title
        $title = $this->isNew() ? \_MA_WGEVENTS_TEXTBLOCK_ADD : \_MA_WGEVENTS_TEXTBLOCK_EDIT;
        // Get Theme Form
        \xoops_load('XoopsFormLoader');
        $form = new \XoopsThemeForm($title, 'formTextblock', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        // Form Table categories
        /*  for the moment textblocks are valid for all categories
        $evCatidSelect = new \XoopsFormSelect(\_MA_WGEVENTS_EVENT_CATID, 'catid', $this->getVar('catid'));
        $evCatidSelect->addOptionArray($categoryHandler->getList());
        $form->addElement($evCatidSelect);
        */
        // Form Text tbName
        $form->addElement(new \XoopsFormText(\_MA_WGEVENTS_TEXTBLOCK_NAME, 'name', 50, 255, $this->getVar('name')));
        // Form Editor DhtmlTextArea tbText
        $editorConfigs = [];
        if ($isAdmin) {
            $editor = $helper->getConfig('editor_admin');
        } else {
            $editor = $helper->getConfig('editor_user');
        }
        $editorConfigs['name'] = 'text';
        $editorConfigs['value'] = $this->getVar('text', 'e');
        $editorConfigs['rows'] = 5;
        $editorConfigs['cols'] = 40;
        $editorConfigs['width'] = '100%';
        $editorConfigs['height'] = '400px';
        $editorConfigs['editor'] = $editor;
        $form->addElement(new \XoopsFormEditor(\_MA_WGEVENTS_TEXTBLOCK_TEXT, 'text', $editorConfigs));
        // Form select tbClass
        $tbClass = $this->isNew() ? Constants::TEXTBLOCK_CLASS_PRIVATE : $this->getVar('class');
        $tbclassSelect = new \XoopsFormSelect(\_MA_WGEVENTS_TEXTBLOCK_CLASS, 'class', $tbClass);
        $tbclassSelect->addOption(Constants::TEXTBLOCK_CLASS_PRIVATE, \_MA_WGEVENTS_TEXTBLOCK_CLASS_PRIVATE);
        $tbclassSelect->addOption(Constants::TEXTBLOCK_CLASS_PUBLIC, \_MA_WGEVENTS_TEXTBLOCK_CLASS_PUBLIC);
        $form->addElement($tbclassSelect);
        // Form Text tbWeight
        $tbWeight = $this->isNew() ? $textblockHandler->getNextWeight() : $this->getVar('weight');
        $form->addElement(new \XoopsFormText(\_MA_WGEVENTS_WEIGHT, 'weight', 50, 255, $tbWeight));
        // Form Text Date Select tbDatecreated
        $tbDatecreated = $this->isNew() ? \time() : $this->getVar('datecreated');
        $form->addElement(new \XoopsFormTextDateSelect(\_MA_WGEVENTS_DATECREATED, 'datecreated', '', $tbDatecreated));
        // Form Select User tbSubmitter
        $uidCurrent = \is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->uid() : 0;
        $tbSubmitter = $this->isNew() ? $uidCurrent : $this->getVar('submitter');
        $form->addElement(new \XoopsFormSelectUser(\_MA_WGEVENTS_SUBMITTER, 'submitter', false, $tbSubmitter));

        // To Save
        $form->addElement(new \XoopsFormHidden('op', 'save'));
        $form->addElement(new \XoopsFormHidden('start', $this->start));
        $form->addElement(new \XoopsFormHidden('limit', $this->limit));
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
    public function getValuesTextblocks($keys = null, $format = null, $maxDepth = null)
    {
        $helper  = \XoopsModules\Wgevents\Helper::getInstance();
        $utility = new \XoopsModules\Wgevents\Utility();
        $ret = $this->getValues($keys, $format, $maxDepth);
        $editorMaxchar = $helper->getConfig('admin_maxchar');
        $categoryHandler = $helper->getHandler('Category');
        $categoryObj = $categoryHandler->get($this->getVar('catid'));
        $catName = '';
        if (\is_object($categoryObj)) {
            $catName = $categoryObj->getVar('name');
        }
        $ret['catname']          = $catName;
        $ret['text_text']        = $this->getVar('text', 'e');
        $ret['text_short']       = $utility::truncateHtml($ret['text'], $editorMaxchar);
        $ret['cat_text']         = $this->getVar('catid');
        $ret['class_text']       = Constants::TEXTBLOCK_CLASS_PUBLIC == $this->getVar('class') ? \_MA_WGEVENTS_TEXTBLOCK_CLASS_PUBLIC : \_MA_WGEVENTS_TEXTBLOCK_CLASS_PRIVATE;
        $ret['datecreated_text'] = \formatTimestamp($this->getVar('datecreated'), 's');
        $ret['submitter_text']   = \XoopsUser::getUnameFromId($this->getVar('submitter'));
        return $ret;
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
