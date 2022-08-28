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
 * Create Line with two textboxes and an add/remove button
 */
class FormTextDouble extends \XoopsFormText
{

    /**
     * placeholder first textbox
     *
     * @var array
     */
    private $elements;

    /**
     * placeholder first textbox
     *
     * @var string
     */
    private $placeholder1;

    /**
     * placeholder second textbox
     *
     * @var string
     */
    private $placeholder2;

    /**
     * value first textbox
     *
     * @var string
     */
    private $value1;

    /**
     * value second textbox
     *
     * @var string
     */
    private $value2;

    /**
     * create HTML to output the group of text fields
     *
     * @return string
     */
    public function render()
    {

        $ret =  "<div id='wrapper_fee'style='width:100%'>";

        foreach($this->getElements() as $key => $ele) {
            $ret .=  "<span><input class='form-control " . $this->getClass() . "' type='text' name='"
                . $this->getName() . "[]' title='" . $this->getTitle() . "' style='width:15%' maxlength='20' 
            value='" . $ele[0] . "'" . $this->getExtra();
            $ret .= ' />';
            $ret .=  "&nbsp;<input class='form-control " . $this->getClass() . "' type='text' name='"
                . $this->getName() . "desc[]' title='" . $this->getTitle() . "' style='width:70%' maxlength='255' 
            value='" . $ele[1] . "'" . $this->getExtra();
            if ($ele['placeholder']) {
                $ret .= ' placeholder="' . $ele['placeholder'] . '"';
            }
            $ret .= ' />';

            if (0 === (int)$key) {
                // button to add new group
                $ret .= '<a class="btn_fee_add btn btn-xs" href="javascript:void(0);" ><img src="' . \WGEVENTS_ICONS_URL_32 . '/add.png"/></a>';
            } else {
                $ret .= '<a class="btn_fee_remove btn btn-xs" href="javascript:void(0);"><img src="' . \WGEVENTS_ICONS_URL_32 . '/remove.png"/></a>';
            }

            $ret .= '</span>';
        }
        $ret .= '</div>';

        // add one hidden group as template for new lines
        $ret .=  "<div id='hidden_group' class='hidden'><span><input class='form-control " . $this->getClass() . "' type='text' name='"
            . $this->getName() . "[]' title='" . $this->getTitle() . "' style='width:15%' maxlength='20' 
        value=''" . $this->getExtra();
        if ($this->placeholder1) {
            $ret .= ' placeholder="' . $this->getPlaceholder1() . '"';
        }
        $ret .= ' />';
        $ret .=  "&nbsp;<input class='form-control " . $this->getClass() . "' type='text' name='"
            . $this->getName() . "desc[]' title='" . $this->getTitle() . "' style='width:70%' maxlength='255' 
        value=''" . $this->getExtra();
        if ($this->placeholder2) {
            $ret .= ' placeholder="' . $this->getPlaceholder2() . '"';
        }
        $ret .= ' />';
        $ret .= '<a class="btn_fee_remove btn btn-xs" href="javascript:void(0);"><img src="' . \WGEVENTS_ICONS_URL_32 . '/remove.png"/></a>';
        $ret .= '</span></div>';
        // end hidden group

        return $ret;

    }

    /**
     * Get first placeholder value
     *
     * @return string
     */
    public function getElements() {

        return $this->elements;

    }

    /**
     * Set first placeholder value
     *
     * @param array $value
     */
    public function setElements($value) {

        $this->elements = $value;

    }

    /**
     * Get first placeholder value
     *
     * @return string
     */
    public function getPlaceholder1() {

        return $this->placeholder1;

    }

    /**
     * Set first placeholder value
     *
     * @param string $value
     */
    public function setPlaceholder1($value) {

        $this->placeholder1 = $value;

    }

    /**
     * Get second placeholder value
     *
     * @return string
     */
    public function getPlaceholder2() {

        return $this->placeholder2;

    }

    /**
     * Set second placeholder value
     *
     * @param string $value
     */
    public function setPlaceholder2($value) {

        $this->placeholder2 = $value;

    }

}
