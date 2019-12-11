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
 * This file handles mootyper exercises.
 *
 *
 * @package    mod_mootyper
 * @copyright  2012 Jaka Luthar (jaka.luthar@gmail.com)
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

use \mod_mootyper\event\course_exercises_viewed;

// Changed to this newer format 03/01/2019.
require(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/lib.php');
require_once(__DIR__ . '/locallib.php');

global $USER;

$id = optional_param('id', 0, PARAM_INT); // Course_module ID.

if ($id) {
    $course = $DB->get_record('course', array('id' => $id), '*', MUST_EXIST);
} else {
    print_error(get_string('mootypererror', 'mootyper'));
}

require_login($course, true);
$context = context_course::instance($id);

$lessonpo = optional_param('lesson', 0, PARAM_INT);

// Trigger module exercise_viewed event.
$params = array(
    'objectid' => $course->id,
    'context' => $context,
    'other' => $lessonpo
);
$event = course_exercises_viewed::create($params);
$event->trigger();

// Print the page header.
$PAGE->set_url('/mod/mootyper/exercises.php', array('id' => $course->id));
$PAGE->set_title(get_string('etitle', 'mootyper'));
$PAGE->set_heading(get_string('eheading', 'mootyper'));

// Other things you may want to set - remove if not needed.
$PAGE->set_cacheable(false);

// Output starts here.
echo $OUTPUT->header();

// Since editing an exercise is a course activity, the keyboard
// background color info for the MooTyper this was called from,
// is not available. So, need to get the default keyboard background
// color from from the MooTyper configuration setting.
$moocfg = get_config('mod_mootyper');
$color3 = $moocfg->keyboardbgc;

echo '<div align="center" style="font-size:1em;
     font-weight:bold;background: '.$color3.';
     border:2px solid black;
     -webkit-border-radius:16px;
     -moz-border-radius:16px;border-radius:16px;">';

// Create link to add new exercise or category at top of page.
$jlnk2 = $CFG->wwwroot . '/mod/mootyper/eins.php?id='.$id;
echo '<a href="'.$jlnk2.'">'.get_string('eaddnew', 'mootyper').'</a><br><br>';

$lessons = get_mootyperlessons($USER->id, $id);

if ($lessonpo == 0 && count($lessons) > 0) {
    $lessonpo = $lessons[0]['id'];
}

echo '<form method="post">';
echo get_string('excategory', 'mootyper').': <select onchange="this.form.submit()" name="lesson">';

$selectedlessonindex = 0;

for ($ij = 0; $ij < count($lessons); $ij++) {
    if ($lessons[$ij]['id'] == $lessonpo) {
        echo '<option selected="true" value="'.$lessons[$ij]['id'].'">'.$lessons[$ij]['lessonname'].'</option>';
        $selectedlessonindex = $ij;
    } else {
        echo '<option value="'.$lessons[$ij]['id'].'">'.$lessons[$ij]['lessonname'].'</option>';
    }
}

echo '</select>';
// Preload not editable by me message for current user.
$jlink = get_string('noteditablebyme', 'mootyper');
if (is_editable_by_me($USER->id, $id, $lessonpo)) {
    // Add a Delete all from lesson link.
    echo '<br>';
    echo ' <a onclick="return confirm(\''.get_string('deletelsnconfirm', 'mootyper').$lessons[$selectedlessonindex]['lessonname'].
    '\')" href="erem.php?id='.$course->id.'&l='.$lessons[$selectedlessonindex]['id'].'">'.
    get_string('deleteall', 'mootyper').'\''.$lessons[$selectedlessonindex]['lessonname'].'\'</a>';
    echo '<br>';
    // Add a export lesson link below the delete all link.
    echo ' <a onclick="return confirm(\''.get_string('exportconfirm', 'mootyper').$lessons[$selectedlessonindex]['lessonname'].
    '\')" href="lsnexport.php?id='.$course->id.'&lsn='.$lessons[$selectedlessonindex]['id'].'">'.
    get_string('export', 'mootyper').'\''.$lessons[$selectedlessonindex]['lessonname'].'\'</a>';
    echo '</form><br>';
    // Create a link with course id and lsn options to export the current Lesson.
    $jlink = '<a onclick="return confirm(\''.get_string('exportconfirm', 'mootyper')
        .$lessons[$selectedlessonindex]['lessonname'].'\')" href="lsnexport.php?id='
        .$course->id.'&lsn='.$lessons[$selectedlessonindex]['id']
        .'"><img src="pix/download_all.svg" alt='
        .get_string('export', 'mootyper').'> '
        .$lessons[$selectedlessonindex]['lessonname'].'';

    // Add a link to let teachers add a new exercise to the Lesson currently being viewed.
    $jlnk3 = $CFG->wwwroot . '/mod/mootyper/eins.php?id='.$id.'&lesson='.$lessonpo;
    echo '<a href="'.$jlnk3.'">'.get_string('eaddnewex', 'mootyper').$lessonpo.'.</a><br>';
} else {
    echo '</form><br>';
}
// Create border and alignment styles for use as needed.
$style1 = 'style="border-color: #000000; border-style: solid; border-width: 3px; text-align: center;"';
$style2 = 'style="border-color: #000000; border-style: solid; border-width: 3px; text-align: left;"';
// Print header row for Lesson table currently being viewed.
echo '<table><tr><td '.$style1.'>'.get_string('ename', 'mootyper').'</td>
                 <td '.$style1.'>'.$lessons[$selectedlessonindex]['lessonname'].'</td>
                 <td '.$style1.'>'.$jlink.'</td></tr>';

// Print table row for each of the exercises in the lesson currently being viewed.
$exercises = get_typerexercisesfull($lessonpo);

foreach ($exercises as $ex) {
    $strtocut = $ex['texttotype'];
    $strtocut = str_replace('\n', '<br>', $strtocut);
    if (strlen($strtocut) > 65) {
        $strtocut = substr($strtocut, 0, 65).'...';
    }
    // If user can edit, create a delete link to the current exercise.
    $jlink1 = '<a onclick="return confirm(\''.get_string('deleteexconfirm', 'mootyper')
              .$lessons[$selectedlessonindex]['lessonname']
              .'\')" href="erem.php?id='.$course->id.'&r='
              .$ex['id'].'&lesson='.$lessonpo.'"><img src="pix/delete.png" alt="'
              .get_string('delete', 'mootyper').'"></a>';

    // If user can edit, create an edit link to the current exercise.
    $jlink2 = '<a href="eedit.php?id='.$course->id.'&ex='.$ex['id']
              .'"><img src="pix/edit.png" alt='
              .get_string('eeditlabel', 'mootyper').'></a>';

    echo '<tr><td '.$style1.'>'.$ex['exercisename'].'</td><td '.$style2.'>'.$strtocut.'</td>';
    // If the user can edit or delete this lesson and its exercises, then added edit and delete tools. 
    if (is_editable_by_me($USER->id, $id, $lessonpo)) {
        echo '<td '.$style1.'>'.$jlink2.' | '.$jlink1.'</td>';
    } else {
        // If the user can not edit or delete, show an empty space.
        echo '<td '.$style2.'></td>';
    }
    echo '</tr>';
}
echo '</table><br>';
echo '</div>';

echo $OUTPUT->footer();
