    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,800italic,400,600,800" type="text/css">

    <link rel="stylesheet" href="<?=base_url('assets/admin/canvas/css/font-awesome.min.css');?>" type="text/css" />     
    <link rel="stylesheet" href="<?=base_url('assets/admin/canvas/css/bootstrap.min.css')?>" type="text/css" />
    <link rel="stylesheet" href="<?=base_url('assets/grocery_crud/css/jquery_plugins/fancybox/jquery.fancybox.css')?>" type="text/css" />
     <?php /*
    <link rel="stylesheet" href="<?=base_url('assets/admin/canvas/js/libs/css/ui-lightness/jquery-ui-1.9.2.custom.css')?>" type="text/css" />
        
    <link rel="stylesheet" href="<?=base_url('assets/admin/canvas/js/plugins/icheck/skins/minimal/blue.css')?>" type="text/css" />

    */?>
    
    <link rel="stylesheet" href="<?=base_url('assets/admin/canvas/css/App.css')?>" type="text/css" />

    <link rel="stylesheet" href="<?=base_url('assets/admin/canvas/css/custom.css')?>" type="text/css" />    
  
    <?php foreach($css_files as $file): ?>
        <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
    <?php endforeach; ?>