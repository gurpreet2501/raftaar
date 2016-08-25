<?php
  
class lako_api_base extends lako_lib_base{
  protected $version = '0.0.1';
  
  function __construct($config = array()){
    parent::__construct($config);
  }
  
  
  function respond($data=null,$status=true, $errors=array(), $messages=array()){
    return array(
      'STATUS'   => $status, 
      'DATA'     => $data, 
      'ERRORS'   => $errors, 
      'MESSAGES' => $messages
    );
  }
}