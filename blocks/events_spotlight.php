<?php declare(strict_types=1);

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
use XoopsModules\Wgevents\Constants;

require_once \XOOPS_ROOT_PATH . '/modules/wgevents/include/common.php';

/**
 * Function show block
 * @param  $options
 * @return array
 */
function b_wgevents_events_spotlight_show($options)
{
    $block       = [];
    $typeBlock   = $options[0];
    $limit       = $options[1];
    $lenghtTitle = $options[2];
    $helper      = Helper::getInstance();
    $eventsHandler = $helper->getHandler('Event');
    $crEvents = new \CriteriaCompo();
    \array_shift($options);
    \array_shift($options);
    \array_shift($options);

    // Criteria for status field
    $crEvents->add(new \Criteria('status', Constants::STATUS_SUBMITTED, '>'));

    if (\count($options) > 0 && (int)$options[0] > 0) {
        $crEvents->add(new \Criteria('id', '(' . \implode(',', $options) . ')', 'IN'));
        $limit = 0;
    }

    $crEvents->setSort('id');
    $crEvents->setOrder('DESC');
    $crEvents->setLimit($limit);
    $eventsAll = $eventsHandler->getAll($crEvents);
    unset($crEvents);
    if (\count($eventsAll) > 0) {
        foreach (\array_keys($eventsAll) as $i) {
            /**
             * If you want to use the parameter for limits you have to adapt the line where it should be applied
             * e.g. change
             *     $block[$i]['title'] = $eventsAll[$i]->getVar('art_title');
             * into
             *     $myTitle = $eventsAll[$i]->getVar('art_title');
             *     if ($limit > 0) {
             *         $myTitle = \substr($myTitle, 0, (int)$limit);
             *     }
             *     $block[$i]['title'] =  $myTitle;
             */
            $block[$i]['id'] = $eventsAll[$i]->getVar('id');
            $block[$i]['name'] = \htmlspecialchars($eventsAll[$i]->getVar('name'), ENT_QUOTES | ENT_HTML5);
            $block[$i]['logo'] = $eventsAll[$i]->getVar('logo');
            $block[$i]['submitter'] = $eventsAll[$i]->getVar('submitter');
        }
    }

    $GLOBALS['xoopsTpl']->assign('wgevents_upload_eventlogos_url', \WGEVENTS_UPLOAD_EVENTLOGOS_URL . '/');

    return $block;

}

/**
 * Function edit block
 * @param  $options
 * @return string
 */
function b_wgevents_events_spotlight_edit($options)
{
    $helper = Helper::getInstance();
    $eventsHandler = $helper->getHandler('Event');
    $GLOBALS['xoopsTpl']->assign('wgevents_upload_url', \WGEVENTS_UPLOAD_URL);
    $form = \_MB_WGEVENTS_DISPLAY_SPOTLIGHT . ' : ';
    $form .= "<input type='hidden' name='options[0]' value='".$options[0]."' >";
    $form .= "<input type='text' name='options[1]' size='5' maxlength='255' value='" . $options[1] . "' >&nbsp;<br>";
    $form .= \_MB_WGEVENTS_TITLE_LENGTH . " : <input type='text' name='options[2]' size='5' maxlength='255' value='" . $options[2] . "' ><br><br>";
    \array_shift($options);
    \array_shift($options);
    \array_shift($options);

    $crEvents = new \CriteriaCompo();
    $crEvents->add(new \Criteria('id', 0, '!='));
    $crEvents->setSort('id');
    $crEvents->setOrder('ASC');
    $eventsAll = $eventsHandler->getAll($crEvents);
    unset($crEvents);
    $form .= \_MB_WGEVENTS_EVENTS_TO_DISPLAY . "<br><select name='options[]' multiple='multiple' size='5'>";
    $form .= "<option value='0' " . (!\in_array(0, $options) && !\in_array('0', $options) ? '' : "selected='selected'") . '>' . \_MB_WGEVENTS_ALL_EVENTS . '</option>';
    foreach (\array_keys($eventsAll) as $i) {
        $id = $eventsAll[$i]->getVar('id');
        $form .= "<option value='" . $id . "' " . (!\in_array($id, $options) ? '' : "selected='selected'") . '>' . $eventsAll[$i]->getVar('name') . '</option>';
    }
    $form .= '</select>';

    return $form;

}
