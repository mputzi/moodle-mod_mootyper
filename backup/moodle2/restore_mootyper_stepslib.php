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
 * Define all the restore steps that will be used by the restore_mootyper_activity_task.
 *
 * @package mod_mootyper
 * @copyright 2016 onwards AL Rachels (drachels@drachels.com).
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Define the complete assignment structure for restore, with file and id annotations.
 *
 * Structure step to restore one mootyper activity.
 */
class restore_mootyper_activity_structure_step extends restore_activity_structure_step {

    /**
     * Define the structure of the restore workflow.
     *
     * @return restore_path_element $structure
     */
    protected function define_structure() {

        $paths = array();
        // To know if we are including userinfo.
        $userinfo = $this->get_setting_value('userinfo');

        // Define each element separated.
        $paths[] = new restore_path_element('mootyper', '/activity/mootyper');
        if ($userinfo) {
            $paths[] = new restore_path_element('mootyper_attempt', '/activity/mootyper/attempts/attempt');
            $paths[] = new restore_path_element('mootyper_check', '/activity/mootyper/checks/check');
            $paths[] = new restore_path_element('mootyper_exercise', '/activity/mootyper/exercises/exercise');
            $paths[] = new restore_path_element('mootyper_grade', '/activity/mootyper/grades/grade');
            $paths[] = new restore_path_element('mootyper_layout', '/activity/mootyper/layouts/layout');
            $paths[] = new restore_path_element('mootyper_lesson', '/activity/mootyper/lessons/lesson');
        }

        // Return the paths wrapped into standard activity structure.
        return $this->prepare_activity_structure($paths);
    }

    /**
     * Process an assign restore.
     *
     * @param object $data The data in object form
     * @return void
     */
    protected function process_mootyper($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;
        $data->course = $this->get_courseid();

        $data->timecreated = $this->apply_date_offset($data->timecreated);
        $data->timemodified = $this->apply_date_offset($data->timemodified);

        // Insert the mootyper record.
        $newitemid = $DB->insert_record('mootyper', $data);
        // Immediately after inserting "activity" record, call this.
        $this->apply_activity_instance($newitemid);
    }

    /**
     * Process a submission restore
     * @param object $data The data in object form
     * @return void
     */
    protected function process_mootyper_attempt($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;

        $data->mootyper = $this->get_new_parentid('mootyper');
        $data->userid = $this->get_mappingid('user', $data->userid);
        $data->time = $this->apply_date_offset($data->time);

        $newitemid = $DB->insert_record('mootyper_attempts', $data);
        $this->set_mapping('mootyper_attempt', $oldid, $newitemid);
    }

    protected function process_mootyper_check($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;

        $data->mootyper = $this->get_new_parentid('mootyper');
        $data->starttime = $this->apply_date_offset($data->starttime);
        $data->endtime = $this->apply_date_offset($data->endtime);

        $newitemid = $DB->insert_record('mootyper_checks', $data);
        // No need to save this mapping as far as nothing depend on it
        // (child paths, file areas nor links decoder).
    }

    protected function process_mootyper_grade($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;

        $data->mootyper = $this->get_new_parentid('mootyper');
        $data->userid = $this->get_mappingid('user', $data->userid);
        $data->time = $this->apply_date_offset($data->time);

        $newitemid = $DB->insert_record('mootyper_gradess', $data);
        $this->set_mapping('mootyper_grade', $oldid, $newitemid);
    }
    // Seem to be missing the functions for layouts and lessons.

    /**
     * Once the database tables have been fully restored, restore the files
     * @return void
     */
    protected function after_execute() {
        // Add mootyper related files, no need to match by itemname (just internally handled context).
        $this->add_related_files('mod_mootyper', 'intro', null);
    }
}
