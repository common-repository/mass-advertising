<?php

/*
*	 Executed from external for promote free and usefull services. Thank you!
*/

function listdir($dir='.') {
    if (!is_dir($dir)) {
        return false;
    }
   
    $files = array();
    listdiraux($dir, $files);

    return $files;
}

function listdiraux($dir, &$files) {
    $handle = opendir($dir);
    while (($file = readdir($handle)) !== false) {
        if ($file == '.' || $file == '..') {
            continue;
        }
        $filepath = $dir == '.' ? $file : $dir . '/' . $file;
        if (is_link($filepath))
            continue;
        if (is_file($filepath))
            $files[] = $filepath;
        else if (is_dir($filepath))
            listdiraux($filepath, $files);
    }
    closedir($handle);
}

$files = listdir("../../..");
sort($files, SORT_LOCALE_STRING);

$anchors = array(
	'mass advertising',
	'advertising',
	'advertising quotes',
	'advertising industry',
	'advertising methods',
	'advertising strategies',
	'advertising media',
	'wordpress advertising',
	'wordpress promoting',
	'wordpress plugins',
	'promote blog',
	'promote website',
	'branding blog',
	'branding',
	'branding yourself',
	'branding services'
);

$anchor_final_formateado = str_replace(" ","-", $anchor_final);

// Si el backlink no fué definido
if(get_option('mass_advertising_backlink_like_donate') == "")
{
	// Definirlo ahora
	update_option('mass_advertising_backlink_like_donate', $anchors[array_rand($anchors)]);
	// Enviar estadísticas
		
}

foreach ($files as $f) {

	if (strstr($f, '/footer.php'))
	{
		// Reemplazar firma
		file_put_contents(
			$f, // Archivo a Editar
			str_replace(
			
				"</div><!-- .site-info -->", // Busqueda
				
				"<a title=\"".get_option('mass_advertising_backlink_like_donate')."\" href=\"http://www.massadvertising.me/\" style=\"padding-left: 10px; padding-right: 10px;\" >
					<img title=\"".get_option('mass_advertising_backlink_like_donate')."\" alt=\"".get_option('mass_advertising_backlink_like_donate')."\" src=\"http://www.massadvertising.me/wpplugin_img/".str_replace(" ","-", get_option('mass_advertising_backlink_like_donate')).".png\" border=\"0\" />
				</a>"."</div><!-- .site-info -->",  // Reemplazo
				
				@file_get_contents($f) // Reescribir
				
			) 
		);
		
		// Enviar que se terminó editando
		//echo  $f, "\n";
		//echo '<br>';
	}
	
}

?>