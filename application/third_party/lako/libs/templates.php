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

class lako_templates extends lako_lib_base{
  protected $version = '0.0.1';
  const IS_SINGLETON = false;
  
  public function __construct($config = array()){
    parent::__construct($config);
    
    if(empty($this->config['paths']))
      throw new Exception('No path set for templates.');
      
    //clean paths
    foreach($this->config['paths'] as $key => $path)
      $this->config['paths'][$key] = $this->clean_dir_path($path);
  }
  
  /**
   * Right now it strips ending slash from directory path
   */
  protected function clean_dir_path($path){
    //striping ending slashes from paths 
    return rtrim($path,'/\\ ');
  }
  
  /**
   * Located template's direct path from one of the available paths
   */
  public function locate($file){
    $template = rtrim($file,'/\\ ');
    //return from the first ever path we find it on
    foreach($this->config['paths'] as $path){
      $may_be_template_path = $path.DIRECTORY_SEPARATOR.$template.'.phtml';
      if(file_exists($may_be_template_path))
        return $may_be_template_path;
    }
    //found nowhere lets raise an exception
    throw new Exception("Template {$file} file is not found in the paths ".implode('; ',$this->config['paths']));
  }
  
  public function render($in_template, $data = array()){
    $path_to_template = $this->locate($in_template);
    
    //process the template
    extract($data);
    require $path_to_template;
  }
  
  public function get($in_template, $data = array()){
    ob_start();
    $this->render($in_template, $data);
    $out = ob_get_contents();
    ob_end_clean();
    return $out;
  }
  
}