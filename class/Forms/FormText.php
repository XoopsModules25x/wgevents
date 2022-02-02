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
class FormText extends \XoopsFormText
{
    
    /**
     * placeholder text
     *
     * @var string
     */
    private $placeholder;

    /**
     * create HTML to output the ext field
     *
     * @return string
     */
    public function render()
    {

        $ret =  "<input class='form-control " . $this->getClass() . "' type='text' name='"
            . $this->getName() . "' title='" . $this->getTitle() . "' id='" . $this->getName()
            . "' size='" . $this->getSize() . "' maxlength='" . $this->getMaxlength()
            . "' value='" . $this->getValue() . "'" . $this->getExtra();
        if ($this->_hidden) {
            $ret .= ' style="display:none" ';
        }
        if ($this->placeholder) {
            $ret .= ' placeholder="' . $this->getPlaceholder() . '"';
        }
        $ret .= ' />';
        if (($desc = $this->getDescription()) != '') {
            $ret .= '<p class="form-text text-muted">' . $desc . '</p>';
        }

        return $ret;

    }

    /**
     * Get placeholder value
     *
     * @return string
     */
    public function getPlaceholder() {

        return $this->placeholder;

    }

    /**
     * Set placeholder value
     *
     * @param string $value
     */
    public function setPlaceholder($value) {

        $this->placeholder = $value;

    }

}
