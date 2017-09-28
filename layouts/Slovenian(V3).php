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
 * This file defines the Slovenian(V3) keyboard layout.
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
<span id="keyboard" class="keyboardback">Slovenian(V3) Keyboard Layout<br><br>
<span id="jkeytildo" class="normal"><span class="textup">¨ </span> ¸</span>
<span id="jkey1" class="normal"><span class="textup">! </span><span class="textdown"> 1</span>
    <span class="textdown" style="color:red"> ~</span></span>
<span id="jkey2" class="normal"><span class="textup">" </span><span class="textdown">2</span>
    <span class="textdown" style="color:red"> ˇ</span></span>
<span id="jkey3" class="normal"><span class="textup"># </span><span class="textdown">3</span>
    <span class="textdown" style="color:red"> ^</span></span>
<span id="jkey4" class="normal"><span class="textup">$ </span><span class="textdown">4</span>
    <span class="textdown" style="color:red"> ˘</span></span>
<span id="jkey5" class="normal"><span class="textup">% </span><span class="textdown">5</span>
    <span class="textdown" style="color:red"> °</span></span>
<span id="jkey6" class="normal"><span class="textup">& </span><span class="textdown">6</span>
    <span style="color:red"> ˛</span></span>
<span id="jkey7" class="normal"><span class="textup">/ </span><span class="textdown">7</span>
    <span class="textdown" style="color:red"> `</span></span>
<span id="jkey8" class="normal"><span class="textup">( </span><span class="textdown">8</span>
    <span class="textdown" style="color:red"> ˙</span></span>
<span id="jkey9" class="normal"><span class="textup">) </span><span class="textdown">9</span>
    <span class="textdown" style="color:red"> ´</span></span>
<span id="jkey0" class="normal"><span class="textup">= </span><span class="textdown">0</span>
    <span class="textdown" style="color:red"> ˝</span></span>
<span id="jkeyapostrophe" class="normal"><span class="textup">? </span><span class="textdown">'</span>
    <span class="textdown" style="color:red"> ¨</span></span>
<span id="jkeyplus" class="normal"><span class="textup">* </span><span class="textdown">+</span>
    <span style="color:red"> ¸</span></span>
<span id="jkeybackspace" class="normal" style="border-right-style: solid;">Backspace</span>
<br>
<div style="float: left;">
<span id="jkeytab" class="normal" style="width: 50px;">Tab</span>
<span id="jkeyq" class="normal">Q <sub style="color:blue">\</sub></span>
<span id="jkeyw" class="normal">W <sub style="color:blue">|</sub></span>
<span id="jkeye" class="normal">E <sub style="color:blue">€</sub></span>
<span id="jkeyr" class="normal">R</span>
<span id="jkeyt" class="normal">T</span>
<span id="jkeyz" class="normal">Z</span>
<span id="jkeyu" class="normal">U</span>
<span id="jkeyi" class="normal">I</span>
<span id="jkeyo" class="normal">O</span>
<span id="jkeyp" class="normal">P</span>
<span id="jkeyš" class="normal">Š <sub style="color:blue">÷</sub></span>
<span id="jkeyđ" class="normal">Đ <sub style="color:blue">×</sub></span>
<br>
<span id="jkeycaps" class="normal" style="width: 60px;">C.lock</span>
<span id="jkeya" class="finger4">A</span>
<span id="jkeys" class="finger3">S</span>
<span id="jkeyd" class="finger2">D</span>
<span id="jkeyf" class="finger1">F <sub style="color:blue">[</sub></span>
<span id="jkeyg" class="normal">G <sub style="color:blue">]</sub></span>
<span id="jkeyh" class="normal">H</span>
<span id="jkeyj" class="finger1">J</span>
<span id="jkeyk" class="finger2">K <sub style="color:blue">ł</sub></span>
<span id="jkeyl" class="finger3">L <sub style="color:blue">Ł</sub></span>
<span id="jkeyč" class="finger4">Č</span>
<span id="jkeyć" class="normal">Ć <sub style="color:blue">ß</sub></span>
<span id="jkeyž" class="normal">Ž <sub style="color:blue">¤</sub></span>
</div>
<span id="jkeyenter" class="normal" style="width: 50px; border-right-style: solid; float: right; height: 105px;">Enter</span>
<br style="clear:both;" /><br />
<span id="jkeyshiftl" class="normal" style="width: 50px;">Shift</span>
<span id="jkeyckck" class="normal"><span class="textup">&lt; </span><span class="textdown">&gt;</span></span>
<span id="jkeyy" class="normal">Y</span>
<span id="jkeyx" class="normal">X</span>
<span id="jkeyc" class="normal">C</span>
<span id="jkeyv" class="normal">V <sub style="color:blue">@</sub></span>
<span id="jkeyb" class="normal">B <sub style="color:blue">{</sub></span>
<span id="jkeyn" class="normal">N <sub style="color:blue">}</sub></span>
<span id="jkeym" class="normal">M <sub style="color:blue">§</sub></span>
<span id="jkeycomma" class="normal"><span class="textup">; </span><span class="textdown"> ,</span></span>
<span id="jkeyperiod" class="normal"><span class="textup">: </span><span class="textdown"> .</span></span>
<span id="jkeyminus" class="normal"><span class="textup">_ </span><span class="textdown"> -</span></span>
<span id="jkeyshiftd" class="normal" style="width: 110px; border-right-style: solid;">Shift</span>
<br>
<span id="jkeyctrll" class="normal" style="width: 55px;">Ctrl</span>
<span id="jkeyfn" class="normal" style="width: 40px;">Win</span>
<span id="jkeyalt" class="normal" style="width: 40px;">Alt</span>
<span id="jkeyspace" class="normal" style="width: 285px;">Preslednica</span>
<span id="jkeyaltgr" class="normal" style="width: 50px;">Alt gr</span>
<span id="jkeyfn" class="normal" style="width: 40px;">Win</span>
<span id="jempty" class="normal" style="width: 45px;">Menu</span>
<span id="jkeyctrlr" class="normal" style="width: 55px; border-right-style: solid;">Ctrl</span><br>
</span>
</div>
