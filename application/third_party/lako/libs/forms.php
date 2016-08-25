<?php

/**
 *  Handles database interaction
 *
    froms
     
     
    type        - [text,textarea,select,radio,checkbox]
    name        - system
    label        - label
    attributes  - {attribute:value,..}
    value       - 'val'
    placeholder - 
    help
    error
 */
class lako_forms extends lako_lib_base{
  protected $version = '0.0.1';
  protected $templates = null;
  protected $possible_inputs = array('type','name', 'label', 'value', 'placeholder', 'help', 'errors','classes','attributes','options');
  protected $default_form_config = array(
    'form-wrap' => true,
    'row-wrap' => true,
    'method' => 'GET',
    'action' => '',
  );
  
  function __construct($config = array()){
    parent::__construct($config);
    //Load a templates instance and initiate with
    lako::import('templates');
    
    //build paths for template instance
    $paths = array();
    
    if(isset($config['templates_path']))
      $paths[] = $config['templates_path'];
    $forms_config = lako::get_config('forms');
    
    if($forms_config || isset($forms_config['templates_path']))
      $paths[] = $forms_config['templates_path'];
    
    //we are supporting it both as instantiated and singleton, so remove any duplicate paths, caused when you using singleton
    $paths = array_unique($paths);
    
    if(empty($paths))
      throw new Exception('No paths found, we need at-least one path for the form templates.');
      
    $this->templates = new lako_templates(array(
      'paths' => $paths
    ));
  }
  
  function generate($fields, $in_config = array()){
    $config = array_merge($this->default_form_config, $in_config);
    
    $all_fields_html = '';
    foreach($fields as $field){
      $field_html = $this->field($field);
      if($config['row-wrap'])
        $all_fields_html .= $this->templates->get('field-wrap',array(
          'field' => $field_html
        ));
      else
        $all_fields_html .= $field_html;
    }
    
    if(!$config['form-wrap'])
      return $all_fields_html;
      
    return $this->templates->get('form-wrap',array(
                                              'method' => $config['method'],
                                              'action' => $config['action'],
                                              'fields' => $all_fields_html
                                            ));
  }
  
  /**
   * Return html for a field
   */
  function field($input, $print = false){
    $normalized_input = $this->normalize_input($input);
    
    if($print){
      $this->templates->render($normalized_input['type'],$normalized_input);
      return;
    }
    return $this->templates->get($normalized_input['type'],$normalized_input);
  }
  
  function normalize_input($in_input){
    $normalized = array();
    foreach($this->possible_inputs as $key){
      switch($key){
        case('type'):
          if(!isset($in_input[$key]))
            $normalized[$key] = 'text';
          else
            $normalized[$key] = $in_input[$key];
          break;
          
        case('name'):
        case('value'):
        case('label'):
        case('placeholder'):
        case('help'):
          if(!isset($in_input[$key]))
            $normalized[$key] = '';
          else
            $normalized[$key] = $in_input[$key];
          break;
          
        case('errors'):
          if(!isset($in_input[$key]))
            $normalized[$key] = false;
          else
            $normalized[$key] = $in_input[$key];
          break;
          
        case('classes'):
          if(!isset($in_input[$key]))
            $normalized[$key] = '';
          elseif(!is_array($in_input[$key]))
            $normalized[$key] = $in_input[$key];
          else
            $normalized[$key] = implode(' ', $in_input[$key]);
          break;
         
        case('attributes'):
          $normalized[$key] = '';
          
          if(!isset($in_input[$key]))
            break;
          if(!is_array($in_input[$key]))
            $normalized[$key] = $in_input[$key];
          else
            foreach($in_input[$key] as $attrib_key => $attrib_val)
              $normalized[$key] .= htmlspecialchars($attrib_key).'="'.htmlspecialchars($attrib_val).'" ';
          break;
          
        case('options'):
          $normalized[$key] = array();
          
          //set the default options usually --SELECT--
          if(isset($in_input['default_option'])){
            if(!is_array($in_input['default_option']))
              $normalized[$key][] = array('' => $in_input['default_option']);
            else
              $normalized[$key][] = $in_input['default_option'];
          }
            
          //normalize the input 
          if(!empty($in_input['options'])){
            $is_assoc = (bool)count(array_filter(array_keys($in_input['options']), 'is_string'));
            if($is_assoc)
              $normalized[$key] = array_merge($normalized[$key],$in_input['options']);
            else
              foreach($in_input['options'] as $val)
                $normalized[$key][$val] = $val;
          }  
          break;
      }
    }
    
    return $normalized;
  }
  
}
  