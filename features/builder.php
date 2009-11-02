<?php

global $plugin_name, $plugin_ref, $plugin_abv;

/**
 * Purpose:
 *  - Get inspiration from feed
 *  - Filter out unwanted material
 *  - Conform to format
 *  - Email finished post
 */

function builder(  ) {
	
	$location = get_option("dib-location");
    // echo 'location: '.$location;
    
    // Get that website's content
    $handle = fopen($location, "r");
    
    // If there is something, read and return
    $buffer = NULL;
    if ($handle) {
        while (!feof($handle)) {
            $buffer .= fgets($handle, 4096);
        }
        fclose($handle);
    }
    // echo $buffer;
    
    /* Parse */
    if ( $buffer != NULL && $buffer ) {
    	preg_match_all( "/<img src=[\"'](.+?)[\"'] alt=[\"'](.*?)[\"'] width=[\"'](\d+)[\"'](?:| )(?:|\/)>/", $buffer, $matches, PREG_SET_ORDER );
    	$images = array();
    
    	foreach ( $matches as $match ) {
    		if ( $match[3] && ( int ) $match[3] > 1 ) {
    			$images[] = $match;
    		}
    	}
    }
    /* foreach ( $images as $image ) {
        echo $image[0];
    } */
    
    $content = get_option('dib-opening');
    $content .= ' <!--more--> ';
    
	$format = get_option("dib-format");
    $terms = array('/\[image\]/', '/\[image-url\]/', '/\[image-alt\]/', '/\[image-width\]/');
    $inspirations = array();
    
    foreach ( $images as $image ) {
        $inspirations[] = preg_replace($terms, $image, $format);
    }
    
    foreach ( $inspirations as $inspiration ) {
        $content .= $inspiration;
    }
    
    $dib_title = get_option("dib-title");
    if ($dib_title['format'] == 'numbered') $dib_title_format = 'Daily Inspiration '.$dib_title['count'];
    if ($dib_title['format'] == 'dated') $dib_title_format = 'Daily Inspiration '.date(get_option('date_format'));
    if ($dib_title['format'] == 'none') $dib_title_format = 'Daily Inspiration';
    $auto_post = get_option("dib-auto");
    if ($auto_post == 'yes') $auto = 'publish';
    if ($auto_post == 'no') $auto = 'pending';
    $dib_cats = get_option('dib-cats');
    
    // Create post object
    $new_post = array();
    $new_post['post_title'] = $dib_title_format;
    $new_post['post_content'] = $content;
    $new_post['post_status'] = $auto;
    $new_post['post_author'] = 1;
    $new_post['post_category'] = $dib_cats;
    
    // Insert the post into the database
    wp_insert_post( $new_post );
    
    $dib_title['count']++;
    update_option('dib-title', $dib_title);
    
}

?>