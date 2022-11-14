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
use XoopsModules\Wgevents\{
    Helper,
    Constants,
    Utility
};

require_once \XOOPS_ROOT_PATH . '/modules/wgevents/include/common.php';

/**
 * Function show block
 * @param  $options
 * @return array
 */
function b_wgevents_event_show($options)
{
    $helper  = Helper::getInstance();
    $eventHandler = $helper->getHandler('Event');
    $permissionsHandler = $helper->getHandler('Permission');
    $registrationHandler = $helper->getHandler('Registration');

    $GLOBALS['xoopsTpl']->assign('user_maxchar', $helper->getConfig('user_maxchar'));
    $uidCurrent  = 0;
    if (\is_object($GLOBALS['xoopsUser'])) {
        $uidCurrent  = $GLOBALS['xoopsUser']->uid();
    }
    $GLOBALS['xoopsTpl']->assign('start', 0);
    $GLOBALS['xoopsTpl']->assign('limit', (int)$helper->getConfig('userpager'));

    $block       = [];
    $typeBlock   = $options[0];
    $limit       = (int)$options[1];
    $lenghtTitle = (int)$options[2];
    $blockType   = (string)$options[3];
    \array_shift($options);
    \array_shift($options);
    \array_shift($options);
    \array_shift($options);

    $GLOBALS['xoTheme']->addStylesheet(\WGEVENTS_URL . '/assets/css/style.css', null);

    $dateCreated = 0;
    switch ($typeBlock) {
        case 'last':
        default:
            // For the block: events last
            $sortBy  = 'datecreated';
            $orderBy = 'DESC';
            break;
        case 'new':
            // For the block: events new
            // new since last week: 7 * 24 * 60 * 60 = 604800
            $dateCreated = \time() - 604800;
            $sortBy      = 'datecreated';
            $orderBy     = 'ASC';
            break;
        case 'random':
            // For the block: events random
            $sortBy  = 'RAND()';
            $orderBy = '';
            break;
        case 'coming':
            // For the block: next events
            $dateFrom = \time();
            $sortBy   = 'datefrom';
            $orderBy  = 'ASC';
            break;
    }

    $eventsArr = $eventHandler->getEvents(0, $limit, $dateFrom, 0, $sortBy, $orderBy, '', 0, '', [], $dateCreated);
    $eventsCount = $eventsArr['count'];

    if ($eventsCount > 0) {
        $eventsAll = $eventsArr['eventsAll'];
        foreach (\array_keys($eventsAll) as $i) {
            $block[$i] = $eventsAll[$i]->getValuesEvents();
            //get progress of registrations
            //currently only for wgevents_block_events_panel
            if ('panel' === $blockType) {
                $crRegistration = new \CriteriaCompo();
                $crRegistration->add(new \Criteria('evid', $i));
                $numberRegCurr = $registrationHandler->getCount($crRegistration);
                $block[$i]['nb_registrations'] = $numberRegCurr;
                $registerMax = (int)$block[$i]['register_max'];
                if ($registerMax > 0) {
                    $block[$i]['regmax'] = $registerMax;
                    $proportion = $numberRegCurr / $registerMax;
                    if ($proportion >= 1) {
                        $block[$i]['regcurrent'] = \_MA_WGEVENTS_REGISTRATIONS_FULL;
                    } else {
                        $block[$i]['regcurrent'] = \sprintf(\_MA_WGEVENTS_REGISTRATIONS_NBCURR_INDEX, $numberRegCurr, $registerMax);
                    }
                    $block[$i]['regcurrent_text'] = $block[$i]['regcurrent'];
                    $block[$i]['regcurrent_tip'] = true;
                    if ($proportion < 0.75) {
                        $block[$i]['regcurrentstate'] = 'success';
                    } elseif ($proportion < 1) {
                        $block[$i]['regcurrentstate'] = 'warning';
                    } else {
                        $block[$i]['regcurrentstate'] = 'danger';
                        $block[$i]['regcurrent_tip'] = false;
                    }
                    $block[$i]['regpercentage'] = (int)($proportion * 100);
                }
            }
            $block[$i]['datefromto_text'] = $eventHandler->getDateFromToText($eventsAll[$i]->getVar('datefrom'), $eventsAll[$i]->getVar('dateto'), $eventsAll[$i]->getVar('allday'));
            $block[$i]['permEdit'] = ($permissionsHandler->getPermEventsEdit($eventsAll[$i]->getVar('submitter'), $eventsAll[$i]->getVar('status')) || $uidCurrent == $eventsAll[$i]->getVar('submitter'));
        }
    }
    $GLOBALS['xoopsTpl']->assign('wgevents_permAdd', ($uidCurrent > 0 && $permissionsHandler->getPermEventsSubmit()));
    $GLOBALS['xoopsTpl']->assign('permRegister', $permissionsHandler->getPermRegistrationsSubmit());
    $GLOBALS['xoopsTpl']->assign('wgevents_blocktype', $blockType);
    $GLOBALS['xoopsTpl']->assign('wgevents_upload_catlogos_url', \WGEVENTS_UPLOAD_CATLOGOS_URL);
    $GLOBALS['xoopsTpl']->assign('wgevents_upload_eventlogos_url', \WGEVENTS_UPLOAD_EVENTLOGOS_URL);
    $GLOBALS['xoopsTpl']->assign('wgevents_url', \WGEVENTS_URL);

    return $block;

}

/**
 * Function edit block
 * @param  $options
 * @return string
 */
function b_wgevents_event_edit($options)
{
    $GLOBALS['xoopsTpl']->assign('wgevents_upload_url', \WGEVENTS_UPLOAD_URL);
    $form = \_MB_WGEVENTS_DISPLAY . ' : ';
    $form .= "<input type='hidden' name='options[0]' value='".$options[0]."' >";
    $form .= "<input type='text' name='options[1]' size='5' maxlength='255' value='" . $options[1] . "' >&nbsp;<br>";
    $form .= \_MB_WGEVENTS_TITLE_LENGTH . " : <input type='text' name='options[2]' size='5' maxlength='255' value='" . $options[2] . "' ><br>";
    $form .= \_MB_WGEVENTS_BLOCKTYPE . ": <select name='options[3]' size='4'>";
    $form .= "<option value='table' " . ('table' === (string)$options[3] ? "selected='selected'" : '') . '>' . \_MB_WGEVENTS_BLOCKTYPE_TABLE . '</option>';
    $form .= "<option value='simple' " . ('simple' === (string)$options[3] ? "selected='selected'" : '') . '>' . \_MB_WGEVENTS_BLOCKTYPE_SIMPLE . '</option>';
    $form .= "<option value='extended' " . ('extended' === (string)$options[3] ? "selected='selected'" : '') . '>' . \_MB_WGEVENTS_BLOCKTYPE_EXTENDED . '</option>';
    $form .= "<option value='panel' " . ('panel' === (string)$options[3] ? "selected='selected'" : '') . '>' . \_MB_WGEVENTS_BLOCKTYPE_PANEL . '</option>';
    $form .= "<option value='bcard2' " . ('bcard2' === (string)$options[3] ? "selected='selected'" : '') . '>' . \_MB_WGEVENTS_BLOCKTYPE_BCARD2 . '</option>';
    $form .= '</select><br>';
    
    /*
    \array_shift($options);
    \array_shift($options);
    \array_shift($options);
    \array_shift($options);

    $crEvent = new \CriteriaCompo();
    $crEvent->add(new \Criteria('id', 0, '!='));
    $crEvent->setSort('id');
    $crEvent->setOrder('ASC');
    */
    /**
     * If you want to filter your results by e.g. a category used in yourevents
     * then you can activate the following code, but you have to change it according your category
     */
    /*
    $helper = Helper::getInstance();
    $eventHandler = $helper->getHandler('Event');
    $eventsAll = $eventHandler->getAll($crEvent);
    unset($crEvent);
    $form .= \_MB_WGEVENTS_EVENTS_TO_DISPLAY . "<br><select name='options[]' multiple='multiple' size='5'>";
    $form .= "<option value='0' " . (!\in_array(0, $options) && !\in_array('0', $options) ? '' : "selected='selected'") . '>' . \_MB_WGEVENTS_ALL_EVENTS . '</option>';
    foreach (\array_keys($eventsAll) as $i) {
        $id = $eventsAll[$i]->getVar('id');
        $form .= "<option value='" . $id . "' " . (!\in_array($id, $options) ? '' : "selected='selected'") . '>' . $eventsAll[$i]->getVar('name') . '</option>';
    }
    $form .= '</select>';

    */
    return $form;

}
