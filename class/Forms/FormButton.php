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

use XoopsFormButton;

\defined('XOOPS_ROOT_PATH') || exit('Restricted access');

/**
 * base class
 */
\xoops_load('\XoopsFormButton');

/**
 * Create hidden form button
 */
class FormButton extends \XoopsFormButton
{
    /**
     * create HTML to output the hidden button
     *
     * @return string
     */
    public function render()
    {
        $ret = "<input type='" . $this->getType() . "' class='btn " . $this->getClass() . "' ";
        if ($this->_hidden) {
            $ret .= 'style="display:none"';
        }
        $ret .= " name='"
            . $this->getName() . "'  id='" . $this->getName() . "' value='" . $this->getValue()
            . "' title='" . $this->getValue() . "'" . $this->getExtra() . ' />';

        return $ret;

    }

}
