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
 * This file displays all grades of the current user of this paricular mootyper instance.
 *
 * The header of each column in the results table can be used to sort the table.
 * Grades cannot be removed from here like they can from the View all grades page.
 *
 * @package    mod_mootyper
 * @copyright  2012 Jaka Luthar (jaka.luthar@gmail.com)
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use \mod_mootyper\event\viewed_own_grades;
use \mod_mootyper\local\results;

// Changed to this newer format 03/01/2019.
require(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/lib.php');

global $USER;

$id = optional_param('id', 0, PARAM_INT); // Course_module ID, or
$n  = optional_param('n', 0, PARAM_INT);  // Mootyper instance ID.
$se = optional_param('exercise', 0, PARAM_INT);
$md = optional_param('jmode', 0, PARAM_INT);
$us = optional_param('juser', 0, PARAM_INT);
$orderby = optional_param('orderby', -1, PARAM_INT);
$des = optional_param('desc', -1, PARAM_INT);
$mtmode = optional_param('mtmode', 0, PARAM_INT);  // Is this Mootyper a lesson or practice activity?

if ($md == 1) {
    $us = 0;
} else if ($md == 0) {
    $se = 0;
}
if ($id) {
    $cm         = get_coursemodule_from_id('mootyper', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $mootyper   = $DB->get_record('mootyper', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($n) {
    $mootyper  = $DB->get_record('mootyper', array('id' => $n), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $mootyper->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('mootyper', $mootyper->id, $course->id, false, MUST_EXIST);
} else {
    print_error(get_string('mootypererror', 'mootyper'));
}

$lsnname    = $DB->get_record('mootyper_lessons', array('id' => $mootyper->lesson), '*', MUST_EXIST);
$mtmode = $mootyper->isexam;
require_login($course, true, $cm);
$context = context_module::instance($cm->id);

// Prevent anyone but students from typing in address to view my grades.
if (!has_capability('mod/mootyper:viewmygrades', context_module::instance($cm->id))) {
    redirect('view.php?id='.$id, get_string('invalidaccess', 'mootyper'));
} else {
    // The following retrieves keybdbgc for setting this background.
    $color3 = $mootyper->keybdbgc;

    $PAGE->set_url('/mod/mootyper/owngrades.php', array('id' => $cm->id));
    $PAGE->set_title(format_string($mootyper->name));
    $PAGE->set_heading(format_string($course->fullname));
    $PAGE->set_context($context);
    $PAGE->set_cacheable(false);
    echo $OUTPUT->header();
    echo $OUTPUT->heading($mootyper->name);
    echo '<div align="center" style="font-size:1em;
        font-weight:bold;background: '.$color3.';
        border:2px solid black;
        -webkit-border-radius:16px;
        -moz-border-radius:16px;
        border-radius:16px;">';

    // Set a heading for the grades table, based on the mode and the lesson/category name.
    switch ($mtmode) {
        case 0:
            echo get_string('fmode', 'mootyper')." = ".get_string('flesson', 'mootyper');
            break;
        case 1:
            echo get_string('fmode', 'mootyper')." = ".get_string('isexamtext', 'mootyper');
            break;
        case 2:
            echo get_string('fmode', 'mootyper')." = ".get_string('practice', 'mootyper');
            break;
        default:
            echo 'error';
    }
    echo '&nbsp;&nbsp;&nbsp;&nbsp;'.get_string('lsnname', 'mootyper')
        ." = ".$lsnname->lessonname;
    echo '<br>'.get_string('timelimit', 'mootyper')
        .' = '.$mootyper->timelimit.':00 '.get_string('min');
    echo '&nbsp;&nbsp;&nbsp;&nbsp;'.get_string('requiredgoal', 'mootyper')
        .' = '.$mootyper->requiredgoal.'%';
    echo '&nbsp;&nbsp;&nbsp;&nbsp;'.get_string('requiredwpm', 'mootyper')
        .' = '.$mootyper->requiredwpm;
    // Update the library.
    if ($des == -1 || $des == 0) {
        $grds = get_typergradesuser(optional_param('n', 0, PARAM_INT), $USER->id, $orderby, 0);
    } else if ($des == 1) {
        $grds = get_typergradesuser(optional_param('n', 0, PARAM_INT), $USER->id, $orderby, 1);
    } else {
        $grds = get_typergradesuser(optional_param('n', 0, PARAM_INT), $USER->id, $orderby, $des);
    }
    if ($des == -1 || $des == 1) {
        $lnkadd = "&desc=0";
    } else {
        $lnkadd = "&desc=1";
    }
    $arrtextadds = array();
    $arrtextadds[2] = '<span class="arrow-s" style="font-size:1em;"></span>';
    $arrtextadds[4] = '<span class="arrow-s" style="font-size:1em;"></span>';
    $arrtextadds[5] = '<span class="arrow-s" style="font-size:1em;"></span>';
    $arrtextadds[6] = '<span class="arrow-s" style="font-size:1em;"></span>';
    $arrtextadds[7] = '<span class="arrow-s" style="font-size:1em;"></span>';
    $arrtextadds[8] = '<span class="arrow-s" style="font-size:1em;"></span>';
    $arrtextadds[9] = '<span class="arrow-s" style="font-size:1em;"></span>';
    $arrtextadds[12] = '<span class="arrow-s" style="font-size:1em;"></span>';
    $arrtextadds[$orderby] = $des == -1 || $des == 1 ? '<span class="arrow-s" style="font-size:1em;">
        </span>' : '<span class="arrow-n" style="font-size:1em;"></span>';
    if ($grds != false) {
        echo '<table style="border-style: solid;"><tr>
            <td>'.get_string('fexercise', 'mootyper').'</td>
            <td><a href="?id='.$id.'&n='.$n.'&orderby=4'.$lnkadd.'">'
            .get_string('vmistakes', 'mootyper').'</a>'.$arrtextadds[4].'</td>
            <td><a href="?id='.$id.'&n='.$n.'&orderby=5'.$lnkadd.'">'
            .get_string('timeinseconds', 'mootyper').'</a>'.$arrtextadds[5].'</td>
            <td><a href="?id='.$id.'&n='.$n.'&orderby=6'.$lnkadd.'">'
            .get_string('hitsperminute', 'mootyper').'</a>'.$arrtextadds[6].'</td>
            <td><a href="?id='.$id.'&n='.$n.'&orderby=7'.$lnkadd.'">'
            .get_string('fullhits', 'mootyper').'</a>'.$arrtextadds[7].'</td>
            <td><a href="?id='.$id.'&n='.$n.'&orderby=8'.$lnkadd.'">'
            .get_string('precision', 'mootyper').'</a>'.$arrtextadds[8].'</td>
            <td><a href="?id='.$id.'&n='.$n.'&orderby=9'.$lnkadd.'">'
            .get_string('timetaken', 'mootyper').'</a>'.$arrtextadds[9].'</td>
            <td><a href="?id='.$id.'&n='.$n.'&orderby=12'.$lnkadd.'">'
            .get_string('wpm', 'mootyper').'</a>'.$arrtextadds[12].'</td>
            <td>'.get_string('delete', 'mootyper').'</td></tr>';

        foreach ($grds as $gr) {
            if ($gr->pass) {
                $stil = 'background-color: '.(get_config('mod_mootyper', 'passbgc')).';';
            } else {
                $stil = 'background-color: '.(get_config('mod_mootyper', 'failbgc')).';';
            }
            if ($mtmode == 2) {
                $removelnk = '<a onclick="return confirm(\''
                    .get_string('deletegradeconfirm', 'mootyper')
                    .$gr->firstname.' '
                    .$gr->lastname.' '
                    .get_string('exercise_abreviation', 'mootyper').'-'
                    .$gr->exercisename.'.'
                    .'\')" href="'.$CFG->wwwroot
                    .'/mod/mootyper/attrem.php?c_id='
                    .optional_param('id', 0, PARAM_INT)
                    .'&m_id='.optional_param('n', 0, PARAM_INT)
                    .'&mtmode='.$mtmode
                    .'&g='.$gr->id.'">'
                    .get_string('delete', 'mootyper').'</a>';
            } else {
                $removelnk = '<a  onclick="return confirm(\''
                    .get_string('deletegradeconfirm', 'mootyper')
                    .$gr->firstname.' '
                    .$gr->lastname.' '
                    .$gr->exercisename.'.'
                    .'\')" href="'.$CFG->wwwroot
                    .'/mod/mootyper/attrem.php?c_id='.optional_param('id', 0, PARAM_INT)
                    .'&m_id='.optional_param('n', 0, PARAM_INT)
                    .'&g='.$gr->id.'">'
                    .'</a>';
            }

            $fcol = $gr->exercisename;
            $fcol = get_string('exercise_abreviation', 'mootyper').'-'.$fcol;  // This gets the exercise number.

            // 20191230 Combine new mistakedetails with mistakes count.
            $strtocut = $gr->mistakes.': '.$gr->mistakedetails;

            echo '<tr align="center" style="border-top-style: solid;'.$stil.'">
                <td>'.$fcol.'</td>
                <td>'.$strtocut.'</td>
                <td>'.format_time($gr->timeinseconds).'</td>
                <td>'.format_float($gr->hitsperminute).'</td>
                <td>'.$gr->fullhits.'</td>
                <td>'.format_float($gr->precisionfield).'%</td>
                <td>'.date(get_config('mod_mootyper', 'dateformat'), $gr->timetaken).'</td>
                <td>'.format_float($gr->wpm).'</td>
                <td>'.$removelnk.'</td>
                <td>'.' '.'</td>
                </tr>';
            $labels[] = $fcol;  // This gets the exercise number to use in the chart.
            $serieshitsperminute[] = $gr->hitsperminute; // Get the hits per minute value.
            $seriesprecision[] = $gr->precisionfield;  // Get the precision percentage value.
            $serieswpm[] = $gr->wpm; // Get the corrected words per minute rate.
        }
        $avg = results::get_grades_avg($grds);
        if (!($mtmode == 1)) {
            echo '<tr align="center" style="border-top-style: solid;">
                <td><strong>'.get_string('average', 'mootyper').': </strong></td>
                <td>'.$avg['mistakes'].'</td>
                <td>'.format_time($avg['timeinseconds']).'</td>
                <td>'.format_float($avg['hitsperminute']).'</td>
                <td>'.$avg['fullhits'].'</td>
                <td>'.format_float($avg['precisionfield']).'%</td>
                <td></td>
                <td></td>
                <td></td>
                </tr>';
        }
        echo '</table>';

    } else {
        echo get_string('nogrades', 'mootyper');
    }

    // 20200414 Added a return button.
    // 20200426 Modified return button for end of lesson.
    // 20200428 Added rounded corners. Moved into table.
    if ($id == 0 && $n > 0) {
        $url2 = '<a href="'.$CFG->wwwroot . '/mod/mootyper/view.php?id='.$cm->id
            .'"class="btn btn-primary" style="border-radius: 8px">'
            .get_string('returnto', 'mootyper', $mootyper->name)
            .'</a>';
    } else {
        $url2 = '<a href="'.$CFG->wwwroot . '/mod/mootyper/view.php?id='.$id
            .'"class="btn btn-primary" style="border-radius: 8px">'
            .get_string('returnto', 'mootyper', $mootyper->name)
            .'</a>';
    }
    echo '<br>'.$url2;

    echo '</div>';
}

// Trigger module viewed_own_grades event.
$params = array('objectid' => $course->id, 'context' => $context);
$event = viewed_own_grades::create($params);
$event->trigger();

if (($grds != false) && ($CFG->branch > 31)) {  // If there are NOT any grades, DON'T draw the chart.
    // Create the info the api needs passed to it for each series I want to chart.
    $serie1 = new core\chart_series(get_string('hitsperminute', 'mootyper'), $serieshitsperminute);
    $serie2 = new core\chart_series(get_string('precision', 'mootyper'), $seriesprecision);
    $serie3 = new core\chart_series(get_string('wpm', 'mootyper'), $serieswpm);

    $chart = new core\chart_bar();  // Tell the api I want a bar chart.
    $chart->set_horizontal(true); // Calling set_horizontal() passing true as parameter will display horizontal bar charts.
    $chart->set_title(get_string('charttitlemyowngrades', 'mootyper')); // Tell the api what I want for a chart title.
    $chart->add_series($serie1);  // Pass the hits per minute data to the api.
    $chart->add_series($serie2);  // Pass the precision data to the api.
    $chart->add_series($serie3);  // Pass the words per minute data to the api.
    $chart->set_labels($labels);  // Pass the exercise number data to the api.
    $chart->get_xaxis(0, true)->set_label(get_string('xaxislabel', 'mootyper'));  // Pass a label to add to the x-axis.
    $chart->get_yaxis(0, true)->set_label(get_string('fexercise', 'mootyper')); // Pass the label to add to the y-axis.
    echo $OUTPUT->render($chart); // Draw the chart on the output page.
}
echo $OUTPUT->footer();
