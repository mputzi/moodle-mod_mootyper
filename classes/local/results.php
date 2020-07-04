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
     * Calculate averages.
     * @param int $grades
     * @return string
     */
    public static function get_grades_avg($grades) {
        // 20200704 Added code to include average of wpm.
        $avg = array();
        $avg['mistakes'] = 0;
        $avg['timeinseconds'] = 0;
        $avg['hitsperminute'] = 0;
        $avg['fullhits'] = 0;
        $avg['precisionfield'] = 0;
        $avg['wpm'] = 0;
        foreach ($grades as $g) {
            $avg['mistakes'] += $g->mistakes;
            $avg['timeinseconds'] += $g->timeinseconds;
            $avg['hitsperminute'] += $g->hitsperminute;
            $avg['fullhits'] += $g->fullhits;
            $avg['precisionfield'] += $g->precisionfield;
            $avg['wpm'] += $g->wpm;
        }
        $c = count($grades);
        $avg['mistakes'] = $avg['mistakes'] / $c;
        $avg['timeinseconds'] = $avg['timeinseconds'] / $c;
        $avg['hitsperminute'] = $avg['hitsperminute'] / $c;
        $avg['fullhits'] = $avg['fullhits'] / $c;
        $avg['precisionfield'] = $avg['precisionfield'] / $c;
        $avg['wpm'] = $avg['wpm'] / $c;

        $avg['mistakes'] = round($avg['mistakes'], 0);
        $avg['timeinseconds'] = round($avg['timeinseconds'], 0);
        $avg['hitsperminute'] = round($avg['hitsperminute'], 2);
        $avg['fullhits'] = round($avg['fullhits'], 0);
        $avg['precisionfield'] = round($avg['precisionfield'], 2);
        $avg['wpm'] = round($avg['wpm'], 2);
        return $avg;
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
