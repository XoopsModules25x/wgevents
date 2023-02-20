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


/**
 * Class Object Handler Textblock
 */
class TextblockHandler extends \XoopsPersistableObjectHandler
{
    /**
     * Constructor
     *
     * @param \XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, 'wgevents_textblock', Textblock::class, 'id', 'name');
    }

    /**
     * @param bool $isNew
     *
     * @return object
     */
    public function create($isNew = true)
    {
        return parent::create($isNew);
    }

    /**
     * retrieve a field
     *
     * @param int $id field id
     * @param $fields
     * @return \XoopsObject|null reference to the {@link Get} object
     */
    public function get($id = null, $fields = null)
    {
        return parent::get($id, $fields);
    }

    /**
     * get inserted id
     *
     * @return int reference to the {@link Get} object
     */
    public function getInsertId()
    {
        return $this->db->getInsertId();
    }

    /**
     * Get Count Textblock in the database
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     * @return int
     */
    public function getCountTextblocks($start = 0, $limit = 0, $sort = 'weight ASC, id', $order = 'ASC')
    {
        $crCountTextblocks = new \CriteriaCompo();
        $crCountTextblocks = $this->getTextblocksCriteria($crCountTextblocks, $start, $limit, $sort, $order);
        return $this->getCount($crCountTextblocks);
    }

    /**
     * Get All Textblock in the database
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     * @return array
     */
    public function getAllTextblocks($start = 0, $limit = 0, $sort = 'weight ASC, id', $order = 'ASC')
    {
        $crAllTextblocks = new \CriteriaCompo();
        $crAllTextblocks = $this->getTextblocksCriteria($crAllTextblocks, $start, $limit, $sort, $order);
        return $this->getAll($crAllTextblocks);
    }

    /**
     * Get Criteria Textblock
     * @param        $crTextblock
     * @param int $start
     * @param int $limit
     * @param string $sort
     * @param string $order
     * @return int
     */
    private function getTextblocksCriteria($crTextblock, int $start, int $limit, string $sort, string $order)
    {
        $crTextblock->setStart($start);
        $crTextblock->setLimit($limit);
        $crTextblock->setSort($sort);
        $crTextblock->setOrder($order);
        return $crTextblock;
    }

    /**
     * @public function getForm
     * param array $textblockAll
     * @param bool $action
     * @return \XoopsThemeForm
     */
    public function getFormSelect($textblockAll, $action = false)
    {
        $helper = Helper::getInstance();
        //$categoryHandler = $helper->getHandler('Category');
        $questionHandler = $helper->getHandler('Question');

        if (!$action) {
            $action = $_SERVER['REQUEST_URI'];
        }

        // Get Theme Form
        \xoops_load('XoopsFormLoader');
        $form = new \XoopsThemeForm(\_MA_WGEVENTS_TEXTBLOCK_ADD, 'form', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');

        // Get All Textblock
        $selectTextblockTray = new \XoopsFormElementTray(\_MA_WGEVENTS_TEXTBLOCKS_LIST, '<br>');
        foreach (\array_keys($textblockAll) as $i) {
            $caption = $textblockAll[$i]->getVar('name');
            $text = $textblockAll[$i]->getVar('text');
            $value = '<p>' . $caption . '</p>' . $text;
            // Form Check Box
            $checkTextblock[$i] = new \XoopsFormCheckBox('', 'cbTextblock[' . $i . ']', 0);
            $checkTextblock[$i]->addOption(1, $caption);
            $selectTextblockTray->addElement($checkTextblock[$i]);
            $selectTextblockTray->addElement(new \XoopsFormLabel('', $text));
        }
        $form->addElement($selectTextblockTray);

        // To Save
        $form->addElement(new \XoopsFormHidden('op', 'save_textblock'));
        $form->addElement(new \XoopsFormButtonTray('', \_SUBMIT, 'submit', '', false));
        return $form;
    }

    /**
     * @public function to get next value for sorting
     *
     * @return int
     */
    public function getNextWeight()
    {
        $nextValue = 0;

        $crField = new \CriteriaCompo();
        $crField->setSort('weight');
        $crField->setOrder('DESC');
        $crField->setLimit(1);
        $fieldsCount = $this->getCount($crField);
        if ($fieldsCount > 0) {
            $fieldsAll = $this->getAll($crField);
            foreach (\array_keys($fieldsAll) as $i) {
                $nextValue = $fieldsAll[$i]->getVar('weight');
            }
        }

        return $nextValue + 1;

    }
}
