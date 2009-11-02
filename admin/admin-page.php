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
    if(isset($_POST['yes']))   $auto_post = $_POST['yes'];
    if(isset($_POST['no']))    $auto_post = $_POST['no'];
	$dib_cats       	    = $_POST['post_category'];
    $dib_opening            = $_POST['opening'];
	
	// Update the DB with the new option values
	update_option("dib-location", mysql_real_escape_string($location));
	update_option("dib-format", mysql_real_escape_string($format));
    update_option("dib-title", $dib_title);
    update_option("dib-auto", $auto_post);
    update_option("dib-cats", $dib_cats);
    update_option("dib-opening", mysql_real_escape_string($dib_opening));
}

// Get Current DB Values
$location			= get_option("dib-location");
$format				= get_option("dib-format");
$dib_title          = get_option("dib-title");
$auto_post          = get_option("dib-auto");
$dib_cats           = get_option("dib-cats");
$dib_opening        = get_option("dib-opening");
		
?>

<div class="wrap">
	<h2><?php echo $plugin_name; ?></h2>
	<form action="" method="post" id="<?php echo $plugin_ref; ?>-config">
		<table class="form-table">
			<?php if (function_exists('wp_nonce_field')) { wp_nonce_field($plugin_ref.'-updatesettings'); } ?>
			<tr>
				<th scope="row" valign="top"><label for="location">URL of inspiration feed:</label></th>
				<td><input type="text" name="location" id="location" class="regular-text" style="width: 99%;" value="<?php echo $location; ?>" /></td>
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
				<td><textarea rows="5" cols="10" name="opening" id="opening" class="large-text"><?php echo $dib_opening; ?></textarea></td>
			</tr>
			<tr>
				<th scope="row" valign="top"><label for="format">Display format:</label></th>
				<td><textarea rows="5" cols="10" name="format" id="format" class="large-text code"><?php echo $format; ?></textarea><br />
                <small>Allowed terms: <em>[image]</em>, <em>[image-url]</em>, <em>[image-alt]</em>, <em>[image-width]</em></small></td>
			</tr>
            <tr>
				<th scope="row" valign="top"><label for="auto-post">Automatically Publish?</label></th>
				<td><fieldset id="auto-publish">
                    <input type="radio" name="yes" id="yes" value="yes" <?php if($auto_post == 'yes') echo 'checked="checked" ' ?>/> Yes &nbsp; &nbsp; &nbsp; &nbsp;
                    <input type="radio" name="no" id="no" value="no" <?php if($auto_post == 'no') echo 'checked="checked" ' ?>/> No
                </fieldset></td>
			</tr>
            <tr>
				<th scope="row" valign="top"><label for="categories">Categories of post:</label></th>
				<td><ul><?php wp_category_checklist(0,0,$dib_cats); ?></ul></td>
			</tr>
		</table>
		<br />
		<span class="submit" style="border: 0;"><input type="submit" name="submit" value="Save Settings" /></span>
	</form>
</div>