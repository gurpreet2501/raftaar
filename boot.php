<?php
 
function env($key, $default_value){
	$path = __DIR__.DIRECTORY_SEPARATOR.'environment.php';
	
	if(!file_exists($path))
		return $default_value;

	$config = require($path);

	if(!isset($config[$key]))
		return $default_value;

	return $config[$key];
		
}
