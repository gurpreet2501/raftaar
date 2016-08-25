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
     <?php $this->load->view('common/css') ?>
</head>
<?php
$replies = strpos($_SERVER['REQUEST_URI'],'replies/edit') ? 'edit_replies' : '';
$spouts = strpos($_SERVER['REQUEST_URI'],'spouts/edit') ? 'edit_spouts' : '';
$sort = strpos($_SERVER['REQUEST_URI'],'spouts') ? 'sort_desc' : (strpos($_SERVER['REQUEST_URI'],'replies')) ? 'sort_desc' : '';
?>
<body class="<?=trim($replies)?> <?=trim($spouts)?> <?=trim($sort)?>">