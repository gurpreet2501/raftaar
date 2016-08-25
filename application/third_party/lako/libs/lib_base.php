<?php

/**
 * Base file for all the lako libraries. All libs must extends this class.
 * 
 * @todo Change version to a constant like IS_SINGLETON
 * 
 */
class lako_lib_base{  
  /**
   * Holds version number, other libs must override this.
   */
  protected $version = '';  
    
  /**
   * Holds any configuration value for this class.
   */
  protected $config = array();  
  
  /**
   * The class those cannot be singletons by nature. Should set this flag to false.
   */
  public static function is_singleton(){
    return true;
  }
  
  
  /**
   * Constructor sets the configuration.
   * 
   * @param Array $config  Optional. Configuration array.
   * @return void
   */
  function __construct($config = array()){    
    if($this->version == '')      
      throw new Exception('Version is not defined.');    
    $this->set_config($config); 
  }
  
  
  /**
   * Read version of the lib.
   * 
   * @todo Make is static, accessible without invoking.
   
   * @return String $version
   */
  function ver(){
    return $this->version;  
  }
  
  
  /**
   * Replaces current config.
   * 
   * @todo Allow to set index based value as well ideally `set_config($index,$val)` or `set_config($full_config)`
   * 
   * @param Array $config  
   * @return void
   */
  function set_config($config = null){
    $this->config = $config;
  }
  
  
  /**
   * Returns config data.
   * 
   * @todo Allow to read single index or whole config like lako main class
   * 
   * @return Mixed $config
   */
  function get_config(){
    return $this->config;
  }
}