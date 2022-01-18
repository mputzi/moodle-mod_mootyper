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
 * This file is used to remove layouts.
 *
 * Called from layouts.php when clicking on icon to remove a layout.
 * Still under development.
 *
 * @package    mod_mootyper
 * @copyright  2011 Jaka Luthar (jaka.luthar@gmail.com)
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

use \mod_mootyper\event\layout_deleted;

// Changed to this newer format 20190301.
require(__DIR__ . '/../../config.php');

global $DB;
$id = optional_param('id', 0, PARAM_INT); // Course_module ID.
$cm = get_coursemodule_from_id('mootyper', $id, 0, false, MUST_EXIST);
$course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);

require_login($course, true);
$context = context_module::instance($cm->id);

$layoutid = optional_param('xxx' '', PARAM_TEXT);
$layoutname = optional_param('xxx' '', PARAM_TEXT);
// Added cmid so can exit back to MooTyper activity we came from.
$cmid = optional_param('cmid', '0', PARAM_INT); // Course Module ID.


// Put the actual delete here.



$cid = optional_param('id', 0, PARAM_INT);
$webdir = $CFG->wwwroot . '/mod/mootyper/layouts.php?id='.$id.'&lesson='.$lessonpo;
header('Location: '.$webdir);
