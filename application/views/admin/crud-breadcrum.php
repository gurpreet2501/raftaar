<style type="text/css">
	.breadcrum {
	    margin-bottom: 15px;
	}
</style>
<?php
$this->load->helper('translation');
?>
<div id="content">
	<div id="content-header">
		<h1><?= $heading ?></h1>
	</div> <!-- #content-header -->	
	<div id="content-container">
	  <div class="breadcrum">
			<a href="<?=site_url()?>users/pro">Pro/Businesses</a> > 
	        <a href="<?=site_url()?>users/Pro/pro_data/<?=$id?>"><?=$companyName?></a> > 
	        <?=nameTranslation(ucfirst($this->uri->segment(3)))?>
	  </div>
      <?= $output ?>
	</div> <!-- #content -->
</div>