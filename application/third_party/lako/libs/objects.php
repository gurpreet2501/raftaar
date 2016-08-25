<?php

lako::import('config');
class lako_objects extends lako_config{
  protected $version = '0.0.1';
  protected $singleton_instances = array();
 
  
  function __construct($config = array()){
    parent::__construct($config);
    lako::import('object');
  }
  /**
   * Get the objects names.
   * 
   * @param String $module|Void  Module name of which you want objects, or empty if you want all module
   * @return Array $objects name of objects
   */
  function get_all_objects($module = null){  
    //to be implemented.
    throw new Exception('To be implemented');
  }
  
  /**
   *  Get instance of a an object Definition
   */
  function get($object_name){
    if(isset($this->singleton_instances[$object_name]))
      return $this->singleton_instances[$object_name];
   
    $definition = parent::get($object_name);
    $this->singleton_instances[$object_name] = new lako_object($definition);
    return $this->singleton_instances[$object_name];
  }
  
  
  /**
   * Tries find local object and objects in modules, and gives you definition in return.
   * 
   * @param String $object_name
   * @return Mixed $object_definition
   * @throws Exception Definition not found.
   * @throws Exception Definition has syntax error.
   */
  function get_deifintion($object_name){
    throw new Exception('To be implemented.');
    //find local
    $def_file_path = $this->config['definitions_path']."/{$object_name}.json";
    if(!file_exists($def_file_path)){
      //find in modules
      $def_file_path = $this->locate_definition_in_modules($object_name);
      if(!$def_file_path)
        throw new Exception("Definition for {$object_name} is not found.");
    }
    
    $maybe_definition = json_decode(file_get_contents($def_file_path),true);
    if(is_null($maybe_definition))
      throw new Exception("Invalid definition file for object {$object_name}, found at {$def_file_path}");
    return $maybe_definition;
  }
  
 
  
  /**
   * Find code file for object.
   * @param String $object_name
   * @return Path on succes or false when not found
   */
  public function locate_object_code_file($object_name){
    $possible_object_dedicated_file = $this->config['code_path']."/{$object_name}.php";
    if(file_exists($possible_object_dedicated_file))
      return $possible_object_dedicated_file;
    
    foreach(lako::get_modules() as $module_path){
      $possible_object_dedicated_file = $module_path.'/objects/code'."/{$object_name}.php";
      if(file_exists($possible_object_dedicated_file))
        return $possible_object_dedicated_file;
    }
    return false;
  }
  
  function make_object_name($object_name){
    return "{$object_name}{$this->config['object_suffix']}";
  }
    
  //.// Create new object //.//
  function create($name,$location){
    lako::get('ljson')->save($location."/{$name}",array(
        'table' => $name,
        'name'  => $name,
        'pkey'  => 'id',
        'fields'=> array(
            'id'  => array(
              'datatype' => ''
            )
          ),
        'relations' => array(
          'data' => array(
            'type'   => '1-M',
            'path'   => ['id','field_id'],
            'object' => 'wp_bp_xprofile_data'
          )
        )
      )
    );
    
  }
      
  //.// rename new object //.//
  function rename($old_name,$name){
    $file = lako_ljson::make_ljson_file($old_name);
    $file = $this->loader->locate($file);
    $definition = lako::get('ljson')->require_file($file);
    $location = dirname($file);
    //edit definition
    $definition['table']  = $definition['name'] = $name;
    lako::get('ljson')->save($location."/{$name}",$definition);
    @unlink($file);
  }
    
}