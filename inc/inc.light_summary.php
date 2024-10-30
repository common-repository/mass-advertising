<?php


// Special 10 Bytes Summary Desing for Stay in Harmony With WordPress.org TOS
$smry = @file_get_contents( 'http://en.massadvertising.me/resumen_new_embedded_light?id='.get_option('mass_advertising_campaign_uuid').'&acc='.get_option('mass_advertising_account_uuid') );

// Explode Data of Summary
$smry_data = explode("#", $smry);


?>
<style type="text/css">
	.BoxCntNotice {
		/* Dinamic Background */
		background: #0050ac;
		<?php echo "background-image: url('".plugins_url('mass-advertising/img/tweed.png')."');"; ?>
		background-repeat: repeat;
	}
</style>
<div class="BoxCntNotice" style="padding-top: 50px; padding-bottom: 80px;">
	<h1>Quick View Summary</h1>
	<p class="little-disclamer">(for bandwith save)</p>
	<span class="status <?php if($smry_data[0] == 3){ echo 'Green'; }elseif($smry_data[0] == 2 || $smry_data[0] == 1){ echo 'Yellow'; }elseif($smry_data[0] == 0){ echo 'Red'; }else{ echo 'Red'; } ?>">
		<strong>Campaign is 
		<?php 
		if($smry_data[0] == 3){ 
			echo 'Running'; 
		}elseif($smry_data[0] == 2){ 
			echo 'Paused (24HS has expired)'; 
		}elseif($smry_data[0] == 1){ 
			// Type of Activation Needed (by Your Side or by Our Side)
			if($smry_data[1] == 0) 
			{ 
				echo 'Pending Activation<br><br>(by your side, go to Full Summary and select your package)'; 
			}else{ 
				echo 'Pending Activation (by our side, can take 8 - 16HS)';
			}
		}elseif($smry_data[0] == 0){ 
			echo 'Finished or Cancelled'; 
		}else{ 
			echo 'Finished or Cancelled'; 
		}
		 ?>
		</strong>
	</span>
	<div class="line"></div>
	<!-- Micro Summary -->
	<div class="Cnt-Box">
		<div class="BoxGrey">
			Visualisations <strong><?php echo $smry_data[2]; ?></strong>
		</div>
		<div class="BoxGrey">
			Interactions <strong><?php echo $smry_data[3]; ?></strong>
		</div>
		<div class="BoxGrey">
			Click Thrus <strong><?php echo $smry_data[4]; ?></strong>
		</div>
		<div class="BoxGrey">
			Most Common Country <strong><?php echo ($smry_data[5] != "") ? $smry_data[5] : 'Unk.'; ?></strong>
		</div>
	</div>
	<!-- Micro Summary End -->
	<h1 style="margin-top: 80px; margin-bottom: 100px;">Full Summary</h1>
	<div class="line"></div>
	<a class="foward bg-grey" style="margin-top: -45px;" href="<?php echo 'http://en.massadvertising.me/resumen_new_embedded_v3.php?id='.get_option('mass_advertising_campaign_uuid').'&acc='.get_option('mass_advertising_account_uuid').'&package='.tags($_GET["package"]).'&tab_popup=1'.'&vers='.plugin_get_version(); ?>" target="_blank">
		VIEW FULL SUMMARY IN NEW TAB
	</a>
	<p class="little-disclamer">or</p>
	<a class="fowardspecial bg-grey" href="<?php echo foward_include_summary(); ?>">
		INCLUDE SUMMARY IN WP DASHBOARD<br><i>(NEED "DECENT" HOSTING WITH GOOD BANDWITH)</i>
	</a>
</div>