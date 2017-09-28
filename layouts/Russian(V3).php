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
 * This file defines the Russian(V3) keyboard layout.
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
"><br>
<span id="keyboard" class="keyboardback">Russian(V3) Keyboard Layout<br><br>
<span id="jkeyё" class="normal">Ё</span>
<span id="jkey1" class="normal"><span class="textup">! </span> 1</span>
<span id="jkey2" class="normal"><span class="textup">" </span> 2</span>
<span id="jkey3" class="normal"><span class="textup">№</span> 3</span>
<span id="jkey4" class="normal"><span class="textup">; </span> 4</span>
<span id="jkey5" class="normal"><span class="textup">% </span> 5</span>
<span id="jkey6" class="normal"><span class="textup">: </span> 6</span>
<span id="jkey7" class="normal"><span class="textup">? </span> 7</span>
<span id="jkey8" class="normal"><span class="textup">* </span> 8</span>
<span id="jkey9" class="normal"><span class="textup">( </span> 9</span>
<span id="jkey0" class="normal"><span class="textup">) </span> 0</span>
<span id="jkeyminus" class="normal"><span class="textup">_ </span><span class="textdown"> -</span></span>
<span id="jkeyequals" class="normal"><span class="textup">+ </span><span class="textdown"> =</span></span>
<span id="jkeybackspace" class="normal" style="border-right-style: solid;">Backspace</span>
<br>
<div style="float: left;">
<span id="jkeytab" class="normal" style="width: 50px;">Tab</span>
<span id="jkeyй" class="normal">Й</span>
<span id="jkeyц" class="normal">Ц</span>
<span id="jkeyу" class="normal">У</span>
<span id="jkeyк" class="normal">К</span>
<span id="jkeyе" class="normal">Е</span>
<span id="jkeyн" class="normal">Н</span>
<span id="jkeyг" class="normal">Г</span>
<span id="jkeyш" class="normal">Ш</span>
<span id="jkeyщ" class="normal">Щ</span>
<span id="jkeyз" class="normal">З</span>
<span id="jkeyх" class="normal">Х</span>
<span id="jkeyъ" class="normal" style="border-right-style: solid;">Ъ</span>
<br>
<span id="jkeycaps" class="normal" style="width: 60px;">C.Lock</span>
<span id="jkeyф" class="finger4">Ф</span>
<span id="jkeyы" class="finger3">Ы</span>
<span id="jkeyв" class="finger2">В</span>
<span id="jkeyа" class="finger1">А</span>
<span id="jkeyп" class="normal">П</span>
<span id="jkeyр" class="normal">Р</span>
<span id="jkeyо" class="finger1">О</span>
<span id="jkeyл" class="finger2">Л</span>
<span id="jkeyд" class="finger3">Д</span>
<span id="jkeyж" class="finger4">Ж</span>
<span id="jkeyэ" class="normal">Э</span>
<span id="jkeybackslash" class="normal"><span class="textup">/</span><span class="textdown"> \</span></span>
</div>
<span id="jkeyenter" class="normal" style="width: 50px; border-right-style: solid; float: right; height: 105px;">Enter</span>
<br style="clear:both;" /><br />
<span id="jkeyshiftl" class="normal" style="width: 45px;">Shift</span>
<span id="jkeyslash" class="normal"><span class="textup">|</span><span class="textdown"> \</span></span>
<span id="jkeyя" class="normal">Я</span>
<span id="jkeyч" class="normal">Ч</span>
<span id="jkeyс" class="normal">С</span>
<span id="jkeyм" class="normal">М</span>
<span id="jkeyи" class="normal">И</span>
<span id="jkeyт" class="normal">Т</span>
<span id="jkeyь" class="normal">Ь</span>
<span id="jkeyб" class="normal">Б</span>
<span id="jkeyю" class="normal">Ю</span>
<span id="jkeyperiod" class="normal"><span class="textup">, </span><span class="textdown"> .</span></span>
<span id="jkeyshiftd" class="normal" style="width: 105px; border-right-style: solid;">Shift</span>
<br>
<span id="jkeyctrll" class="normal" style="width: 45px;">Ctrl</span>
<span id="jempty" class="normal" style="width: 40px;">Meta</span>
<span id="jkeyalt" class="normal" style="width: 40px;">Alt</span>
<span id="jkeyspace" class="normal" style="width: 288px;">Пробел</span>
<span id="jkeyaltgr" class="normal" style="width: 50px;">Alt gr</span>
<span id="jempty" class="normal" style="width: 40px;">Meta</span>
<span id="jempty" class="normal" style="width: 40px;">Menu</span>
<span id="jkeyctrlr" class="normal" style="width: 45px; border-right-style: solid;">Ctrl</span><br>
</span>
</div>
