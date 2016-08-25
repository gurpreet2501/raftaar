<?php $this->load->view('admin/partials/html-head'); ?>


<div id="wrapper">
    <?php $this->load->view('admin/partials/header'); ?>
    <?php $this->load->view('admin/partials/nav'); ?>
    <?php $this->load->view('admin/partials/sidebar'); ?>
    <?php if(isset($template)): ?>
    <?php $this->load->view("admin/{$template}"); ?>
    <?php endif; ?>
</div> <!-- #wrapper -->

<?php $this->load->view('admin/partials/footer'); ?>
<?php $this->load->view('admin/partials/html-footer'); ?>
