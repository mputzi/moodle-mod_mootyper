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
 * Lists all KB layouts in the database table, mdl_mootyper_layouts.
 *
 * @package    mod_mootyper
 * @copyright  2019 AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

use \mod_mootyper\event\course_module_viewed;
use \mod_mootyper\local\keyboards;
use \mod_mootyper\local\lessons;

require_once('../../config.php');
//require_once(__DIR__ . '/lib.php');
//require_once(__DIR__ . '/locallib.php');

global $DB, $OUTPUT, $PAGE;

print_object('1 spacer in mootyper layouts.php.');
print_object('2 spacer in mootyper layouts.php.');
print_object('3 spacer in mootyper layouts.php.');
print_object('4 spacer in mootyper layouts.php.');

// Fetch URL parameters.
$id = optional_param('id', 0, PARAM_INT); // Course ID.

//print_object('In mootyper layouts.php printing $id. Currently, it is the mootyper activity id!');
//print_object($id);

if (! $cm = get_coursemodule_from_id('mootyper', $id)) {
    print_error("Course Module ID was incorrect");
}

//print_object('In mootyper layouts.php printing $cm.');
//print_object($cm);

if (! $course = $DB->get_record("course", array('id' => $cm->course))) {
    print_error("Course is misconfigured");
}

//print_object('In mootyper layouts.php printing $course.');
//print_object($course);


require_login($course, true, $cm);

$context = context_module::instance($cm->id);

$mootyper = $DB->get_record('mootyper', array('id' => $cm->instance) , '*', MUST_EXIST);

//print_object('In mootyper layouts.php printing $mootyper.');
//print_object($mootyper);

// Print the page header.
$PAGE->set_url('/mod/mootyper/layouts.php', array('id' => $id));
//$PAGE->set_pagelayout('course');

//$PAGE->set_title(get_string('etitle', 'mootyper'));
$PAGE->set_heading($course->fullname);

$renderer = $PAGE->get_renderer('mod_mootyper');
//echo $renderer->header($mootyper->title, $course->fullname);

$returnedit = new moodle_url('/mod/mootyper/layouts.php', array('id' => $id));

// Other things you may want to set - remove if not needed.
$PAGE->set_cacheable(false);

// Output starts here.
echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('loheading', 'mootyper'));
echo '<h2>Under development - Remove not implemented yet.</h2>';

// This is copied from simplelesson.
//$tabledata = table_data::get_edit_table_data($cm);
//echo $renderer->edit_table_links($tabledata);
//$linkdata = link_data::get_page_management_links($cm);
//echo $renderer->page_management_links($linkdata);




// 20200226 Switched from course id to current MooTyper id so
// that I can use the current MooTyper keyboard background color.
$color3 = $mootyper->keybdbgc;

echo '<div align="left" style="font-size:1em;
     font-weight:bold;background: '.$color3.';
     border:2px solid black;
     -webkit-border-radius:16px;
     -moz-border-radius:16px;border-radius:16px;"><table>';

//$layouts = get_keyboard_layouts_db(); // Function is in locallib.php.
$layouts = keyboards::get_keyboard_layouts_db();

   // print_object('In mootyper layouts.php after getting layouts and printing $layouts[55].');
   // print_object($layouts[55]);

//$layoutspo = optional_param('layouts', 1, PARAM_INT);
$layoutspo = array();
//$layoutspo = 0;



// Cannot remember why I have this if here.
//if ($layoutspo[0] == 0 && count($layouts) > 0) {
if ($layoutspo == 0 && count($layouts) > 0) {
    $layoutspo = $layouts[0];
}

$selectedlayoutindex = 0;
/*
for ($ij = 0; $ij < count($layouts); $ij++) {
    if ($layouts[$ij] == $layoutspo) {
        echo '<option selected="true" value="'.$layouts[$ij].'">'.$layouts[$ij]['name'].'</option>';
        $selectedlessonindex = $ij;
    } else {
        echo '<option value="'.$layouts[$ij].'">'.$layouts[$ij].'</option>';
    }
}
*/

/*
    // If user can edit, create a delete link to the current exercise.
    $jlink1 = '<a onclick="return confirm(\''.get_string('deleteexconfirm', 'mootyper')
              .$lessons[$selectedlessonindex]['lessonname']
              .'\')" href="erem.php?id='.$id.'&r='
              .$ex->id.'&lesson='.$lessonpo.'"><img src="pix/delete.png" alt="'
              .get_string('delete', 'mootyper').'"></a>';
*/


// Will need to create a remove layout php file klrem.php, like the one
// I have for exercise remove, erem.php.
// Will need to pass the name of the layout to remove as at this point
// the layout name is all we have to work with. MIGHT be able to backtrack
// and get the id from $layouts.

    // If user can edit, create a remove link to the current KB layout.
    $jlink1 = '<a onclick="return confirm(\''.get_string('deleteexconfirm', 'mootyper')
              .'"><img src="pix/delete.png" alt="'
              .get_string('delete', 'mootyper').'"></a>';
    
//print_object($jlink1);

echo '<div class="container">';
echo '<table class="table table-hover">';
echo '<thead><tr><th>Layout name</th><th>Remove</th>';
echo '</tr></thead>';

echo '<tbody>';

// Need to make it so only an admin can do a layout delete!
foreach ($layouts as $lo) {
   // if (lessons::is_editable_by_me($USER->id, $layoutspo, $lsn)) {
        // List the keyboards by name.
        echo '<tr><td>'.$lo.'</td><td>'.$jlink1.'</td></tr>';
   // }
   // echo ' ';
}
echo '</tbody>';
echo '</table>';
echo '</div>';

// 20200226 Added a continue button that takes you back to the MooTyper you came from.
$url = $CFG->wwwroot . '/mod/mootyper/view.php?id='.$id;

echo '<br><a href="'.$url.'" class="btn btn-primary" style="border-radius: 8px">'.get_string('returnto', 'mootyper', $mootyper->name).'</a><br><br>';

echo $OUTPUT->footer();
