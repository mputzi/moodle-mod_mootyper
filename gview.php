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
 * This file displays all the grades of a particular MooTyper Lesson.
 *
 * It is also possible to remove the results of any individual attempt.
 * 20200624 The grades that are visible now depend on the group settings.
 *
 * @package    mod_mootyper
 * @copyright  2012 Jaka Luthar (jaka.luthar@gmail.com)
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use \mod_mootyper\event\viewed_all_grades;
use \mod_mootyper\event\invalid_access_attempt;
use \mod_mootyper\local\lessons;
use \mod_mootyper\local\results;

// Changed to this newer format 20190301.
require(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/lib.php');

global $USER;

$id = optional_param('id', 0, PARAM_INT); // Course_module ID, or
$n  = optional_param('n', 0, PARAM_INT);  // Mootyper instance ID - it should be named as the first character of the module.
$se = optional_param('exercise', 0, PARAM_INT);
$md = optional_param('jmode', 0, PARAM_INT);
$us = optional_param('juser', 0, PARAM_INT);
$orderby = optional_param('orderby', -1, PARAM_INT);
$des = optional_param('desc', -1, PARAM_INT);
if ($md == 1) {
    $us = 0;
} else if ($md == 0 || $md == 2) {
    $se = 0;
}
if ($id) {
    $cm         = get_coursemodule_from_id('mootyper', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $mootyper  = $DB->get_record('mootyper', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($n) {
    $mootyper  = $DB->get_record('mootyper', array('id' => $n), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $mootyper->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('mootyper', $mootyper->id, $course->id, false, MUST_EXIST);
    $id = $cm->id; // Since we had ID of 0, we really need Course module ID for cvsexport, so set it.
} else {
    print_error(get_string('mootypererror', 'mootyper'));
}
$lsnname = $DB->get_record('mootyper_lessons', array('id' => $mootyper->lesson), '*', MUST_EXIST);
$mtmode = $mootyper->isexam;
require_login($course, true, $cm);
$context = context_module::instance($cm->id);

// 20200706 Added to prevent student direct URL access attempts.
if (!has_capability('mod/mootyper:viewgrades', context_module::instance($cm->id))) {
    // Trigger invalid_access_attempt with redirect to course page.
    $params = array(
        'objectid' => $id,
        'context' => $context,
        'other' => array(
            'file' => 'gview.php'
        )
    );
    $event = invalid_access_attempt::create($params);
    $event->trigger();
    redirect('view.php?id='.$id, get_string('invalidaccessexp', 'mootyper'));
} else {
    // Trigger view all grades event.
    $params = array(
            'objectid' => $mootyper->id,
            'context' => $context,
            'other' => array(
                'lessonid' => $mootyper->lesson,
                'lessonname' => $lsnname->lessonname
            )
        );
    $event = viewed_all_grades::create($params);
    $event->trigger();
    // The following needs to retrieve keybdbgc for setting this background.
    $color3 = $mootyper->keybdbgc;

    $PAGE->set_url('/mod/mootyper/gview.php', array('id' => $cm->id));
    $PAGE->set_title(format_string($mootyper->name));
    $PAGE->set_heading(format_string($course->fullname));
    $PAGE->set_context($context);
    $PAGE->set_cacheable(false);
    echo $OUTPUT->header();
    echo '<link rel="stylesheet" type="text/css" href="styles.css">';
    echo $OUTPUT->heading($mootyper->name);
    // 20200620 Changed $htmlout's to echo's.
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
            echo get_string('error', 'moodle');
    }
    echo '&nbsp;&nbsp;&nbsp;&nbsp;'.get_string('lsnname', 'mootyper')
        ." = ".$lsnname->lessonname;
    echo '<br>'.get_string('timelimit', 'mootyper')
        .' = '.$mootyper->timelimit.':00 '.get_string('min');
    echo '&nbsp;&nbsp;&nbsp;&nbsp;'.get_string('requiredgoal', 'mootyper')
        .' = '.$mootyper->requiredgoal.'%';
    echo '&nbsp;&nbsp;&nbsp;&nbsp;'.get_string('requiredwpm', 'mootyper')
        .' = '.$mootyper->requiredwpm;
    echo '<br>';
    echo '<table><tr><td>';

    // Print group information (A drop down box will be displayed if the user
    // is a member of more than one group, or has access to all groups).
    echo groups_print_activity_menu($cm, $CFG->wwwroot . '/mod/mootyper/gview.php?id=' . $cm->id);
    // 20200620 Check to see if groups are being used here.
    $groupmode = groups_get_activity_groupmode($cm);
    $currentgroup = groups_get_activity_group($cm, true);
    if ($currentgroup) {
        $groups = $currentgroup;
    } else {
        $groups = '';
    }
    echo '</td>';

    echo '<form method="post">';
    // 20190308 Changed the code for mode 1 to use the same code as mode 0 and mode 2.
    echo '<td>'.get_string('gviewmode', 'mootyper').'   ';
    // 20200620 Added class="custom-select singleselect" to look like group selector.
    echo '<select onchange="this.form.submit()" class="custom-select singleselect" name="jmode">
                <option value="0">'.get_string('byuser', 'mootyper').'</option>';
    if ($md == 1) {
        echo '<option value="1" selected="true">'.get_string('bymootyper', 'mootyper').'</option>';
    } else {
        echo '<option value="1">'.get_string('bymootyper', 'mootyper').'</option>';
    }

    echo '</select></td>';

    if ($md == 0 || $md == 1 || $mtmode == 2) {
        $usrs = get_users_of_one_instance($mootyper->id);
        echo '<td>'.get_string('student', 'mootyper').'   </td><td>';
        // 20200620 Added class="custom-select singleselect" to look like group selector.
        echo '<select name="juser" onchange="this.form.submit()" class="custom-select singleselect">';
        echo '<option value="0">'.get_string('allstring', 'mootyper').'</option>';
        // 20200621 Filter Student selector to only show group memebers when set to visible groups or separate groups.
        if (($usrs != false) && ($groupmode != false)) {
            foreach ($usrs as $x) {
                if (($us == $x->id) && (groups_is_member($groups, $x->id))) {
                    echo '<option value="'.$x->id.'" selected="true">'.$x->firstname.' '.$x->lastname.'</option>';
                } else if (groups_is_member($groups, $x->id)) {
                    echo '<option value="'.$x->id.'">'.$x->firstname.' '.$x->lastname.'</option>';
                }
            }
        }
        // 20200621 Filter Student selector to show everyone when set to no groups.
        if (($usrs != false) && ($groupmode == false)) {
            foreach ($usrs as $x) {
                if ($us == $x->id) {
                    echo '<option value="'.$x->id.'" selected="true">'.$x->firstname.' '.$x->lastname.'</option>';
                } else {
                    echo '<option value="'.$x->id.'">'.$x->firstname.' '.$x->lastname.'</option>';
                }
            }
        }
        echo '</select>';
        echo '</td>';
    }
    // Show the Exercise selector.
    if ($md == 0 || $md == 1 || $mtmode == 2) {
        $exes = lessons::get_exercises_by_lesson($mootyper->lesson);
        echo '<td>'.get_string('fexercise', 'mootyper').'  </td><td>';
        // 20200620 Added class="custom-select singleselect" to look like group selector.
        echo '<select name="exercise" onchange="this.form.submit()" class="custom-select singleselect">';
        echo '<option value="0">'.get_string('allstring', 'mootyper').'</option>';
        foreach ($exes as $x) {
            if ($se == $x['id']) {
                echo '<option value="'.$x['id'].'" selected="true">'.$x['exercisename'].'</option>';
            } else {
                echo '<option value="'.$x['id'].'">'.$x['exercisename'].'</option>';
            }
        }
        echo '</select>';
        echo '</td></tr></table></form>';
    }

    if ($des == -1) {
        $des = 0;
    }
    $grds = get_typer_grades_adv($mootyper->id, $se, $us, $orderby, $des);
    if ($grds != false) {
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
        $arrtextadds[10] = '<span class="arrow-s" style="font-size:1em;"></span>';
        $arrtextadds[11] = '<span class="arrow-s" style="font-size:1em;"></span>';
        $arrtextadds[12] = '<span class="arrow-s" style="font-size:1em;"></span>';
        $arrtextadds[$orderby] = $des == -1 || $des == 1 ? '<span class="arrow-s" style="font-size:1em;">
                                 </span>' : '<span class="arrow-n" style="font-size:1em;"></span>';

        echo '<table style="border-style: solid;"><tr>
            <td><a href="?id='.$id.'&n='.$n.'&orderby=2'.$lnkadd.'">'
            .get_string('student', 'mootyper').'</a>'.$arrtextadds[2].'</td>
            <td><a href="?id='.$id.'&n='.$n.'&orderby=10'.$lnkadd.'">'
            .get_string('fexercise', 'mootyper').'</a>'.$arrtextadds[10].'</td>
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

        $labels = null; // 20200624 Set to use as a flag for graphing.
        $grds2 = array(); // 20200730 Array to hold data for current group members.
        foreach ($grds as $gr) {
            if ((! $groups) || (groups_is_member($groups, $gr->u_id))) {
                if ($gr->suspicion) {
                    $exclamation = '<span style="color: '.(get_config('mod_mootyper', 'suspicion')).';"><b>!!!!!</b></span>';
                } else {
                    $exclamation = '';
                }
                if ($gr->pass) {
                    $stil = 'background-color: '.(get_config('mod_mootyper', 'passbgc')).';';
                } else {
                    $stil = 'background-color: '.(get_config('mod_mootyper', 'failbgc')).';';
                }
                // Keep grades for group members so stats will be just for the group selected.
                $grds2[] = $gr;
                $fcol = $gr->exercisename;
                $fcol = get_string('exercise_abreviation', 'mootyper').'-'.$fcol;  // This gets the exercise number.
                $removelnk = '<a onclick="return confirm(\''
                    .get_string('deletegradeconfirm', 'mootyper')
                    .$gr->firstname.' '
                    .$gr->lastname.' '
                    .$fcol.'.'
                    .'\')" href="'.$CFG->wwwroot.'/mod/mootyper/attrem.php?c_id='
                    .optional_param('id', 0, PARAM_INT)
                    .'&m_id='.optional_param('n', 0, PARAM_INT)
                    .'&g='.$gr->id
                    .'">'.get_string('delete', 'mootyper').'</a>';
                $namelnk = '<a href="'.$CFG->wwwroot.'/user/view.php?id='.$gr->u_id
                    .'&amp;course='.$course->id
                    .'">'.$gr->firstname.' '
                    .$gr->lastname.'</a>';

                // 20191230 Combine new mistakedetails with mistakes count.
                $strtocut = $gr->mistakes.': '.$gr->mistakedetails;

                echo '<tr align="center" style="border-top-style: solid;'.$stil.'">
                             <td>'.$exclamation.' '.$namelnk.'</td>
                             <td>'.$fcol.'</td>
                             <td>'.$strtocut.'</td>
                             <td>'.format_time($gr->timeinseconds).'</td>
                             <td>'.format_float($gr->hitsperminute).'</td>
                             <td>'.$gr->fullhits.'</td>
                             <td>'.format_float($gr->precisionfield).'%</td>
                             <td>'.date(get_config('mod_mootyper', 'dateformat'), $gr->timetaken).'</td>
                             <td>'.format_float($gr->wpm).'</td>
                             <td>'.$removelnk.'</td></tr>';

                // Get information to draw the chart for all exercises in this lesson.
                $labels[] = $gr->firstname.' '.$gr->lastname.' '.$fcol;  // This gets the exercise number.
                $serieshitsperminute[] = $gr->hitsperminute; // Get the hits per minute value.
                $seriesprecision[] = $gr->precisionfield;  // Get the precision percentage value.
                $serieswpm[] = $gr->wpm; // Get the corrected words per minute rate.
            }
        }

        // 20200704 Added code to include mean date of completion and mean wpm.
        // 20200727 Changed from avg to mean and added code for additional statistics.
        if ($grds2 != false) {
            $mean = results::get_grades_mean($grds2);
            $median = results::get_grades_median($grds2);
            $mode = results::get_grades_mode($grds2);
            $range = results::get_grades_range($grds2);

            $stil = 'background-color: '.(get_config('mod_mootyper', 'textbgc')).';';
            // Print blank table row.
            echo '<tr align="center" style="border-top-style: solid;">
                <td></td><td></td><td></td><td></td><td></td>
                <td></td><td></td><td></td><td></td></tr>';

            // Print means.
            echo '<tr align="center" style="border-top-style: solid;'.$stil.'">
                <td><strong>'.get_string('mean', 'mootyper').': </strong></td><td></td>
                <td>'.$mean['mistakes'].'</td>
                <td>'.format_time($mean['timeinseconds']).'</td>
                <td>'.format_float($mean['hitsperminute']).'</td>
                <td>'.$mean['fullhits'].'</td>
                <td>'.format_float($mean['precisionfield']).'%</td>
                <td>'.date(get_config('mod_mootyper', 'dateformat'), $mean['timetaken']).'</td>
                <td>'.format_float($mean['wpm']).'</td>
                <td></td>
                </tr>';

            // Print medians.
            echo '<tr align="center" style="border-top-style: solid;'.$stil.'">
                <td><strong>'.get_string('median', 'mootyper').': </strong></td><td></td>
                <td>'.$median['mistakes'].'</td>
                <td>'.format_time($median['timeinseconds']).'</td>
                <td>'.format_float($median['hitsperminute']).'</td>
                <td>'.$median['fullhits'].'</td>
                <td>'.format_float($median['precisionfield']).'%</td>
                <td>'.date(get_config('mod_mootyper', 'dateformat'), $median['timetaken']).'</td>
                <td>'.format_float($median['wpm']).'</td>
                <td></td>
                </tr>';

/*            // Print modes.
            echo '<tr align="center" style="border-top-style: solid;'.$stil.'">
                <td><strong>'.get_string('mode', 'mootyper').': </strong></td><td></td>
                <td>'.$mode['mistakes'].'</td>
                <td>'.format_time($mode['timeinseconds']).'</td>
                <td>'.format_float($mode['hitsperminute']).'</td>
                <td>'.$mode['fullhits'].'</td>
                <td>'.format_float($mode['precisionfield']).'%</td>
                <td>'.date(get_config('mod_mootyper', 'dateformat'), $mode['timetaken']).'</td>
                <td>'.format_float($mode['wpm']).'</td>
                <td></td>
                </tr>';
*/
            // Print modes.
            echo '<tr align="center" style="border-top-style: solid;'.$stil.'">
                <td><strong>'.get_string('mode', 'mootyper').': </strong></td><td></td>
                <td>'.$mode['mistakes'].'</td>
                <td>'.$mode['timeinseconds'].'</td>
                <td>'.$mode['hitsperminute'].'</td>
                <td>'.$mode['fullhits'].'</td>
                <td>'.$mode['precisionfield'].'</td>
                <td>'.$mode['timetaken'].'</td>
                <td>'.$mode['wpm'].'</td>
                <td></td>
                </tr>';


            // Print ranges.
            echo '<tr align="center" style="border-top-style: solid;'.$stil.'">
                <td><strong>'.get_string('range', 'mootyper').': </strong></td><td></td>
                <td>'.$range['mistakes'].'</td>
                <td>'.format_time($range['timeinseconds']).'</td>
                <td>'.format_float($range['hitsperminute']).'</td>
                <td>'.$range['fullhits'].'</td>
                <td>'.format_float($range['precisionfield']).'%</td>
                <td>'.$range['timetaken'].'</td>
                <td>'.format_float($range['wpm']).'</td>
                <td></td>
                </tr>';
            echo '</table>';
        }
    } else {
        echo get_string('nogrades', 'mootyper');
        $labels = null;
    }
}

echo '</table><br>';

// 20200413 Added a return button. 20200428 Added round corners.
echo '<a href="'.$CFG->wwwroot . '/mod/mootyper/view.php?id='.$id
    .'"class="btn btn-primary" style="border-radius: 8px">'
    .get_string('returnto', 'mootyper', $mootyper->name)
    .'</a>';

// 20200103 Changed to button. 20200428 Added round corners.
// Create link for export and pass mode, lesson name, and required goal to csvexport file.
echo ' <a href="'.$CFG->wwwroot.'/mod/mootyper/csvexport.php?mootyperid='.$mootyper->id
    .'&id='.$id
    .'&coursename='.$course->fullname
    .'&mtname='.$mootyper->name
    .'&isexam='.$mootyper->isexam
    .'&lsnname='.$lsnname->lessonname
    .'&timelimit='.$mootyper->timelimit
    .'&requiredgoal='.$mootyper->requiredgoal
    .'&requiredwpm='.$mootyper->requiredwpm
    .'"class="btn btn-secondary" style="border-radius: 8px">'.get_string('csvexport', 'mootyper')
    .'</a>';

echo '</form>';
echo '</div><br>';

// 20200624 Must have data in $labels, must have grades in $grds, and branch 32 or higher, to graph anything.
if (($labels) && ($grds != false) && ($CFG->branch > 31)) {
    // There was data selected so create the info the api needs passed to it for each series we want to chart.
    $serie1 = new core\chart_series(get_string('hitsperminute', 'mootyper'), $serieshitsperminute);
    $serie2 = new core\chart_series(get_string('precision', 'mootyper'), $seriesprecision);
    $serie3 = new core\chart_series(get_string('wpm', 'mootyper'), $serieswpm);

    $chart = new core\chart_bar();  // Tell the api we want a bar chart.
    $chart->set_horizontal(true); // Calling set_horizontal() passing true as parameter will display horizontal bar charts.
    $chart->set_title(get_string('charttitleallgrades', 'mootyper')); // Tell the api what we want for a the chart title.
    $chart->add_series($serie1);  // Pass the hits per minute data to the api.
    $chart->add_series($serie2);  // Pass the precision data to the api.
    $chart->add_series($serie3);  // Pass the words per minute data to the api.
    $chart->set_labels($labels);  // Pass the exercise number data to the api.
    $chart->get_xaxis(0, true)->set_label(get_string('xaxislabel', 'mootyper'));  // Pass a label to add to the x-axis.
    $chart->get_yaxis(0, true)->set_label(get_string('fexercise', 'mootyper')); // Pass the label to add to the y-axis.
    echo $OUTPUT->render($chart); // Draw the chart on the output page.
}
echo $OUTPUT->footer();
