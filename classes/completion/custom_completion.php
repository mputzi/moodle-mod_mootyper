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

namespace mod_mootyper\completion;

use core_completion\activity_custom_completion;

defined('MOODLE_INTERNAL') || die();

/**
 * Class custom_completion.
 * @package mod_mootyper
 */
class custom_completion extends \core_completion\activity_custom_completion {

    /**
     * Get the completion state for a given completion rule.
     *
     * @param string $rule The completion rule.
     * @return int The completion state.
     */
    public function get_state(string $rule): int {
        global $DB;
        $debug = array();
        $debug['CP0a spacer $rule: '] = 'made it here';
        $debug['CP0b entered get_state(string $rule) function of custom_completiong and checking item string $rule: '] = 'made it here';


        $this->validate_rule($rule);

        $userid = $this->userid;
        $mootyperid = $this->cm->instance;

        if (!$mootyper = $DB->get_record('mootyper', ['id' => $this->cm->instance])) {
            throw new \moodle_exception('Unable to find mootyper with id ' . $mootyperid);
        }

        // Fetch the completion status of the custom completion rule.
        $status = COMPLETION_INCOMPLETE;
        if ($rule === 'completedlesson') {
            [$ticked, $total] = mootyper_class::get_user_progress($mootyper->id, $userid);
            if ($mootyper->completedlessontype === 'items') {
                // Completedlesson is the actual number of items that need checking-off.
                // For MooTyper I think this will need to call the completed lesson check.
                $status = $mootyper->completedlesson <= $ticked;
            } else {
                // Completedlesson is the percentage of items that need checking-off.
                $status = $total ? ($mootyper->completedlesson <= ($ticked * 100 / $total)) : false;
            }
        }
print_object($debug);
die;
        return $status ? COMPLETION_COMPLETE : COMPLETION_INCOMPLETE;
    }

    /**
     * Fetch the list of custom completion rules that this module defines.
     *
     * @return array
     */
    public static function get_defined_custom_rules(): array {
        $debug['CP1a spacer: '] = 'made it here';
        $debug['CP1b entered get_defined_custom_rules() function of custom_completion and returning completedlesson: '] = 'made it here - completedlesson';

print_object($debug);
//die;
        return ['completedlesson'];
    }

    /**
     * Returns an associative array of the descriptions of custom completion rules.
     *
     * @return array
     */
    public function get_custom_rule_descriptions(): array {
        $entries = $this->cm->customdata['customcompletionrules']['completedlesson'] ?? 0;
        $debug['CP2a spacer: '] = 'made it here';
        $debug['CP2b entered get_custom_rule_descriptions() function of custom_completion and returning completedlesson: '] = 'made it here';
print_object($debug);
die;
        return [
            'completedlesson' => get_string('completiondetail:lesson', 'mootyper', $entries),
        ];
    }

    /**
     * Returns an array of all completion rules, in the order they should be displayed to users.
     *
     * @return array
     */
    public function get_sort_order(): array {
        $debug['CP3 entered get_sort_order() function of custom_completion and returning completedlesson: '] = 'made it here';
print_object($debug);
die;
        return [
            'completionview',
            'completedlesson',
            'completionusegrade',
        ];
    }
}
