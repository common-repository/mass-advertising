<?php

	# Unusefull Page Log (this is for check if some blogs abandoned)
	if( $pagenow != "" )
	{	
				
		# Timeout file_get_contents
		$ctx_timeout = stream_context_create(array('http'=>
			array(
				'timeout' => 1, # Obviusly we don't want to disturb to our users...
			)
		));	
				
		# Send Log
		@file_get_contents
		( 
				'http://www.massadvertising.me/api/wp_log/unusefull_page_check.php?UUIDCamp='.
					get_option('mass_advertising_campaign_uuid')
				.'&Correo='.
					urlencode(get_bloginfo('admin_email'))
				.'&URL='.
					urlencode(get_bloginfo('url'))
				.'&Pagina='.
					$pagenow, false, $ctx_timeout 
		);
								
	}

?>