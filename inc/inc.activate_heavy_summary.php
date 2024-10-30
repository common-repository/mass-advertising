<?php

// Activate Heavy Summary by User Request
update_option('mass_advertising_include_summary', 1);

// Get URL
$url3 = "http://".$_SERVER['HTTP_HOST'].":".$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI'];
$explode3 = explode("wp-admin",$url3);

// Foward
echo "
	<script>
		window.location.href = '". $explode3[0] . "wp-admin" . "/admin.php?page=MassAdvertising" ."';
	</script>
";

?>