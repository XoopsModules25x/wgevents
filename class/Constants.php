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

/**
 * Interface  Constants
 */
interface Constants
{
    // Constants for tables
    public const TABLE_EVENTS             = 1;
    public const TABLE_CATEGORIES         = 2;
    public const TABLE_REGISTRATIONS      = 3;
    public const TABLE_QUESTIONS          = 4;
    public const TABLE_ANSWERS            = 5;
    public const TABLE_TEXTBLOCKS         = 6;
    public const TABLE_FIELDS             = 7;
    public const TABLE_REGISTRATIONS_HIST = 8;
    public const TABLE_ANSWERS_HIST       = 9;

    // Constants for status
    public const STATUS_NONE      = 0;
    public const STATUS_OFFLINE   = 1;
    public const STATUS_SUBMITTED = 2;
    public const STATUS_ONLINE    = 3;
    public const STATUS_VERIFIED  = 4;
    public const STATUS_APPROVED  = 5;
    public const STATUS_LOCKED    = 6;
    public const STATUS_CANCELED  = 7;

    // Constants for permissions
    public const PERM_GLOBAL_NONE             = 0;
    public const PERM_GLOBAL_VIEW             = 1;
    public const PERM_GLOBAL_SUBMIT           = 2;
    public const PERM_GLOBAL_APPROVE          = 3;
    public const PERM_EVENTS_VIEW             = 4;
    public const PERM_EVENTS_SUBMIT           = 5;
    public const PERM_EVENTS_APPROVE          = 6;
    public const PERM_EVENTS_APPROVE_AUTO     = 7;
    public const PERM_REGISTRATIONS_AUTOVERIF = 8;
    public const PERM_REGISTRATIONS_SUBMIT    = 9;

    // Constants for question types
    public const FIELD_NONE       = 0;
    public const FIELD_LABEL      = 1;
    public const FIELD_TEXTBOX    = 2;
    public const FIELD_TEXTAREA   = 3;
    public const FIELD_RADIO      = 4;
    public const FIELD_RADIOYN    = 5;
    public const FIELD_SELECTBOX  = 6;
    public const FIELD_COMBOBOX   = 7;
    public const FIELD_CHECKBOX   = 8;
    public const FIELD_DATE       = 9;
    public const FIELD_DATETIME   = 10;
    public const FIELD_NAME       = 11;
    public const FIELD_EMAIL      = 12;
    public const FIELD_COUNTRY    = 13;
    public const FIELD_TEXTEDITOR = 14;

    // Constants for salutation
    public const SALUTATION_NONE  = 0;
    public const SALUTATION_MEN   = 1;
    public const SALUTATION_WOMEN = 2;

    // Constants for financial
    public const FINANCIAL_UNPAID = 0;
    public const FINANCIAL_PAID   = 1;

    // Constants for mail sending
    public const MAIL_REG_CONFIRM_IN      = 1;
    public const MAIL_REG_CONFIRM_OUT     = 2;
    public const MAIL_REG_CONFIRM_MODIFY  = 3;
    public const MAIL_REG_NOTIFY_IN       = 4;
    public const MAIL_REG_NOTIFY_OUT      = 5;
    public const MAIL_REG_NOTIFY_MODIFY   = 6;
    //public const MAIL_REG_NOTIFY_ALL      = 7;
    public const MAIL_EVENT_NOTIFY_MODIFY = 8;

    // constants for accounts
    public const ACCOUNT_TYPE_VAL_PHP_MAIL      = 1;
    public const ACCOUNT_TYPE_VAL_PHP_SENDMAIL     = 2;
    public const ACCOUNT_TYPE_VAL_POP3  = 3;
    public const ACCOUNT_TYPE_VAL_SMTP       = 4;
    public const ACCOUNT_TYPE_VAL_GMAIL      = 5;

    // Constants for financial
    public const TEXTBLOCK_CLASS_PRIVATE = 0;
    public const TEXTBLOCK_CLASS_PUBLIC  = 1;

}
