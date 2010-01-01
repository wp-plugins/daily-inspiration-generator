<?php

global $plugin_name, $plugin_ref, $plugin_abv;

// Update Settings
if ( isset($_POST['submit']) ) {
    // Security checks
	$nonce = $_REQUEST['_wpnonce'];
	if (! wp_verify_nonce($nonce, $plugin_ref.'-updatesettings') ) die('Security check failed'); 
	if (!current_user_can('manage_options')) die(__('You cannot edit the '.$plugin_name.' options.'));
	check_admin_referer($plugin_ref.'-updatesettings');
	
	// Get our new option values
	$location               = $_POST['location'];
	$format                 = $_POST['format'];
    $dib_title              = get_option("dib-title");
    $dib_title['format']    = $_POST['title'];
    // $auto_post              = $_POST['auto'];
	$dib_cats       	    = $_POST['post_category'];
    $dib_opening            = $_POST['opening'];
    $dib_tags               = $_POST['tags'];
    // $dib_hour               = $_POST['hour'];
    $dib_limit              = $_POST['limit'];
	
	// Update the DB with the new option values
	update_option("dib-location", mysql_real_escape_string($location));
	update_option("dib-format", mysql_real_escape_string($format));
    update_option("dib-title", $dib_title);
    // update_option("dib-auto", $auto_post);
    update_option("dib-cats", $dib_cats);
    update_option("dib-opening", mysql_real_escape_string($dib_opening));
    update_option("dib-tags", mysql_real_escape_string($dib_tags));
    // update_option("dib-hour", mysql_real_escape_string($dib_hour));
    update_option("dib-limit", mysql_real_escape_string($dib_limit));
    
    // wp_clear_scheduled_hook('dib_cron');
    // wp_schedule_event(gmmktime(get_option('dib-hour'),'0','0'), 'daily', 'dib_cron');
}

// Get Current DB Values
$location			= get_option("dib-location");
$format				= get_option("dib-format");
$dib_title          = get_option("dib-title");
// $auto_post          = get_option("dib-auto");
$dib_cats           = get_option("dib-cats");
$dib_opening        = get_option("dib-opening");
$dib_tags           = get_option("dib-tags");
// $dib_hour           = get_option("dib-hour");
$dib_limit          = get_option("dib-limit");

$newUrl = get_bloginfo('url').'/wp-content/plugins/daily-inspiration-generator/builder.php';

if(!isset($_POST['filtered'])) :
?>

<div class="wrap">
    <?php 
        $configured = file_get_contents('http://google.com');
        if(!$configured) echo '<div class="error" style="text-align: center;"><p style="color: red; font-size: 14px; font-weight: bold;">You\'re not ready to use this plugin! Please revist the download page and follow the instructions!</p></div>';
    ?>
	<h2><?php echo $plugin_name; ?></h2>
    <form action="<?php echo $newUrl; ?>" method="post">
        <input type="hidden" name="referrer" id="referrer" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />
        <input type="submit" name="new-inspiration" id="new-inspiration" style="display: block; height: 40px; width: 98%; margin: 0px auto;" value="New Inspiration" />
    </form>
    
    <h2>Settings</h2>
	<form action="" method="post" id="<?php echo $plugin_ref; ?>-config">
		<table class="form-table">
			<?php if (function_exists('wp_nonce_field')) { wp_nonce_field($plugin_ref.'-updatesettings'); } ?>
			<tr>
				<th scope="row" valign="top"><label for="location">URL of inspiration feed:</label></th>
				<td><input type="text" name="location" id="location" class="regular-text" style="width: 99%;" value="<?php echo $location; ?>" /> <br />
                <small>Default url is based on Eastern Standard Time</small></td>
			</tr>
            <!-- <tr>
				<th scope="row" valign="top"><label>Automatically Publish?</label></th>
				<td><fieldset id="auto-publish">
                    <input type="radio" name="auto" value="yes" <?php if($auto_post == 'yes') echo 'checked="checked" ' ?>/> Yes &nbsp; &nbsp; &nbsp; &nbsp;
                    <input type="radio" name="auto" value="no" <?php if($auto_post == 'no') echo 'checked="checked" ' ?>/> No
                </fieldset></td>
			</tr>
            <tr>
				<th scope="row" valign="top"><label for="hour">Time to post:</label></th>
				<td><input type="text" name="hour" id="hour" class="regular-text" style="width: 3.5%;" size="2" maxlength="2" value="<?php echo $dib_hour; ?>" />pm &nbsp; <small>(hour in military time)</small></td>
			</tr> -->
            <tr>
				<th scope="row" valign="top"><label for="limit">Number of images:</label></th>
				<td><input type="text" name="limit" id="limit" class="regular-text" style="width: 5%;" size="2" maxlength="3" value="<?php echo $dib_limit; ?>" /> <small>Leave blank for no limit</small></td>
			</tr>
            <tr>
				<th scope="row" valign="top"><label for="title">Title format:</label></th>
				<td><select name="title" id="title">
                    <option value="numbered" <?php if($dib_title['format'] == 'numbered') echo 'selected="selected"'; ?>>Daily Inspiration 123</option>
                    <option value="dated" <?php if($dib_title['format'] == 'dated') echo 'selected="selected"'; ?>>Daily Inspiration: 12/31/09</option>
                    <option value="none" <?php if($dib_title['format'] == 'none') echo 'selected="selected"'; ?>>Daily Inspiration</option>
                </select></td>
			</tr>
            <tr>
				<th scope="row" valign="top"><label for="opening">Opening paragraph of the post:</label></th>
				<td><textarea rows="5" cols="10" name="opening" id="opening" class="large-text"><?php echo stripslashes(stripslashes($dib_opening)); ?></textarea></td>
			</tr>
			<tr>
				<th scope="row" valign="top"><label for="format">Display format:</label></th>
				<td><textarea rows="5" cols="10" name="format" id="format" class="large-text code"><?php echo stripslashes(stripslashes($format)); ?></textarea><br />
                <small>Sample input: <em>&lt;div class="inspiration"&gt;[image]&lt;/div&gt;</em></small></td>
			</tr>
            <tr>
				<th scope="row" valign="top"><label for="tags">Tags:</label></th>
				<td><input type="text" name="tags" id="tags" class="regular-text" style="width: 99%;" value="<?php echo $dib_tags; ?>" /><br />
                <small>Seperate tags with commas</small></td>
			</tr>
            <tr>
				<th scope="row" valign="top"><label>Categories of post:</label></th>
				<td><ul><?php wp_category_checklist(0,0,$dib_cats); ?></ul></td>
			</tr>
		</table>
		<br />
		<span class="submit" style="border: 0;"><input type="submit" name="submit" value="Save Settings" /></span>
	</form>
</div>
<?php
else :    
    $return = $_POST['filtered'];
    // echo '<div style="display: none;">'.$return.'</div>';
    
    $filtered = str_replace('>','>|',$return);
    $images = explode('|',$filtered);
    array_pop($images);
    // print_r($images);
    
    $content = stripslashes(stripslashes(get_option("dib-opening")));
    $content .= ' <!--more--> ';
        
	$format = stripslashes(stripslashes(get_option("dib-format")));
    $terms = array('/\[image\]/', '/\[image-url\]/', '/\[image-alt\]/', '/\[image-width\]/');
    $inspirations = array();
    
    $dib_limit = get_option("dib-limit");
    if(!empty($dib_limit)) $items = array_slice($images, 0, $dib_limit);
    else $items = $images;
    
    foreach ( $items as $item ) {
        $content .= $item;
    }
    
    $dib_title = get_option("dib-title");
    if ($dib_title['format'] == 'numbered') $dib_title_format = 'Daily Inspiration '.$dib_title['count'];
    if ($dib_title['format'] == 'dated') $dib_title_format = 'Daily Inspiration '.date(get_option('date_format'));
    if ($dib_title['format'] == 'none') $dib_title_format = 'Daily Inspiration';
    $auto_post = get_option("dib-auto");
    $auto = 'publish';
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
?>
<div class="wrap">
	<h2>Creating New Post</h2>
	<p>Please wait as the process creates the new post. You will be redirected to the post edit page upon completion.</p>
    <script type="text/javascript">
    <!--
    setTimeout(delayer(),5000);
    function delayer(){
        window.location = "<?php echo get_bloginfo('url').'/wp-admin/edit.php'; ?>";
    }
    //-->
    </script>
</div>
<?php
endif;
?>