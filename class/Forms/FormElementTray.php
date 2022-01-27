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
\xoops_load('\XoopsFormElementTray');

/**
 * Create hidden form button
 */
class FormElementTray extends \XoopsFormElementTray
{

    /**
     * create HTML to output the hidden button
     *
     * @return string
     */
    public function render()
    {
        $count = 0;
        $ret = '<span id="' . $this->getName() . '"';
        $ret .= ' style="';
        if ($this->_hidden) {
            $ret .= 'display:none;';
        }
        $ret .= 'width:100%;">';
        $inline = ('<br>' == $this->getDelimeter());
        foreach ($this->getElements() as $ele) {
            if ($count > 0 && !$inline) {
                $ret .= $this->getDelimeter();
            }
            if ($inline) {
                $ret .= '<div class="form-inline">';
            }
            if ($ele->getCaption() != '') {
                $ret .= '<label for="' . $ele->getName() . '" class="' . $this->getClass() . ' col-form-label wge-label-left">'
                    . $ele->getCaption()
                    . ($ele->isRequired() ? '<span class="caption-required">*</span>' : '')
                    . '</label>&nbsp;';
            }
            $ret .= $ele->render()  . NWLINE;
            if ($inline) {
                $ret .= '</div>';
            }
            if (!$ele->isHidden()) {
                ++$count;
            }
        }
        $ret .= '</span>';

        return $ret;

    }
}
