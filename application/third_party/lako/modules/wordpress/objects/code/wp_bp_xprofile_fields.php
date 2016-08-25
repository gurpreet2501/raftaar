<?php

class wp_bp_xprofile_fields_object_lako extends lako_object{  
  function __construct($definition){    
    parent::__construct($definition);  
  }    
  
  function make_table_name($table){    
    if(function_exists('bp_core_get_table_prefix'))      
      return bp_core_get_table_prefix().$table;    
    return parent::make_table_name($table);  
  }
}