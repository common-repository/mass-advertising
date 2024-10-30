<?php

wp_enqueue_style( 'wp-pointer' );
wp_enqueue_script( 'jquery-ui' );
wp_enqueue_script( 'wp-pointer' );
wp_enqueue_script( 'utils' );

# Sum +1 or Start at Cero
if(get_option( 'mass_advertising_POPUP' ) == "")
{
	# Start at Cero
	update_option( 'mass_advertising_POPUP', 0 );
}else{
	# Sum +1
	update_option( 'mass_advertising_POPUP', get_option( 'mass_advertising_POPUP' ) + 1 );
}
		
# Check how time needs to load
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time; # Start time var
		
		# Timeout file_get_contents
		$ctx = stream_context_create(array('http'=>
			array(
				'timeout' => 7, # 7 seconds (we don't want to angry our users, really?)
			)
		));	
				
		# Get Content (Light Summary)
					
					#
					# Note: Light Summary start to be used for pop-up state check
					#
					
					
		$state = @file_get_contents
		( 
				'http://en.massadvertising.me/resumen_new_embedded_light?id='.
					get_option('mass_advertising_campaign_uuid')
				.'&acc='.
					get_option('mass_advertising_account_uuid'), false, $ctx 
		);
		
		#sleep(2);
		
# Loaded In...	
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$finish = $time;
$total_time = round(($finish - $start), 4);

# Slow?
if( 
	// Version: 5.0.4 - Fixed to 7 because 3 was too small and sometimes take it like "false-positive"
		#	$total_time > 7 # More than 7 seconds?
	// Version: 6.5.0 - Moved limit to 13 seconds
		$total_time > 13 # More than 13 seconds?
)  
{
	# Yes, is slow, check how many times is slow
	# if( get_option( 'mass_advertising_POPUP_TimeOut' ) < 2 ) # Check just by 3 times, if something is wrong, nextly block by 5 minutes ALL POPUP SYSTEM
	// Version: 6.5.0 - Check just by 4 times and unplug by 2 minutes
	if( get_option( 'mass_advertising_POPUP_TimeOut' ) < 4 ) # Check just by 4 times, if something is wrong, nextly block by 5 minutes ALL POPUP SYSTEM
	{
		# Less or equal than 3 times, SO, SUM +1 (unfinded exist works too)
		update_option( 'mass_advertising_POPUP_TimeOut', get_option( 'mass_advertising_POPUP_TimeOut' ) + 1 );
	}else{
		# More than three
		# Block by 5 minutes all MASS ADVERTISING system, INCLUDE, MASS ADV. BUTTON
		update_option( 'mass_advertising_BLOCK_UNTIL', date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s').' + 2 minute')) ); # Changed to 2 minutes in 6.5.0 Version
		
		// Feedback mistake to support
		$err = "Block by TimeOut [".$total_time." Seconds]";
		include 'inc.feedback_mistake.php';	
	}
	
}else{
	# Put empty timeout, looks ok...
	delete_option('mass_advertising_POPUP_TimeOut'); # Delete
	delete_option('mass_advertising_BLOCK_UNTIL'); # Delete
}

#echo 'Page generated in '.$total_time.' seconds.'; # Print
	
# Explode Data of Summary		
$state_explo = explode("#", $state);

# Show "is Running" once if was paused before
if( $state_explo[0] == 2 )
{
	# Ok
	update_option( 'mass_advertising_POPUP_Show_Running', 1);
}
elseif( get_option( 'mass_advertising_POPUP_Show_Running') == "" && $state_explo[0] == 3 ) # Is absolutely empty with the campaign running?
{
	update_option( 'mass_advertising_POPUP_Show_Running', 1);
}
if
(
	get_option( 'mass_advertising_registered_account_from_api' ) != "1" # Campaign haven't account?
	&&
	get_option( 'mass_advertising_POPUP' ) <= 10 # Don't show more twice every 5 pages
	&&
	(get_option( 'mass_advertising_POPUP' ) % 5) == 0 # Don't show more twice every 5 pages
)
{ 
	# POPUP
	$print_in =  'content: \'<h3 id="popup-header-new-account">Requires a new account to be registered in order to continue.</h3><p>Should you have any questions, please contact us at support@massadvertising.me</p>\',';
}
elseif
(
	# Some data was lost?
	get_option( 'mass_advertising_account_user' ) == "" ||
	get_option( 'mass_advertising_account_password' ) == "" ||
	get_option( 'mass_advertising_account_uuid' ) == "" ||
	get_option( 'mass_advertising_campaign_uuid' ) == ""
)
{
	# Just prinf if have created account (note, sometimes print about needs account and sometimes not)
	if(get_option( 'mass_advertising_registered_account_from_api' ) == "1")
	{
		# Yes was lost, but show POPUP just by 3 times
		if( get_option( 'mass_advertising_POPUP_Data_Lost' ) <= 3 )
		{
			# Sum one time
			update_option( 'mass_advertising_POPUP_Data_Lost', get_option( 'mass_advertising_POPUP_Data_Lost' ) + 1 );
			# POPUP
			$print_in =  'content: \'<h3 id="popup-header-new-package">Oops...Data was not saved during registration process.</h3><p><strong>Please contact us at: support@massadvertising.me</p>\',';
			# Send an e-mail with feedback of error
			wp_mail( "emergency@pluscaptcha.com", "User Data Lost on WP Plugin", "Account UUID: ". get_option( 'mass_advertising_account_uuid' ) . ", Campaign: ".get_option( 'mass_advertising_campaign_uuid' ) );
		}else{
			# Sum one time
			update_option( 'mass_advertising_POPUP_Data_Lost', get_option( 'mass_advertising_POPUP_Data_Lost' ) + 1 ); # If we don't sum this, will be an infinite cycle
		}
	}

}
elseif 
( 
	$state_explo[1] == 0 &&  # Campaigns have account, but haven't selected package
	(get_option( 'mass_advertising_POPUP' ) % 5) == 0 # Show every 5 times
)
{
	# POPUP
	$print_in =  'content: \'<h3 id="popup-header-new-package">An advertising package must be selected in order to run your campaign.</h3><p><strong>Simply navigate to your summary and select the package that meets your needs.</p>\',';
}
elseif 
( 
	# Campaign is in creation process? (by our side)
	$state_explo[1] == 1 && 
	$state_explo[0] == 1 &&
	get_option( 'mass_advertising_POPUP_Patient' ) < 1 # Show POPUP just by once
)
{
	# Hide POPUP
	update_option( 'mass_advertising_POPUP_Patient', get_option( 'mass_advertising_POPUP_Patient' ) + 1 );
	# POPUP
	$print_in =  'content: \'<h3 id="popup-header-new-package">Please, be patient!</h3><p><strong>All campaigns undergo a rigorous battery of quality assurance tests to ensure the very best representation of YOUR site. This process can take up to 24hrs.</i></strong>Should you have any questions, please contact us at support@massadvertising.me</p>\',';
}
elseif
( 
	$state_explo[0] == 2 &&  # Campaign is Paused? (by any reason)
	(get_option( 'mass_advertising_POPUP' ) % 6) == 0 # Show every 6 times
)
{ 
	# POPUP
	$print_in =  'content: \'<h3 id="popup-header-paused">Your Campaign is temporarily paused.</h3><p>Check your Summary Stats, Results and Renew the campaign. If you have questions, send us an e-mail to: support@massadvertising.me</p>\',';
}
elseif
( 
	$state_explo[0] == 3 && # Campaign is running?
	(get_option( 'mass_advertising_POPUP' ) % 15) == 0 # Show every 15 times
	|| # If was paused before show anywhay
	$state_explo[0] == 3 && # Is running, really?
	get_option( 'mass_advertising_POPUP_Show_Running' ) == 1 # Show POPUP because is running after pause
)
{
	# Hide POPUP
	update_option( 'mass_advertising_POPUP_Show_Running', 0 );
	# POPUP
	
	// Version: 5.0.5 - Check if is Premium Account
	
	# Timeout file_get_contents
	$ctx_premium = stream_context_create(array('http'=>
		array(
			'timeout' => 5, # 5 seconds (we don't want to angry our users, really?)
		)
	));	
			
	# Get Content
	$premium_or_not = @file_get_contents( 'http://massadvertising.me/api/wp_log/is_premium?id='.get_option('mass_advertising_campaign_uuid'), false, $ctx_premium );
	
	if($premium_or_not == 0)
	{
		# Regular Account
		$print_in =  'content: \'<h3 id="popup-header-running">Your Campaign is Running, Congrats! </h3><p><strong>Check your Summary in the next 24hrs! Remember: Your campaign needs to be manually restarted/renewed. If you are a Free Package subscriber, then you need to manually go into your account and manually renew/restart your campaign. If you have questions, send us an e-mail to: support@massadvertising.me</p>\',';
	}
	else if($premium_or_not == 1)
	{
		# Premium Account
		$print_in =  'content: \'<h3 id="popup-header-running">Congrats on becoming a Premium subscriber!</h3><p><strong>Now you can harness features such as: Zero expiry on your campaign! Check your summary for more details. Thank you for trusting us! Do you want to know more benefits about your premium upgrade? Contact us via e-mail to: support@massadvertising.me</p>\',';
	} # No answer from server, nothing to do...
}


if($print_in != '') # Don't delete, this hide all script if is not message
{

?>
<style type="text/css">
	#popup-header-new-account {
		background-color: red; border-color: red;
	}
	#popup-header-new-account:before {
		color: red;
	}
	#popup-header-new-package {
		background-color: #FF6633; border-color: #FF6633;
	}
	#popup-header-new-package:before {
		color: #FF6633;
	}
	#popup-header-paused {
		background-color: #FF6633; border-color: #FF6633;
	}
	#popup-header-paused:before {
		color: #FF6633;
	}
	#popup-header-running {
		background-color: #009900; border-color: #009900;
	}
	#popup-header-running:before {
		color: #009900;
	}
</style>
<script type="text/javascript">
	(function($) {
		var setup = function() {
			$('#toplevel_page_MassAdvertising').pointer({
					<?php echo $print_in; ?>
					position: {
						edge: 'left', 
						align: 'center'
					},
					pointerWidth: 350,
					close: function() {
					}
			}).pointer('open');
		};
		$(window).bind('load.wp-pointers', setup);
	})(jQuery);
</script>
<?php
} # End
?>