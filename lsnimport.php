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
 * This file is used to import new lessons found in the lesson folder.
 * Called from admin block while in view.php.
 *
 * @package    mod
 * @subpackage mootyper
 * @copyright  2016 AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

function read_lessons_file($dafile, $authoridarg, $visiblearg, $editablearg, $coursearg=null) {
    require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
    global $DB, $CFG;
    $thefile = $CFG->dirroot."/mod/mootyper/lessons/".$dafile;
    // Echo the file.
    $record = new stdClass();
    $periodpos = strrpos($dafile, '.');
    $lessonname = substr($dafile, 0, $periodpos);

    // Echo lessonname.
    $record->lessonname = $lessonname;
    $record->authorid = $authoridarg;
    $record->visible = $visiblearg;
    $record->editable = $editablearg;
    if (!is_null($coursearg)) {
        $record->courseid = $coursearg;
    }
    $lessonid = $DB->insert_record('mootyper_lessons', $record, true);
    $fh = fopen($thefile, 'r');
    $thedata = fread($fh, filesize($thefile));
    fclose($fh);
    $haha = "";
    for ($i = 0; $i < strlen($thedata); $i++) {
        $haha .= $thedata[$i];
    }
    $haha = trim($haha);
    // Break lesson into separate exercises.
    $splitted = explode ('/**/' , $haha);
    for ($j = 0; $j < count($splitted); $j++) {
        $exercise = trim($splitted[$j]);
        // Saved copy of allowed characters prior to adding missing ones.
        // $allowed = array('!', '@', '#', '$', '%', '^', '&', '(', ')', '*', '_', '+', ':', ';', '"', '{', '}', '>', '<', '?', '\'', '-', '/', '=', '.', ',', ' ', '|');
        $allowed = array('\\', '~', '!', '@', '#', '$', '%', '^', '&', '(', ')', '*', '_', '+', ':', ';', '"', '{', '}', '>', '<', '?', '\'', '-', '/', '=', '.', ',', ' ', '|', '¡', '`', 'ç', 'ñ', 'º', '¿', 'ª', '·', '\n', '\r', '\r\n', '\n\r', ']', '[', '¬', '´', '`');
        $nm = "".($j + 1);
        $texttotype = "";
        for ($k = 0; $k < strlen($exercise); $k++) {
            // TODO
            // * If it is not a letter
            // * and if it is not a number
            // * compare against $allowed array.
            // * If not included die
            // * or something.
            $ch = $exercise[$k];
            if ($ch == "\n") {
                $texttotype .= '\n';
            } else {
                $texttotype .= $ch;
            }
        }
        $erecord = new stdClass();
        $erecord->texttotype = $texttotype;
        $erecord->exercisename = $nm;
        $erecord->lesson = $lessonid;
        $erecord->snumber = $j + 1;
        $DB->insert_record('mootyper_exercises', $erecord, false);
    }
}

global $DB, $CFG, $USER;
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/locallib.php');

$id = optional_param('id', 0, PARAM_INT); // Course ID.
$lsn = optional_param('lsn', 0, PARAM_INT); // Lesson ID to download.

if ($id) {
    $course     = $DB->get_record('course', array('id' => $id), '*', MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}
require_login($course, true);
$context = context_course::instance($id);

// Print the page header.
$PAGE->set_url('/mod/mootyper/exercises.php', array('id' => $course->id));
$PAGE->set_title(get_string('etitle', 'mootyper'));
$PAGE->set_heading(get_string('eheading', 'mootyper'));

// Other things you may want to set - remove if not needed.
$PAGE->set_cacheable(false);

// Output starts here.
echo $OUTPUT->header();

// Set pointer to lessons folder, then get all lesson names in there.
$pth = $CFG->dirroot."/mod/mootyper/lessons";
$res = scandir($pth);

for ($i = 0; $i < count($res); $i++) {
    if (is_file($pth."/".$res[$i])) {
        // Get a filename from the lessons folder.
        $fl = $res[$i]; // Argument list ($dafile, $authorid_arg, $visible_arg, $editable_arg, $course_arg).

        // Strip away the .txt portion of the filename.
        $periodpos = strrpos($fl, '.');
        $lsn = substr($fl, 0, $periodpos);
        // Create sql to see if lesson name is already an installed lesson.
        $sql = "SELECT lessonname
            FROM {mootyper_lessons}
            WHERE lessonname = '".$lsn."'";

        if ($importlesson = $DB->get_record_sql($sql)) {
            // If it's true the name is already in the database, do nothing.
            echo "$lsn".get_string('lsnimportnotadd', 'mootyper').'<br>';
        } else {
            // If it's not found in the db, then add the new lesson to the database.
            echo "<b>$lsn".get_string('lsnimportadd', 'mootyper').'</b><br>';
            read_lessons_file($fl, $USER->id, 0, 2);
            // Since we added a new lesson, make a log entry about it.
            $data = new StdClass();
            $data->mootyper = $id;
            // $context = context_module::instance($this->cm->id);
            $context = context_course::instance($id);
            // Trigger lesson_import event.
            $event = \mod_mootyper\event\lesson_imported::create(array(
                'objectid' => $data->mootyper,
                'context' => $context
            ));
            $event->trigger();
        }
    }
}

$jlnk2 = $CFG->wwwroot . '/mod/mootyper/exercises.php?id='.$id;
echo '<a href="'.$jlnk2.'">'.get_string('fcontinue', 'mootyper').'</a><br><br>';
echo $OUTPUT->footer();
return;
