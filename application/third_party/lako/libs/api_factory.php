<?php

/**
 *  Handles database interaction
 */
class lako_api_factory extends lako_lib_base{
  protected $version = '0.0.1';
  public $factory = null;
  
  function __construct($config = array()){
    parent::__construct($config);
    lako::import('factory');
    $this->factory = new lako_factory(array('subclass_of' => 'lako_api_base'));
  }
}