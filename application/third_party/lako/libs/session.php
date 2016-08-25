<?php

/**
 * Session Management Library 
 */
class lako_session extends lako_lib_base{
  protected $version = '0.0.1';
  
  function __construct($config = array()){
    parent::__construct($config = array());
    $this->start();
  }
  
  /**
   * Start session, You will not need to call it manually in most cases, session is automatically started when initialized, or invoked from `lako::get('session')`.
   * 
   * @return void
   */
  function start(){
    if(session_id() == '')
      session_start();
  }
  
  /**
   * Set value of a session index
   * 
   * @param String $key  Required, The key against which the value will be stored.
   * @param Mixed $value Optional, The value to store, Default is null.
   * @return void
   */
  function set($key, $value = null){
    $_SESSION[$key] = $value;
  }
  
  /**
   * Get value stored in a session index
   * 
   * @param String $key  Optional, If key is null or not present whole session is returned.
   * @return Mixed $value
   */
  function get($key = null){
    if(is_null($key))
      return $_SESSION;
      
    if(!isset($_SESSION[$key]))
      return null;
    return $_SESSION[$key];
  }
  
  
  /**
   * Delete value stored in a session index, or delete entire session.
   * 
   * @param String $key  Optional, If key is null or not present whole session is destroyed.
   * @return void
   */
  function delete($key = null){
    if(is_null($key))
      $_SESSION = array();
    else
      unset($_SESSION[$key]);
  }
  
}