<?php

// Hide PopUp and Back to Page of Plugins
update_option('mass_advertising_hide_popup', 1);

// Get URL
$url3 = "http://".$_SERVER['HTTP_HOST'].":".$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI'];
$explode3 = explode("wp-admin",$url3);

// Foward
echo "
	<script>
		window.location.href = '". $explode3[0] . "wp-admin" . "/plugins.php" ."';
	</script>
";

?>