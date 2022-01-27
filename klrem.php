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
$kb = optional_param('kb', '', PARAM_TEXT); // Name of the keyboard layout to delete.

$cm = get_coursemodule_from_id('mootyper', $id, 0, false, MUST_EXIST);
$course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);

require_login($course, true);
$context = context_module::instance($cm->id);

//$layoutname = optional_param('kb', '', PARAM_TEXT);
if ($kb) {
    // Put the actual delete here.
    echo 'Put the actual delete here as we have made it to klrem.<br>';
    echo 'I have two variables being passed to this file, $id and $kb.<br>';
    echo 'The varible $id tells us which MooTyper activity we are in and it is: '.$id.' and $kb is: '.$kb.'<br>';
    echo 'Need to search for and delete the keyboard layout named, '.$kb;
    $kbrecord = $DB->get_record('mootyper_layouts', array('name' => $kb), '*', MUST_EXIST);

    print_object($kbrecord);
    // Get the absolute path to the current working directory.
    $pathtodir = getcwd();
    echo "Absolute Path To Directory is: ";
    echo $pathtodir.'<br>';
    // Create an absolute pointer to the php and js files that are to be deleted.
    $filepointer1 = $pathtodir.'/layouts/'.$kb.'.php';
    $filepointer2 = $pathtodir.'/layouts/'.$kb.'.js';
    echo "Modified Paths to the files is: ";
    echo $filepointer1.'<br>';
    echo $filepointer2.'<br>';


    //$filepointer1 = $CFG->wwwroot . '/mod/mootyper/layouts/'.$kb.'.php';
    //$filepointer2 = $CFG->wwwroot . '/mod/mootyper/layouts/'.$kb.'.js';
    //$filepointer1 = $CFG->dataroot . '/mod/mootyper/layouts/'.$kb.'.php';
    //$filepointer2 = $CFG->dataroot . '/mod/mootyper/layouts/'.$kb.'.js';
    echo 'The database record we need to delete is in the mootyper_layouts table with an $id of: '.$kbrecord->id.'<br>';
    echo 'The keyboard php file to delete is $filepointer1: '.$filepointer1.'<br>';
    echo '$The keyboard js file to delete is $filepointer2: '.$filepointer2.'<br>';

    // Use unlink() function to delete the two physical files for the layout being deleted. 
    if (!unlink($filepointer1)) { 
        echo ("$filepointer1 cannot be deleted due to an error."); 
    } 
    else { 
        echo ("$filepointer1 has been deleted."); 
    }
    if (!unlink($filepointer2)) { 
        echo ("$filepointer2 cannot be deleted due to an error."); 
    } 
    else { 
        echo ("$filepointer2 has been deleted."); 
    }

    // Delete the database record for the layout being deleted.
    $DB->delete_records('mootyper_layouts', array('id' => $kbrecord->id));

    // Trigger module layout_deleted event.
    $params = array(
        'objectid' => $course->id,
        'context' => $context,
        'other' => array(
            'layout' => $kb,
        )
    );
    $event = layout_deleted::create($params);
    $event->trigger();

    //$kbrecord2 = $DB->get_record('mootyper_layouts', array('id' => $kbrecord->id), '*', MUST_EXIST);
    //print_object($kbrecord2);

    //die;
}
$webdir = $CFG->wwwroot . '/mod/mootyper/layouts.php?id='.$id;
header('Location: '.$webdir);
