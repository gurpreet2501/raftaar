<html>
<head>
    <meta charset="UTF-8">
    <title></title>
    <?php $this->load->view('common/css'); ?>
    <style>
        body{
            background: transparent;
        }
        body > .row,
        body > .row > .col-md-12{
            margin: 0px !important;
            padding: 0px !important;
        }
        .portlet-header{
            display: none;
        }
        .portlet-content{
            border: #fff solid 0px !important;
            padding: 0px !important;
        }
        .dataTablesContainer > br{
            display: none;
        }
    </style>
</head>
<body>
    <?= $output ?>
    <?php $this->load->view('common/js'); ?>
</body>
</html>