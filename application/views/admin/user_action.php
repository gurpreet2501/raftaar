<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="<?=base_url('assets/css/bootstrap.min.css')?>">
	<title>Take action on user</title>
</head>
<body>
<div class="container">
<br/>
	<div class="row">
		<div class='col-xs-12'>
			<div class="text-center">
				<h3>Choose action on user</h3>
				<hr/>
			</div>
		</div>	
	</div>
	<div class="row">
		<div class="col-xs-12 text-center">
			<a class="btn btn-danger close_popup"  href="<?=site_url('data/user_action/Blocked/'.$type.'/'.$user_id.'/'.$record_id)?>" role="button">Block</a>
			<a class="btn btn-warning close_popup"  href="<?=site_url('data/user_action/Suspend/'.$type.'/'.$user_id.'/'.$record_id)?>" role="button">Suspend</a>
			<a class="btn btn-success close_popup" href="<?=site_url('data/user_action/Just_delete/'.$type.'/'.$user_id.'/'.$record_id)?>"" role="button">Just Delete</a>
		</div>
	</div>

</div>
<?php $this->load->view('admin/partials/html-footer');?>
</body>
</html>