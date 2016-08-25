<?php

class lako_config extends lako_lib_base{
  protected $version = '0.0.1';
  protected $configs = array();
  public function __construct($config=array()){
    parent::__construct($config);
  }
  
  /**
   add_path will load all the config files on given path
  */
  public function add_path($in_path){
    $path = rtrim($in_path,'/\\');
    $files = array_diff(scandir($path), array('..', '.'));
    
    foreach($files as $file){
      $extension = pathinfo($file,PATHINFO_EXTENSION);
      if($extension != 'php')
        continue;
      require_once $path.'/'.$file;
    }
  }
  
  public function add_config($config_name,Array $data = array()){
    if(isset($this->configs[$config_name])){
      lako::get('exception')->raise("Trying to add an already existing config '{$config_name}'");
      return false;
    }
    $this->configs[$config_name] = $data;
    return true;
  }
  
  public function get($config_name){
    if(isset($this->configs[$config_name]))
      return $this->configs[$config_name];
    return array();
  }
  
}