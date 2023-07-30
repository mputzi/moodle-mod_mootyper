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
 * Custom completion - Used in Moodle 3.11 and above (ignored by earlier versions).
 *
 * @package   mod_mootyper
 * @copyright 2021 AL Rachels <drachels@drachels.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

declare(strict_types=1);

namespace mod_mootyper\completion;

use core_completion\activity_custom_completion;
use \mod_mootyper\local\lessons;
use \mod_mootyper\local\results;

/**
 * Activity custom completion subclass for the MooTyper activity.
 *
 * Class for defining mod_mootyper's custom completion rules and fetching the completion statuses
 * of the custom completion rules for a given mootyper instance and a user.
 *
 * @package mod_mootyper
 */
class custom_completion extends activity_custom_completion {

    /**
     * Get the completion state for a given completion rule.
     *
     * @param string $rule The completion rule.
     * @return int The completion state.
     */
    public function get_state(string $rule): int {
        global $DB;

        //$debug = array();
        //$debug['Entering get_state(string $rule): '] = $rule;

        // Will need to do a final precision check and return the 'completionprecision' message only when final precision passes.
        // Will need to do a final WPM check and return the 'completionwpm' message only when final precision passes.
        // Will need to do a final exercises check and return the 'completionexercise' message only when final exercises passes.
        // Will need to do a lesson completion check and return the 'completionlesson' message only when all precision, wpm, and exercises are done AND each combined result status is passed.

        $this->validate_rule($rule);

        $userid = $this->userid;
        $mootyperid = $this->cm->instance;

        //$debug['The current $userid is: '] = $userid;
        //$debug['The current $mootyperid is: '] = $mootyperid;
        //if (!$mootyper = $DB->get_record('mootyper', ['id' => $this->cm->instance])) {
        if (!$mootyper = $DB->get_record('mootyper', ['id' => $mootyperid])) {
            throw new moodle_exception(get_string('incorrectmodule', 'mootyper'));
        }

        $status = COMPLETION_INCOMPLETE;

        $finalexercisecompleteparams = ['userid' => $userid, 'mootyper' => $mootyperid];
        $finallessoncompleteparams = ['userid' => $userid, 'mootyper' => $mootyperid];
        $finalprecisionparams = ['userid' => $userid, 'mootyper' => $mootyperid];
        $finalwpmparams = ['userid' => $userid, 'mootyper' => $mootyperid];
        $finalmootypergradeparams = ['userid' => $userid, 'mootyper' => $mootyperid];

        //$debug['The current $finalexercisecompleteparams is: '] = $finalexercisecompleteparams;
        //$debug['The current $finallessoncompleteparams is: '] = $finallessoncompleteparams;
        //$debug['The current $finalprecisionparams is: '] = $finalprecisionparams;
        //$debug['The current $finalwpmparams is: '] = $finalwpmparams;
        //$debug['The current $finalmootypergradeparams is: '] = $finalmootypergradeparams;
        //print_object($debug);

        // All the exercises of a lesson must be successfully completed before bothering to check
        // for lesson, precision, or WPM completion.
        // If the exercises are successfully completed, is the lesson sucessfully compled?
        // If the exercises and the lesson are complete, was the required precision achieved?
        // If the exercises and the lesson are complete, was the required wpm achieved?

        // Need count of exercises in current lesson and then need the count of exercise grades for the current lesson that have been passed.
        // mdl_mootyper_grades has info for mootyper, userid, exercise id, pass status
        // Need $mootyper_lesson, which is id from, mootyper activity.
        // Need count of mootyper_exercises id,  where me.lesson = mt.lesson
        // Need count of mootyper_grades where mtg.exercise = mte.id AND mtg.pass =1
        $exercisecountforthislesson = count(lessons::get_exercises_by_lesson($mootyper->lesson));
        //$debug['The current $mootyper->lesson is: '] = $mootyper->lesson;
        //$debug['The current $exercisecountforthislesson is: '] = $exercisecountforthislesson;
/*
        $finalexercisecompletesql = "SELECT COUNT(mte.id)
                                   FROM {mootyper_exercises} mte
                                     JOIN {mootyper_lessons} mtl
                                     JOIN {mootyper_grades} mtg
                                     JOIN {mootyper} mt
                                    WHERE mte.lesson = mtl.id
                                      AND mte.lesson = mt.lesson
                                      AND mt.id = mtg.mootyper
                                      AND mtg.userid = :userid";

*/
        $finalexercisecompletesql = "SELECT mtl.id,
                                            mtl.lessonname,
                                            mt.id,
                                            mt.name,
                                            mt.requiredgoal,
                                            mt.requiredwpm,
                                            mtg.id,
                                            mtg.userid,
                                            mtg.grade,
                                            mtg.exercise,
                                            mtg.pass,
                                            mtg.precisionfield,
                                            mtg.wpm
                                       FROM {mootyper_lessons} mtl
                                       JOIN {mootyper} mt
                                       JOIN {mootyper_exercises} mte
                                       JOIN {mootyper_grades} mtg
                                      WHERE mtl.id = mt.lesson
                                        AND mt.completionlesson > 0
                                        AND mte.lesson = mt.lesson
                                        AND mt.id = mtg.mootyper
                                        AND mtg.userid = :userid
                                        AND mtg.grade > mt.grade_mootyper
                                        AND mtg.exercise = mte.id
                                        AND mtg.pass = 1
                                        AND mtg.wpm > mt.requiredwpm";

        //$debug['The current $finalexercisecompletesql SQL is: '] = $finalexercisecompletesql;

        // Need SQL that gets the final lesson completed status.
        // mdl_mootyper has lesson id, mode, requiredgoal, requiredwpm, and the four completions
        $finallessoncompletesql = "SELECT COUNT(mte.id)
                                     FROM {mootyper_exercises} mte
                                     JOIN {mootyper_lessons} mtl
                                     JOIN {mootyper_grades} mtg
                                     JOIN {mootyper} mt
                                    WHERE mte.lesson = mtl.id
                                      AND mte.lesson = mt.lesson
                                      AND mt.id = mtg.mootyper
                                      AND mtg.userid = :userid";

        //$debug['The current $finallessoncompletesql SQL is: '] = $finallessoncompletesql;

        // Need SQL that gets the final precision.
        $finalprecisionsql = "SELECT mtg.id,
                                     AVG(mtg.precisionfield),
                                     mtg.pass,
                                     mtg.mootyper,
                                     mt.requiredgoal
                                FROM {mootyper_grades} mtg
                                JOIN {mootyper} mt
                               WHERE mtg.userid = :userid
                                 AND mtg.mootyper = :mootyper
                                 AND mtg.precisionfield >= mt.requiredgoal
                                 AND mtg.pass = 1";

        //$debug['The current $finalprecisionsql SQL is: '] = $finalprecisionsql;

        // Need SQL that gets the final wpm.
        $finalwpmsql = "SELECT mtg.id,
                               AVG(mtg.wpm),
                               mtg.pass,
                               mtg.mootyper,
                               mt.requiredwpm
                          FROM {mootyper_grades} mtg
                          JOIN {mootyper} mt
                         WHERE mtg.userid = :userid
                           AND mtg.mootyper = :mootyper
                           AND mtg.wpm >= mt.requiredwpm
                           AND mtg.pass = 1";


        //$debug['The current $finalwpmsql SQL is: '] = $finalwpmsql;

        // Need SQL that gets the final MooTyper Grade.
        $finalmootypergradesql = "SELECT mtg.id,
                                         AVG(mtg.grade),
                                         mtg.pass,
                                         mtg.mootyper,
                                         mt.grade_mootyper
                                    FROM {mootyper_grades} mtg
                                    JOIN {mootyper} mt
                                   WHERE mtg.userid = :userid
                                     AND mtg.mootyper = :mootyper
                                     AND mtg.grade >= mt.grade_mootyper
                                     AND mtg.pass = 1";


        //$debug['The current $finalmootypergradesql SQL is: '] = $finalmootypergradesql;

        if ($rule == 'completionexercise') {
            // Need to get the count of, completed and passed, exercises in the mdl_mootyper_grades table for the whole lesson.
            $status = $mootyper->completionexercise <=
                $DB->count_records('mootyper_grades', ['mootyper' => $mootyperid, 'userid' => $userid, 'pass' => 1]);
                // This version is used only with the SQL from above.
                //$DB->get_field_sql($finalexercisecompletesql, $finalexercisecompleteparams);

                //$debug['The current completionexercise $status is: '] = $status;

        } else if ($rule == 'completionlesson') {
            // Fetch the completion status of the custom completionlesson rule.

            $status = $mootyper->completionlesson <=
                $DB->count_records('mootyper_grades', ['mootyper' => $mootyperid, 'userid' => $userid, 'pass' => 1]);

            //$debug['The current completionlesson $status is: '] = $status;

            //[$ticked, $total] = mootyper_class::get_user_progress($mootyper->id, $userid);

        } else if ($rule == 'completionprecision') {
            // Need to get the final precision for the whole lesson.
            $status = $mootyper->completionprecision <=
                //$DB->count_records('mootyper_grades', ['mootyper' => $mootyper, 'userid' => $userid]);
                //$DB->get_field_sql($finalexercisecompletesql, $finalexercisecompleteparams);
                $DB->get_field_sql($finalprecisionsql, $finalprecisionparams);
            //$debug['The current completionlesson $status is: '] = $status;

        } else if ($rule == 'completionwpm') {
            // Need to get the final wpm for the whole lesson.
            $status = $mootyper->completionwpm <=
            //    $DB->count_records('mootyper_grades', ['mootyper' => $mootyper, 'userid' => $userid]);
                //$DB->get_field_sql($finalexercisecompletesql, $finalexercisecompleteparams);
                $DB->get_field_sql($finalwpmsql, $finalwpmparams);
            //$debug['The current completionwpm $status is: '] = $status;

        } else if ($rule == 'completionmootypergrade') {
            // Need to get the final mootyper grade for the whole lesson.
            $status = $mootyper->completionmootypergrade <=
            //    $DB->count_records('mootyper_grades', ['mootyper' => $mootyper, 'userid' => $userid]);
                //$DB->get_field_sql($finalexercisecompletesql, $finalexercisecompleteparams);
                $DB->get_field_sql($finalmootypergradesql, $finalmootypergradeparams);
            //$debug['The current completionmootypergrade $status is: '] = $status;
        }

        $debug['The current completion $status ? COMPLETION_COMPLETE : COMPLETION_INCOMPLETE is: '] = $status ? COMPLETION_COMPLETE : COMPLETION_INCOMPLETE;


//print_object('==================================================================================');
//print_object($debug);
//print_object('**********************************************************************************');
//die;
        // I think I am going to need to add some code here for receive a passing grade to make sure it is NOT done until the lesson is complete.
        // I am not sure if I can track what is set for the Rating so that all the above tracks average, count, max, min, or sum.
        return $status ? COMPLETION_COMPLETE : COMPLETION_INCOMPLETE;

    }

    /**
     * Fetch the list of custom completion rules that this module defines.
     *
     * @return array
     */
    public static function get_defined_custom_rules(): array {
        // This function gets called upon after completing the first exercise.
        return [
            'completionexercise',
            'completionlesson',
            'completionprecision',
            'completionwpm',
            'completionmootypergrade',
        ];
    }

    /**
     * Returns an associative array of the descriptions of custom completion rules.
     *
     * @return array
     */
    public function get_custom_rule_descriptions(): array {
        $completionexercise = $this->cm->customdata['customcompletionrules']['completionexercise'] ?? 0;
        $completionlesson = $this->cm->customdata['customcompletionrules']['completionlesson'] ?? 0;
        $completionprecision = $this->cm->customdata['customcompletionrules']['completionprecision'] ?? 0;
        $completionwpm = $this->cm->customdata['customcompletionrules']['completionwpm'] ?? 0;
        $completionmootypergrade = $this->cm->customdata['customcompletionrules']['completionmootypergrade'] ?? 0;
        return [
            'completionexercise' => get_string('completiondetail:exercise', 'mootyper', $completionexercise),
            'completionlesson' => get_string('completiondetail:lesson', 'mootyper', $completionlesson),
            'completionprecision' => get_string('completiondetail:precision', 'mootyper', $completionprecision),
            'completionwpm' => get_string('completiondetail:wpm', 'mootyper', $completionwpm),
            'completionmootypergrade' => get_string('completiondetail:mootypergrade', 'mootyper', $completionmootypergrade),
        ];
    }

    /**
     * Returns an array of all completion rules, in the order they should be displayed to users.
     *
     * @return array
     */
    public function get_sort_order(): array {
        return [
            'completionview',
            'completionexercise',
            'completionlesson',
            'completionprecision',
            'completionwpm',
            'completionmootypergrade',
            'completionusegrade',
            'completionpassgrade',
        ];
    }
}
