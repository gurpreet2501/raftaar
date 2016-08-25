<?php

/**
 * lako ljson 0.0.1
 */
lako::import('json');
class lako_ljson extends lako_json{
  protected $version = '0.0.1';
  const IS_SINGLETON = true;
  
  public function __construct($config = array()){
    parent::__construct($config);
  }
  
  public function make_ljson_file($in_path){
    $ext = strtolower((String)pathinfo($in_path, PATHINFO_EXTENSION));
    if($ext != 'ljson')
      return $in_path.'.ljson';
    return $in_path;
  }
  /**
   * Loads json from a file
   * Raise exception on missing file or,invalid json string 
   */
  public function require_file($in_path){
    return parent::require_file($this->make_ljson_file($in_path));
  }
  
  /**
   * Loads json from a file
   */
  public function file($in_path){
    return parent::file($this->make_ljson_file($in_path));
  }
  
  /**
   * saves array|object  into a file after converting to json
   */
  public function save($in_path, $in_data){
    return parent::save($this->make_ljson_file($in_path), $in_data);
  }
  
}