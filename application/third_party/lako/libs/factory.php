<?php


/**
 *  A factory class that keeps track of all the instances
 */
class lako_factory extends lako_lib_base{
  protected $version = '0.0.1';
  const IS_SINGLETON = false;
  public $loader = null;
  public $config = array();
  protected $collection = array();
  
  public function __construct($config = array()){
    lako::import('loader');
    $this->loader = new lako_loader();
    $this->config = $config;
    parent::__construct($config);
  }
  
  /**
   * get single instance
   */
  public function get($object){
    if(isset($this->collection[$object]))
      return $this->collection[$object];
      
    $this->setup($object);
    if(isset($this->collection[$object]))
      return $this->collection[$object];
    return null;
  }
  
  /**
   * Sets up an object the collection
   */
  public function setup($object){
    if(isset($this->collection[$object]))
      return;
      
    $object_path = $this->loader->locate($object);
    
    if(!$object_path)
      return;
      
    try{
      require_once $object_path;
      $instance = new $object();
      
      if(isset($this->config['subclass_of'])){
        if(is_subclass_of($instance, $this->config['subclass_of']))
          $this->collection[$object] = $instance;
      }else
        $this->collection[$object] = $instance;
        
    }catch(Exception $e){
      //stay silent
    }
  }
  
  /**
   * get single instance
   */
  public function get_all(){
    $files = $this->loader->get_all();
    foreach($files as $file){
      $object = preg_replace('/\.php$/i','',trim($file));
      $this->setup($object);
    }
    return $this->collection;
  }
  
}