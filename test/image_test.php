<?php

// Instructions.
// 1.  Ensure this file and the image '515-797no09sa.jpg' are in the Moodle installation folder '/course/format/grid/test'.
// 2.  Ensure the value of $courseid is for a valid course.
// 3.1 In a browser, log into Moodle so that you have a valid MoodleSession cookie.
// 3.2 In another tab of the same browser navigate to 'your moodle installation'/course/format/grid/test/image_test.php
//     E.g. http://localhost/moodlegjb/course/format/grid/test/image_test.php
// Success: Image shows.
// Failure: Image does not show.

require_once('../../../../config.php');
global $CFG;
require_once($CFG->libdir . '/filelib.php');
require_once($CFG->libdir . '/weblib.php');
require_once($CFG->dirroot . '/repository/lib.php');

/* Script settings */
define('GRID_ITEM_IMAGE_WIDTH', 210);
define('GRID_ITEM_IMAGE_HEIGHT', 140);
$courseid = 2; // Must be a valid course id.
$context = context_course::instance($courseid);
$contextid = $context->id;
$itemid = 24;

// Adapted code from test_convert_image()' of '/lib/filestorage/tests/file_storage_test.php'
$newfilename = '515-797no09sa.jpg';  // Image taken by G J Barnard 2002 - Only use for this test.
$filepath = $CFG->dirroot . '/course/format/grid/test/' . $newfilename;
$filerecord = array(
    'contextid' => $contextid,
    'component' => 'course',
    'filearea' => 'section',
    'itemid' => $itemid,
    'filepath' => '/',
    'filename' => $newfilename
);

$fs = get_file_storage();

// Clean area from previous test run...
$fs->delete_area_files($contextid, 'course', 'section', $itemid);

$original = $fs->create_file_from_pathname($filerecord, $filepath);

$convertedfilename = 'converted_'.$newfilename;
$filerecord['filename'] = $convertedfilename;
$converted = $fs->convert_image($filerecord, $original, GRID_ITEM_IMAGE_WIDTH, GRID_ITEM_IMAGE_HEIGHT, true, 75);

echo '<!DOCTYPE html>';
echo '<html dir="ltr" lang="en" xml:lang="en">';
echo '<head>';
echo '<title>Grid Format Image Test</title>';
echo '<link rel="shortcut icon" href="'.$CFG->wwwroot.'/theme/image.php?theme=standard&amp;component=theme&amp;image=favicon" />';
echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
echo '<meta name="keywords" content="moodle, Grid Format Image Test" />';
echo '<meta http-equiv="pragma" content="no-cache" />';
echo '<meta http-equiv="expires" content="0" />';
echo '</head>';
echo '<body>';
echo '<div>';
$src = moodle_url::make_pluginfile_url($contextid, 'course', 'section', $itemid, '/', $convertedfilename);
//echo '<img src="'.$CFG->wwwroot.'/pluginfile.php/'.$contextid.'/course/section/'.$sectionid.'/'.$convertedfilename.'" alt="" />';
echo '<img src="'.$src.'" alt="Grid Format Image Test" />';
echo '</div>';
echo '<br />Converted object:<br/>';
print_object($converted);
echo '<br />Course Id:';
print($courseid);
echo '<br />Context Id:';
print($contextid);
echo '<br />Item Id:';
print($itemid);
echo '<br />Plugin URL:';
print($src);
echo '<br />';
echo '</body>';
echo '</html>';

// Remove original...
$original->delete();
unset($original);

?>
