<?php
/*
Plugin Name: Daily Inspiration Generator
Plugin URI: http://fire-studios.com/
Description: Automatically generates a "Daily Inspiration" at the end of each day
Version: 1.3.3
Author: Jonathan Wolfe
Author URI: http://fire-g.com/
*/

/* 
Change log

__1.3.3__
 - Fixed bug that caused multiple instances of cron job

1.3.2
 - Fixed opening slashes bug

1.3.1
 - Fixed auto-publish bug
 - Fixed custom format slashes bug
 - added remover for extra cron scheduals

1.3
 - Added customizations: "tags", "hour", & "limit"

1.2.1
 - Fixed auto-publish selecting
 - Added warning if not configured correctly

1.2
 - Fixed "fopen()" not working on all servers

1.1.1
 - Added check for returned feed content

1.1
 - Slight updates for stability

1.0
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
$dib_tags                    = 'daily, inspiration, inspire';
$dib_hour                    = '23';
$dib_limit                   = '';


// Put our defaults in the "wp-options" table
add_option("dib-location", $location);
add_option("dib-format", $format);
add_option("dib-title", $dib_title);
add_option("dib-auto", $auto_post);
add_option("dib-cats", $dib_cats);
add_option("dib-opening", $dib_opening);
add_option("dib-tags", $dib_tags);
add_option("dib-hour", $dib_hour);
add_option("dib-limit", $dib_limit);


// Creates an admin page for your plugin to allow users to edit options
// Delete this function and the action at the bottom to remove this functionality
require('admin/add-admin-page.php');
 
function builder(  ) {
	
	$location = rtrim(get_option("dib-location"));
    
    $buffer = file_get_contents($location);
    if ( $buffer != NULL && $buffer ) {
        /* Parse */
    	preg_match_all( "/<img src=[\"'](.+?)[\"'] alt=[\"'](.*?)[\"'] width=[\"'](\d+)[\"'](?:| )(?:|\/)>/", $buffer, $matches, PREG_SET_ORDER);
    	$images = array();
    
    	foreach ( $matches as $match ) {
    		if ( $match[3] && ( int ) $match[3] > 1 ) {
    			$images[] = $match;
    		}
    	}
        /* foreach ( $images as $image ) {
            echo $image[0];
        } */
        
        $content = stripslashes(stripslashes(get_option("dib-opening")));
        $content .= ' <!--more--> ';
        
    	$format = stripslashes(stripslashes(get_option("dib-format")));
        $terms = array('/\[image\]/', '/\[image-url\]/', '/\[image-alt\]/', '/\[image-width\]/');
        $inspirations = array();
        
        foreach ( $images as $image ) {
            $inspirations[] = preg_replace($terms, $image, $format);
        }
        
        $dib_limit = get_option("dib-limit");
        if(!empty($dib_limit)) $items = array_slice($inspirations, 0, $dib_limit);
        else $items = $inspirations;
        
        foreach ( $items as $item ) {
            $content .= $item;
        }
        
        $dib_title = get_option("dib-title");
        if ($dib_title['format'] == 'numbered') $dib_title_format = 'Daily Inspiration '.$dib_title['count'];
        if ($dib_title['format'] == 'dated') $dib_title_format = 'Daily Inspiration '.date(get_option('date_format'));
        if ($dib_title['format'] == 'none') $dib_title_format = 'Daily Inspiration';
        $auto_post = get_option("dib-auto");
        if ($auto_post == 'yes') $auto = 'publish';
        if ($auto_post == 'no') $auto = 'pending';
        $dib_cats = get_option('dib-cats');
        $dib_tags = get_option('dib-tags').", ".$dib_title['count'].", ".date(get_option('date_format'));
        
        // Create post object
        $new_post = array();
        $new_post['post_title'] = $dib_title_format;
        $new_post['post_content'] = $content;
        $new_post['post_status'] = $auto;
        $new_post['post_author'] = 1;
        $new_post['post_category'] = $dib_cats;
        $new_post['tags_input'] = $dib_tags;
        
        // Insert the post into the database
        wp_insert_post( $new_post );
        
        $dib_title['count']++;
        update_option('dib-title', $dib_title);
    }
}

// insert admin page into menu, delete if not using an admin page
add_action('admin_menu', 'add_config_page');

// create custom hook for cron
add_action('dib_cron', 'builder');

register_activation_hook(__FILE__, 'activate_cron');
function activate_cron() {
    wp_clear_scheduled_hook('dib_cron');
    wp_clear_scheduled_hook('dib_cron');
    wp_clear_scheduled_hook('dib_cron');
    wp_schedule_event(gmmktime(get_option('dib-hour'),'0','0'), 'daily', 'dib_cron');
}
register_deactivation_hook(__FILE__, 'deactivate_cron');
function deactivate_cron() {
	wp_clear_scheduled_hook('dib_cron');
    wp_clear_scheduled_hook('dib_cron');
    wp_clear_scheduled_hook('dib_cron');
    wp_clear_scheduled_hook('dib_cron');
}
?>