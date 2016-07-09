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
 * Administration settings definitions for the quiz module.
 *
 * @package    mod
 * @subpackage mootyper
 * @copyright  2012 Jaka Luthar (jaka.luthar@gmail.com)
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */


defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    require_once($CFG->dirroot.'/mod/mootyper/lib.php');
    require_once($CFG->dirroot.'/mod/mootyper/locallib.php');

    // Default keyboard layout.
    $layouts = get_keyboard_layouts_db();
    $settings->add(new admin_setting_configselect(
    'mod_mootyper/defaultlayout',
        get_string('defaultlayout', 'mootyper'),
        '', 1, $layouts)
    );

    // Default typing precision.
    $precs = array();
    for ($i = 0; $i <= 100; $i++) {
        $precs[] = $i;
    }
    $settings->add(new admin_setting_configselect(
    'mod_mootyper/defaultprecision',
        get_string('defaultprecision', 'mootyper'),
        '', 97, $precs)
    );

    // Date format setting.
    $settings->add(new admin_setting_configtext('mod_mootyper/dateformat', new lang_string('dateformat', 'mootyper'), new lang_string('configdateformat', 'mootyper'), 'M d, Y G:i', PARAM_TEXT, 15));

    // Passing grade background colour setting.
    $settings->add(new admin_setting_configcolourpicker(
    'mod_mootyper/passbgc',
        get_string('passbgc_title', 'mootyper'),
        get_string('passbgc_descr', 'mootyper'),
        get_string('passbgc_colour', 'mootyper'),
        null)
    );

    // Failing grade background colour setting.
    $settings->add(new admin_setting_configcolourpicker(
    'mod_mootyper/failbgc',
        get_string('failbgc_title', 'mootyper'),
        get_string('failbgc_descr', 'mootyper'),
        get_string('failbgc_colour', 'mootyper'),
        null)
    );

    // Suspicion marks colour setting.
    $settings->add(new admin_setting_configcolourpicker(
    'mod_mootyper/suspicion',
        get_string('suspicion_title', 'mootyper'),
        get_string('suspicion_descr', 'mootyper'),
        get_string('suspicion_colour', 'mootyper'),
        null)
    );
    
//    // Keyboard background colour setting.
//    $settings->add(new admin_setting_configcolourpicker(
//    'mod_mootyper/keyboardbgc',
//        get_string('keyboardbgc_title', 'mootyper'),
//        get_string('keyboardbgc_descr', 'mootyper'),
//        get_string('keyboardbgc_colour', 'mootyper'),
//        null)
//    );
    // Keyboard background colour setting.
    $name = 'mod_mootyper/keyboardbgc';
    $title = get_string('keyboardbgc_title', 'mootyper');
    $description = get_string('keyboardbgc_descr', 'mootyper');
    $default = get_string('keyboardbgc_colour', 'mootyper');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);    
    
    
$old = '[[setting:keyboardbgc]]';
$new = get_config('mod_mootyper', 'keyboardbgc');
$data = file_get_contents($CFG->dirroot.'/mod/mootyper/template.css');
$newdata = str_replace($old, $new, $data);
file_put_contents($CFG->dirroot.'/mod/mootyper/styles.css', $newdata);
}
