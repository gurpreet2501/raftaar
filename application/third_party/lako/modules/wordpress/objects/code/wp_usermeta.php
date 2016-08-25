<?php

class wp_usermeta_object_lako extends lako_object{  
  function __construct($definition){    
    parent::__construct($definition);  
  }  
  
  function make_table_name($table){    
    global $wpdb;    
    return $wpdb->usermeta;  
  }
}