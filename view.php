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
 * Prints a particular instance of mootyper
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod_mootyper
 * @copyright  2012 Jaka Luthar (jaka.luthar@gmail.com)
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

use \mod_mootyper\event\course_module_viewed;

// Changed to this newer format 03/01/2019.
require(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/lib.php');
require_once(__DIR__ . '/locallib.php');

global $USER, $CFG, $THEME;

$id = optional_param('id', 0, PARAM_INT); // Course_module ID, or
$n = optional_param('n', 0, PARAM_INT); // Mootyper instance ID - it should be named as the first character of the module.
// Get the current "thisdirection" string from the langconfig.php file, so that
// the status bar and any dual keyboard layouts render correctly.
$directionality = get_string('thisdirection', 'langconfig');
$userpassword = optional_param('userpassword', '', PARAM_RAW);
$backtocourse = optional_param('backtocourse', false, PARAM_RAW);
/*
 Upon first time entry to the activity, $id is available and used while
 $n is not available and is set to 0. When returning to the activity
 after completing an exercise, $id is unavailable and gets set to 0,
 and $n is available from gcnext.php and is used instead.
*/
if ($id) {
    $cm = get_coursemodule_from_id('mootyper', $id, 0, false, MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $cm->course) , '*', MUST_EXIST);
    $mootyper = $DB->get_record('mootyper', array('id' => $cm->instance) , '*', MUST_EXIST);
} else if ($n) {
    $mootyper = $DB->get_record('mootyper', array('id' => $n), '*', MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $mootyper->course), '*', MUST_EXIST);
    $cm = get_coursemodule_from_instance('mootyper', $mootyper->id, $course->id, false, MUST_EXIST);
} else {
    print_error(get_string('mootypererror', 'mootyper'));
}

$mtmode = $mootyper->isexam;

require_login($course, true, $cm);

if ($backtocourse) {
    redirect(new moodle_url('/course/view.php', array('id' => $course->id)));
}

// Following two lines are for View, Automatic Completion marking. 20180630.
$completion = new completion_info($course);
$completion->set_module_viewed($cm);

// I have moved set_title and set_heading to renederer.php.
$PAGE->set_url('/mod/mootyper/view.php', array('id' => $cm->id));

$context = context_module::instance($cm->id);

$mootyperoutput = $PAGE->get_renderer('mod_mootyper');

// Output starts here.
echo $mootyperoutput->header($mootyper, $cm);
echo '<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>';

// Get the color and text alignment configuration settings and use them in the MooTyper activity.
$color1 = $mootyper->statsbgc;
$color2 = $mootyper->keytopbgc;
$color3 = $mootyper->keybdbgc;
$color4 = $mootyper->cursorcolor;
$color5 = $mootyper->textbgc;
$color6 = $mootyper->texterrorcolor;
$mistakessetting = $mootyper->countmistakes;

$gettextalign = $mootyper->textalign;
$aligns = array(get_string('defaulttextalign_left', 'mod_mootyper'),
              get_string('defaulttextalign_center', 'mod_mootyper'),
              get_string('defaulttextalign_right', 'mod_mootyper'));
foreach ($aligns as $akey => $aval) {
    if ($akey == $gettextalign) {
        $textalign = $aval;
    }
}
// Apply colors and text alignment to current MooTyper.
echo '<style>
    .keyboardback {
        background-color: '.$color3.';
        box-shadow:
            0 1px 0 #aaa,
            0 4px 0 #bbb,
            0 5px 0px #ddd;
    }
    .keyboardpadback {
        background-color: '.$color3.';
        box-shadow:
            0 1px 0 #aaa,
            0 4px 0 #bbb,
            0 5px 0px #ddd;
    }
    .keypadback {
        background-color: '.$color3.';
        box-shadow:
            0 1px 0 #aaa,
            0 4px 0 #bbb,
            0 5px 0px #ddd;
    }
    .normal {
        background: '.$color2.'
    }
    div#statsLDiv {
        background: '.$color1.'
    }
    div#statsMDiv {
        background: '.$color1.'
    }
    div#statsRDiv {
        background: '.$color1.'
    }
    #texttoenter {
        text-align: '.$textalign.';
        line-height: 1.2;
        background-color: '.$color5.';
        padding:0px 10px 0px 10px;
        border: 1px solid black;
        box-shadow:
            0 1px 0 #aaa,
            0 4px 0 #bbb,
            0 5px 0px #ddd;
        border-top-left-radius: 8px ;
        border-bottom-left-radius: 8px ;
    }
    .tb1 {
        text-align: '.$textalign.'
    }
    .txtBlue {
        background-color: '.$color4.'
    }
    .txtRed {
        background-color: '.$color6.'
    }
    </style>';


if ($mootyper->intro) {
    echo $OUTPUT->box(format_module_intro('mootyper', $mootyper, $cm->id) , 'generalbox mod_introbox', 'mootyperintro');
}
if ($mootyper->lesson != null) {
    // Availability restrictions applied to students only.
    if ((!(is_available($mootyper)))&& (!(has_capability('mod/mootyper:viewgrades', $context)))) {
        if ($mootyper->timeclose != 0 && time() > $mootyper->timeclose) {
            echo $mootyperoutput->mootyper_inaccessible(get_string('mootyperclosed', 'mootyper', userdate($mootyper->timeclose)));
        } else {
            echo $mootyperoutput->mootyper_inaccessible(get_string('mootyperopen', 'mootyper', userdate($mootyper->timeopen)));
        }
        echo $OUTPUT->footer();
        exit();
    } else if ($mootyper->usepassword && empty($USER->mootyperloggedin[$mootyper->id])) { // Password protected mootyper code.
        $correctpass = false;
        if (!empty($userpassword) && (($mootyper->password == md5(trim($userpassword))) ||
            ($mootyper->password == trim($userpassword)))) {
            require_sesskey();
            // With or without md5 for backward compatibility (MDL-11090).
            $correctpass = true;
            $USER->mootyperloggedin[$mootyper->id] = true;

        } else if (isset($mootyper->extrapasswords)) {

            // Group overrides may have additional passwords.
            foreach ($mootyper->extrapasswords as $password) {
                if (strcmp($password, md5(trim($userpassword))) === 0 || strcmp($password, trim($userpassword)) === 0) {
                    require_sesskey();
                    $correctpass = true;
                    $USER->mootyperloggedin[$mootyper->id] = true;
                }
            }
        }
        if (!$correctpass) {
            echo $mootyperoutput->login_prompt($mootyper, $userpassword !== '');
            echo $mootyperoutput->footer();
            exit();
        }
    }

    $reqiredgoal = $mootyper->requiredgoal;
    $reqiredwpm = $mootyper->requiredwpm;
    $exercise = get_exercise_from_mootyper($mootyper->id, $mootyper->lesson, $USER->id);
    // 5/20/2019 Get the lesson name.
    $lsnname = $DB->get_record('mootyper_lessons', array('id' => $mootyper->lesson), '*', MUST_EXIST);

    if ($exercise != false) {
        $exerciseid = $exercise->id;
        $texttoenter = $exercise->texttotype;
    }

    // Need to get count of exercises in the current lesson.
    $sqlc = "SELECT COUNT(mte.texttotype)
        FROM {mootyper_lessons} mtl
        LEFT JOIN {mootyper_exercises} mte
        ON mte.lesson =  mtl.id
        WHERE mtl.id = $mootyper->lesson";

    $count = $DB->count_records_sql($sqlc, $params = null);

    if (isset($texttoenter)) {
        // 11/30/19 Modified to pass data required by exercise_, exam_, and lesson_completed events.
        $insertdir = $CFG->wwwroot . '/mod/mootyper/gcnext.php?cmid='.$cm->id.
            '&lsnname='.$lsnname->id.'&exercisename='.$exercise->exercisename.'&mtmode='.$mtmode.'&count='.$count;
    }

    if (exam_already_done($mootyper, $USER->id) && $mtmode === '1') {
        echo get_string('examdone', 'mootyper');
        echo "<br>";
        if (has_capability('mod/mootyper:viewgrades', context_module::instance($cm->id))) {
            $jlnk4 = $CFG->wwwroot . '/mod/mootyper/gview.php?id=' . $id . '&n=' . $mootyper->id;
            echo '<a href="' . $jlnk4 . '">' . get_string('viewgrades', 'mootyper') . '</a><br /><br />';
        }

        if (has_capability('mod/mootyper:viewmygrades', context_module::instance($cm->id))) {
            $jlnk7 = $CFG->wwwroot . "/mod/mootyper/owngrades.php?id=" . $id . "&n=" . $mootyper->id;
            echo '<a href="' . $jlnk7 . '">' . get_string('viewmygrades', 'mootyper') . '</a><br /><br />';
        }
    } else if ($exercise != false) {
        if ($mootyper->showkeyboard) {
            $displaynone = false;
        } else {
            $displaynone = true;
        }
        $keyboardjs = get_instance_layout_js_file($mootyper->layout);
        echo '<script type="text/javascript" src="' . $keyboardjs . '"></script>';
        echo '<script type="text/javascript" src="typer.js"></script>';
?>
<div id="mainDiv">
<form name='form1' id='form1' method='post' action='<?php echo $insertdir; ?>'> 
<div id="keyboard" style="float: left; text-align:center; margin-left: auto; margin-right: auto;">
<h4>
        <?php

        // Old MooTypers without the mode set need this initialized to empty.
        $tempstr = '';
        // Add label containing mode, lesson name, and exercise x of x above the status bar.
        if ($mtmode === '0') {
            $tempstr = get_string('fmode', 'mootyper')
                       .' = '
                       .get_string('flesson', 'mootyper');
        } else if ($mtmode === '1') {
            $tempstr = get_string('fmode', 'mootyper')
                       .' = '
                       .get_string('isexamtext', 'mootyper');
        } else if ($mtmode === '2') {
            $tempstr = get_string('fmode', 'mootyper')
                       .' = '
                       .get_string('practice', 'mootyper');
        }
        $tempstr = $tempstr.'&nbsp;&nbsp; '
                           .get_string('flesson', 'mootyper')
                           .'/'
                           .get_string('lsnname', 'mootyper')
                           .' = '
                           .$lsnname->lessonname
                           .'&nbsp;&nbsp; '
                           .get_string('exercise', 'mootyper', $exercise->exercisename)
                           .$count;
        echo $tempstr;
        ?>
</h4>

<div id="reportDiv">
<input name='rpCourseId' type='hidden' value='<?php
        echo $course->id; ?>'>
<input name='rpSityperId' type='hidden' value='<?php
        echo $mootyper->id; ?>'>
<input name='rpUser' type='hidden' value='<?php
        echo $USER->id; ?>'>
<input name='rpExercise' type='hidden' value='<?php
        echo $exerciseid; ?>'>
<input name='rpAttId' type='hidden' value=''>
<input name='rpFullHits' type='hidden' value=''>
<input name='rpGoal' type='hidden' value='
        <?php
        if (isset($reqiredgoal)) {
            echo $reqiredgoal;
        }
        ?>
'>

<input name='rpWPM' type='hidden' value='
        <?php
        if (isset($reqiredwpm)) {
            echo $reqiredwpm;
        }
        ?>
'>
    <input name='rpTimeInput' type='hidden'>
    <input name='rpMistakesInput' type='hidden'>
    <input name='rpAccInput' type='hidden'>
    <input name='rpSpeedInput' type='hidden'>
    <input name='rpWpmInput' type='hidden'>

        <?php

        // Set the status bar CSS based on direction of language currently in use.
        if ($directionality == 'rtl' && ($CFG->branch > 31)) {
            $stats1 = "'statsRDiv'";
            $stats2 = "'statsLDiv'";
        } else {
            $stats1 = "'statsLDiv'";
            $stats2 = "'statsRDiv'";
        }
        if (has_capability('mod/mootyper:viewgrades', context_module::instance($cm->id))) {
            $jlnk4 = $CFG->wwwroot . '/mod/mootyper/gview.php?id=' . $id . '&n=' . $mootyper->id;;
            echo '<a href="' . $jlnk4 . '" class="btn btn-primary btn-sm">' . get_string('viewgrades', 'mootyper') . '</a>&nbsp;';
        }
        if (has_capability('mod/mootyper:aftersetup', context_module::instance($cm->id))) {
            $jlnk6 = $CFG->wwwroot . "/mod/mootyper/mod_setup.php?n=" . $mootyper->id . "&e=1";
            echo '<a href="' . $jlnk6 . '" class="btn btn-primary btn-sm">' . get_string('fsettings', 'mootyper') . '</a>&nbsp;';
        }
        if (has_capability('mod/mootyper:viewmygrades', context_module::instance($cm->id))) {
            $jlnk7 = $CFG->wwwroot . "/mod/mootyper/owngrades.php?id=" . $id . "&n=" . $mootyper->id;
            echo '<a href="' . $jlnk7 . '" class="btn btn-primary btn-sm">' . get_string('viewmygrades', 'mootyper') . '</a>';
        }
        ?>

<input class="btn btn-primary btn-sm" style="visibility: hidden;" id="btnContinue" name='btnContinue' type="submit" value=<?php
        echo "'" . get_string('fcontinue', 'mootyper') . "'"; ?>> 

    <div id='wrapStats'>
                    <div id=<?php echo $stats1; ?> style='text-align: center; float: left; width:15%;'>
                        <div id="timerText" class="statsText">
                            <?php echo get_string('rtime', 'mootyper'); ?></div>
                        <div id='timer'><span id="jsTime2">00:00</span></div>
                    </div>

                    <div id='statsMDiv' style='text-align: center; float: left; width:15%;'>
                        <div id='progressText' class="statsText">
                            <?php echo get_string('rprogress', 'mootyper'); ?></div>
                        <div id='progressValue'><span id="jsProgress2">0/0</span></div>
                    </div>

                    <div id='statsMDiv' style='text-align: center; float: left; width:15%;'>
                        <div id='mistakesText' class="statsText">
                            <?php echo get_string('rmistakes', 'mootyper'); ?></div>
                        <div id='mistakesValue'><span id="jsMistakes2">0</span></div>
                    </div>

                    <div id='statsMDiv' style='text-align: center; float: left; width:15%;'>
                        <div id='precisionText' class="statsText">
                            <?php echo get_string('rprecision', 'mootyper'); ?></div>
                        <div id='precisionValue'><span id="jsAcc2">0</span><span> %</span></div>
                    </div>

                    <div id='statsMDiv' style='text-align: center; float: left; width:20%;'>
                        <div id='speedText' class="statsText">
                            <?php echo get_string('rhitspermin', 'mootyper'); ?></div>
                        <div id='speedValue'><span id="jsSpeed2">0</span></div>
                    </div>

                    <div id=<?php echo $stats2; ?> style='text-align: center; float: left; width:20%;'>
                        <div id='wpmText' class="statsText">
                            <?php echo get_string('wpm', 'mootyper'); ?></div>
                        <div id='wpmValue'><span id="jsWpm2">0 | 0</span></div>
                    </div>
    </div>
</div>

<textarea name="tb1" wrap="off" id="tb1" class="tb1" onfocus="return focusSet(event)"  
    onpaste="return false" onselectstart="return false"
    onCopy="return false" onCut="return false" 
    onDrag="return false" onDrop="return false" autocomplete="off">
    <?php
        echo get_string('chere', 'mootyper') . '...';
    ?>
</textarea>
<div style="float: left; padding-bottom: 10px;" id="texttoenter"></div><br />
        <?php
        if ($mootyper->showkeyboard) {
            $displaynone = false;
        } else {
            $displaynone = true;
        }
        $keyboard = get_instance_layout_file($mootyper->layout);
        include($keyboard);
        ?>
</div>
</form>
</div>
        <?php
        $texttoinit = '';
        for ($it = 0; $it < strlen($texttoenter); $it++) {
            if ($texttoenter[$it] == "\n") {
                $texttoinit .= '\n';
            } else if ($texttoenter[$it] == '"') {
                $texttoinit .= '\"';
            } else if ($texttoenter[$it] == "\\") {
                $texttoinit .= '\\';
            } else {
                $texttoinit .= $texttoenter[$it];
            }
        }

        $record = get_last_check($mootyper->id);
        if (is_null($record)) {
            echo '<script type="text/javascript">inittexttoenter("' . $texttoinit . '", 0, 0, 0, 0, 0, "' .
                $CFG->wwwroot . '", ' . $mootyper->showkeyboard . ', ' . $mootyper->continuoustype .
                ', '. $mootyper->countmistypedspaces . ', '. $mootyper->countmistakes . ');</script>';
        } else {
            echo '<script type="text/javascript">inittexttoenter("' . $texttoinit . '", 1, ' . $record->mistakes . ', ' .
                $record->hits . ', ' . $record->timetaken . ', ' . $record->attemptid . ', "' . $CFG->wwwroot . '", ' .
                $mootyper->showkeyboard . ', ' . $mootyper->continuoustype .', '. $mootyper->countmistypedspaces .
                ', '. $mootyper->countmistakes . ');</script>';
        }
    } else {
        echo get_string('endlesson', 'mootyper');
        echo "<br />";
        if (has_capability('mod/mootyper:viewgrades', context_module::instance($cm->id))) {
            $jlnk4 = $CFG->wwwroot . '/mod/mootyper/gview.php?id=' . $id . '&n=' . $mootyper->id;
            echo '<a href="' . $jlnk4 . '">' . get_string('viewgrades', 'mootyper') . '</a><br /><br />';
        }
        if (has_capability('mod/mootyper:viewmygrades', context_module::instance($cm->id))) {
            $jlnk7 = $CFG->wwwroot . "/mod/mootyper/owngrades.php?id=" . $id . "&n=" . $mootyper->id;
            echo '<a href="' . $jlnk7 . '">' . get_string('viewmygrades', 'mootyper') . '</a><br /><br />';
        }
    }

} else {
    if (has_capability('mod/mootyper:setup', context_module::instance($cm->id))) {
        $valnk = $CFG->wwwroot . "/mod/mootyper/mod_setup.php?n=" . $mootyper->id;
        echo '<a href="' . $valnk . '">' . get_string('fsetup', 'mootyper') . '</a>';
    } else {
        echo get_string('notreadyyet', 'mootyper');
    }
}

// Trigger module viewed event.
$params = array('objectid' => $mootyper->id,
    'context' => $context
);
$event = course_module_viewed::create($params);
$event->add_record_snapshot('course', $course);
$event->add_record_snapshot('mootyper', $mootyper);
$event->trigger();

echo $mootyperoutput->footer();
