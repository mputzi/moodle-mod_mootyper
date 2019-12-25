
# Moodle Mootyper module
- Moodle tracker component: https://github.com/drachels/moodle-mod_mootyper/issues
- Documentation: https://github.com/drachels/moodle-mod_mootyper/wiki
- Source Code: https://github.com/drachels/moodle-mod_mootyper
- License: http://www.gnu.org/licenses/gpl.txt

## Install from git
- Navigate to Moodle root folder
- **git clone git://github.com/drachels/moodle-mod_mootyper.git mod/mootyper**
- **cd mootyper**
- **git checkout MOODLE_XY_STABLE** (where XY is the moodle version, e.g: MOODLE_30_STABLE, MOODLE_28_STABLE...)
- Click the 'Notifications' link on the frontpage administration block or **php admin/cli/upgrade.php** if you have access to a command line interpreter.

## Install from a compressed file
- Extract the compressed file data
- Rename the main folder to mootyper
- Copy to the Moodle mod/ folder
- Click the 'Notifications' link on the frontpage administration block or **php admin/cli/upgrade.php** if you have access to a command line interpreter.

This is the README file for the MooTyper project. MooTyper is
a moodle extension, that adds the 'typing instructor' functionallity to Moodle. 
The plugin url is: https://moodle.org/plugins/view.php?plugin=mod_mootyper

MooTyper is free software, the same as Moodle.

1. REQUIREMENTS

The MooTyper module uses javascript which is not welcome in Moodle but it is a
requirement for the use of the module. The typing procedure cannot be done without
the client side logic.

It creates some new tables in a moodle database and inserts some sample
typing exercises. This is all done automaticaly through the intstallation,
but real lessons and exercises should be added by teachers afterwards.

2. INSTALLATION

MooTyper is an activity module. You have to extract it to the 'mod' directory.
If the directory name is something like "moodle_mod_mootyper" you have to change
that to just "mootyper". So for example, the path should be like this:
<your moodle installation>/mod/mootyper/view.php

Than go to Site Administration -> Notifications and click on the button to start
the install.

Right after an install or upgrade of the new MooTyper 3.6.1, you will then see
the settings page. You can now set the date display format for the grade views.
You can also set colors for grade views, the background of the new keyboard 
layouts, and the color for keytops that are not home row keys. This allows you
to color coordinate MooTyper with your theme color scheme. 

3. USING MOOTYPER

Using MooTyper activity module is very simple. An instance can be added as a
new activity in a course like Lesson or Quiz. The simplest way to setup a
MooTyper, is select MooTyper from the list, Add an activity or resource.
Add the name you want to use for this activity. I recommend adding the
lesson name as part of the activity name. Add a description if you desire.
Then, add Availability times, if desired, then click, Save and display.
Yes, that's right! Don't bother with any other options as they will then be
available on the, Setup link.
Thanks to Mary Cooch from moodle.org we have this video, which shows how
to add exercises, create mootyper instance, and then view grades.
It's a little outdated (one of the first versions of mootyper), but I
guess everything still holds:

http://www.youtube.com/watch?v=Twl-7CGrS0g

4. ADDITIONAL KEYBOARD LAYOUTS

This MooTyper currently includes keyboard layouts to support many languages.
If you do not see one that need, please let the maintainer know.
Note: There are two special layouts, English(USV6) that includes both the normal
keys and the number keypad, and numberKeypadOnly(V1), which shows ONLY the
number keypad.


To implement any other layout you have to:
Create a php file with keyboard layout defined with HTML. Create a javascript
file (with the same name and .js extension) that implements the logic of the keyboard
layout. If you have any mistakes in your js file the module won't work, so in
this case try to validate your code with a tool like this...
http://www.javascriptlint.com/online_lint.php

5. SAVING YOUR OWN OR MODIFIED LESSONS

In previous versions of MooTyper, if you created your own lessons there was
no built in way to export a copy for backup purposes or for safekeeping.
Once installed, there was no way to add additional lessons to MooTyper
unless you used the built in editing capability, which is not as convenient
as it would be to create lessons in a word processing program. If you wanted
to add lessons to MooTyper without using the built in editor, you had to do
a completely new installation of MooTyper which meant you would lose all
student progress and grades.
As of version 3.1.0, MooTyper now supports Import and Export of MooTyper Lessons
via links in the Administration block, which overcomes these limitations.

6. NEW IN THIS RELEASE

There is now a new key top text color setting. This will allow you to now
have a layout with dark colored keys, with white text, or some other light
colored text.

There is now a new time limit setting that can be used for any Mode.

There is now a new required WPM rate setting that can be used for any Mode.

Information labels on the pages for view.php, gview.php, and owngrades.php
now include the new time limit, Required precision, and the new Required WPM.

You can now set a typing time limit from 0:00 (not used) to 10:00 minutes.
However, you will need to make sure each exercise is long enough, character
count wise, so that your fastest typer will NOT run out of something to type.

You can now set a required Word Per Minute rate. Failure to achieve the
posted rate, is treated just the same as failing to achieve the required
precision.

You can mix and match the time limit, precision, and wpm.

A zero timelimit lets the student type all the way to the end of the exercise,
just as it always has. If a time limit is set, the exercise is halted when
time expires.

A zero required precision means the grade will NOT be shown with a red
background no matter the precision achieved for the exercise. Any other
required precision is treated as before.

A zero required WPM rate means the grade will NOT be shown with a red
background no matter the WPM rate achieved for the exercise. Any other
required precision like the required precision rate. Red grade background
for not exceeding the required WPM. Green if it is exceeded.

If using both a required precision and required WPM, they must both be
exceeded to get a green "passed" background. If one is exceeded and one
is not, the grade background will be red.

7. OTHER IMPORTANT CAPABILITIES

There are four settings that affect the typing modes. 
  1.The original mode - This mode requires you to type the correct letter before
  you can move to the next letter. Extra spaces are ignored. Mistyped letters
  are counted as mistakes.
  2. Count mistyped spaces - Same as the original mode, but counts mistyped spaces
  as well as mistyped letters.
  3. Continuous typing - In this mode you do not have to type the correct letter
  before moving to the next letter. All mistyped letters and space are counted
  as mistakes.
  4. Count all keystrokes - If you mistype the same key multiple times, you have the
  option of counting it as one mistake, or counting all of them.

If a user clicks, View my grades, depending on the Moodle version, they will now
see a horizontal bar chart beneath their grade table that charts Hits per minute,
Precision, and WPM results. If a non-editing teacher, teacher, manager, or admin,
clicks on, 'View all grades, depending on the Moodle version, they will see the
normal grade table as well as horizontal bar charts showing Hits per minute,
Precision, and WPM for each exercise for each student.
 
For more info please visit the plugins wiki on github:
https://github.com/drachels/moodle-mod_mootyper/wiki