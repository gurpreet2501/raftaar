<?php
/**
 *  Lako Web Framework.
 *
 *
 *  Lako is a bundle of code modules to make Web Development faster, easier, secure and more stable. These modules are designed to seamlessly work with other modules in lako. 
 *  
 *  When used together these modules act intelligently and make the most out of other modules to provides out of the box solutions to many common Web Dev tasks. 
 *  
 *  Think of it as small intelligent robots helping each other doing work for you.
 */
 
/**
 *  Main lako class.
 *
 *
 *  lako is main class and entry point for all the other lako libraries. It manages and keep track of things.
 *  
 *  Typically used like lako::do_awesome();
 *  
 */
class lako{
  /**
   * keeps the lako path
   */
  private static $path = array();
  
  /*
    Libs are stored as 
    lib_name = {
      path     = null
      module   = null
      instance = null
    }
  */
  private static $libs = array();
  
  private function __construct(){}
  
  public static function path(){
    return self::$path;
  }
  
  /**
   * Initialize lako.  
   *
   * This must called before everything. 
   * @example lako-examples/lako-init.php Codeigniter Example.
   * 
   * 
   * @param $config  The configuration Array
   * @return void
   */
  public static function init(){
    self::$path = dirname(__FILE__);
    //Set up base for libs
    self::add_lib(self::path().'/libs/lib_base.php');
    self::import('lib_base');
    
    //load the essential libs for running the system
    //It is very important to load the libs in this order
    self::add_lib(self::path().'/libs/config.php');
    self::get('config')->add_path(self::path().'/config');
    
    self::add_lib(self::path().'/libs/exception.php');
    self::add_lib(self::path().'/libs/file.php');
    
    //add rest of the core libs
    self::add_module(self::path().'/libs');
    
    //add extra modules for wordpress etc
    self::add_modules_path(self::path().'/modules');
    return true;
  }
  
  public static function add_lib($path,$module = null){
    if(file_exists($path))
      require_once $path;
    $lib_name = pathinfo($path, PATHINFO_FILENAME);
    
    if(isset(self::$libs[$lib_name]))
      return false;
    
    self::$libs[$lib_name] = array(
      'path'     => $path,
      'module'   => $module,
      'instance' => null
    );
    return true;
  }
  
  public static function add_module($module_path){
    $files = lako::get('file')->get_dir_content($module_path,
                                                array('extension'=>'php'));
    if($files === false){
      lako::get('exception')->raise("Cannot read files from module path '{$module_path}'");
      return false;
    }
    $module_name = basename($module_path);
    foreach($files as $file)
      self::add_lib($file['path'],$module_name);
    return true;
  }
 
  /**
   * Tracks all the modules on given path.
   * @param String $path Full path to modules DIR
   * @return null
   */
  public static function add_modules_path($path){
    $modules = lako::get('file')->get_dir_content($path,array('type'=>'dir'));
    foreach($modules as $module)
      self::add_module($module['path']);
    return true;
  }

  /**
   * Imports a library to global scope.
   * 
   * Similar to `require_once` but only for lako libs. Used for non singleton libs e.g. lako_templates.
   *
   * @example lako-examples/lako-import.php Importing lako_templates.
   *
   * @throws Exception  If lib file is not found.
   *
   * @todo Allow option to add more paths where libs could be found.
   *
   * @param String $lib Name of library without prefix e.g. lako
   * @return void
   */
  public static function import($lib){
    if(!isset(self::$libs[$lib])){
      self::get('exception')->raise("Cannot import lib {$lib}, as it is not found in the system. Please use lako::add_lib('{$lib}') before trying to import.");
      return false;
    }
    require_once self::$libs[$lib]['path'];
    return true;
  }
  
  /**
   * Get a singleton instance of a library, remember only certain libraries can be invoked this way.
   * 
   * @example lako-examples/lako-get.php Getting lako_objects.
   *
   * @throws Exception  If lib file is not found.
   * @throws Exception  If class names doesn't follows pattern.
   * @throws Exception  If class does not extends the lako_lib_base class.
   *
   * @param String $lib Name of library without prefix e.g. lako
   * @return Object Instance of a singleton laco lib.
   */
  public static function get($lib){
    if(!self::import($lib))
      return false;
    
    //if we have it created then send
    if(self::$libs[$lib]['instance'])
      return self::$libs[$lib]['instance'];
    
    $class_name = self::make_lib_name($lib);
    
    if(!class_exists($class_name)){
      lako::get('exception')->raise("Name of class in library is incorrect it should be '{$class_name}'");
      return false;
    }
    
    //check if the class allow singleton
    if(!$class_name::is_singleton()){
      lako::get('exception')->raise("'{$lib}' is not a singleton.");
      return false;
    }
    
    $config = array();
    //if the it is another lib than config 
    if($lib != 'config')
      $config = self::get('config')->get($class_name);     
    
    //save in the single instance for the next time
    self::$libs[$lib]['instance'] = new $class_name($config);
    
    if(!(self::$libs[$lib]['instance'] instanceof lako_lib_base)){
      unset(self::$singleton_instances[$lib]);
      lako::get('exception')->raise("{$lib} Library class must extend 'lako_lib_base'");
      return false;
    }
    return self::$libs[$lib]['instance'];
  }
  
  /**
   * Adds prefix to a class name.
   * @param $lib_name A lib name without prefix
   * @return string Returns prefixed lib
   */
  public static function make_lib_name($lib_name){ 
    return 'lako_'.$lib_name;
  } 
}

//Initialize
lako::init();