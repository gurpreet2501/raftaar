<?php 
/*

<script src="<?=base_url('assets/admin/canvas/js/libs/jquery-ui-1.9.2.custom.min.js')?>"></script>
<script src="<?=base_url('assets/admin/canvas/js/plugins/icheck/jquery.icheck.min.js')?>"></script>

<script src="<?=base_url('assets/admin/canvas/js/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?=base_url('assets/admin/canvas/js/plugins/datatables/DT_bootstrap.js')?>"></script>
<script src="<?=base_url('assets/admin/canvas/js/plugins/tableCheckable/jquery.tableCheckable.js')?>"></script>
<script src="<?=base_url('assets/admin/canvas/js/libs/jquery-1.9.1.min.js')?>"></script>
*/ ?>



<!-- <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
  <script>tinymce.init({ selector:'textarea' });</script> -->
<!-- <script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
<script type="text/javascript">bkLib.onDomLoaded(nicEditors.allTextAreas);</script>
						 -->
						
<script src="<?=base_url('assets/grocery_crud/js/jquery-1.11.1.min.js')?>"></script>
<script src="<?=base_url('assets/grocery_crud/js/jquery_plugins/jquery.fancybox-1.3.4.js')?>"></script>
<script src="<?=base_url('assets/js/init-fancybox.js')?>"></script>
<script src="<?=base_url('assets/js/get_technicians_of_pro.js')?>"></script>
<script src="<?=base_url('assets/js/delete_spout.js')?>"></script>
<script src="<?=base_url('assets/js/delete_on_edit_form.js')?>"></script>

<?php 
if(isset($js_files)){
  foreach($js_files as $file): 
	    if (strpos($file, 'jquery-1.11.1.min.js')) continue; ?>
	  <script src="<?= $file; ?>"></script>
	<?php endforeach; 
}	
?>
<script type="text/javascript">
	jQuery(function() {
		
		jQuery(".fancybox").fancybox({
					'width'         : 340,
					'height'        : 200,
				 });

		jQuery(".close_popup").on('click', function(){
			parent.$.fancybox.remove();
			/*parent.$.fancybox({ 
				'title'     : 'CONFIRMATION MESSAGE' ,
				'content'   : '<h3>Spout Deleted Successfully</h3>', 
				'padding'   : '30px'
				});*/
		});
		
	});
</script>

<script  type="text/javascript">
	jQuery(function($){
		
   		 $('.groceryCrudTable tbody tr td div').each(function(index){
  		  
   		  var str = $(this).text();

   		  var matches = str.match(/_\|(.*)\|_/);

 	      if(matches[1].length)
 	     	var img = "<img class='img-thumbnail' style='margin-top:-17px; margin-left:85px; margin-bottom:30px;width:292px;height:auto;' width='50%' align='left' src='"+matches[1]+"'>"	
 	     else 
		    var img = 'No Image';
 	     	

          $(this).html(img);

   		 });
	})
</script>
<script src="<?=base_url('assets/admin/canvas/js/libs/bootstrap.min.js')?>"></script>
<script src="<?=base_url('assets/admin/canvas/js/App.js')?>"></script>
<!-- Sort spouts and replies -->
<script type="text/javascript">
	jQuery(function(){
	var t= $('body').hasClass('sort_desc');
	 if(t){
	 	// $('.ui-icon-carat-2-n-s').click()
	 }
	
	});
</script>
