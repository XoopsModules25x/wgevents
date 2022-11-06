<?php

namespace XoopsModules\Wgevents\SimpleCalendar;

use XoopsModules\Wgevents\Forms;
/**
 * Simple Calendar
 *
 * @author Jesse G. Donat <donatj@gmail.com>
 * @see http://donatstudios.com
 * @license http://opensource.org/licenses/mit-license.php
 */
class SimpleCalendarforms {

    /**
     * @public function getFormGotoMonth
     * @param array $arrMonth
     * @param int   $month
     * @param int   $year
     * @param bool  $action
     * @return Forms\FormInline
     */
    public function getFormGotoMonth($arrMonth, $month = 0, $year = 0, $action = false)
    {
        if (!$action) {
            $action = $_SERVER['REQUEST_URI'];
        }
        // Get Theme Form
        \xoops_load('XoopsFormLoader');
        $form = new Forms\FormInline('', 'formGotoMonth', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        // Form Text select
        $gotoTray = new \XoopsFormElementTray('', '');
        // Form select month
        $monthSelect = new \XoopsFormSelect(\_MA_WGEVENTS_CAL_GOTO, 'gotoMonth', $month);
        foreach($arrMonth as $keyM => $valM) {
            $monthSelect->addOption($keyM, $valM);
        }
        $gotoTray->addElement($monthSelect, true);
        // Form select year
        $yearSelect = new \XoopsFormSelect('', 'gotoYear', $year);
        for ($i = $year - 10; $i <= $year + 10; $i++) {
            $yearSelect->addOption($i);
        }
        $gotoTray->addElement($yearSelect, true);
        $form->addElement($gotoTray);

        // To Save
        $form->addElement(new \XoopsFormHidden('op', 'list'));
        $form->addElement(new \XoopsFormButton('', 'submit_monthyear' , \_MA_WGEVENTS_CAL_SHOW, 'submit'));
        return $form;
    }

}
