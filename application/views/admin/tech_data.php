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
</style>
<div id="content">
  <div id="content-header">
    <h1>
    <?php if(!is_null($data['pro_profile'])):?>
    		<a href="/admin/users/Pro/pro_data/<?=$data['pro_profile']['user_id']?>"><?=$data['pro_profile']['company_name']?></a> -> <?=$data['first_name'].' '.$data['last_name']?>   
    <?php endif; ?>		
   	</h1>
    </div>
	<div class="container">
		 	<div class="row">
	            <div class="col-xs-8">
	        		
	        	   <a href="<?=base_url('/users/technician/index/edit/'.$data['user_id'])?>"><button type="button" class="pull-right btn btn-danger">Edit</button></a>
	        	<hr/>  
		 		  	 	    <?php  if(!is_null($data)):?>
		 		  	 	      <table class="table table-stripped">
			 		  	 		<tr>
			 		  	 			<th>First Name</th>
			 		  	 			<td><?=$data['first_name']?></td>
			 		  	 		</tr>
			 		  	 		<tr>
			 		  	 			<th>Last Name</th>
			 		  	 			<td><?=$data['last_name']?></td>
			 		  	 		</tr>
							<?php   if(!is_null($data['users'])): ?>	 		  	 		
				 		  	 		<tr>
				 		  	 			<th>Phone</th>
				 		  	 			<td><?=$data['users']['phone']?></td>
				 		  	 		</tr>
				 		  	 		<tr>
				 		  	 			<th>Account Status</th>
				 		  	 			<td>
				 		  	 			   <h4><span class="label label-<?=($data['account_status'] == 'ACTIVE') ? 'success' : 'danger';?>"><?=$data['users']['account_status']?></span></h4></td>
				 		  	 		</tr>
				 		  	<?php endif; ?> 		
		 		  	 
		 		  	 	</table>
			 		  	 	<?php else: echo "<h1 class='text-center'> Nothing Found </h1>"; endif; ?>		

                
		 		   <div class="sp-40"></div>
		 		</div> <!-- col-xs-12 -->
		 	</div><!-- row -->
	</div><!-- container -->
</div>