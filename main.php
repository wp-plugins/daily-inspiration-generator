<?php
/*
Plugin Name: Daily Inspiration Generator
Plugin URI: http://fire-studios.com/
Description: Automatically generates a "Daily Inspiration" at the end of each day
Version: 1.0
Author: Jonathan Wolfe
Author URI: http://fire-g.com/
*/

/* 
Change log

_1.0_
 - Automatically posts using cron

Beta 2
 - Writes post
 - Additional customizations: "Title", "Automatically Publish", "Categories", & "Opening"

Beta 1
 - Removes all unwanted data via PHP
 - Initial customizations: "Location" & "Format"

Alpha 1
 - Removes all unwanted data via jQuery
 - Gathers data via Yahoo-Pipe

*/

$plugin_name		= 'Daily Inspiration Builder';
$plugin_ref			= 'diBuilder';
$plugin_abv			= 'dib';


// Some Defaults
$location			         = 'http://pipes.yahoo.com/pipes/pipe.run?_id=5d70d840b02a3fa9300d46a1c45e115a&_render=rss';
$format			  	         = '[image]';
$dib_title                   = array();
$dib_title['format']         = 'numbered';
$dib_title['count']          = 1;
$auto_post                   = 'yes';
$dib_cats                    = array(get_option('default_category'));
$dib_opening                 = 'This post is part of our daily series of posts showing the most inspiring images from the biggest galleries on the web presented throughout the day.';


// Put our defaults in the "wp-options" table
add_option("dib-location", $location);
add_option("dib-format", $format);
add_option("dib-title", $dib_title);
add_option("dib-auto", $auto_post);
add_option("dib-cats", $dib_cats);
add_option("dib-opening", $dib_opening);


// Creates an admin page for your plugin to allow users to edit options
// Delete this function and the action at the bottom to remove this functionality
require('admin/add-admin-page.php');

require('features/builder.php');

// insert admin page into menu, delete if not using an admin page
add_action('admin_menu', 'add_config_page');

// create custom hook for cron
add_action('dib_cron', 'builder');

register_activation_hook(__FILE__, 'activate_cron');
function activate_cron() {
    wp_schedule_event('1257030000', 'daily', 'dib_cron');
}
register_deactivation_hook(__FILE__, 'deactivate_cron');
function deactivate_cron() {
	wp_clear_scheduled_hook('dib_cron');
}
?>