<?php

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
 * @copyright      2020 XOOPS Project (https://xooops.org)
 * @license        GPL 2.0 or later
 * @package        wgevents
 * @since          1.0
 * @min_xoops      2.5.10
 * @author         Goffy - XOOPS Development Team - Email:<webmaster@wedega.com> - Website:<https://xoops.wedega.com>
 */

use Xmf\Request;
use XoopsModules\Wgevents;
use XoopsModules\Wgevents\{
    Constants,
    Utility,
    Export\Simplexlsxgen,
    Export\Simplecsv,
};

require __DIR__ . '/header.php';
require_once \XOOPS_ROOT_PATH . '/header.php';
$GLOBALS['xoopsTpl']->assign('template_sub', 'db:wgevents_outputs.tpl');

// Permissions
if (!$permissionsHandler->getPermGlobalView()) {
    \redirect_header('index.php', 0, '');
}

$op      = Request::getCmd('op', 'none');
$evId    = Request::getInt('id');
$redir   = Request::getString('redir');
$outType = Request::getString('output_type', 'none');

switch ($op) {
    case 'none':
    default:
        echo 'Invalid op';
        break;


    case 'reg_all';
        if (0 == $evId) {
            \redirect_header('registrations.php?op=list', 3, \_MA_WGEVENTS_INVALID_PARAM);
        }
        $eventsObj = $eventsHandler->get($evId);
        if (!$permissionsHandler->getPermRegistrationsApprove($eventsObj->getVar('submitter'), $eventsObj->getVar('status'))) {
            \redirect_header('index.php', 3, _NOPERM);
        }
        switch ($outType) {
            case 'csv':
            case 'xlsx':
                $eventname = \preg_replace('/[^a-zA-Z0-9]/', '', (string)$eventsObj->getVar('name'));
                $filename = \date('Ymd_H_i_s_', \time()) . \_MA_WGEVENTS_REGISTRATIONS . '_' . $eventname . '.' . $outType;

                $eventFee = (float)$eventsObj->getVar('fee');
                $eventRegisterMax = (int)$eventsObj->getVar('register_max');
                // Add data
                $crRegistrations = new \CriteriaCompo();
                $crRegistrations->add(new \Criteria('evid', $evId));
                $registrationsCount = $registrationsHandler->getCount($crRegistrations);
                $GLOBALS['xoopsTpl']->assign('registrationsCount', $registrationsCount);

                if ($registrationsCount > 0) {
                    $i = 0;
                    // get all questions for this event
                    $questionsArr = $questionsHandler->getQuestionsByEvent($evId);
                    //add field names
                    if ('xlsx' == $outType) {
                        $data[$i] = [\_MA_WGEVENTS_REGISTRATION_SALUTATION, \_MA_WGEVENTS_REGISTRATION_FIRSTNAME, \_MA_WGEVENTS_REGISTRATION_LASTNAME,
                            \_MA_WGEVENTS_REGISTRATION_EMAIL];
                        foreach ($questionsArr as $question) {
                            $data[$i][] = $question['caption'];
                        }
                        if ($eventFee > 0) {
                            $data[$i][] = \_MA_WGEVENTS_REGISTRATION_FINANCIAL;
                        }
                        if ($eventRegisterMax > 0) {
                            $data[$i][] = \_MA_WGEVENTS_REGISTRATION_LISTWAIT;
                        }
                        $data[$i][] = \_MA_WGEVENTS_DATECREATED;
                        $data[$i][] = \_MA_WGEVENTS_SUBMITTER;
                    } else {
                        $data[$i] = [
                            '"' . \_MA_WGEVENTS_REGISTRATION_SALUTATION . '"',
                            '"' . \_MA_WGEVENTS_REGISTRATION_FIRSTNAME . '"',
                            '"' . \_MA_WGEVENTS_REGISTRATION_LASTNAME . '"',
                            '"' . \_MA_WGEVENTS_REGISTRATION_EMAIL . '"'
                        ];
                        foreach ($questionsArr as $question) {
                            $data[$i][] = '"' . $question['caption'] . '"';
                        }
                        if ($eventFee > 0) {
                            $data[$i][] = '"' . \_MA_WGEVENTS_REGISTRATION_FINANCIAL . '"';
                        }
                        if ($eventRegisterMax > 0) {
                            $data[$i][] = '"' . \_MA_WGEVENTS_REGISTRATION_LISTWAIT . '"';
                        }
                        $data[$i][] = '"' . \_MA_WGEVENTS_DATECREATED . '"';
                        $data[$i][] = '"' . \_MA_WGEVENTS_SUBMITTER . '"';
                    }
                    //get list of existing registrations for current user/current IP
                    $registrations = $registrationsHandler->getRegistrationDetailsByEvent($evId, $questionsArr, false);
                    // Get All Transactions
                    foreach ($registrations as $registration) {
                        $i++;
                        if ('xlsx' == $outType) {
                            $data[$i] = [
                                $registration['salutation_text'],
                                $registration['firstname'],
                                $registration['lastname'],
                                $registration['email']
                            ];
                            foreach ($registration['answers'] as $answer) {
                                $data[$i][] = $answer;
                            }
                            if ($eventFee > 0) {
                                $data[$i][] = $registration['financial_text'];
                            }
                            if ($eventRegisterMax > 0) {
                                $data[$i][] = $registration['listwait_text'];
                            }
                            $data[$i][] = $registration['datecreated_text'];
                            $data[$i][] = $registration['submitter_text'];
                        } else {
                            $data[$i] = [
                                '"' . $registration['salutation_text'] . '"',
                                '"' . $registration['firstname'] . '"',
                                '"' . $registration['lastname'] . '"',
                                '"' . $registration['email'] . '"'
                            ];
                            foreach ($registration['answers'] as $answer) {
                                $data[$i][] = '"' . $answer . '"';
                            }
                            if ($eventFee > 0) {
                                $data[$i][] = '"' . $registration['financial_text'] . '"';
                            }
                            if ($eventRegisterMax > 0) {
                                $data[$i][] = '"' . $registration['listwait_text'] . '"';
                            }
                            $data[$i][] = '"' . $registration['datecreated_text'] . '"';
                            $data[$i][] = '"' . $registration['submitter_text'] . '"';
                        }
                    }
                    unset($registrations);
                }
                if ('xlsx' == $outType) {
                    $xlsx = Simplexlsxgen\SimpleXLSXGen::fromArray($data);
                    $xlsx->downloadAs($filename);
                } else {
                    $csv = Simplecsv\SimpleCSV::downloadAs( $data, $filename);
                }
                break;
            case 'none':
            default:
                break;
        }
        break;
}

require __DIR__ . '/footer.php';

/**
 * function to clean output for csv
 *
 * @param $text
 * @return string
 */
function cleanOutputCsv ($text) {
    //replace possible column break in output
    $cleanText = \str_replace(';', ',', $text);

    //convert to utf8
    \mb_convert_encoding($cleanText, 'UTF-8');
    foreach(\mb_list_encodings() as $chr){
        $cleanText = \mb_convert_encoding($cleanText, 'UTF-8', $chr);
    }

    return $cleanText;
}

/**
 * function to clean output for xlsx
 *
 * @param $text
 * @return string
 */
function cleanOutputXlsx ($text) {
    //replace line breaks by blank space
    $cleanText = \str_replace(['<br>', '</p>'], ' ', $text);
    //replace html code by clean char
    $cleanText = \html_entity_decode($cleanText);

    return $cleanText;
}
