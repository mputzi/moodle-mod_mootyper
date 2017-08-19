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
 * This file defines the Spanish(V2) keyboard layout.
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
<span id="keyboard" class="keyboardback">Spanish(V2) Keyboard Layout<br><br>
<span id="jkeytildo" class="normal"><span class="textup">ª </span><span class="textdown"> º</span></span>
<span id="jkey1" class="normal"><span class="textup">! </span>1</span>
<span id="jkey2" class="normal"><span class="textup">" </span>2</span>
<span id="jkey3" class="normal"><span class="textup">. </span>3</span>
<span id="jkey4" class="normal"><span class="textup">$ </span>4</span>
<span id="jkey5" class="normal"><span class="textup">% </span>5</span>
<span id="jkey6" class="normal"><span class="textup">& </span>6</span>
<span id="jkey7" class="normal"><span class="textup">/ </span>7</span>
<span id="jkey8" class="normal"><span class="textup">( </span>8</span>
<span id="jkey9" class="normal"><span class="textup">) </span>9</span>
<span id="jkey0" class="normal"><span class="textup">= </span>0</span>
<span id="jkeycrtica" class="normal"><span class="textup">? </span><span class="textdown"> '</span></span>
<span id="jkey¡" class="normal"><span class="textup">¿ </span>¡</span>
<span id="jkeybackspace" class="normal" style="border-right-style: solid;">Retroceso</span><br>
<span id="jkeytab" class="normal" style="width: 50px;">Tab</span>
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
<span id="jkeylefttick" class="normal"><span class="textup">^ </span><span class="textdown"> `</span></span>
<span id="jkeyplus" class="normal"><span class="textup">* </span><span class="textdown"> +</span></span>
<br>
<span id="jkeycaps" class="normal" style="width: 60px;">Bloq.mayús</span>
<span id="jkeya" class="finger4">A</span>
<span id="jkeys" class="finger3">S</span>
<span id="jkeyd" class="finger2">D</span>
<span id="jkeyf" class="finger1">F</span>
<span id="jkeyg" class="normal">G</span>
<span id="jkeyh" class="normal">H</span>
<span id="jkeyj" class="finger1">J</span>
<span id="jkeyk" class="finger2">K</span>
<span id="jkeyl" class="finger3">L</span>
<span id="jkeyñ" class="finger4">Ñ</span>
<span id="jkeyrighttick" class="normal"><span class="textup">¨ </span><span class="textdown"> ´</span></span>
<span id="jkeyç" class="normal">Ç</span>
<span id="jkeyenter" class="normal" style="border-right-style: solid;">Enter</span>
<br>
<span id="jkeyshiftl" class="normal" style="width: 75px;">Mayús</span>
<span id="jkeyckck" class="normal"><span class="textup">&lt;</span><span class="textdown"> &gt;</span></span>
<span id="jkeyz" class="normal">Z</span>
<span id="jkeyx" class="normal">X</span>
<span id="jkeyc" class="normal">C</span>
<span id="jkeyv" class="normal">V</span>
<span id="jkeyb" class="normal">B</span>
<span id="jkeyn" class="normal">N</span>
<span id="jkeym" class="normal">M</span>
<span id="jkeyvejica" class="normal"><span class="textup">;</span><span class="textdown">,</span></span>
<span id="jkeypika" class="normal"><span class="textup">:</span><span class="textdown">.</span></span>
<span id="jkeypomislaj" class="normal"><span class="textup">_</span><span class="textdown"> -</span></span>
<span id="jkeyshiftd" class="normal" style="width: 75px; border-right-style: solid;">Mayús</span>
<br>
<span id="jkeyctrll" class="normal" style="width: 40px;">Ctrl</span>
<span id="jkeyfn" class="normal" style="width: 50px;">Fn</span>
<span id="jkeyalt" class="normal" style="width: 40px;">Alt</span>
<span id="jkeyspace" class="normal" style="width: 285px;">Espacio</span>
<span id="jkeyaltgr" class="normal" style="width: 50px;">Alt gr</span>
<span id="jkeyfn" class="normal" style="width: 50px;">Fn</span>
<span id="jempty" class="normal" style="width: 30px;">Menu</span>
<span id="jkeyctrlr" class="normal" style="width: 60px;">Ctrl</span><br>
</span>
</div>
