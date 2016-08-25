<?php

/*
  A error management lib for lako
*/

class lako_exception extends lako_lib_base{
  protected $version = '0.0.1';
  function __construct($config=array()){
    parent::__construct($config);
  }
  
  /*
    Raise Exception
  */
  function raise($exception){
    throw new Exception($exception);
  }
}