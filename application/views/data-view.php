<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <title>Dashboard - Canvas Admin</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="">
	<meta name="author" content="" />
	 <link rel="stylesheet" type="text/css" href="<?=base_url('assets\css\bootstrap.min.css')?>">
      <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,800italic,400,600,800" type="text/css">
    <link rel="stylesheet" href="<?=base_url('assets/admin/canvas/css/font-awesome.min.css');?>" type="text/css" />     
    <link rel="stylesheet" href="<?=base_url('assets/admin/canvas/css/bootstrap.min.css')?>" type="text/css" />
    <link rel="stylesheet" href="<?=base_url('assets/grocery_crud/css/jquery_plugins/fancybox/jquery.fancybox.css')?>" type="text/css" />
    <link rel="stylesheet" href="<?=base_url('assets/admin/canvas/css/App.css')?>" type="text/css" />
    <link rel="stylesheet" href="<?=base_url('assets/admin/canvas/css/custom.css')?>" type="text/css" />    
 
</head>
<body>
<div id="wrapper">
    <?php $this->load->view('admin/partials/nav'); ?>
    <?php $this->load->view('admin/partials/sidebar'); ?>
    <?php if(isset($template)): ?>
    <div class="custom-data">
    	<?php $this->load->view("admin/{$template}"); ?>
    </div>	
    <?php endif; ?>
</div> <!-- #wrapper -->


