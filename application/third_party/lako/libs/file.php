<?php

/*
  A file system library for lako
*/

class lako_file extends lako_lib_base{
  protected $version = '0.0.1';
  const IS_SINGLETON = true;
  
  function __construct($config){
    parent::__construct($config);
  }
  
  /*
    Read Files list from a directory
    return false on failure and 
  */
  function get_dir_content($in_dir,$in_args=array()){
    $dir = rtrim($in_dir, '\\/');
    $args = array_merge(array(
              'recursive'       => false,
              'keep_structure'  => false,
              'name_pattren'    => false,
              'extension'         => false,
              'size_greater_than' => false,
              'size_less_than'    => false,
              'type'              => 'file',//can be dir also
            ),$in_args);
            
    if(!is_dir($dir)){
      lako::get('exception')->raise("Unable to read files from '{$dir}' as this is not a DIR.");
      return false;
    }
    
    $files = array_diff(scandir($dir), array('..', '.'));
    $send_files = array();
    foreach($files as $key => $file){
      $send_file = array(
        'name' => $file,
        'path' => $dir.'/'.$file,
      );
      $send_file['type'] = is_dir($send_file['path'])? 'dir': 'file';
      
      
      if($args['type'] != $send_file['type'])
        continue;
        
      if(($send_file['type'] == 'file') && $args['extension'])
        if($this->get_extension($file) != $args['extension'])
          continue;
        
      $send_files[] = $send_file;
    }
    
    return $send_files;
  }
  
  function get_extension($file){
    return pathinfo($file,PATHINFO_EXTENSION);
  }
  
  function remove_trailing_slash($path){
    
  }
  
  function update_file(){}
  function rename_file(){}
  function delete_file(){}
  
  function rename_dir(){}
  function delete_dir(){}
  
  /**
   * create a file with given content or blank if content is not given
   * Raise exception if file cannot be written
   * Lock file while writing
   */
  function create_file($file_path, $content=''){
    die( __FILE__ . ' : ' . __LINE__ );
  }
  
  /**
   * create a directory 
   * Raise exception if dir cannot be written
   */
  function create_dir($dir_path){
    die( __FILE__ . ' : ' . __LINE__ );
  }
  
  
}