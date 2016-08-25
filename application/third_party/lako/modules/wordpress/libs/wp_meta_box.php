<?php

/**
 * Meta box for wordpress
 */

class lako_wp_meta_box extends lako_lib_base{
  protected $version = '0.0.1';
  const IS_SINGLETON = false;
  
  public function __construct($config = array()){
    parent::__construct($config);
  }
  
  public function init(){
    add_action('add_meta_boxes',array($this,'_hook_meta_box'));
  }
  
  public function _hook_meta_box(){
    foreach($this->config['on_screens'] as $screen)
      add_meta_box(
        $this->config['id'],
        $this->config['title'],
        array($this,'_print_meta_box_content'),
        $screen
      );
  }
  
  public function _print_meta_box_content(){
    echo $this->config['content'];
  }
  
  
  
}