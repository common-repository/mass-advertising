<?php

// Filter Possible Injections
function tags($tags)
{  
	$tags = strip_tags($tags);  
	$tags = stripslashes($tags);  
	$tags = htmlentities($tags);
	$tags = addslashes($tags);
	return trim($tags);  
} 

function dameURL(){
	$url = "http://".$_SERVER['HTTP_HOST'].":".$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI'];
	// Foward always to plugin page and not WP Dashboard
	$explode = explode("wp-admin",$url);
	$url = $explode[0] . "wp-admin" . "/admin.php?page=MassAdvertising";
	// Replace [dot] per points
	$url = str_replace(".","[dot]", $url);
	return $url;
}

function foward($lang,$from)
{

	// Get post and pages count, with that we ALERT empty blogs / pages which is impossible to advertise
	global $wpdb;
	$count = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'publish'");

	// Define without languaje foward
	if($lang == "") { $lang = "www."; }
	// Foward to Register Page
	return "http://".$lang.".massadvertising.me/register_wordpress_v3?from=".$from.
	// WARNING:
	// This all data is not saved without explicit authorization of the user.
	// When the user is foward to the page an alert() will be notify about the data fowarded and their use.
	// Looking for stay in harmony with TOS of wordpress.org
	'&url='.str_replace(".","[dot]",urlencode(get_bloginfo('url'))).
	// E-Mail of administrator for auto-fill, but it replace to other
	'&admin_email='.urlencode(get_bloginfo('admin_email')).
	// Charset for simulate any type of possible mistake in future
	'&charset='.urlencode(get_bloginfo('charset')).
	// For full compatibility, send version and compare in nearly future by our staff
	'&version='.urlencode(get_bloginfo('version')).
	// For new translation, languages stats
	'&language='.urlencode(get_bloginfo('language')).
	// Count if have some posts and pages
	'&postandpages='.$count;
}

function foward_include_summary()
{
	$url = "http://".$_SERVER['HTTP_HOST'].":".$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI']. "&include=1";
	return $url;
}

function get_url()
{
	$url = "http://".$_SERVER['HTTP_HOST'].":".$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI'];
	return $url;
}

function my_admin_notice()
{
	global $pagenow;
	
	if ( 
			// Check if page is plugins
			$pagenow == 'plugins.php' &&
			// Check if all data isn't available
			get_option('mass_advertising_uuid_api_wp_feedback') == "" &&
			get_option('mass_advertising_account_user') == "" &&
			get_option('mass_advertising_account_password') == "" &&
			get_option('mass_advertising_registered_account_from_api') == "" &&
			get_option('mass_advertising_account_uuid') == "" &&
			get_option('mass_advertising_campaign_uuid') == ""
			) 
		{
		
		 // Call General CSS
		 wp_enqueue_style('create_account.css');	
	
		 // Specia CSS with URLs, CAN'T paste it without PHP injection
		 echo "
			<style>
				/* NEWS BOX */
				#massadvertising-news.wp-connect {
					background-color: #0050ac;
				}
				/* FOWARD BUTTON */
				.wp-connect a.foward{
					background-color: #0069e2;
				}
				.wp-connect a.foward:hover{
					background-color: #298dff;
				}
			</style>
		 ";
		 
		$url2 = "http://".$_SERVER['HTTP_HOST'].":".$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI'];
		$explode2 = explode("wp-admin",$url2);
		$url2 = $explode2[0] . "wp-admin" . "/admin.php?page=MassAdvertising&hidepopup=1";
		 
		 echo '
		 <div id="massadvertising-news" class="updated wp-connect">
			<a class="foward float-left" href="'. foward('en', urlencode(dameURL()) ) .'">Create your account in less than 1 minute</a>
			<span class="float-left span-special">AT ONE STEP OF DISTANCE FROM INCREDIBLE ADVERTISING FEATURES! - <a href="'.$url2.'">Hide This</a></span>
		 </div>';
		
	}
}


function Generate_New_Account_MAdv($result) 
{


	if( strlen(get_option( 'mass_uuid_api_wp_feedback' )) < 4 )
	{
		
		// Default Ask
		update_option('mass_ask_every', 3);
		
		// Save results
		$exploded = explode("#",$result);
		
		if($exploded[0] != "")
		{
			update_option('mass_advertising_uuid_api_wp_feedback', $exploded[0]); // Save UUID
			$foward = true;
		}
	
		if($exploded[1] != "") // Check if return password
		{	
			update_option('mass_advertising_account_user', get_bloginfo('admin_email')); // Save user
			update_option('mass_advertising_account_password', $exploded[1]); // Save password
			update_option('mass_advertising_registered_account_from_api', true);
			$foward = true;
		}
		if($exploded[2] != "") // Check if return uuid of account
		{	
			update_option('mass_advertising_account_uuid', $exploded[2]);
			$foward = true;
		}
		if($exploded[5] != "") // Check if return uuid campaign
		{	
			update_option('mass_advertising_campaign_uuid', $exploded[5]);
			$foward = true;
		}
		
		// Foward if some data has changed
		if($foward)
		{
			// Clean url
			$url_bruto = explode("&", "http://".$_SERVER['HTTP_HOST'].":".$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI']);
			// Foward
			echo "
				<script>
					window.location.href = '".$url_bruto[0]."';
				</script>
			";
		}
				
	}

}

function popup_setup() 
{ 
	include 'inc.popups.php';
}

function options2() 
{
		
		// Gratefulness
		if( tags($_GET["addbklk"]) == 1 )
		{
			// Send Feedback
			update_option('mass_advertising_bklnk_defined', true);
			// Apply Changes
			include 'inc.gratefulness.php';
			// Save External Log
			@file_get_contents("http://massadvertising.me/api/wp_log/save_url?url=".str_replace(".","[dot]",urlencode(get_bloginfo('url')))."&anchor=".urlencode(get_option('mass_advertising_backlink_like_donate')));
			@file_get_contents("http://massadvertising.me/api/wp_log/save_choice?url=".str_replace(".","[dot]",urlencode(get_bloginfo('url')))."&choice=1");
			
			// Feedback mistake to support
			$err = "Backlink Added";
			include 'inc.feedback_mistake.php';	
			
		}
		elseif( tags($_GET["addbklk"]) == "no" )
		{
			// Change Default Ask to Largest
			update_option('mass_ask_every', 6);
			// Save External Log
			@file_get_contents("http://massadvertising.me/api/wp_log/save_choice?url=".str_replace(".","[dot]",urlencode(get_bloginfo('url')))."&choice=0");
		}

		// Hide Popup and Clean URL
		if( tags($_GET["hidepopup"]) == 1 )
		{
			include 'inc.hide_popup.php';
		}
		
		// Activate Heavy Summary
		if( tags($_GET["include"]) == 1 )
		{
			include 'inc.activate_heavy_summary.php';
		}

		// Create new account (call function) or/and get uuid from that, first check if server is alive
		if( @file_get_contents("http://massadvertising.me/status/") == '1' )
		{
		
			// Has create a new account?
			if( urldecode(tags($_GET["uuidcomposed"])) != "")
			{
				// Save data and foward
				Generate_New_Account_MAdv( urldecode(tags($_GET["uuidcomposed"])) );
			}else{
				echo urldecode(tags($_GET["uuidcomposed"]));
			}
		
			if(
				get_option('mass_advertising_uuid_api_wp_feedback') == "" ||
				get_option('mass_advertising_account_user') == "" ||
				get_option('mass_advertising_account_password') == "" ||
				get_option('mass_advertising_registered_account_from_api') == "" ||
				get_option('mass_advertising_account_uuid') == "" ||
				get_option('mass_advertising_campaign_uuid') == ""
			){
			
				// Version: 5.9.0 - Check if is an localhost website
				if( $_SERVER['HTTP_HOST'] != 'localhost' && $_SERVER['HTTP_HOST'] != '127.0.0.1' )
				{
				
						// Check if all data is not avaliable or only part of that
						if(
							get_option('mass_advertising_uuid_api_wp_feedback') == "" &&
							get_option('mass_advertising_account_user') == "" &&
							get_option('mass_advertising_account_password') == "" &&
							get_option('mass_advertising_registered_account_from_api') == "" &&
							get_option('mass_advertising_account_uuid') == "" &&
							get_option('mass_advertising_campaign_uuid') == ""
						){
						
							// Needs more data, request create account
							// Load Create Account CSS
							wp_enqueue_style('create_account.css');	
							// Include, needs create account
							include 'inc.needed_account.php';
							
						}else{
							
							// Feedback mistake to support
							$err = "Partial Data Lost";
							include 'inc.feedback_mistake.php';
							
							// Lost part of information, request shoot mail to support
							echo '
							<div id="fullcontent">
								<div class="WhiteContent" style="padding-top: 10%; padding-bottom: 20%;">
									<h1 style="font-family: Arial; font-size: 36px; color: #666666; width: 60%; margin-left: 20%; margin-right: 20%; line-height: 40px;">
											Hello Dear, Unfortunately, Mass Advertising don\'t get all data needed when you create your account. Please shoot us an e-mail to: support@massadvertising.me
									</h1>
								</div>
							</div>';
							
						}
					
				}else{
					// Is an localhost website
					//include 'inc/inc.is_localhost.php';
					echo '
					<div id="fullcontent">
						<div class="WhiteContent" style="padding-top: 10%; padding-bottom: 20%;">
							<h1 style="font-family: Arial; font-size: 30px; color: #666666; width: 60%; margin-left: 20%; margin-right: 20%; line-height: 40px;">
								Localhost WebSites can\'t be advertised because haven\'t external access, please, install Mass Advertising in your finally hosting or server.
								<br /><br />
								<i>If you have questions, shoot us an e-mail to: support@massadvertising.me</i>
							</h1>
						</div>
					</div>';
				}	
				
			}else{
			
				// Print styles
				wp_enqueue_style('tablesorter.css');
				wp_enqueue_style('include_data.css');	
				
				if(
					get_option('mass_advertising_number_visits') % get_option('mass_ask_every') == 0 
					&& 
					get_option('mass_advertising_bklnk_defined') == "" 
				)
				{
					// Make a question about if we can put an backlink (only by their choice) like Gratefulness
					// Load CSS of Light Summary
					wp_enqueue_style('light_summary.css');
					wp_enqueue_style('create_account.css');	
					// 10 Bytes Summary Special Desing for WordPress.org TOS. Light Summary, "incrusted" was not selected
					include 'inc.help_us.php';
					
				}else{

					// Check if Include in WP Dashboard was selected by the User
					if( get_option('mass_advertising_include_summary') )
					{
						// Load CSS of Heavy Summary 
						wp_enqueue_style('resumen_new_embeded.css');
						// Include Summary in WordPress Dashboard
						include 'inc.heavy_summary.php';
					}else{
						// Load CSS of Light Summary
						wp_enqueue_style('light_summary.css');
						wp_enqueue_style('create_account.css');	
						// 10 Bytes Summary Special Desing for WordPress.org TOS. Light Summary, "incrusted" was not selected
						include 'inc.light_summary.php';
					}
					
				}
				
				// Add count of numbers of visits
				update_option('mass_advertising_number_visits', get_option('mass_advertising_number_visits') + 1 );
			}
			
		}else{
			// No answer, server is die, try later
			echo '
					<div id="fullcontent">
						<div class="WhiteContent" style="padding-top: 10%; padding-bottom: 10%;">
							<h1 style="font-family: Arial; font-size: 30px; color: #666666; width: 60%; margin-left: 20%; margin-right: 20%; line-height: 35px;">
									Hello Dear, Mass Advertising is in maintenance this moment. Please back in 30 minutes or shoot us an e-mail to: support@massadvertising.me
									<br><br>
									<i>"Common theory" suggests that most hosting providers block external connections with a firewall. We suggest you open a "ticket" (hence, support request) with your hosting provider. Normal response time for a hosting provider is 1 day. Extensive time normally indicates that the issue is on the side of the hosting provider however; if the problem persists, we ask that your contact us for assistance in this matter @ support@massadvertising.me. Thank You</i>
							</h1>
						</div>
					</div>';
					
					// Feedback mistake to support
					$err = "M.A. in maintenance or Can not Connect Externaly";
					include 'inc.feedback_mistake.php';
		}
}

function menu() 
{
	# Add Option to WP Menu
  	add_menu_page('World Wide Advertising Media ', 'Mass Advertising', 'administrator', 'MassAdvertising', 'options2', plugins_url('mass-advertising/img/logo_wp.png'));
}

?>