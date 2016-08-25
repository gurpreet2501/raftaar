<?php

/**
 * Meta box for wordpress
 */

class lako_wp_conditions extends lako_lib_base{
  protected $version = '0.0.1';
  
  public function __construct($config = array()){
    parent::__construct($config);
  }
  
  function is_edit_page($new_edit = null){
    global $pagenow;
    //make sure we are on the backend
    if (!is_admin()) return false;


    if($new_edit == "edit")
        return in_array( $pagenow, array( 'post.php',  ) );
    elseif($new_edit == "new") //check for new post page
        return in_array( $pagenow, array( 'post-new.php' ) );
    else //check for either new or edit
        return in_array( $pagenow, array( 'post.php', 'post-new.php' ) );
  }
}