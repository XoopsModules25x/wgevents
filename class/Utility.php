<?php

namespace XoopsModules\Wgevents;

/*
 Utility Class Definition

 You may not change or alter any portion of this comment or credits of
 supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit
 authors.

 This program is distributed in the hope that it will be useful, but
 WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * Module:  wgevents
 *
 * @package      \module\wgevents\class
 * @license      http://www.fsf.org/copyleft/gpl.html GNU public license
 * @copyright    https://xoops.org 2001-2017 &copy; XOOPS Project
 * @author       ZySpec <zyspec@yahoo.com>
 * @author       Mamba <mambax7@gmail.com>
 * @since
 */

use XoopsModules\Wgevents;

/**
 * Class Utility
 */
class Utility
{
    use Common\VersionChecks;

    //checkVerXoops, checkVerPhp Traits

    use Common\ServerStats;

    // getServerStats Trait

    use Common\FilesManagement;

    // Files Management Trait

    /**
     * truncateHtml can truncate a string up to a number of characters while preserving whole words and HTML tags
     * www.gsdesign.ro/blog/cut-html-string-without-breaking-the-tags
     * www.cakephp.org
     *
     * @param string $text         String to truncate.
     * @param int    $length       Length of returned string, including ellipsis.
     * @param string $ending       Ending to be appended to the trimmed string.
     * @param bool   $exact        If false, $text will not be cut mid-word
     * @param bool   $considerHtml If true, HTML tags would be handled correctly
     *
     * @return string Trimmed string.
     */
    public static function truncateHtml($text, $length = 100, $ending = '...', $exact = false, $considerHtml = true)
    {
        if ($considerHtml) {
            // if the plain text is shorter than the maximum length, return the whole text
            if (\mb_strlen(\preg_replace('/<.*?' . '>/', '', $text)) <= $length) {
                return $text;
            }
            // splits all html-tags to scanable lines
            \preg_match_all('/(<.+?' . '>)?([^<>]*)/s', $text, $lines, \PREG_SET_ORDER);
            $total_length = \mb_strlen($ending);
            $open_tags    = [];
            $truncate     = '';
            foreach ($lines as $line_matchings) {
                // if there is any html-tag in this line, handle it and add it (uncounted) to the output
                if (!empty($line_matchings[1])) {
                    // if it's an "empty element" with or without xhtml-conform closing slash
                    if (\preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
                        // do nothing
                        // if tag is a closing tag
                    } elseif (\preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
                        // delete tag from $open_tags list
                        $pos = \array_search($tag_matchings[1], $open_tags, true);
                        if (false !== $pos) {
                            unset($open_tags[$pos]);
                        }
                        // if tag is an opening tag
                    } elseif (\preg_match('/^<\s*([^\s>!]+).*?' . '>$/s', $line_matchings[1], $tag_matchings)) {
                        // add tag to the beginning of $open_tags list
                        \array_unshift($open_tags, \mb_strtolower($tag_matchings[1]));
                    }
                    // add html-tag to $truncate'd text
                    $truncate .= $line_matchings[1];
                }
                // calculate the length of the plain text part of the line; handle entities as one character
                $content_length = \mb_strlen(\preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
                if ($total_length + $content_length > $length) {
                    // the number of characters which are left
                    $left            = $length - $total_length;
                    $entities_length = 0;
                    // search for html entities
                    if (\preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, \PREG_OFFSET_CAPTURE)) {
                        // calculate the real length of all entities in the legal range
                        foreach ($entities[0] as $entity) {
                            if ($left >= $entity[1] + 1 - $entities_length) {
                                $left--;
                                $entities_length += \mb_strlen($entity[0]);
                            } else {
                                // no more characters left
                                break;
                            }
                        }
                    }
                    $truncate .= \mb_substr($line_matchings[2], 0, $left + $entities_length);
                    // maximum lenght is reached, so get off the loop
                    break;
                }
                $truncate     .= $line_matchings[2];
                $total_length += $content_length;

                // if the maximum length is reached, get off the loop
                if ($total_length >= $length) {
                    break;
                }
            }
        } else {
            if (\mb_strlen($text) <= $length) {
                return $text;
            }
            $truncate = \mb_substr($text, 0, $length - \mb_strlen($ending));
        }
        // if the words shouldn't be cut in the middle...
        if (!$exact) {
            // ...search the last occurance of a space...
            $spacepos = \mb_strrpos($truncate, ' ');
            if (isset($spacepos)) {
                // ...and cut the text in this position
                $truncate = \mb_substr($truncate, 0, $spacepos);
            }
        }
        // add the defined ending to the text
        if ('' != $truncate) {
            $truncate .= $ending;
        }
        if ($considerHtml) {
            // close all unclosed html-tags
            foreach ($open_tags as $tag) {
                $truncate .= '</' . $tag . '>';
            }
        }

        return $truncate;
    }

    /**
     * @public function to convert float into string
     * @param  float $float
     * @return string
     */
    public static function FloatToString($float) {

        $helper = \XoopsModules\Wgevents\Helper::getInstance();
        $dec = $helper->getConfig('sep_comma');
        $thnd = $helper->getConfig('sep_thousand');

        return \number_format($float, 2, $dec, $thnd);

    }

    /**
     * @public function to convert string into float
     * @param  string $str
     * @return float
     */
    public static function StringToFloat($str) {

        $helper = \XoopsModules\Wgevents\Helper::getInstance();
        $dec = $helper->getConfig('sep_comma');
        $thnd = $helper->getConfig('sep_thousand');

        $str = \preg_replace('[^0-9\,\.\-\+]', '', (string)$str);
        $str = \str_replace($thnd, '', $str);
        $str = \str_replace(' ', '', $str);
        $str = \str_replace($dec, '.', $str);

        return (float)$str;

    }

    /**
     * @public function to get text of status
     * @param $status
     * @return string
     */
    public static function getStatusText($status)
    {
        switch ($status) {
            case '':
            default:
                $status_text = 'invalid status';
                break;
            case Constants::STATUS_NONE:
                $status_text = \_MA_WGEVENTS_STATUS_NONE;
                break;
            case Constants::STATUS_OFFLINE:
                $status_text = \_MA_WGEVENTS_STATUS_OFFLINE;
                break;
            case Constants::STATUS_ONLINE:
                $status_text = \_MA_WGEVENTS_STATUS_ONLINE;
                break;
            case Constants::STATUS_SUBMITTED:
                $status_text = \_MA_WGEVENTS_STATUS_SUBMITTED;
                break;
            case Constants::STATUS_VERIFIED:
                $status_text = \_MA_WGEVENTS_STATUS_VERIFIED;
                break;
            case Constants::STATUS_APPROVED:
                $status_text = \_MA_WGEVENTS_STATUS_APPROVED;
                break;
            case Constants::STATUS_LOCKED:
                $status_text = \_MA_WGEVENTS_STATUS_LOCKED;
                break;
            case Constants::STATUS_CANCELED:
                $status_text = \_MA_WGEVENTS_STATUS_CANCELED;
                break;
            case Constants::STATUS_PENDING:
                $status_text = \_MA_WGEVENTS_STATUS_PENDING;
                break;
            case Constants::STATUS_PROCESSING:
                $status_text = \_MA_WGEVENTS_STATUS_PROCESSING;
                break;
            case Constants::STATUS_DONE:
                $status_text = \_MA_WGEVENTS_STATUS_DONE;
                break;
        }

        return $status_text;

    }

    /**
     * @public function to get text of salutation
     * @param $salutation
     * @return string
     */
    public static function getSalutationText($salutation)
    {
        switch ($salutation) {
            case Constants::SALUTATION_NONE:
            default:
                $salutation_text = '';
                break;
            case Constants::SALUTATION_MEN:
                $salutation_text = \_MA_WGEVENTS_REGISTRATION_SALUTATION_MEN;
                break;
            case Constants::SALUTATION_WOMEN:
                $salutation_text = \_MA_WGEVENTS_REGISTRATION_SALUTATION_WOMEN;
                break;
        }

        return $salutation_text;

    }

    /**
     * @public function to get text of salutation
     * @param $financial
     * @return string
     */
    public static function getFinancialText($financial)
    {
        switch ($financial) {
            case Constants::FINANCIAL_UNPAID:
                $financial_text = \_MA_WGEVENTS_REGISTRATION_FINANCIAL_UNPAID;
                break;
            case Constants::FINANCIAL_PAID:
                $financial_text = \_MA_WGEVENTS_REGISTRATION_FINANCIAL_PAID;
                break;
            case -1:
            default:
                $financial_text = 'Invalid financial';
                break;
        }

        return $financial_text;

    }

    /**
     * @public function to get text of waiting list
     * @param $listwait
     * @return string
     */
    public static function getListWaitText($listwait)
    {
        switch ($listwait) {
            case 1:
                $listwait_text = \_MA_WGEVENTS_REGISTRATION_LISTWAIT_Y;
                break;
            case 0:
            default:
            $listwait_text = \_MA_WGEVENTS_REGISTRATION_LISTWAIT_N;
                break;
        }

        return $listwait_text;

    }
}
