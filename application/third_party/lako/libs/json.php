<?php

/**
 * lako json 0.0.1
 */

class lako_json extends lako_lib_base{
  protected $version = '0.0.1';
  const IS_SINGLETON = true;
  
  public function __construct($config = array()){
    parent::__construct($config);
  }
  
  /**
   * Loads json from a file
   * Raise exception on missing file or,invalid json string 
   */
  public function require_file($in_path){
    $decoded = $this->file($in_path);
    if(!$decoded)
      throw new Exception('Could not parse JSON data from '.$in_path);
    return $decoded;
  }
  
  /**
   * Loads json from a string, raise exception on invalid string
   */
  public function require_string($in_string){
    $decoded = $this->decode($in_string);
    if(!$decoded)
      throw new Exception('Invalid JSON data');
    return $decoded;
  }
  
  /**
   * Loads json from a file
   */
  public function file($in_path){
    if(!file_exists($in_path))
      return false;
    return $this->decode(file_get_contents($in_path));
  }
  
  /**
   * saves array|object  into a file after converting to json
   */
  public function save($in_path, $in_data){
    return file_put_contents($in_path, $this->encode($in_data,JSON_PRETTY_PRINT));
  }
  
  
  /*
    We will hopefully improve these over the time, to be better and safer from common pitfalls.
  */
  
  /**
   * Loads json from a string
   */
  public function decode($in_string){
    return json_decode($in_string, true);
  }
  
  /**
   * encodes a json string
   */
  public function encode($in_data,$options){
    return json_encode($in_data,$options);
  }
  
  
}