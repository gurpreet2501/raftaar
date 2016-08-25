<a href="#" id="manage-technician-btn">Manage Technician</a>
<div id="manage-technician-model" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Manage Technician </h4>
      </div>
      <div class="modal-body">
        <iframe src="<?= site_url('/data/technician/added_by/') .'/'. $id ?>" frameborder="0" ></iframe>
      </div>
      <?php /*
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      */ ?>
    </div>

  </div>
</div>