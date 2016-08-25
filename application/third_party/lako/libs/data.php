<?php

/**
 * template_ag 0.0.1
 
 * Features
 * - renders/generate templates
 
 * Usage
   $templates = new templates_ag(array(
    'paths'     => array('/path/to/templates/folder'), 
   ));
   $templates->render('folder/template',array('data'=>'data')); //print
   $templates->get('folder/template',array('data'=>'data')); //returns
 */

class lako_data extends lako_lib_base{
  protected $version = '0.0.1';
  
  
  public function __construct($config = array()){
    parent::__construct($config);
  }
  
  /**
   * Returns array of one particular index from a Array of Arrays.
   * @param Array &$arr Array of Arrays.
   * @param String $key Index to look for.
   * @return Array
   */
  public function array_extract_key(Array &$arr, $key){
    reset($arr);
    $vals = array();
    foreach($arr as $item)
      if(isset($item[$key]))
        $vals[] = $item[$key];
		return $vals;
	}
  
  /**
   * Returns filtered Array where  $key and $value matches.
   * @param Array &$arr Array of Arrays.
   * @param String $key Index to look for.
   * @param String $value Value to compare.
   * @return Array
   */
  public function array_where(Array &$arr, $key , $value){
    reset($arr);
    
    $filtered_rows = array();
    foreach($arr as $row){
      if($row[$key] == $value)
        $filtered_rows[] = $row;
    }
		return $filtered_rows;
	}
  
  
  public function array_trim_vals(Array &$arr){
    foreach($arr as $key => $val)
      $arr[$key] = trim($val);
  }
  
  
  public function array_remove_index(Array &$arr,$index){
    foreach($arr as $key => $val){
      if(isset($val[$index]))
        unset($arr[$key][$index]);
    }
  }
  
  /** 
   * Remove many indexes from an array.
   *
   * @param Array $array
   * @param Array $keys
   * @return void
   */
  public function unset_keys(Array &$arr,$keys){
    foreach($keys as $key){
      if(isset($arr[$key]))
        unset($arr[$key]);
    }
  }
  
  /** 
   * Returns false it any of the $keys doesn not exists in the indexed array.
   *
   * @param Array $arr
   * @param Array $keys
   * @return Boolean
   */
  public function verify_keys_exists(Array $arr,$keys){
    foreach($keys as $key)
      if(!isset($arr[$key]))
        return false;
    return true;
  }
}