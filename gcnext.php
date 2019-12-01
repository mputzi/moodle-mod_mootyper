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
 * This file adds grade and performance info to mdl_mootyper_grades after an exercise.
 *
 * @package    mod_mootyper
 * @copyright  2012 Jaka Luthar (jaka.luthar@gmail.com)
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

use \mod_mootyper\event\exercise_completed;
use \mod_mootyper\event\exam_completed;

// Changed to this newer format 03/01/2019.
require(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/locallib.php');

global $CFG, $DB;

$cmid = optional_param('cmid', 0, PARAM_INT); // Course_module ID.
$lsnname = optional_param('lsnname', '', PARAM_RAW); // MooTyper lesson name.
$exercisename = optional_param('exercisename', 0, PARAM_INT); // MooTyper exercise name.
$mtmode = optional_param('mtmode', 0, PARAM_INT); // MooTyper mode.

if ($cmid) {
    $cm = get_coursemodule_from_id('mootyper', $cmid, 0, false, MUST_EXIST);
    $courseid = $cm->course;
    $context = context_module::instance($cm->id);
}

require_login(0, true, null, false);
if (optional_param('rpAccInput', '', PARAM_FLOAT) >= optional_param('rpGoal', '', PARAM_FLOAT)) {
    $passfield = 1;
} else {
    $passfield = 0;
}

$record = new stdClass();
$record->mootyper = optional_param('rpSityperId', '', PARAM_INT);
$record->userid = optional_param('rpUser', '', PARAM_INT);
// Gradebook entry has not been implemented, 10/10/17.
// $record->grade = 0;
// Temp change to put precision in gradebook for exam.
$record->grade = optional_param('rpAccInput', '', PARAM_FLOAT);
$record->mistakes = optional_param('rpMistakesInput', '', PARAM_INT);
$record->timeinseconds = optional_param('rpTimeInput', '', PARAM_INT);
$record->hitsperminute = optional_param('rpSpeedInput', '', PARAM_FLOAT);
$record->fullhits = optional_param('rpFullHits', '', PARAM_INT);
$record->precisionfield = optional_param('rpAccInput', '', PARAM_FLOAT);
$record->timetaken = time();
$record->exercise = optional_param('rpExercise', '', PARAM_INT);
$record->pass = $passfield;
$record->attemptid = optional_param('rpAttId', '', PARAM_INT);
$record->wpm = (max(0, optional_param('rpWpmInput', '', PARAM_FLOAT)));

$DB->insert_record('mootyper_grades', $record, false);
// Trigger exercise_completed event.
// Added 11/29/19. 12/1/19 modified to trigger exam completed.
$params = array(
    'objectid' => $cmid,
    'context' => $context,
    'other' => array(
        'exercise' => $record->exercise,
        'lessonname' => $lsnname,
        'activity' => $cm->name
    )
);
if ($mtmode === 1) {
    $event = exam_completed::create($params);
} else {
    $event = exercise_completed::create($params);
}
$event->trigger();

$webdir = $CFG->wwwroot . '/mod/mootyper/view.php?n='.$record->mootyper;
echo '<script type="text/javascript">window.location="'.$webdir.'";</script>';
