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

\defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class Object Answerhist
 */
class Answerhist extends \XoopsObject
{
    /**
     * Constructor
     *
     */
    public function __construct()
    {
        $this->initVar('hist_id', \XOBJ_DTYPE_INT);
        $this->initVar('hist_info', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('hist_datecreated', \XOBJ_DTYPE_INT);
        $this->initVar('hist_submitter', \XOBJ_DTYPE_INT);
        $this->initVar('id', \XOBJ_DTYPE_INT);
        $this->initVar('regid', \XOBJ_DTYPE_INT);
        $this->initVar('queid', \XOBJ_DTYPE_INT);
        $this->initVar('evid', \XOBJ_DTYPE_INT);
        $this->initVar('text', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('datecreated', \XOBJ_DTYPE_INT);
        $this->initVar('submitter', \XOBJ_DTYPE_INT);
    }

    /**
     * @static function &getInstance
     *
     */
    public static function getInstance()
    {
        static $instance = false;
        if (!$instance) {
            $instance = new self();
        }
    }

    /**
     * Get Values
     * @param array $questionsArr
     * @param null  $keys
     * @param null  $format
     * @param null  $maxDepth
     * @return array
     */
    public function getValuesAnswerhists($questionsArr, $keys = null, $format = null, $maxDepth = null)
    {
        $helper  = \XoopsModules\Wgevents\Helper::getInstance();
        $ret = $this->getValues($keys, $format, $maxDepth);
        $ret['hist_datecreated_text'] = \formatTimestamp($this->getVar('hist_datecreated'), 's');
        $ret['hist_submitter_text']   = \XoopsUser::getUnameFromId($this->getVar('hist_submitter'));
        $questionHandler = $helper->getHandler('Question');
        $questionObj = $questionHandler->get($this->getVar('queid'));
        $queCaption = '';
        if (\is_object($questionObj)) {
            $queCaption = $questionObj->getVar('caption');
        }
        $ret['quecaption'] = $queCaption;
        $queItem = $questionsArr[$this->getVar('queid')];
        $ansText = $this->getVar('text', 'n');
        if (Constants::FIELD_RADIOYN == $queItem['type']) {
            if ((bool)$ansText) {
                $ansText = \_YES;
            } else {
                $ansText = \_NO;
            }
        }
        if (Constants::FIELD_CHECKBOX == $queItem['type'] ||
            Constants::FIELD_COMBOBOX == $queItem['type']) {
            $queValues = \unserialize($queItem['values'], ['allowed_classes' => false]);
            $ansItems = \unserialize($ansText, ['allowed_classes' => false]);
            $ansText = '';
            foreach ($ansItems as $ansItem) {
                $ansText .= $queValues[(int)$ansItem] . ' <br>';
            }
        }
        if (Constants::FIELD_SELECTBOX == $queItem['type']) {
            $queValues = \unserialize($queItem['values'], ['allowed_classes' => false]);
            $ansItem = (string)\unserialize($ansText, ['allowed_classes' => false]);
            $ansText = $queValues[(int)$ansItem];
        }
        if (Constants::FIELD_RADIO == $queItem['type']) {
            $queValues = \unserialize($queItem['values']);
            $ansText = $queValues[$ansText];
        }
        $ret['text_text'] = $ansText;
        $eventHandler = $helper->getHandler('Event');
        $eventObj = $eventHandler->get($this->getVar('evid'));
        $evName = 'invalid event';
        if (\is_object($eventObj)) {
            $evName = $eventObj->getVar('name');
        }
        $ret['eventname']        = $evName;
        $ret['datecreated_text'] = \formatTimestamp($this->getVar('datecreated'), 's');
        $ret['submitter_text']   = \XoopsUser::getUnameFromId($this->getVar('submitter'));

        return $ret;
    }
}
