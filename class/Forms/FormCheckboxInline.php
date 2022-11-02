<?php

namespace XoopsModules\Wgevents\Forms;

/**
 * XOOPS button
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       (c) 2000-2016 XOOPS Project (www.xoops.org)
 * @license             GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package             kernel
 * @subpackage          form
 * @since               2.0.0
 * @author              Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 */

use XoopsFormText;

\defined('XOOPS_ROOT_PATH') || exit('Restricted access');

/**
 * base class
 */
\xoops_load('\XoopsFormText');

/**
 * Create hidden form button
 */
class FormCheckboxInline extends \XoopsFormCheckbox
{

    /**
     * create HTML to output of checkboxes inline
     *
     * @return string
     */
    public function render()
    {

        $ret = '';

        $idSuffix = 0;
        $elementValue = $this->getValue();
        $elementOptions = $this->getOptions();
        foreach ($elementOptions as $value => $name) {
            ++$idSuffix;
            $ret .= '<label class="checkbox-inline">';
            $ret .= "<input type='checkbox' name='" . $this->getName() . "[]' id='" . $this->getName() . $idSuffix . "' title='"
                . htmlspecialchars(strip_tags($this->getName()), ENT_QUOTES) . "' value='"
                . $value . "'";

            if (is_array($elementValue) ? in_array($value, $elementValue): $value == $elementValue) {
                $ret .= ' checked';
            }
            $ret .= $this->getExtra() . ' />' . $name . $this->getDelimeter();
            $ret .= '</label>';
        }

        return $ret;

    }
}
