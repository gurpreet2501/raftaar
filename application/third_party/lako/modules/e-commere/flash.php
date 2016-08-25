<?php

/**
 * Flash storage
 */

class lako_flash extends lako_lib_base{
  protected $version = '0.0.1';
  
  public function __construct($config = array()){
    parent::__construct($config);  
  }
  
  /**
   * Set a message
   */
  public function set($key, $val){
    return lako::get('session')->set($key, $val);
	}
  
  /**
   * get a message
   */
  public function get($key){
    $val = lako::get('session')->get($key);
    lako::get('session')->delete($key);
    return $val;
	}
}