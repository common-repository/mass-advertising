<?php
/*
Plugin Name: Mass Advertising
Plugin URI: http://www.massadvertising.me/
Description: World Wide Advertising Media. Grow your -enterprise brand- and your business! Simply install, select your -advertising power- and... you are off to the races! Just World Wide Advertising Made Easy!
Author: Mass Advertising
Version: 6.5.8
Author URI: http://www.massadvertising.me/
*/
/*
Copyright (c) 2014 Mass Advertising. (www.massadvertising.me). All rights reserved.

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License,
version 2, as published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/
/*
	For Irina Luchinetz & Igor Borovskoy. I'm not sure about if I could finish this project without their support. My infinite thanks to them! Thanks for make this dream possible, I know about you couldn't help to me like you would like, but this untidy house with infinite love is all I need, all everyone needs...
		- Denis, CEO Mass Advertising
*/


// Version: 5.5.0 - Get Plugin Version
function plugin_get_version() 
{
	$plugin_data = get_plugin_data( __FILE__ );
	$plugin_version = $plugin_data['Version'];
	return $plugin_version;
}

// General CSS
wp_register_style('resumen_new_embeded.css', plugins_url('mass-advertising/css/resumen_new_embeded.css'));
// Table Styled CSS
wp_register_style('tablesorter.css', plugins_url('mass-advertising/css/tablesorter.css'));
// Create Account CSS
wp_register_style('create_account.css', plugins_url('mass-advertising/css/create_account.css'));
// Include Data CSS
wp_register_style('include_data.css', plugins_url('mass-advertising/css/include_data.css'));
// Light Summary CSS
wp_register_style('light_summary.css', plugins_url('mass-advertising/css/light_summary.css'));

// Version: 5.0.2 - Activate weight summary by default, just a test...
if( get_option('mass_advertising_include_summary') == "" )
{
	update_option('mass_advertising_include_summary', 1);
}
// Version: 4.9.9 - Whas block by lost connection with the server?
if( date('Y-m-d H:i:s') > get_option( 'mass_advertising_BLOCK_UNTIL') )
{

	// No, all is ok 
	// Version: 4.9.5 - Log "first touch"
	if(get_option('mass_advertising_f_touch') == "")
	{
		// Save Log
		update_option('mass_advertising_f_touch', date('Y-m-d H:i:s'));
	}
	
	// Version: 4.9.5 - Log Uninstall
	include 'inc/inc.uninstall.php';
	register_uninstall_hook(__FILE__, 'uninstall');
	
	
	// Get Functions
	include 'inc/inc.functions.php';
	
	// Some important to say to the user?
	add_action('admin_notices', 'popup_setup');
	
	// Call to Action
	add_action('admin_menu', 'menu');
	// Check If Publish Plugin Admin Notice was not deactivate
	if( get_option('mass_advertising_hide_popup') != 1 )
	{
		// Publish Plugin Admin Notice
		add_action('admin_notices', 'my_admin_notice');
	}
	
	
	# Unusefull Pages Log
	global $pagenow;
	include 'inc/inc.unusefull_blogs_log.php';
	
	
} // Yes, was blocked by lost connection with server, don't do anything


?>