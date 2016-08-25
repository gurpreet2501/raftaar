<?php
class Sort_results extends grocery_CRUD_Model  {
 		
	public function order_by($post_array, $primary_key_value)
	{	
			echo "<pre>";
			print_r($post_array);
			exit;
			$post_array = [];
			return $post_array;
	}
}