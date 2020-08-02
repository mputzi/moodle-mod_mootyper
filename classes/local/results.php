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
 * Keyboard utilities for MooTyper.
 *
 * 3/19/2020 Moved these functions from locallib.php to here.
 *
 * @package    mod_mootyper
 * @copyright  AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace mod_mootyper\local;
defined('MOODLE_INTERNAL') || die();

/**
 * Utility class for MooTyper results.
 *
 * @package    mod_mootyper
 * @copyright  AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class results  {


    /**
     * Get the last keystroke and check if correct.
     * @param int $mid
     */
    public static function get_last_check($mid) {
        global $USER, $DB, $CFG;
        $sql = "SELECT * FROM ".$CFG->prefix."mootyper_checks".
               " JOIN ".$CFG->prefix."mootyper_attempts ON "
                       .$CFG->prefix."mootyper_attempts.id = "
                       .$CFG->prefix."mootyper_checks.attemptid".
               " WHERE ".$CFG->prefix."mootyper_attempts.mootyperid = "
                        .$mid." AND "
                        .$CFG->prefix."mootyper_attempts.userid = "
                        .$USER->id.
               " AND ".$CFG->prefix."mootyper_attempts.inprogress = 1".
               " ORDER BY ".$CFG->prefix."mootyper_checks.checktime DESC LIMIT 1";
        if ($rec = $DB->get_record_sql($sql, array())) {
            return $rec;
        } else {
            return null;
        }
    }

    /**
     * Check for suspicous results.
     * @param int $checks
     * @param int $starttime
     * @return boolean
     */
    public static function suspicion($checks, $starttime) {
        for ($i = 1; $i < count($checks); $i++) {
            $udarci1 = $checks[$i]['mistakes'] + $checks[$i]['hits'];
            $udarci2 = $checks[($i - 1)]['mistakes'] + $checks[($i - 1)]['hits'];
            if ($udarci2 > ($udarci1 + 60)) {
                return true;
            }
            if ($checks[($i - 1)]['checktime'] > ($starttime + 300)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Calculate averages (mean).
     *
     * @param int $grades
     * @return string
     */
    public static function get_grades_avg($grades) {
        // 20200704 Added code to include average date of completion and average wpm.
        $avg = array();
        $avg['mistakes'] = 0;
        $avg['timeinseconds'] = 0;
        $avg['hitsperminute'] = 0;
        $avg['fullhits'] = 0;
        $avg['precisionfield'] = 0;
        $avg['timetaken'] = 0;
        $avg['wpm'] = 0;
        foreach ($grades as $g) {
            $avg['mistakes'] += $g->mistakes;
            $avg['timeinseconds'] += $g->timeinseconds;
            $avg['hitsperminute'] += $g->hitsperminute;
            $avg['fullhits'] += $g->fullhits;
            $avg['precisionfield'] += $g->precisionfield;
            $avg['timetaken'] += $g->timetaken;
            $avg['wpm'] += $g->wpm;
        }
        $c = count($grades);
        $avg['mistakes'] = $avg['mistakes'] / $c;
        $avg['timeinseconds'] = $avg['timeinseconds'] / $c;
        $avg['hitsperminute'] = $avg['hitsperminute'] / $c;
        $avg['fullhits'] = $avg['fullhits'] / $c;
        $avg['precisionfield'] = $avg['precisionfield'] / $c;
        $avg['timetaken'] = $avg['timetaken'] / $c;
        $avg['wpm'] = $avg['wpm'] / $c;
        // Due to limited display space, round off the results.
        $avg['mistakes'] = round($avg['mistakes'], 0);
        $avg['timeinseconds'] = round($avg['timeinseconds'], 0);
        $avg['hitsperminute'] = round($avg['hitsperminute'], 2);
        $avg['fullhits'] = round($avg['fullhits'], 0);
        $avg['precisionfield'] = round($avg['precisionfield'], 2);
        $avg['timetaken'] = round($avg['timetaken'], 0);
        $avg['wpm'] = round($avg['wpm'], 2);
        return $avg;
    }

    /**
     * Calculate mean (average).
     *
     * @param int $grades
     * @return string
     */
    public static function get_grades_mean($grades) {
        $mean = array();
        $mean['mistakes'] = 0;
        $mean['timeinseconds'] = 0;
        $mean['hitsperminute'] = 0;
        $mean['fullhits'] = 0;
        $mean['precisionfield'] = 0;
        $mean['timetaken'] = 0;
        $mean['wpm'] = 0;
        foreach ($grades as $g) {
            $mean['mistakes'] += $g->mistakes;
            $mean['timeinseconds'] += $g->timeinseconds;
            $mean['hitsperminute'] += $g->hitsperminute;
            $mean['fullhits'] += $g->fullhits;
            $mean['precisionfield'] += $g->precisionfield;
            $mean['timetaken'] += $g->timetaken;
            $mean['wpm'] += $g->wpm;
        }
        $c = count($grades);
        $mean['mistakes'] = $mean['mistakes'] / $c;
        $mean['timeinseconds'] = $mean['timeinseconds'] / $c;
        $mean['hitsperminute'] = $mean['hitsperminute'] / $c;
        $mean['fullhits'] = $mean['fullhits'] / $c;
        $mean['precisionfield'] = $mean['precisionfield'] / $c;
        $mean['timetaken'] = $mean['timetaken'] / $c;
        $mean['wpm'] = $mean['wpm'] / $c;

        // Due to limited display space, round off the results.
        $mean['mistakes'] = round($mean['mistakes'], 2);
        $mean['timeinseconds'] = round($mean['timeinseconds'], 2);
        $mean['hitsperminute'] = round($mean['hitsperminute'], 2);
        $mean['fullhits'] = round($mean['fullhits'], 0);
        $mean['precisionfield'] = round($mean['precisionfield'], 2);
        $mean['timetaken'] = round($mean['timetaken'], 0);
        $mean['wpm'] = round($mean['wpm'], 2);
        return $mean;
    }

    /**
     * Calculate median (middle).
     *
     * @param int $grades
     * @return string
     */
    public static function get_grades_median($grades) {
        $median = array();
        $mistakes = array();
        $timeinseconds = array();
        $hitsperminute = array();
        $fullhits = array();
        $precisionfield = array();
        $timetaken = array();
        $wpm = array();

        $c = count($grades);

        foreach ($grades as $g) {
            $mistakes[$c] = $g->mistakes;
            $timeinseconds[$c] = $g->timeinseconds;
            $hitsperminute[$c] = $g->hitsperminute;
            $fullhits[$c] = $g->fullhits;
            $precisionfield[$c] = $g->precisionfield;
            $timetaken[$c] = $g->timetaken;
            $wpm[$c] = $g->wpm;
            $c = $c - 1;
        }

        sort($mistakes);
        $count = count($mistakes);
        $index = floor($count / 2);
        if (!$count) {
            $total = "no values";
        } else if ($count & 1) {    // Count is odd.
            $total = $mistakes[$index];
        } else {                   // Count is even.
            $total = ($mistakes[$index - 1] + $mistakes[$index]) / 2;
        }
        $median['mistakes'] = $total;

        sort($timeinseconds);
        $count = count($timeinseconds);
        $index = floor($count / 2);
        if (!$count) {
            $total = "no values";
        } else if ($count & 1) {    // Count is odd.
            $total = $timeinseconds[$index];
        } else {                   // Count is even.
            $total = ($timeinseconds[$index - 1] + $timeinseconds[$index]) / 2;
        }
        $median['timeinseconds'] = $total;

        sort($hitsperminute);
        $count = count($hitsperminute);
        $index = floor($count / 2);
        if (!$count) {
            $total = "no values";
        } else if ($count & 1) {    // Count is odd.
            $total = $hitsperminute[$index];
        } else {                   // Count is even.
            $total = ($hitsperminute[$index - 1] + $hitsperminute[$index]) / 2;
        }
        $median['hitsperminute'] = $total;

        sort($fullhits);
        $count = count($fullhits);
        $index = floor($count / 2);
        if (!$count) {
            $total = "no values";
        } else if ($count & 1) {    // Count is odd.
            $total = $fullhits[$index];
        } else {                   // Count is even.
            $total = ($fullhits[$index - 1] + $fullhits[$index]) / 2;
        }
        $median['fullhits'] = $total;

        sort($precisionfield);
        $count = count($precisionfield);
        $index = floor($count / 2);
        if (!$count) {
            $total = "no values";
        } else if ($count & 1) {    // Count is odd.
            $total = $precisionfield[$index];
        } else {                   // Count is even.
            $total = ($precisionfield[$index - 1] + $precisionfield[$index]) / 2;
        }
        $median['precisionfield'] = $total;

        sort($timetaken);
        $count = count($timetaken);
        $index = floor($count / 2);
        if (!$count) {
            $total = "no values";
        } else if ($count & 1) {    // Count is odd.
            $total = $timetaken[$index];
        } else {                   // Count is even.
            $total = ($timetaken[$index - 1] + $timetaken[$index]) / 2;
        }
        $median['timetaken'] = $total;

        sort($wpm);
        $count = count($wpm);
        $index = floor($count / 2);
        if (!$count) {
            $total = "no values";
        } else if ($count & 1) {    // Count is odd.
            $total = $wpm[$index];
        } else {                   // Count is even.
            $total = ($wpm[$index - 1] + $wpm[$index]) / 2;
        }
        $median['wpm'] = $total;

        return $median;
    }

    /**
     * Calculate mode (item with highest count).
     *
     * @param int $grades
     * @return string
     */
    public static function get_grades_mode($grades) {
        $mode = array();
        $mistakes = array();
        $timeinseconds = array();
        $hitsperminute = array();
        $fullhits = array();
        $precisionfield = array();
        $timetaken = array();
        $wpm = array();

        $c = count($grades);

        foreach ($grades as $g) {
            $mistakes[$c] = $g->mistakes;
            $timeinseconds[$c] = $g->timeinseconds;
            $hitsperminute[$c] = $g->hitsperminute;
            $fullhits[$c] = $g->fullhits;
            $precisionfield[$c] = $g->precisionfield;
            // Convert to date and disregard the seconds so we can get
            // mode to nearest month, day, year, hour and minute.
            $timetaken[$c] = date(get_config('mod_mootyper', 'dateformat'), $g->timetaken);
            $wpm[$c] = $g->wpm;
            $c = $c - 1;
        }

        // Calculate mode for Total Mistakes.
        $v = array_count_values($mistakes);
        arsort($v);
        foreach ($v as $k => $v) {
            $total = $k;
            break;
        }
        if ($v <= 1) {
            $mode['mistakes'] = 'no mode';
        } else {
            $mode['mistakes'] = $total;
        }

        // Calculate mode for Elapsed time.
        $v = array_count_values($timeinseconds);
        arsort($v);
        foreach ($v as $k => $v) {
            $total = $k;
            break;
        }
        if ($v <= 1) {
            $mode['timeinseconds'] = 'no mode';
        } else {
            $mode['timeinseconds'] = format_time($total);
        }

        // Calculate mode for Hits Per Minute.
        $v = array_count_values($hitsperminute);
        arsort($v);
        foreach ($v as $k => $v) {
            $total = $k;
            break;
        }
        if ($v <= 1) {
            $mode['hitsperminute'] = 'no mode';
        } else {
            $mode['hitsperminute'] = format_float($total);
        }

        // Calculate mode for All Hits.
        $v = array_count_values($fullhits);
        arsort($v);
        foreach ($v as $k => $v) {
            $total = $k;
            break;
        }
        if ($v <= 1) {
            $mode['fullhits'] = 'no mode';
        } else {
            $mode['fullhits'] = $total;
        }

        // Calculate mode for Precision.
        $v = array_count_values($precisionfield);
        arsort($v);
        foreach ($v as $k => $v) {
            $total = $k;
            break;
        }
        if ($v <= 1) {
            $mode['precisionfield'] = 'no mode';
        } else {
            $mode['precisionfield'] = format_float($total).('%');
        }

        // Calculate mode for Time Completed.
        $v = array_count_values($timetaken);
        arsort($v);
        foreach ($v as $k => $v) {
            $total = $k;
            break;
        }
        if ($v <= 1) {
            $mode['timetaken'] = 'no mode';
        } else {
            //$mode['timetaken'] = date(get_config('mod_mootyper', 'dateformat'), $total);
            $mode['timetaken'] = $total;
        }

        // Calculate mode for Words Per Minute.
        $v = array_count_values($wpm);
        arsort($v);
        foreach ($v as $k => $v) {
            $total = $k;
            break;
        }
        if ($v <= 1) {
            $mode['wpm'] = 'no mode';
        } else {
            $mode['wpm'] = format_float($total);
        }

        return $mode;
    }

    /**
     * Calculate range.
     *
     * @param int $grades
     * @return string
     */
    public static function get_grades_range($grades) {
        $range = array();
        $mistakes = array();
        $timeinseconds = array();
        $hitsperminute = array();
        $fullhits = array();
        $precisionfield = array();
        $timetaken = array();
        $wpm = array();

        $c = count($grades);

        foreach ($grades as $g) {
            $mistakes[$c] = $g->mistakes;
            $timeinseconds[$c] = $g->timeinseconds;
            $hitsperminute[$c] = $g->hitsperminute;
            $fullhits[$c] = $g->fullhits;
            $precisionfield[$c] = $g->precisionfield;
            $timetaken[$c] = $g->timetaken;
            $wpm[$c] = $g->wpm;
            $c = $c - 1;
        }

        $range['mistakes'] = max($mistakes) - min($mistakes);
        $range['timeinseconds'] = max($timeinseconds) - min($timeinseconds);
        $range['hitsperminute'] = max($hitsperminute) - min($hitsperminute);
        $range['fullhits'] = max($fullhits) - min($fullhits);
        $range['precisionfield'] = max($precisionfield) - min($precisionfield);

        // Need to see about refining this a little more.
        $diff1 = (max($timetaken)) - (min($timetaken));
        $days = floor($diff1 / (60 * 60 * 24));
        $diff2 = ($diff1 - ((60 * 60 * 24) * $days));
        $hours = floor($diff2 / (60 * 60));
        $diff3 = ($diff1 - ((60 * 60 * 24) * $days) - (60 * 60 * $hours));
        $minutes = floor($diff3 / 60);
        $diff4 = ($diff1 - ((60 * 60 * 24) * $days) - (60 * 60 * $hours) - (60 * $minutes));
        $seconds = floor($diff4 / 60);
        $range['timetaken'] = $days.' d '.$hours.' h '.$minutes.' m ';

        $range['wpm'] = max($wpm) - min($wpm);

        return $range;
    }


    /**
     * Check to see if this MooTyper is available for use.
     *
     * Used in view.php.
     * @param int $mootyper
     */
    public static function is_available($mootyper) {
        $timeopen = $mootyper->timeopen;
        $timeclose = $mootyper->timeclose;
        return (($timeopen == 0 || time() >= $timeopen) && ($timeclose == 0 || time    () < $timeclose));
    }
}
