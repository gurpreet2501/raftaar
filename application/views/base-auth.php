<?php 
$css_files = isset($css_files) ? $css_files : [];
$js_files = isset($js_files) ? $js_files : [];
?>
<html>
<head>
    <meta charset="UTF-8">
    <title></title>
    <?php $this->load->view('common/css', array('css_files' => $css_files)); ?>
</head>
<body class="base-auth">

    <div class="wrapper">
        <?php if (isset($tmp)): ?>
            <?php $this->load->view($tmp); ?>
        <?php endif; ?>        
    </div>

    
    <?php $this->load->view('common/js', array('js_files' => $js_files)); ?>
</body>
</html>