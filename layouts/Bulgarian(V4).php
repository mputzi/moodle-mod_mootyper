<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This file defines the Bulgarian(V4) keyboard layout.
 *
 * @package    mod_mootyper
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
 require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
 require_login($course, true, $cm);
?>
<div id="innerKeyboard" style="margin: 0px auto;display: inline-block;
<?php
echo (isset($displaynone) && ($displaynone == true)) ? 'display:none;' : '';
?>
">
<div id="keyboard" class="keyboardback">Bulgarian(V4) Keyboard Layout<br>
    <section>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkey(" class="normal">)<br>(</div>
            <div id="jkey1" class="normal">!<br>1</div>
            <div id="jkey2" class="normal">?<br>2</div>
            <div id="jkey3" class="normal">+<br>3</div>
            <div id="jkey4" class="normal">"<br>4</div>
            <div id="jkey5" class="normal">%<br>5</div>
            <div id="jkey6" class="normal">=<br>6</div>
            <div id="jkey7" class="normal">:<br>7</div>
            <div id="jkey8" class="normal">/<br>8</div>
            <div id="jkey9" class="normal">–<br>9</div>
            <div id="jkey0" class="normal">№<br>0</div>
            <div id="jkeyminus" class="normal">$<br>-</div>
            <div id="jkeyperiod" class="normal">€<br>.</div>
            <div id="jkeybackspace" class="normal" style="width: 95px;">Backspace</div>
        </div>

    <div style="float: left;">
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeytab" class="normal" style="width: 60px;">Tab</div>
            <div id="jkeycomma" class="normal">ы<br>,</div>
            <div id="jkeyу" class="normal">У</div>
            <div id="jkeyе" class="normal">Е</div>
            <div id="jkeyи" class="normal">И</div>
            <div id="jkeyш" class="normal">Ш</div>
            <div id="jkeyщ" class="normal">Щ</div>
            <div id="jkeyк" class="normal">К</div>
            <div id="jkeyс" class="normal">С</div>
            <div id="jkeyд" class="normal">Д</div>
            <div id="jkeyз" class="normal">З</div>
            <div id="jkeyц" class="normal">Ц</div>
            <div id="jkey;" class="normal">§<br>;</div>
            <div id="jkey„" class="normal" style="width: 75px;">“<br>„</div>
        </div>

        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeycaps" class="normal" style="width: 80px;">C.Lock</div>
            <div id="jkeyь" class="finger4">ѝ<br>ь</div>
            <div id="jkeyя" class="finger3">Я</div>
            <div id="jkeyа" class="finger2">А</div>
            <div id="jkeyо" class="finger1">О</div>
            <div id="jkeyж" class="normal">Ж</div>
            <div id="jkeyг" class="normal">Г</div>
            <div id="jkeyт" class="finger1">Т</div>
            <div id="jkeyн" class="finger2">Н</div>
            <div id="jkeyв" class="finger3">В</div>
            <div id="jkeyм" class="finger4">М</div>
            <div id="jkeyч" class="normal">Ч</div>
        <div id="jkeyenter" class="normal" style="width: 95px;">Enter</div>
    </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeyshiftl" class="normal" style="width: 100px;">Shift</div>
            <div id="jkeyю" class="normal">Ю</div>
            <div id="jkeyй" class="normal">Й</div>
            <div id="jkeyъ" class="normal">Ъ</div>
            <div id="jkeyэ" class="normal">Э</div>
            <div id="jkeyф" class="normal">Ф</div>
            <div id="jkeyх" class="normal">Х</div>
            <div id="jkeyп" class="normal">П</div>
            <div id="jkeyр" class="normal">Р</div>
            <div id="jkeyл" class="normal">Л</div>
            <div id="jkeyб" class="normal">Б</div>
            <div id="jkeyshiftd" class="normal" style="width: 115px;">Shift</div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px;'>
            <div id="jkeyctrll" class="normal" style="width: 50px;">Ctrl</div>
            <div id="jempty" class="normal" style="width: 50px;">Win</div>
            <div id="jkeyalt" class="normal" style="width: 50px;">Alt</div>
            <div id="jkeyspace" class="normal" style="width: 265px;">Space</div>
            <div id="jkeyaltgr" class="normal" style="width: 50px;">Alt gr</div>
            <div id="jempty" class="normal" style="width: 50px;">Win</div>
            <div id="jempty" class="normal" style="width: 50px;">Menu</div>
            <div id="jkeyctrlr" class="normal" style="width: 50px; border-right-style: solid;">Ctrl</div>
        <div>
    </section>
</div>
</div>
