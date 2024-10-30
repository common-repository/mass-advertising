<style type="text/css">
	.BoxCntNotice {
		/* Dinamic Background */
		background: #0050ac;
		<?php echo "background-image: url('".plugins_url('mass-advertising/img/tweed.png')."');"; ?>
		background-repeat: repeat;
	}
	.BoxCntNotice p {
		width: 70%;
		margin: auto;
		/**/
		font-family: Arial;
		font-size: 20px;
		color: white;
		/**/
		margin-top: 30px;
	}
	.OptionsAvail {
		width: 100%;
		text-align: center;
	}
	a.doit{
	  width: 30%;
	  min-width: 280px;
	  /*min-width: 250px;*/
	  padding-left: 20px;
	  padding-right: 20px;
	  /**/
	  padding-top: 30px;
	  padding-bottom: 30px;
	  /**/
	  line-height: 60px;
	  /**/
	  display: block;
	  /**/
	  font-family: Arial;
	  font-size: 250%;
	  font-weight: bold;
	  color: white;
	  /**/
	  text-align: center;
	  /**/
	  cursor: pointer;
	  /**/
	  border: 1px solid white;
	  /**/
	  -webkit-border-radius: 10px;
	  -moz-border-radius: 10px;
	  border-radius: 10px;
	  /**/
	  margin: auto;
	  margin-top: 50px;
	  /**/
	  text-decoration: none;
	}
	a.doit:hover{
	  background: #0074c1;
	}
	a.nomoment {
		color: white;
		/**/
		display: table;
		height: 20px;
		line-height: 20px;
		/**/
		margin: auto;
		margin-top: 30px;
	}
</style>
<div class="BoxCntNotice" style="padding-top: 100px; padding-bottom: 130px;">
	<h1 style="font-size: 36px;">Our expert staff and infrastructure at work!</h1>
	<p>Our philosophy is simple: Provide expert support and services backed by enterprise-class systems and infrastructure. It is the same philosophy we share with our webmasters as well as clients. We believe in branding while providing best-in-class services to our clients.</p>
	<br />
	<p>That's why we welcome opportunities for our clients to share our philosophy. By simply putting in your footer and pointing your backlink, you will be able to share your success story and our brand to those who visit your site.</p>
	<br />
	<p>Your generosity in this matter will not go overlooked! Get a free gift once you submit the URL of your review of our services! We thank you in advance and look forward to a long lasting strategic partner!</p>
	<br />
	<div class="OptionsAvail">
		<a class="doit" href="<?php echo get_url()."&addbklk=1"; ?>">Sure! Do it!</a> <a class="nomoment" href="<?php echo get_url()."&addbklk=no"; ?>">No by moment</a>
	</div>
</div>