<?php

/**
 * Adds dashboard widget to Wordpress admin
 */

class lako_wp_dashboard_widget extends lako_lib_base{
  protected $version = '0.0.1';
  public function __construct($config = array()){
    parent::__construct($config);
    //add hook to display widgets
    add_action('wp_dashboard_setup', array($this, '_hook_widget'));
  }
  
  public function _hook_widget(){
    wp_add_dashboard_widget(
      $this->config['id'],         // Widget slug.
      $this->config['title'],         // Title.
      array($this,'_print_html')
    );
  }
  
  public function _print_html(){
    echo $this->config['html'];
  }
  
}

