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
 * Shows the setup of a particular instance of mootyper.
 *
 * You can set whether this instance is a lesson or exam,
 * select the exercise category, required precision, as
 * well as which keyboard to show and use.
 *
 * @package    mod_mootyper
 * @copyright  2012 Jaka Luthar (jaka.luthar@gmail.com)
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/locallib.php');

global $USER;

$id = optional_param('id', 0, PARAM_INT); // Course_module ID, or
$n  = optional_param('n', 0, PARAM_INT);  // Mootyper instance ID - it should be named as the first character of the module.

if ($id) {
    $cm         = get_coursemodule_from_id('mootyper', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $mootyper  = $DB->get_record('mootyper', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($n) {
    $mootyper  = $DB->get_record('mootyper', array('id' => $n), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $mootyper->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('mootyper', $mootyper->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}
require_login($course, true, $cm);
$context = context_module::instance($cm->id);

// Get the default config for MooTyper.
$moocfg = get_config('mod_mootyper');
$epo = optional_param('e', 0, PARAM_INT);
$modepo = optional_param('mode', $mootyper->isexam, PARAM_INT);
$exercisepo = optional_param('exercise', $mootyper->exercise, PARAM_INT);
$lessonpo = optional_param('lesson', $mootyper->lesson, PARAM_INT);
$showkeyboardpo = optional_param('showkeyboard', "off", PARAM_CLEAN);
$continuoustypepo = optional_param('continuoustype', "off", PARAM_CLEAN);
$countmistypedspacespo = optional_param('countmistypedspaces', "off", PARAM_CLEAN);

// Check to see if current MooTyper showkeyboard is empty.
if ($mootyper->showkeyboard == null || is_null($mootyper->showkeyboard)) {
    $dfkb = "off";
} else if ($mootyper->showkeyboard) {
    // Otherwise use current MooTyper showkeyboard.
    $dfkb = "on";
} else {
    $dfkb = "off";
}
$showkeyboardpo = optional_param('showkeyboard', $dfkb, PARAM_CLEAN);

// Check to see if current MooTyper continuoustype is empty.
if ($mootyper->continuoustype == null || is_null($mootyper->continuoustype)) {
    $dfct = "off";
} else if ($mootyper->continuoustype) {
    // Otherwise use current MooTyper continuoustype.
    $dfct = "on";
} else {
    $dfct = "off";
}
$continuoustypepo = optional_param('continuoustype', $dfct, PARAM_CLEAN); // Display with default or current setting.


if ($mootyper->countmistypedspaces == null || is_null($mootyper->countmistypedspaces)) {
    // Current MooTyper continuoustype is empty so set it to the site default.
    $dfms = "off";
} else if ($mootyper->countmistypedspaces) {
    // Otherwise use current MooTyper countmistypedspaces.
    $dfms = "on";
} else {
    $dfms = "off";
}
$countmistypedspacespo = optional_param('countmistypedspaces', $dfms, PARAM_CLEAN); // Display with default or current setting.

// Check to see current MooTyper layout is empty.
if ($mootyper->layout == null || is_null($mootyper->layout)) {
    // Current MooTyper layout is empty so set it to the site default.
    $dfly = $moocfg->defaultlayout;
} else {
    // Otherwise use current MooTyper layout.
    $dfly = $mootyper->layout;
}
$layoutpo = optional_param('layout', $dfly, PARAM_INT); // Display with default or current setting.

// Check to see current MooTyper precision goal is empty.
if ($mootyper->requiredgoal == null || is_null($mootyper->requiredgoal)) {
    // Current MooTyper precision goal is empty so set it to the site default.
    $dfgoal = $moocfg->defaultprecision;
} else {
    // Otherwise use current MooTyper precision goal.
    $dfgoal = $mootyper->requiredgoal;
}
$goalpo = optional_param('requiredgoal', $dfgoal, PARAM_INT); // Display with default or current setting.

// Check to see if Confirm button is clicked and returning 'Confirm' to trigger insert record.
$param1 = optional_param('button', '', PARAM_TEXT);
if (isset($param1) && get_string('fconfirm', 'mootyper') == $param1) {
    $modepo = optional_param('mode', null, PARAM_INT);
    $lessonpo = optional_param('lesson', null, PARAM_INT);

    $goalpo = optional_param('requiredgoal', $moocfg->defaultprecision, PARAM_INT);
    if ($goalpo == 0) {
        $goalpo = $moocfg->defaultprecision;
    }
    $layoutpo = optional_param('layout', 0, PARAM_INT);

    $showkeyboardpo = optional_param('showkeyboard', null, PARAM_CLEAN);
    $continuoustypepo = optional_param('continuoustype', null, PARAM_CLEAN);
    $countmistypedspacespo = optional_param('countmistypedspaces', null, PARAM_CLEAN);
    global $DB, $CFG;
    $mootyper  = $DB->get_record('mootyper', array('id' => $n), '*', MUST_EXIST);
    $mootyper->lesson = $lessonpo;
    $mootyper->showkeyboard = $showkeyboardpo == 'on';
    $mootyper->continuoustype = $continuoustypepo == 'on';
    $mootyper->countmistypedspaces = $countmistypedspacespo == 'on';
    $mootyper->layout = $layoutpo;
    $mootyper->isexam = $modepo;
    $mootyper->requiredgoal = $goalpo;
    if ($modepo == 1) {
        $exercisepo = optional_param('exercise', null, PARAM_INT);
        $mootyper->exercise = $exercisepo;
    }
    $DB->update_record('mootyper', $mootyper);
    header('Location: '.$CFG->wwwroot.'/mod/mootyper/view.php?n='.$n);
}

// Print the page header.
$PAGE->set_url('/mod/mootyper/mod_setup.php', array('id' => $cm->id));
$PAGE->set_title(format_string($mootyper->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);
$PAGE->set_cacheable(false);

echo $OUTPUT->header();
echo $OUTPUT->heading($mootyper->name);
$htmlout = '';
$htmlout .= '<script type="text/javascript">
function removeAtts()
{
    document.getElementById("lesson").disabled = false;
    document.getElementById("mode").disabled = false;
    document.getElementById("exercise").disabled = false;
}
</script>';
$htmlout .= '<form id="setupform" onsubmit="removeAtts();" name="setupform" method="POST">';
$disselect = $epo == 1 ? ' disabled="disabled"' : '';
$htmlout .= '<table><tr><td>'.get_string('fmode', 'mootyper').'</td>
                        <td><select'.$disselect.' onchange="this.form.submit()" name="mode" id="mode">';

// 3/22/16 Modified to use only improved function get_mootyperlessons.
if (has_capability('mod/mootyper:aftersetup', context_module::instance($cm->id))) {
    $lessons = get_mootyperlessons($USER->id, $course->id);
}

// Start building htmlout for this page based on exam or lesson exercise.
if ($modepo == 0 || is_null($modepo)) { // Since mode is 0, this is a lesson?
    $htmlout .= '<option selected="true" value="0">'.
            get_string('sflesson', 'mootyper').'</option><option value="1">'.
            get_string('isexamtext', 'mootyper').'</option>';
    $htmlout .= '</select></td></tr><tr><td>';
    $htmlout .= get_string('excategory', 'mootyper').'</td>
                <td><select'.$disselect.' onchange="this.form.submit()" id="lesson" name="lesson">';
    for ($ij = 0; $ij < count($lessons); $ij++) {
        if ($lessons[$ij]['id'] == $lessonpo) {
            $htmlout .= '<option selected="true" value="'.$lessons[$ij]['id'].'">'.$lessons[$ij]['lessonname'].'</option>';
        } else {
            $htmlout .= '<option value="'.$lessons[$ij]['id'].'">'.$lessons[$ij]['lessonname'].'</option>';
        }
    }
    $htmlout .= '</select></td></tr><tr><td>'.get_string('requiredgoal', 'mootyper').'</td>
                 <td><input value="'.$goalpo.'" style="width: 35px;" type="text" name="requiredgoal"> % </td></tr>';
} else if ($modepo == 1) { // Or, since mode is 1, this is an exam?
    $htmlout .= '<option value="0">'.
            get_string('sflesson', 'mootyper').'</option><option value="1" selected="true">'.
            get_string('isexamtext', 'mootyper').'</option>';
    $htmlout .= '</select></td></tr><tr><td>';
    $htmlout .= get_string('flesson', 'mootyper').'</td>
                <td><select'.$disselect.' onchange="this.form.submit()" id="lesson" name="lesson">';
    for ($ij = 0; $ij < count($lessons); $ij++) {
        if ($lessons[$ij]['id'] == $lessonpo) {
            $htmlout .= '<option selected="true" value="'.$lessons[$ij]['id'].'">'.$lessons[$ij]['lessonname'].'</option>';
        } else {
            $htmlout .= '<option value="'.$lessons[$ij]['id'].'">'.$lessons[$ij]['lessonname'].'</option>';
        }
    }
    $htmlout .= '</select></td></tr>';
    $exercises = get_exercises_by_lesson($lessonpo);
    $htmlout .= '<tr><td>'.get_string('fexercise', 'mootyper').'</td><td><select'.$disselect.' name="exercise" id="exercise">';
    for ($ik = 0; $ik < count($exercises); $ik++) {
        if ($exercises[$ik]['id'] == $exercisepo) {
            $htmlout .= '<option selected="true" value="'.$exercises[$ik]['id'].'">'.$exercises[$ik]['exercisename'].'</option>';
        } else {
            $htmlout .= '<option value="'.$exercises[$ik]['id'].'">'.$exercises[$ik]['exercisename'].'</option>';
        }
    }
    $htmlout .= '</select></td></tr>';
}

//xxxxxx

// Need to keep the next line as it is helping get rid of _POST in line 245.
$tempchkkb = optional_param('showkeyboard', 0, PARAM_BOOL);

// Add the check box to enable continuous typing.
$htmlout .= '<tr><td>'.get_string('continuoustype', 'mootyper').'</td><td>';
$continuoustypechecked = $continuoustypepo == 'on' ? ' checked="checked"' : '';
$htmlout .= '<input type="checkbox"'.$continuoustypechecked.' " name="continuoustype">';

// Add the check box to enable counting mistyped spaces.
$htmlout .= '<tr><td>'.get_string('countmistypedspaces', 'mootyper').'</td><td>';
$countmistypedspaceschecked = $countmistypedspacespo == 'on' ? ' checked="checked"' : '';
$htmlout .= '<input type="checkbox"'.$countmistypedspaceschecked.' " name="countmistypedspaces">';

// Add the check box for show keyboard.
$htmlout .= '<tr><td>'.get_string('showkeyboard', 'mootyper').'</td><td>';
$showkeyboardchecked = $showkeyboardpo == 'on' ? ' checked="checked"' : '';
$htmlout .= '<input type="checkbox"'.$showkeyboardchecked.' " name="showkeyboard">';

// Add the dropdown slector for keyboard layouts.
$layouts = get_keyboard_layouts_db();
$deflayout = $moocfg->defaultlayout;
$htmlout .= '<tr><td>'.get_string('layout', 'mootyper').'</td><td><select name="layout">';
// Get the ID and name of each keyboard layout in the DB.
foreach ($layouts as $lkey => $lval) {
    // The first if is executed ONLY when Showkeyboard is
    // clicked to turn it on or off. It seems to have the
    // the job of selecting our default layout when turned ON.
    if (($tempchkkb) && ($lkey == $deflayout)) {
        $htmlout .= '<option value="'.$lkey.'" selected="true">'.$lval.'</option>';
    } else if ($lkey == $layoutpo) {
        // This part of the if is reached when going to setup with a
        // keyboard layout already slected and it is the one already in use.
        $htmlout .= '<option value="'.$lkey.'" selected="true">'.$lval.'</option>';
    } else {
        // This part of the if is reached the most and its when a keyboard layout
        // is already selected but it is not the one being checked.
        $htmlout .= '<option value="'.$lkey.'">'.$lval.'</option>';
    }
}

// Finish adding html to our page.
$htmlout .= '</select>';
$htmlout .= '</td></tr>';
$htmlout .= '</table>';
$htmlout .= '<br><input name="button" onclick="this.form.submit();" value="'.get_string('fconfirm', 'mootyper').'" type="submit">';
$htmlout .= '</form>';

// Finally show the complete page.
echo $htmlout;
// Finish the page by adding a footer.
echo $OUTPUT->footer();
