<div id="content">
	<div id="content-header">
		<h1><?= $heading ?></h1>
	</div> <!-- #content-header -->	

	<div id="content-container">
	<table class="table table-striped">
	<?php 
	$data = (array)$record;
	foreach($data as $key => $value):	?>
	  <tr>
	  	<th><?=ucfirst($key)?></th>
	  	<?php
	  	if($key == 'image'): 
	  		if($value)
	  			$img = '<img class="img-thumbnail" src="'.$value.'" width="200px" height="auto"/>';
	  		else
	  			$img = 'No Image';
	  		?>
	  	<td><?=$img?></td>
	  	<?php else: ?>
	  	<td><?=$value?></td>
	  	<?php endif; ?>
	  </tr>
	<?php endforeach;?>  
	</table>
 
	</div> <!-- #content -->
</div>