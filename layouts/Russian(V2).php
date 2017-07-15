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
 * This file defines the Russian(V2) keyboard layout.
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
<span id="keyboard" class="keyboardback">Russian(V2) Keyboard Layout<br><br>
<span id="jkey?" class="normal">?</span>
<span id="jkey1" class="normal"><span class="textup">! </span> 1</span>
<span id="jkey2" class="normal"><span class="textup">" </span> 2</span>
<span id="jkey3" class="normal"><span class="textup">?</span> 3</span>
<span id="jkey4" class="normal"><span class="textup">; </span> 4</span>
<span id="jkey5" class="normal"><span class="textup">% </span> 5</span>
<span id="jkey6" class="normal"><span class="textup">: </span> 6</span>
<span id="jkey7" class="normal"><span class="textup">? </span> 7</span>
<span id="jkey8" class="normal"><span class="textup">* </span> 8</span>
<span id="jkey9" class="normal"><span class="textup">( </span> 9</span>
<span id="jkey0" class="normal"><span class="textup">) </span> 0</span>
<span id="jkeypomislaj" class="normal"><span class="textup">_ </span><span class="textdown"> -</span></span>
<span id="jkeyequals" class="normal"><span class="textup">+ </span><span class="textdown"> =</span></span>
<span id="jkeybackspace" class="normal" style="border-right-style: solid;">Backspace</span>
<br>
<span id="jkeytab" class="normal" style="width: 50px;">Tab</span>
<span id="jkey?" class="normal">?</span>
<span id="jkey?" class="normal">?</span>
<span id="jkey?" class="normal">?</span>
<span id="jkey?" class="normal">?</span>
<span id="jkey?" class="normal">?</span>
<span id="jkey?" class="normal">?</span>
<span id="jkey?" class="normal">?</span>
<span id="jkey?" class="normal">?</span>
<span id="jkey?" class="normal">?</span>
<span id="jkey?" class="normal">?</span>
<span id="jkey?" class="normal">?</span>
<span id="jkey?" class="normal" style="border-right-style: solid;">?</span>
<br>
<span id="jkeycaps" class="normal" style="width: 60px;">CapsLock</span>
<span id="jkey?" class="finger4">?</span>
<span id="jkey?" class="finger3">?</span>
<span id="jkey?" class="finger2">?</span>
<span id="jkey?" class="finger1">?</span>
<span id="jkey?" class="normal">?</span>
<span id="jkey?" class="normal">?</span>
<span id="jkey?" class="finger1">?</span>
<span id="jkey?" class="finger2">?</span>
<span id="jkey?" class="finger3">?</span>
<span id="jkey?" class="finger4">?</span>
<span id="jkey?" class="normal">?</span>
<span id="jkeyslash" class="normal"><span class="textup">/</span><span class="textdown"> \</span></span>
<span id="jkeyenter" class="normal" style="border-right-style: solid; width: 40px;">Enter</span>
<br>
<span id="jkeyshiftl" class="normal" style="width: 40px;">Shift</span>
<span id="jkeyslash" class="normal"><span class="textup">|</span><span class="textdown"> \</span></span>
<span id="jkey?" class="normal">?</span>
<span id="jkey?" class="normal">?</span>
<span id="jkey?" class="normal">?</span>
<span id="jkey?" class="normal">?</span>
<span id="jkey?" class="normal">?</span>
<span id="jkey?" class="normal">?</span>
<span id="jkey?" class="normal">?</span>
<span id="jkey?" class="normal">?</span>
<span id="jkey?" class="normal">?</span>
<span id="jkeypika" class="normal"><span class="textup">, </span><span class="textdown"> .</span></span>
<span id="jkeyshiftd" class="normal" style="width: 95px; border-right-style: solid;">Shift</span>
<br>
<span id="jkeyctrll" class="normal" style="width: 45px;">Ctrl</span>
<span id="jempty" class="normal" style="width: 40px;">Meta</span>
<span id="jkeyalt" class="normal" style="width: 40px;">Alt</span>
<span id="jkeyspace" class="normal" style="width: 210px;">??????</span>
<span id="jkeyaltgr" class="normal" style="width: 40px;">Alt gr</span>
<span id="jempty" class="normal" style="width: 40px;">Meta</span>
<span id="jempty" class="normal" style="width: 40px;">Menu</span>
<span id="jkeyctrlr" class="normal" style="width: 45px; border-right-style: solid;">Ctrl</span><br>
</span>
</div>
