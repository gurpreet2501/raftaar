<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lakoci{ 
  function __construct(){
 
    require_once APPPATH.'third_party/lako/lako.php';
    lako::get('objects')->add_path(APPPATH.'/../../../third_party/ag-soft/lako-objects'); 
  }
  
}
