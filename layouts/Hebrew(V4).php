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
 * This file defines the English(USV4)keyboard layout.
 *
 * @package    mod_mootyper
 * @copyright  2012 Jaka Luthar (jaka.luthar@gmail.com)
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
<div id="keyboard" class="keyboardback">עברית (V4) פריסת מקלדת<br>
    <section>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeysemicolon" class="normal" style='text-align:left;'>~<br>;</div>
            <div id="jkey1" class="normal" style='text-align:left;'>!<br>1</div>
            <div id="jkey2" class="normal" style='text-align:left;'>@<br>2</div>
            <div id="jkey3" class="normal" style='text-align:left;'>#<br>3<span style="color:blue">&nbsp;&nbsp;&nbsp;€</span></div>
            <div id="jkey4" class="normal" style='text-align:left;'>$<br>4<span style="color:blue">&nbsp;&nbsp;&nbsp;₪</span></div>
            <div id="jkey5" class="normal" style='text-align:left;'>%<br>5</div>
            <div id="jkey6" class="normal" style='text-align:left;'>^<br>6</div>
            <div id="jkey7" class="normal" style='text-align:left;'>&<br>7</div>
            <div id="jkey8" class="normal" style='text-align:left;'>*<br>8</div>
            <div id="jkey9" class="normal" style='text-align:left;'>)<br>9</div>
            <div id="jkey0" class="normal" style='text-align:left;'>(<br>0</div>
            <div id="jkeyminus" class="normal" style='text-align:left;'>_<br>-<span style="color:blue">&nbsp;&nbsp;&nbsp;־</span></div>
            <div id="jkeyequals" class="normal" style='text-align:left;'>+<br>=</div>
            <div id="jkeybackslash" class="normal" style='text-align:left;'>|<br>\</div>
            <div id="jkeybackspace" class="normal" style="width: 54px;">←</div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeytab" class="normal" style="width: 60px;">Tab</div>
            <div id="jkeyQ" class="normal" style='text-align:left;'>Q<br>/</div>
            <div id="jkeyW" class="normal" style='text-align:left;'>W<br>'</div>
            <div id="jkeyE" class="normal" style='text-align:left;'>E<br>ק</div>
            <div id="jkeyR" class="normal" style='text-align:left;'>R<br>ר</div>
            <div id="jkeyT" class="normal" style='text-align:left;'>T<br>א</div>
            <div id="jkeyY" class="normal" style='text-align:left;'>Y<br><span style="color:blue">װ</span>&nbsp;&nbsp;&nbsp;ט</div>
            <div id="jkeyU" class="normal" style='text-align:left;'>U<br>ו</div>
            <div id="jkeyI" class="normal" style='text-align:left;'>I<br>ן</div>
            <div id="jkeyO" class="normal" style='text-align:left;'>O<br>ם</div>
            <div id="jkeyP" class="normal" style='text-align:left;'>P<br>פ</div>
            <div id="jkeybracketr" class="normal" style='text-align:left;' style='font-size: 15px !important; line-height: 15px'>}<br>]</div>
            <div id="jkeybracketl" class="normal" style='text-align:left;' style='font-size: 15px !important; line-height: 15px'>{<br>[</div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeycaps" class="normal" style="width: 80px;  font-size: 12px !important;">Caps Lock</div>
            <div id="jkeyA" class="finger4" style='text-align:left;'>A<br>ש</div>
            <div id="jkeyS" class="finger3" style='text-align:left;'>S<br>ד</div>
            <div id="jkeyD" class="finger2" style='text-align:left;'>D<br>ג</div>
            <div id="jkeyF" class="finger1" style='text-align:left;'>F<br>כ</div>
            <div id="jkeyG" class="normal" style='text-align:left;'>G<br><span style="color:blue">ױ</span>&nbsp;&nbsp;&nbsp;ע</div>
            <div id="jkeyH" class="normal" style='text-align:left;'>H<br><span style="color:blue">ײ</span>&nbsp;&nbsp;&nbsp;י</div>
            <div id="jkeyJ" class="finger1" style='text-align:left;'>J<br>ח</div>
            <div id="jkeyK" class="finger2" style='text-align:left;'>K<br>ל</div>
            <div id="jkeyL" class="finger3" style='text-align:left;'>L<br>ך</div>
            <div id="jkeycolon" class="finger4" style='text-align:left;'>:<br>ף</div>
            <div id="jkeycomma" class="normal" style='text-align:left;'>"<br>,</div>
            <div id="jkeyenter" class="normal" style="width: 95px;">Enter</div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeyshiftl" class="normal" style="width: 100px;">Shift</div>
            <div id="jkeyZ" class="normal" style='text-align:left;'>Z<br>ז</div>
            <div id="jkeyX" class="normal" style='text-align:left;'>X<br>ס</div>
            <div id="jkeyC" class="normal" style='text-align:left;'>C<br>ב</div>
            <div id="jkeyV" class="normal" style='text-align:left;'>V<br>ה</div>
            <div id="jkeyB" class="normal" style='text-align:left;'>B<br>נ</div>
            <div id="jkeyN" class="normal" style='text-align:left;'>N<br>מ</div>
            <div id="jkeyM" class="normal" style='text-align:left;'>M<br>צ</div>
            <div id="jkeyת" class="normal" style='text-align:left;'><b>&gt;<br>ת</b></div>
            <div id="jkeyץ" class="normal" style='text-align:left;'><b>&lt;<br>ץ</b></div>
            <div id="jkeyperiod" class="normal" style='text-align:left;'>?<br>.</div>
            <div id="jkeyshiftd" class="normal" style="width: 115px;">Shift</div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px;'>
            <div id="jkeyctrll" class="normal" style="width: 60px;">Ctrl</div>
            <div id="jkeyfn" class="normal" style="width: 50px;">Fn</div>
            <div id="jkeyalt" class="normal" style="width: 50px;">Alt</div>
            <div id="jkeyspace" class="normal" style="width: 295px;">Space</div>
            <div id="jkeyaltgr" class="normal" style="width: 50px;">Alt</div>
            <div id="jkeyfn" class="normal" style="width: 50px;">Fn</div>
            <div id="jkeyctrlr" class="normal" style="width: 60px;">Ctrl</div>
        </div>
</section>
</div>
</div>
