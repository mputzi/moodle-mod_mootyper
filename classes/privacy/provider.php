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
 * Privacy Subsystem implementation for mod_mootyper.
 * 5/18/18 this is giving context for everything.
 * Now giving an output if I have something in attempts, grades, lessons, and exercises.
 * Now have nicely formatted separate output for each grade with it's attempt info.
 * Using strings. May need some new ones.
 * This is closest yet.1955 hours, May19. Problem with duplicate timetaken
 * in attempts and grades.
 *
 * @package    mod_mootyper
 * @copyright  2016 AL Rachels drachels@drachels.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_mootyper\privacy;
defined('MOODLE_INTERNAL') || die();

use context;
use context_helper;
use context_module;
use core_privacy\local\metadata\collection;
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\contextlist;
use core_privacy\local\request\deletion_criteria;
use core_privacy\local\request\helper;
use core_privacy\local\request\transform;
use core_privacy\local\request\writer;

require_once($CFG->dirroot . '/mod/mootyper/lib.php');

/**
 * Data provider class.
 *
 * @package    mod_mootyper
 * @copyright  2018 AL Rachels
 * @author     AL Rachels <drachels@drachels.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider implements
    \core_privacy\local\metadata\provider,
    \core_privacy\local\request\plugin\provider {

    /**
     * Return metadata.
     *
     * @param collection $collection The initialised collection to add items to.
     * @return collection The updated collection of metadata items.
     */
    public static function get_metadata(collection $collection) : collection {
        $collection->add_database_table('mootyper_attempts', [
            'mootyperid' => 'privacy:metadata:mootyper_attempts:mootyperid',
            'userid' => 'privacy:metadata:mootyper_attempts:userid',
            'timetaken' => 'privacy:metadata:mootyper_attempts:timetaken',
            'inprogress' => 'privacy:metadata:mootyper_attempts:inprogress',
            'suspicion' => 'privacy:metadata:mootyper_attempts:suspicion',
        ], 'privacy:metadata:mootyper_attempts');

        $collection->add_database_table('mootyper_grades', [
            'mootyper' => 'privacy:metadata:mootyper_grades:mootyper',
            'userid' => 'privacy:metadata:mootyper_grades:userid',
            'grade' => 'privacy:metadata:mootyper_grades:grade',
            'mistakes' => 'privacy:metadata:mootyper_grades:mistakes',
            'timeinseconds' => 'privacy:metadata:mootyper_grades:timeinseconds',
            'hitsperminute' => 'privacy:metadata:mootyper_grades:hitsperminute',
            'fullhits' => 'privacy:metadata:mootyper_grades:fullhits',
            'precisionfield' => 'privacy:metadata:mootyper_grades:precisionfield',
            'timetaken' => 'privacy:metadata:mootyper_grades:timetaken',
            'exercise' => 'privacy:metadata:mootyper_grades:exercise',
            'pass' => 'privacy:metadata:mootyper_grades:pass',
            'attemptid' => 'privacy:metadata:mootyper_grades:attemptid',
            'wpm' => 'privacy:metadata:mootyper_grades:wpm',
        ], 'privacy:metadata:mootyper_grades');

        $collection->add_database_table('mootyper_lessons', [
            'lessonname' => 'privacy:metadata:mootyper_lessons:lessonname',
            'authorid' => 'privacy:metadata:mootyper_lessons:authorid',
            'visible' => 'privacy:metadata:mootyper_lessons:visible',
            'editable' => 'privacy:metadata:mootyper_lessons:editable',
            'courseid' => 'privacy:metadata:mootyper_lessons:courseid',
        ], 'privacy:metadata:mootyper_lessons');

        $collection->add_database_table('mootyper_exercises', [
                'texttotype' => 'privacy:metadata:mootyper_exercises:texttotype',
                'exercisename' => 'privacy:metadata:mootyper_exercises:exercisename',
                'lesson' => 'privacy:metadata:mootyper_exercises:lesson',
                'snumber' => 'privacy:metadata:mootyper_exercises:snumber',
             ], 'privacy:metadata:mootyper_exercises');

        return $collection;
    }

    /**
     * Get the list of contexts that contain user information for the specified user.
     *
     * @param int $userid The user to search for.
     * @return contextlist $contextlist The contextlist containing the list of contexts used in this plugin.
     */
    public static function get_contexts_for_userid(int $userid) : \core_privacy\local\request\contextlist {
        $contextlist = new contextlist();

        // Fetch all mootyper content.
        $sql = "SELECT c.id
                  FROM {context} c
                  JOIN {course_modules} cm ON cm.id = c.instanceid AND c.contextlevel = :contextlevel
                  JOIN {modules} m ON m.id = cm.module and m.name = :modname
                  JOIN {mootyper} mt ON mt.id = cm.instance
                  JOIN {mootyper_grades} mtg ON mtg.mootyper = mt.id
             LEFT JOIN {mootyper_attempts} mta ON mta.mootyperid = mt.id
                 WHERE (mta.userid = :userid1 AND mtg.userid = :userid2)";

        $params = [
            'modname' => 'mootyper',
            'contextlevel' => CONTEXT_MODULE,
            'userid1' => $userid,
            'userid2' => $userid,
        ];

        $contextlist->add_from_sql($sql, $params);

        return $contextlist;
    }

    /**
     * Export personal data for the given approved_contextlist. User and context information is contained within the contextlist.
     *
     * @param approved_contextlist $contextlist a list of contexts approved for export.
     */
    public static function export_user_data(approved_contextlist $contextlist) {
        global $DB;

        if (empty($contextlist->count())) {
            return;
        }

        $user = $contextlist->get_user();

        list($contextsql, $contextparams) = $DB->get_in_or_equal($contextlist->get_contextids(), SQL_PARAMS_NAMED);

        $sql = "SELECT cm.id AS cmid,
                       mg.mootyper as mootyper,
                       mg.userid as userid,
                       mg.grade as grade,
                       mg.mistakes as mistakes,
                       mg.timeinseconds as timeinseconds,
                       mg.hitsperminute as hitsperminute,
                       mg.fullhits as fullhits,
                       mg.precisionfield as precisionfield,
                       mg.timetaken as mgtimetaken,
                       mg.exercise as exercise,
                       mg.pass as pass,
                       mg.attemptid as attemptid,
                       mg.wpm as wpm,
                       ma.id as maid,
                       ma.mootyperid as mamootyperid,
                       ma.userid as mauserid,
                       ma.timetaken as matimetaken,
                       ma.inprogress as mainprogress,
                       ma.suspicion as masuspicion
                  FROM {context} c
            INNER JOIN {course_modules} cm ON cm.id = c.instanceid
            INNER JOIN {mootyper} mt ON mt.id = cm.instance
             LEFT JOIN {mootyper_grades} mg ON mg.mootyper = cm.instance
             LEFT JOIN {mootyper_attempts} ma ON ma.mootyperid = mt.id AND mg.attemptid = ma.id
                 WHERE c.id {$contextsql}
                       AND mg.userid = :userid
              ORDER BY cm.id ASC, mg.timetaken ASC";

        $params = ['userid' => $user->id] + $contextparams;
        $mootypercontents = $DB->get_recordset_sql($sql, $params);

        // Reference to the mootyper activity seen in the last iteration of the loop.
        // By comparing this with the current record, and because we know the results
        // are ordered, we know when we've moved to the contents for a new mootyper
        // activity and therefore when we can export the complete data for the last activity.
        $lastcmid = null;

        $mootyperdata = [];

        foreach ($mootypercontents as $record) {
            $mootypergrades = format_string($record->mootyper);
            $path = array_merge([get_string('modulename', 'mod_mootyper'), $mootypergrades . " ({$record->mootyper})"]);
            // If we've moved to a new mootyper, then write the last mootyper data and reinit the mootyper data array.
            if (!is_null($lastcmid)) {
                if ($lastcmid != $record->cmid) {
                    if (!empty($mootyperdata)) {
                        $context = \context_module::instance($lastcmid);
                        self::export_mootyper_data_for_user($mootyperdata, $context, [], $user);
                        $mootyperdata = [];
                    }
                }
            }
            $lastcmid = $record->cmid;
            $context = \context_module::instance($lastcmid);

            $mootyperdata['mootyper'][] = [
                'mootyper' => $record->mootyper,
                'userid' => $record->userid,
                // 'grade' => $record->grade,
                'mistakes' => $record->mistakes,
                'timeinseconds' => format_time($record->timeinseconds),
                'hitsperminute' => $record->hitsperminute,
                'fullhits' => $record->fullhits,
                'precision' => $record->precisionfield .' %',
                'mgtimetaken' => transform::datetime($record->mgtimetaken),
                'exercise' => $record->exercise,
                'pass' => transform::yesno($record->pass),
                'attemptid' => $record->attemptid,
                'wpm' => $record->wpm,
                'attempt id' => $record->maid,
                'mootyper id' => $record->mamootyperid,
                'mauserid' => $record->mauserid,
                'matimetaken' => transform::datetime($record->matimetaken),
                'inprogress' => transform::yesno($record->mainprogress),
                'suspicion' => transform::yesno($record->masuspicion)
            ];
        }

        $mootypercontents->close();

        // The data for the last activity won't have been written yet, so make sure to write it now!
        if (!empty($mootyperdata)) {
            $context = \context_module::instance($lastcmid);
            self::export_mootyper_data_for_user($mootyperdata, $context, [], $user);
        }
    }

    /**
     * Export the supplied personal data for a single mootyper activity, along with any generic data or area files.
     *
     * @param array $mootyperdata the personal data to export for the mootyper.
     * @param \context_module $context the context of the mootyper.
     * @param \stdClass $user the user record
     */
    protected static function export_mootyper_data_for_user(array $mootyperdata, \context_module $context,
                                                            array $subcontext, \stdClass $user) {

        // Fetch the generic module data for the mootyper.
        $contextdata = helper::get_context_data($context, $user);

        // Merge with mootyper data and write it.
        $contextdata = (object)array_merge((array)$contextdata, $mootyperdata);
        writer::with_context($context)->export_data($subcontext, $contextdata);

        // Write generic module intro files.
        helper::export_context_files($context, $user);
    }

    /**
     * Delete all data for all users in the specified context.
     *
     * @param \context $context the context to delete in.
     */
    public static function delete_data_for_all_users_in_context(\context $context) {
        global $DB;

        if (empty($context)) {
            return;
        }
        $instanceid = $DB->get_field('course_modules', 'instance', ['id' => $context->instanceid], MUST_EXIST);
        $DB->delete_records('mootyper_grades', ['mootyper' => $instanceid]);
        $DB->delete_records('mootyper_attempts', ['mootyper' => $instanceid]);
    }

    /**
     * Delete all user data for the specified user, in the specified contexts.
     *
     * @param approved_contextlist $contextlist a list of contexts approved for deletion.
     */
    public static function delete_data_for_user(approved_contextlist $contextlist) {
        global $DB;

        if (empty($contextlist->count())) {
            return;
        }

        $userid = $contextlist->get_user()->id;
        foreach ($contextlist->get_contexts() as $context) {
            $instanceid = $DB->get_field('course_modules', 'instance', ['id' => $context->instanceid], MUST_EXIST);
            $DB->delete_records('mootyper_grades', ['mootyper' => $instanceid, 'userid' => $userid]);
            $DB->delete_records('mootyper_attempts', ['mootyperid' => $instanceid, 'userid' => $userid]);
        }
    }
}
