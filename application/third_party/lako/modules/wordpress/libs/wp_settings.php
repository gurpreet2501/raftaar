<?php
/**
 * Library to manage wordpress settings
 * Stores information in siteoptions
 * 
 */

class lako_wp_settings extends lako_lib_base{
  protected $version = '0.0.1';
  const IS_SINGLETON = false;
  
  protected $fields = array();
  protected $templates = null;
  protected $forms = null;
  /**
   * flag to set that we have cached values
   */
  protected $has_cached = false;
  
  
  /**
   * Accepts $config object.
   *
   * $config is clearly the configuration options for the stuff
   * it has index fields that acceps of lako_form like input settings
   * 
   */
  public function __construct($config = array()){
    parent::__construct($config);
    $this->fields = isset($config['fields'])? $config['fields']: array();
    
    lako::import('templates');
    lako::import('forms');
    
    $this->templates = new lako_templates(array(
      'paths' => array(realpath(__DIR__.'/../templates/wp_settings'))
    ));
    
    $this->forms = new lako_forms(array(
      'templates_path' => realpath(__DIR__.'/../templates/wp_forms')
    ));
    
  }
  
  /**
   * this will fetch all the from db and saves them in config array
   */
  public function fetch_values($cached = true){
    if($cached &&  $this->has_cached)
      return;
      
    $option_names = array_keys($this->config['fields']);
    $values = lako::get('objects')->get('wp_options')->read(array(
      'select' => array('option_name','option_value'),
      'where' => array('option_name','IN',$option_names)
    ));
    
    
    //attach values to fields
    foreach($this->config['fields'] as $fname => $settings){
      $found = lako::get('data')->array_where($values,'option_name',$fname);
      if(!empty($found))
        $this->config['fields'][$fname]['value'] = $found[0]['option_value'];
    }
    
    $this->has_cached = true;
  }
  
  /**
   * return value of the settings
   */
  function get($field){
    $this->fetch_values();
    if(!isset($this->config['fields'][$field]['value']))
      return null;
    return $this->config['fields'][$field]['value'];
  }
  
  /**
   *  does what it says sets up an admin page.
   */
  public function setup_admin_page(){
    add_action('admin_menu',array($this,'_install_menu')); 
  }
  
  public function _install_menu(){
    add_submenu_page( $this->config['menu']['parent_slug'], $this->config['menu']['menu_title'], $this->config['menu']['page_title'], 'manage_options', $this->config['menu']['slug'], array($this,'_render_settings_page'));
  }
  
  public function _render_settings_page(){
    $field_keys = array_keys($fields);
    $updated = false;
    if(isset($_POST) && !empty($_POST)){
      foreach($_POST as $key => $val)
        if(!in_array($key , $field_keys))
          update_option( $key , $val );
      
      //we updated next time you fetch fetch the updates.
      $this->has_cached = false;
      $updated = true;
    }
    
    //get the fields from DB if not already fetched
    $this->fetch_values();
    $fields = $this->config['fields'];
    $fields[] = array(
      'type' => 'submit',
      'value' => 'Save'
    );
    
    $this->templates->render('settings-page',array(
      'updated' => $updated,
      'title'   => $this->config['menu']['page_title'],
      'form'    => $this->forms->generate($fields, array('method'=>'post')),
    ));
  }
  
}
 