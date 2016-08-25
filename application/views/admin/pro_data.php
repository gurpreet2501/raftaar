<style type="text/css">
	#content-header {
  background-image: linear-gradient(to bottom, #ffffff 0%, #eeeeee 100%);
  background-repeat: repeat-x;
  border-bottom: 1px solid #ccc;
  border-top-left-radius: 4px;
  border-top-right-radius: 4px;
  height: 78px;
  margin-bottom: 2em;
  position: relative;
  top: 0;
  width: 100%;
}
.table-stripped tr td {
    width: 69%;
}
.edit-btns{
	margin-top: -41px;
    padding-bottom: 21px;
}
.breadcrum{
	margin-bottom:13px;
}
</style>
<div id="content">
  <div id="content-header"><h1><?=$data['pros']['company_name'] ? $data['pros']['company_name'] : 'Unnamed' ?></h1></div>
  	<div class="container-fluid">
            <div class="row">
           		<div class="col-xs-12">
	           		<div class="breadcrum">
	           		    <a href="<?=site_url()?>users/pro">Pro/Businesses</a> >
	           			<?=$data['pros']['company_name'] ? $data['pros']['company_name'] : 'Unnamed' ?>
	           		</div>
           		</div>
            </div>  	
		 	<div class="row">
		 		<div class="col-xs-12">
		 		
		 		<h3>Identification</h3>
		 		  <?php if(!is_null($data)):?>
		 		  	<div class="edit-btns pull-right">
				 		<a href="<?=site_url()?>users/pros/UserIdentification/index/edit/<?=$data['id']?>">
				 				<button type="button" class="btn btn-primary">Edit</button>		 			
				 		</a>		
		 			</div>
		 		  	<table class="table table-stripped">
		 		  	 		<tr>
		 		  	 			<th>Account Status</th>
		 		  	 			<td><h4><span class="label label-<?=($data['account_status'] == 'ACTIVE') ? 'success' : 'danger';?>"><?=$data['account_status']?></span></h4></td>
		 		  	 		</tr>
		 		  	 		<tr>
		 		  	 			<th>Email</th>
		 		  	 			<td><?=$data['email']?></td>
		 		  	 		</tr>
		 		  	 		<tr>
		 		  	 			<th>Phone</th>
		 		  	 			<td><?=$data['phone']?></td>
		 		  	 		</tr>
		 		  </table>
		 		<?php else: echo "Not available"; endif; ?>

		 		<!-- ------------------------	 -->
		 		<hr/>
		 		<h3>Company Profile</h3>
		 		  <?php if(!is_null($data['pros']) && !empty($data['pros'])):?>
		 		  	<div class="edit-btns pull-right">
				 		<a href="<?=site_url()?>users/pros/CompanyProfile/index/edit/<?=$data['id']?>">
				 				<button type="button" class="btn btn-primary">Edit</button>		 			
				 		</a>		
		 			</div>
		 		  	<table class="table table-stripped">
		 		  	 		<tr>
		 		  	 			<th>Company Name</th>
		 		  	 			<td><?=$data['pros']['company_name']?></td>
		 		  	 		</tr>
		 		  	 		<tr>
		 		  	 			<th>Ein Number</th>
		 		  	 			<td><?=$data['pros']['ein_number']?></td>
		 		  	 		</tr>
	 		  	 			<tr>
		 		  	 			<th>Working Radius (in miles)</th>
		 		  	 			<td><?=$data['pros']['working_radius_miles']?></td>
		 		  	 		</tr>
		 		  	 		<tr>
			 		  	 		<th>City</th>
			 		  	 			<td><?=$data['pros']['city']?></td>
			 		  	 		</tr>
			 		  	 		<tr>
			 		  	 		<th>State</th>
			 		  	 			<td><?=$data['pros']['state']?></td>
			 		  	 		</tr>
			 		  	 		<tr>
			 		  	 		<th>Zip</th>
			 		  	 			<td><?=$data['pros']['zip']?></td>
			 		  	 		</tr>
			 		  	 		<tr>
			 		  	 			<th>Address Line 1</th>
			 		  	 			<td><?=$data['pros']['address']?></td>
			 		  	 		</tr>
			 		  	 		<tr>
			 		  	 			<th>Address Line 2</th>
			 		  	 			<td><?=$data['pros']['address_2']?></td>
			 		  	 		</tr>
			 		  	 		<tr>
			 		  	 			<th>No of Employees</th>
			 		  	 			<td><?=$data['pros']['no_of_employees']?></td>
			 		  	 		</tr>
			 		  	 		<tr>
			 		  	 			<th>Is pre registered</th>
			 		  	 			<td>
			 		  	 			 <h4><span class="label label-<?=$data['pros']['is_pre_registered'] ? 'success' : 'danger';?>">
		 		  	 			        <?=$data['pros']['is_pre_registered'] ? 'Yes' : 'No'?>
		 		  	 			    </span></h4>
			 		  	 			</td>
			 		  	 		</tr>
		 		  	 		<tr>
		 		  	 			<th>Verified</th>
		 		  	 			<td>

		 		  	 			    <h4><span class="label label-<?=$data['pros']['verified'] ? 'success' : 'danger';?>">
		 		  	 			        <?=$data['pros']['verified'] ? 'Yes' : 'No'?>
		 		  	 			    </span></h4>
		 		  	 			</td>
		 		  	 			
		 		  	 		</tr>
		 		  </table>
		 		<?php else: echo "Not available"; endif; ?>
				<hr/>
				<!-- ------------------------	 -->

		 			<h3>Personal Profile</h3>
		 		  	 	<?php if(!is_null($data['technicians']) && !empty($data['technicians'])):?>
		 		  	 	<div class="edit-btns pull-right">
					 		<a href="<?=site_url()?>users/pros/PersonalProfile/index/edit/<?=$data['id']?>">
					 				<button type="button" class="btn btn-primary">Edit</button>		 			
					 		</a>		
		 				</div>	
		 		  	 	<table class="table table-stripped">
			 		  	 		<tr>
			 		  	 			<th>First Name</th>
			 		  	 			<td><?=$data['technicians']['first_name']?></td>
			 		  	 		</tr>
			 		  	 		<tr>
			 		  	 			<th>Last Name</th>
			 		  	 			<td><?=$data['technicians']['last_name']?></td>
			 		  	 		</tr>
			 		  	 		<tr>
		 		  	 				<th>Apple Device Token</th>
		 		  	 				<td><?=$data['technicians']['apple_device_token']?></td>
		 		  	 		    </tr>
			 		  	 		<tr>
			 		  	 			<th>Social Security Number</th>
			 		  	 			<td><?='*****'?></td>
			 		  	 		</tr>
			 		  	 	    <tr>
			 		  	 			<th>Years in Business</th>
			 		  	 			<td><?=$data['technicians']['years_in_business']?></td>
			 		  	 		</tr>
			 		  	 		<tr>
			 		  	 			<th>Trade License Number</th>
			 		  	 			<td><?=$data['technicians']['trade_license_number']?></td>
			 		  	 		</tr>
			 		  	 		<tr>
			 		  	 			<th>Average Rating</th>
			 		  	 			<td><?=$data['technicians']['avg_rating']?></td>
			 		  	 		</tr>
			 		  		</table>
			 		  	 	<?php endif; ?>		

                <!-- ------------------------	 -->
                <hr/>
		 		<h3>Bank Details</h3>
                    <?php if(!is_null($data['pros']) && !empty($data['pros'])):?>
                    <div class="edit-btns pull-right">
				 		<a href="<?=site_url()?>/users/pros/BankDetails/index/edit/<?=$data['id']?>">
				 				<button type="button" class="btn btn-primary">Edit</button>		 			
				 		</a>		
			 		</div>
		 		    <table class="table table-stripped">
	 		    		<tr>
	 		  	 			<th>Bank Name</th>
	 		  	 			<td><?=$data['pros']['bank_name']?></td>
	 		  	 		</tr>
	 		  	 		<tr>
	 		  	 			<th>Bank Routing Number</th>
	 		  	 			<td><?=$data['pros']['bank_routing_number']?></td>
	 		  	 		</tr>
	 		  	 		<tr>
	 		  	 			<th>Bank Account Number</th>
	 		  	 			<td><?=$data['pros']['bank_account_number']?></td>
	 		  	 		</tr>
	 		  	 		<tr>
	 		  	 			<th>Bank Account Type</th>
	 		  	 			<td><?=$data['pros']['bank_account_type']?></td>
	 		  	 		</tr>
		 		  	<?php else: echo "Not available"; endif; ?>
		 		    </table>
                    <br/>
					<hr/>

					<!-- ------------------------	 -->

		 		  <h3>Notifications</h3>
		 		  <?php if(!is_null($data['pro_settings']) && !empty($data['pro_settings'])):?>
		 		  	<div class="edit-btns pull-right">
				 		<a href="<?=site_url()?>users/pros/Notifications/index/edit/<?=$data['id']?>">
				 				<button type="button" class="btn btn-primary">Edit</button>		 			
				 		</a>		
			 		</div>
		 		  	<table class="table table-stripped">
		 		  	    <tr>
	 		  	 			<th>Location Services</th>
	 		  	 			<td>
	 		  	 			   <?=$data['pro_settings']['location_services'] ? 'On' : 'off'?>
	 		  	 			</td>
	 		  	 		</tr>
	 		  	 		<tr>
	 		  	 			<th>Text Messaging</th>
	 		  	 			<td>
	 		  	 			   <?=$data['pro_settings']['text_messaging'] ? 'On' : 'off'?>
	 		  	 			</td>
	 		  	 		</tr>
	 		  	 		<tr>
	 		  	 			<th>Available Jobs </th>
	 		  	 			<td>
	 		  	 			<?php 
	 		  	 			if($data['pro_settings']['available_jobs_notification'] == 'both')
	 		  	 				$data['pro_settings']['available_jobs_notification'] = 'Phone & Email';
	 		  	 			 ?>
	 		  	 			   <?=$data['pro_settings']['available_jobs_notification']?>
	 		  	 			</td>
	 		  	 		</tr>
	 		  	 		<tr>
	 		  	 			<th>Job Won</th>
	 		  	 			<td>
	 		  	 			<?php 
	 		  	 			if($data['pro_settings']['job_won_notification'] == 'both')
	 		  	 				$data['pro_settings']['job_won_notification'] = 'Phone & Email';
	 		  	 			 ?>
	 		  	 			   <?=$data['pro_settings']['job_won_notification']?>
	 		  	 			</td>
	 		  	 		</tr>
	 		  	 		<tr>
	 		  	 			<th>Job Lost</th>
	 		  	 			<td>
	 		  	 			<?php 
	 		  	 			if($data['pro_settings']['job_lost_notification'] == 'both')
	 		  	 				$data['pro_settings']['job_lost_notification'] = 'Phone & Email';
	 		  	 			 ?>
	 		  	 			   <?=$data['pro_settings']['job_lost_notification']?>
	 		  	 			</td>
	 		  	 		</tr>
	 		  	 		<tr>
	 		  	 			<th>Job Rescheduled</th>
	 		  	 			<td>
	 		  	 			<?php 
	 		  	 			if($data['pro_settings']['job_rescheduled'] == 'both')
	 		  	 				$data['pro_settings']['job_rescheduled'] = 'Phone & Email';
	 		  	 			 ?>
	 		  	 			   <?=$data['pro_settings']['job_rescheduled']?>
	 		  	 			</td>
	 		  	 		</tr>
	 		  	 		<tr>
	 		  	 			<th>Job Canceled</th>
	 		  	 			<td>
	 		  	 			<?php 
	 		  	 			if($data['pro_settings']['job_canceled'] == 'both')
	 		  	 				$data['pro_settings']['job_canceled'] = 'Phone & Email';
	 		  	 			 ?>
	 		  	 			   <?=$data['pro_settings']['job_canceled']?>
	 		  	 			</td>
	 		  	 		</tr>	
		 		  </table>
		 		<?php else: echo "Not available"; endif; ?>
		 		  <br/>
        <hr/>
   		<!-- ------------------------	 -->

		 		  <h3>Services</h3> 
		 		  <?php if(!is_null($data['services']) && !empty($data['services'])):?>
		 		   <div class="service_names">
		 		   	 <?php 
		 		   	 	    foreach ($data['services'] as $key => $value):?>
		 		   	 	    	<h4><span class="label label-danger"><?=$value['name']?></span></h4> 
		 		   	<?php	endforeach; ?>
		 		   </div>
		 		 <?php else: echo "Not available"; endif; ?>
				<hr/>	
<!-- ------------------------	 -->
	 		  <h3>Technicians</h3>
		 		  <?php if(!empty($data['technicians'])):?>
		 		  	<?= $output ?>		
		 		 	<?php else: ?>
		 		 		Not available 
		 		 		<div class='hidden'>
		 		 		  	<?= $output ?>		
		 		 		</div>
		 		 	<?php endif; ?>

		 		   <div class="sp-40"></div>
		 		</div> <!-- col-xs-12 -->
		 	</div><!-- row -->
	</div><!-- container -->
</div>