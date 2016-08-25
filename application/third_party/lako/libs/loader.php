<?php

/**
 * Loads files given paths
 */

class lako_loader extends lako_lib_base{
  protected $version = '0.0.1';
  const IS_SINGLETON = false;
  public $paths = array();
  
  public function __construct($config = array()){
    parent::__construct($config);
  }
  
  /**
   * Adds Path
   */
  public function add_path($path){
    $this->paths[] = $path;
  }
  
  /**
   * Finds a file on the given set of Paths
   */
  public function locate($in_file){
    $file = rtrim($in_file,'/\\ ');
    //Return from the first ever path we find it on
    foreach($this->paths as $path){
      $may_be_file = $path.DIRECTORY_SEPARATOR.$file;
      $ext = pathinfo($may_be_file, PATHINFO_EXTENSION);
      if(!$ext || !strlen($ext))
        $may_be_file .= $may_be_file.'.php';
      
      if(file_exists($may_be_file))
        return $may_be_file;
    }
    return null;
  }
  
  /**
   * Get all from all paths
   */
  public function get_all(){
    $files = array();
    foreach($this->paths as $path){
      $_files = scandir($path);
      array_shift($_files);
      array_shift($_files);
      $files = array_merge($files, $_files);
    }
    return $files;
  }
}