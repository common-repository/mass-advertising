<?php

# �ntes de mostrar cualquier tipo de resumen, ejecutar package, para que pueda guardarlo 
# y finalmente refrescar sin el package como t�l (as� no se guarda dos veces
# Defini� alg�n tipo de package?
if( tags($_GET["package"]) != "" )
{
	# Si, defini� uno
	# Enviar la solicitud
	file_get_contents('http://en.massadvertising.me/resumen_new_embedded_v3.php?id='.
	tags($_GET["cal_campuuid"]).'&acc='.tags($_GET["cal_accountuuid"]).'&package='.tags($_GET["package"]).'&vers='.plugin_get_version());
	
	# Obtener la url necesaria para redireccionar
	$url_foward_clean = "http://".$_SERVER['HTTP_HOST'].":".$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI'];
	$url_foward_clean = str_replace("&package=shared2", "", $url_foward_clean);
	$url_foward_clean = str_replace("&package=semidedicated", "", $url_foward_clean);
	
	# Foward (Redireccionar)
	echo '
	<script type="text/javascript">
		window.location = "'.$url_foward_clean.'"
	</script>';

} # No defini� un package, no pasa nada, continuar...

// Calibration ?
if( tags($_GET["cal_campuuid"]) != "" && tags($_GET["cal_accountuuid"]) != "")
{
	// With Calibration Option
	$url = 'http://en.massadvertising.me/resumen_new_embedded_v3.php?id='.tags($_GET["cal_campuuid"]).'&acc='.tags($_GET["cal_accountuuid"]).'&package='.tags($_GET["package"]).'&vers='.plugin_get_version();
}else{
	// Without Calibration Option
	$url = 'http://en.massadvertising.me/resumen_new_embedded_v3.php?id='.get_option('mass_advertising_campaign_uuid').'&acc='.get_option('mass_advertising_account_uuid').'&package='.tags($_GET["package"]).'&vers='.plugin_get_version();
}

// Print External Request
echo @file_get_contents( $url );

?>