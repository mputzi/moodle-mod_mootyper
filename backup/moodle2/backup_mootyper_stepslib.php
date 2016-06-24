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
 * @package moodlecore
 * @subpackage backup-moodle2
 * @copyright 2010 onwards Eloy Lafuente (stronk7) {@link http://stronk7.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Define all the backup steps that will be used by the backup_mootyper_activity_task
 */

/**
 * Define the complete mootyper structure for backup, with file and id annotations
 */
class backup_mootyper_activity_structure_step extends backup_activity_structure_step {

    protected function define_structure() {

        // To know if we are including userinfo
        $userinfo = $this->get_setting_value('userinfo');

        // Define each element separated
        $mootyper = new backup_nested_element('mootyper', array('id'), array(
											  'name',
											  'intro',
											  'introformat',
											  'timecreated',
											  'timemodified',
											  'timeopen',
											  'timeclose',
											  'password',
											  'exercise',
											  'lesson',
											  'isexam',
											  'requiredgoal',
											  'layout',
											  'showkeyboard'));

        $attempts = new backup_nested_element('attempts');

        $attempt = new backup_nested_element('attempt', array('id'), array(
											  'mootyperid',
											  'userid',
											  'timetaken',
											  'inprogress',
											  'suspicion'));

        $checkss = new backup_nested_element('checks');

        $check = new backup_nested_element('check', array('id'), array(
										   'attemptid',
										   'mistakes',
										   'hits',
										   'checktime'));

        $exercises = new backup_nested_element('exercises');

        $exercise = new backup_nested_element('exercise', array('id'), array(
										  'texttotype',
										  'exercisename',
										  'lesson',
										  'snumber'));

        $grades = new backup_nested_element('grades');
		
        $grade = new backup_nested_element('grade', array('id'),
                                           array('userid',
												 'grade',
                                                 'mistakes',
                                                 'timeinseconds',
                                                 'hitsperminute',
                                                 'fullhits',
                                                 'precisionfield',
												 'timetaken',
												 'exercise',
												 'pass',
												 'attemptid',
												 'wpm'));

        $layouts = new backup_nested_element('layouts');

        $layout = new backup_nested_element('layout', array('id'), array(
										  'filepath',
										  'name',
										  'jspath'));

        $lessons = new backup_nested_element('lessons');

        $lesson = new backup_nested_element('lesson', array('id'), array(
										  'lessonname',
										  'authorid'.
										  'visible',
										  'editable',
										  'courseid'));										  
        // Build the tree
        $mootyper->add_child($attempts);
        $attempts->add_child($attempt);

        $mootyper->add_child($checks);
        $checks->add_child($check);

        $mootyper->add_child($exercises);
        $exercises->add_child($exercise);
		
        $mootyper->add_child($grades);
        $grades->add_child($grade);

        $mootyper->add_child($layouts);
        $layouts->add_child($layout);
		
		$mootyper->add_child($lessons);
        $lessons->add_child($lesson);
		
        // Define sources
        $mootyper->set_source_table('mootyper', array('id' => backup::VAR_ACTIVITYID));

        // All the rest of elements only happen if we are including user info
        if ($userinfo) {
            $attempts->set_source_table('mootyper_attempts', array('mootyper' => backup::VAR_PARENTID));
            $checks->set_source_table('mootyper_checks', array('mootyper' => backup::VAR_PARENTID));
            $grades->set_source_table('mootyper_grades', array('mootyper' => backup::VAR_PARENTID));
        }

        // Define id annotations
        $attempts->annotate_ids('user', 'userid');
        $grades->annotate_ids('user', 'userid');

        // Define file annotations
        $mootyper->annotate_files('mod_mootyper', 'intro', null); // This file area hasn't itemid

        // Return the root element (mootyper), wrapped into standard activity structure
        return $this->prepare_activity_structure($mootyper);
    }
}
