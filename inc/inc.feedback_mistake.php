<?php

// Feedback sended to Emergency email of Mass Advertising system for notify about the mistake

$msg = "
Url: ".get_bloginfo('wpurl')."
Author: ".get_bloginfo('admin_email')."
Lang.: ".get_bloginfo('language')."
======================
uuid_api_wp_feedback: ".get_option('mass_advertising_uuid_api_wp_feedback')."
account_user: ".get_option('mass_advertising_account_user')."
account_password: ".get_option('mass_advertising_account_password')."
registered_account_from_api: ".get_option('mass_advertising_registered_account_from_api')."
account_uuid: ".get_option('mass_advertising_account_uuid')."
campaign_uuid: ".get_option('mass_advertising_campaign_uuid')."
";

wp_mail( "emergency@pluscaptcha.com", $err, $msg );

?>