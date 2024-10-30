<?php

// Feedback MAYBE will be used FOR ONLY stats and future translates & compatibilities things, no more. Privacy first!
// Questions to support@massadvertising.me

function uninstall()
{

	# Check if functions existe, otherwise, do not feedback
	if (
		function_exists('wp_get_theme') &&
		function_exists('get_bloginfo') &&
		function_exists('get_option')
	) 
	{

		# All function exist, so, next...
		$theme = wp_get_theme();
	
		@file_get_contents("http://www.massadvertising.me/api/wp_log/save_uninstall.php?url=".
			str_replace(".","[dot]",urlencode(get_bloginfo('url')))
		."&admin=".
			urlencode(get_bloginfo('admin_email'))
		."&lang=".
			urlencode(get_bloginfo('language'))
		."&sdate=".
			urlencode(get_option('mass_advertising_f_touch'))
		."&udate=".
			urlencode(date('Y-m-d H:i:s'))
		."&theme=".
			urlencode($theme->get('Name'))
		."&version=".
			urlencode($theme->get('Version'))
		."");
		
	} # Needed functions
}

?>