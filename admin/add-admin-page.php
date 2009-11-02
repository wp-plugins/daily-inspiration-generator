<?php

global $plugin_name, $plugin_ref, $plugin_abv;

// prep options page insertion
function add_config_page() {
	add_options_page( 'Daily Inspiration Builder', 'Daily Inspiration Builder', 10, 'diBuilder-options', 'config_page' );
	add_filter( 'plugin_action_links', 'filter_plugin_actions', 10, 2 );
	add_filter( 'ozh_adminmenu_icon', 'add_ozh_adminmenu_icon' );
}

// Place in Settings Option List
function filter_plugin_actions( $links, $file ){
	//Static so we don't call plugin_basename on every plugin row.
	static $this_plugin;
	if ( ! $this_plugin ) $this_plugin = plugin_basename(__FILE__);
	
	if ( $file == $this_plugin ){
		$settings_link = '<a href="options-general.php?page=main.php">' . __('Settings') . '</a>';
		array_unshift( $links, $settings_link ); // before other links
	}
	return $links;
}

// Our actual page
function config_page(){
	include('admin-page.php');
}

?>