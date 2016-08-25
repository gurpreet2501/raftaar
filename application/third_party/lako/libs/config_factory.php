<?php

/**
 *  Handles database interaction
 */
 
class lako_config_factory extends lako_lib_base{
  protected $version = '0.0.1';
  const IS_SINGLETON = true;
  public $loader = null;
  protected $collection = null;
  
  function __construct($config = array()){
    parent::__construct($config);
    lako::import('loader');
    lako::import('ljson');
    
    $this->loader = new lako_loader();
  }
  
  function get($in_conf){
    $file = lako_ljson::make_ljson_file($in_conf);
    $file = $this->loader->locate($file);
    if(!$file)
      throw new Exception('Not able to find configuration file '. $file .'for ');
    return lako::get('ljson')->require_file($file);
  }

  
  public function add_path($path){
    return $this->loader->add_path($path);
  }
}