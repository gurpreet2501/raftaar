<?php
/**
 * PHP AG Soft CRUD Lib
**/
require_once __DIR__.'/Grocery_CRUD.php';

class Ag_CRUD extends Grocery_CRUD{
  //allow unset for chosen lib and css
	protected $unset_chosen			= false;
  
  function __construct(){
    parent::__construct();
  }
  
  
	public function unset_chosen()
	{
		$this->unset_chosen = true;

		return $this;
	}
  
  
  
  protected function get_layout()
	{
    if ($this->unset_chosen){
      unset($this->css_files[sha1($this->default_css_path.'/jquery_plugins/chosen/chosen.css')]);
      unset($this->js_files[sha1($this->default_javascript_path.'/jquery_plugins/jquery.chosen.min.js')]);
      unset($this->js_files[sha1($this->default_javascript_path.'/jquery_plugins/config/jquery.chosen.config.js')]);
      unset($this->js_lib_files[sha1($this->default_javascript_path.'/jquery_plugins/jquery.chosen.min.js')]);
      unset($this->js_config_files[sha1($this->default_javascript_path.'/jquery_plugins/config/jquery.chosen.config.js')]);
		}
    return parent::get_layout();
	}
}


