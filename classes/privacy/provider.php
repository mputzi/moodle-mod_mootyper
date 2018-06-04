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

    // This trait must be included.
    use \core_privacy\local\legacy_polyfill;

    /**
     * Return metadata.
     *
     * @param collection $collection The initialised collection to add items to.
     * @return collection The updated collection of metadata items.
     */
    public static function _get_metadata(collection $collection) {
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

        return $collection;
    }

    /**
     * Get the list of contexts that contain user information for the specified user.
     *
     * @param int $userid The user to search for.
     * @return contextlist $contextlist The contextlist containing the list of contexts used in this plugin.
     */
    public static function _get_contexts_for_userid(int $userid) {
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
    public static function _export_user_data(approved_contextlist $contextlist) {
        global $DB;

        $user = $contextlist->get_user();
        $userid = $user->id;
        $cmids = array_reduce($contextlist->get_contexts(), function($carry, $context) {
            if ($context->contextlevel == CONTEXT_MODULE) {
                $carry[] = $context->instanceid;
            }
            return $carry;
        }, []);
        if (empty($cmids)) {
            return;
        }

        // If the context export was requested, then let's at least describe the MooTyper.
        foreach ($cmids as $cmid) {
            $context = context_module::instance($cmid);
            $contextdata = helper::get_context_data($context, $user);
            helper::export_context_files($context, $user);
            writer::with_context($context)->export_data([], $contextdata);
        }
        // Find the MooTyper IDs.
        $mootyperidstocmids = static::_get_mootyper_ids_to_cmids_from_cmids($cmids);

        list($contextsql, $contextparams) = $DB->get_in_or_equal($contextlist->get_contextids(), SQL_PARAMS_NAMED);

        // Export the grades.
        $sql = "SELECT cm.id AS cmid,
                       mg.mootyper AS mootyper,
                       mg.userid AS userid,
                       mg.grade AS grade,
                       mg.mistakes AS mistakes,
                       mg.timeinseconds AS timeinseconds,
                       mg.hitsperminute AS hitsperminute,
                       mg.fullhits AS fullhits,
                       mg.precisionfield AS precisionfield,
                       mg.timetaken AS timetaken,
                       mg.exercise AS exercise,
                       mg.pass AS pass,
                       mg.attemptid AS attemptid,
                       mg.wpm AS wpm,
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
                 WHERE (c.id {$contextsql} AND ma.userid = :userid AND ma.mootyperid = mt.id)
              ORDER BY cm.id ASC, mg.timetaken ASC";

        $params = [
            'userid' => $user->id,
        ] + $contextparams;
        $recordset = $DB->get_recordset_sql($sql, $params);

        static::_recordset_loop_and_export($recordset, 'mootyper', [], function($carry, $record) {
            $carry[] = (object) [
                'mootyper' => $record->mootyper,
                'userid' => $record->userid,
                'mistakes' => $record->mistakes,
                'timeinseconds' => format_time($record->timeinseconds),
                'hitsperminute' => $record->hitsperminute,
                'fullhits' => $record->fullhits,
                'precisionfield' => $record->precisionfield .' %',
                'timetaken' => transform::datetime($record->timetaken),
                'exercise' => $record->exercise,
                'pass' => transform::yesno($record->pass),
                'attemptid' => $record->attemptid,
                'wpm' => $record->wpm,
            ];
            return $carry;
        }, function($mootyperid, $data) use ($mootyperidstocmids) {
            $context = context_module::instance($mootyperidstocmids[$mootyperid]);
            writer::with_context($context)->export_related_data([], 'grades', (object) ['grades' => $data]);
        });

        // Export the attempts.
        $sql = "SELECT cm.id AS cmid,
                       mg.mootyper AS mootyper,
                       mg.userid AS userid,
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
                 WHERE (c.id {$contextsql} AND ma.userid = :userid AND ma.mootyperid = mt.id)
              ORDER BY cm.id ASC, mg.timetaken ASC";

        $params = [
            'userid' => $user->id,
        ] + $contextparams;
        $recordset = $DB->get_recordset_sql($sql, $params);

        static::_recordset_loop_and_export($recordset, 'mootyper', [], function($carry, $record) {
            $carry[] = (object) [
                'mootyperid' => $record->mamootyperid,
                'userid' => $record->mauserid,
                'timetaken' => transform::datetime($record->matimetaken),
                'inprogress' => transform::yesno($record->mainprogress),
                'suspicion' => transform::yesno($record->masuspicion),
            ];
            return $carry;
        }, function($mootyperid, $data) use ($mootyperidstocmids) {
            $context = context_module::instance($mootyperidstocmids[$mootyperid]);
            writer::with_context($context)->export_related_data([], 'attempts', (object) ['attempts' => $data]);
        });
    }

    /**
     * Export the supplied personal data for a single mootyper activity, along with any generic data or area files.
     *
     * @param array $mootyperdata the personal data to export for the mootyper.
     * @param \context_module $context the context of the mootyper.
     * @param \stdClass $user the user record
     */
    protected static function _export_mootyper_data_for_user(array $mootyperdata, \context_module $context,
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
    public static function _delete_data_for_all_users_in_context(\context $context) {
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
    public static function _delete_data_for_user(approved_contextlist $contextlist) {
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

    /**
     * Return a dict of mootyper IDs mapped to their course module ID.
     *
     * @param array $cmids The course module IDs.
     * @return array In the form of [$hotquestionid => $cmid].
     */
    protected static function _get_mootyper_ids_to_cmids_from_cmids(array $cmids) {
        global $DB;
        list($insql, $inparams) = $DB->get_in_or_equal($cmids, SQL_PARAMS_NAMED);
        $sql = "
            SELECT h.id, cm.id AS cmid
              FROM {mootyper} h
              JOIN {modules} m
                ON m.name = :mootyper
              JOIN {course_modules} cm
                ON cm.instance = h.id
               AND cm.module = m.id
             WHERE cm.id $insql";
        $params = array_merge($inparams, ['mootyper' => 'mootyper']);
        return $DB->get_records_sql_menu($sql, $params);
    }

    /**
     * Loop and export from a recordset.
     *
     * @param moodle_recordset $recordset The recordset.
     * @param string $splitkey The record key to determine when to export.
     * @param mixed $initial The initial data to reduce from.
     * @param callable $reducer The function to return the dataset, receives current dataset, and the current record.
     * @param callable $export The function to export the dataset, receives the last value from $splitkey and the dataset.
     * @return void
     */
    protected static function _recordset_loop_and_export(\moodle_recordset $recordset, $splitkey, $initial,
            callable $reducer, callable $export) {

        $data = $initial;
        $lastid = null;

        foreach ($recordset as $record) {
            if ($lastid && $record->{$splitkey} != $lastid) {
                $export($lastid, $data);
                $data = $initial;
            }
            $data = $reducer($data, $record);
            $lastid = $record->{$splitkey};
        }
        $recordset->close();

        if (!empty($lastid)) {
            $export($lastid, $data);
        }
    }
}
