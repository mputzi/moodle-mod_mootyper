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
 * This file defines the Japanese(V3)keyboard layout.
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
<span id="keyboard" class="keyboardback">Japanese(V3) Keyboard Layout<br><br>
<span id="jkeykanji" class="normal" style=" font-size: 10px !important;"><span class="textup">~ </span>
    <span class="textdown">`</span></span>
<span id="jkey1" class="normal"><span class="textup">! </span><span class="textdown">1</span></span>
<span id="jkey2" class="normal"><span class="textup">" </span><span class="textdown">2</span></span>
<span id="jkey3" class="normal"><span class="textup"># </span><span class="textdown">3</span></span>
<span id="jkey4" class="normal"><span class="textup">$ </span><span class="textdown">4</span></span>
<span id="jkey5" class="normal"><span class="textup">% </span><span class="textdown">5</span></span>
<span id="jkey6" class="normal"><span class="textup">& </span><span class="textdown">6</span></span>
<span id="jkey7" class="normal"><span class="textup">' </span><span class="textdown">7</span></span>
<span id="jkey8" class="normal"><span class="textup">( </span><span class="textdown">8</span></span>
<span id="jkey9" class="normal"><span class="textup">) </span><span class="textdown">9</span></span>
<span id="jkey0" class="normal"><span class="textdown">0</span></span>
<span id="jkeyminus" class="normal"><span class="textup">= </span><span class="textdown">-</span></span>
<span id="jkeycaret" class="normal"><span class="textup">^ </span><span class="textdown">^</span></span>
<span id="jkeyyen" class="normal"><span class="textup">| </span><span class="textdown">&yen;</span></span>
<span id="jkeybackspace" class="normal" style=" font-size: 10px !important;">Back-<br />space</span>
<br>
<div style="float: left;">
    <span id="jkeytab" class="normal" style="width: 54px;">Tab</span>
    <span id="jkeyq" class="normal">Q</span>
    <span id="jkeyw" class="normal">W</span>
    <span id="jkeye" class="normal">E</span>
    <span id="jkeyr" class="normal">R</span>
    <span id="jkeyt" class="normal">T</span>
    <span id="jkeyy" class="normal">Y</span>
    <span id="jkeyu" class="normal">U</span>
    <span id="jkeyi" class="normal">I</span>
    <span id="jkeyo" class="normal">O</span>
    <span id="jkeyp" class="normal">P</span>
    <span id="jkeyat" class="normal">@</span>
    <span id="jkeybracketopen" class="normal" style="border-right-style: solid;">[</span>
    <br>
    <span id="jkeycaps" class="normal" style="width: 60px;">C.lock</span>
    <span id="jkeya" class="finger4">A</span>
    <span id="jkeys" class="finger3">S</span>
    <span id="jkeyd" class="finger2">D</span>
    <span id="jkeyf" class="finger1">F</span>
    <span id="jkeyg" class="normal">G</span>
    <span id="jkeyh" class="normal">H</span>
    <span id="jkeyj" class="finger1">J</span>
    <span id="jkeyk" class="finger2">K</span>
    <span id="jkeyl" class="finger3">L</span>
    <span id="jkeysemicolon" class="finger4">;</span>
    <span id="jkeycolon" class="normal">:</span>
    <span id="jkeybracketclose" class="normal">]</span>
</div>
<span id="jkeyenter" class="normal" style="width: 50px; border-right-style: solid; float: right; height: 102px;">Enter</span>
<br style="clear:both;" /><br />

<span id="jkeyshiftl" class="normal" style="width: 75px;">Shift</span>
<span id="jkeyz" class="normal">Z</span>
<span id="jkeyx" class="normal">X</span>
<span id="jkeyc" class="normal">C</span>
<span id="jkeyv" class="normal">V</span>
<span id="jkeyb" class="normal">B</span>
<span id="jkeyn" class="normal">N</span>
<span id="jkeym" class="normal">M</span>
<span id="jkeycomma" class="normal">,</span>
<span id="jkeyperiod" class="normal">.</span>
<span id="jkeyslash" class="normal">/</span>
<span id="jkeybackslash" class="normal">\</span>
<span id="jkeyshiftr" class="normal" style="width: 75px; border-right-style: solid;">Shift</span>
<br>
<span id="jkeyctrll" class="normal" style="width: 40px;">Ctrl</span>
<span id="jkeywinl" class="normal" style="width: 40px;">Win</span>
<span id="jkeyalt" class="normal" style="width: 40px;">Alt</span>
<span id="jkeynotrans" class="normal" style="width: 40px; font-size: smaller;">無変換</span>
<span id="jkeyspace" class="normal" style="width: 200px;">Space</span>
<span id="jkeytrans" class="normal" style="width: 40px;">変換</span>
<span id="jkeyalt" class="normal" style="width: 40px;">かな</span>
<span id="jkeyaltr" class="normal" style="width: 40px;">Alt</span>
<span id="jkeywinr" class="normal">Win</span>
<span id="jkeyapp" class="normal">App</span>
<span id="jkeyctrlr" class="normal" style="width: 40px; border-right-style: solid;">Ctrl</span><br>
</div>
