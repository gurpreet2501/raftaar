<?php

class lako_wp_plugin_framework extends lako_lib_base{
  protected $version = '0.0.1';
  protected $plugins = array();
  public function __construct($config = array()){
    parent::__construct($config);
  }
  
  public function add_plugin($name, $path){
    
  }
  
}