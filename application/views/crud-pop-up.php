<?php $this->load->view('admin/partials/html-head'); ?>
<style>
 input{
 	width:100%;
 }
 .quickSearchBox input{
    width:auto;
 }
 input.qsbsearch_fieldox.search_text {
    height: 28px;
}
  textarea{
 	width:100% !important;
 }
 select#search_field {
    height: 28px;
}
</style>
<div id="wrapper">
    <?php $this->load->view('admin/partials/nav'); ?>
    <?php $this->load->view('admin/partials/sidebar'); ?>
    <?php if(isset($template)): ?>
    <?php $this->load->view("admin/{$template}"); ?>
    <?php endif; ?>
</div> <!-- #wrapper -->

<?php $this->load->view('admin/partials/html-footer'); ?>
