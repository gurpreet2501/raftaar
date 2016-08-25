<?php

class lako_object extends lako_lib_base{
  protected $version = '0.0.1';
  const IS_SINGLETON = false;
  protected $definition = array();
  
  function __construct($config){
    parent::__construct($config);
    $this->definition = $config;
    $this->db = lako::get('database');
  }
  
  /**
   * utility, outs full definition of object
   */
  function get_definition(){
    return $this->definition;
  }
  
  /**
   * utility, outs full definition of object
   */
  function pkey(){
    return $this->definition['pkey'];
  }
  
  function table(){
    return $this->make_table_name($this->definition['table']);
  }
  
  /**
   * Prefixes and suffixes the table name
   */
  function make_table_name($table){
    $config = lako::get('config')->get('database');
    return "{$config['table_prefix']}{$table}{$config['table_suffix']}";
  }
  
  /**
   * Name of object.
   * @return String Name of object
   */
  function name(){
    return $this->definition['name'];
  }
  
  function save_definition($defintion){
    $name     = lako::get('ljson')->make_ljson_file($this->name());
    $file     = lako::get('objects')->loader->locate($name);
    $location = dirname($file);
    //edit definition
    lako::get('ljson')->save($location."/{$name}",$defintion);
    $this->defintion = $defintion;
  }
  
  /*
    create a new field to current object
  */
  function add_local_data($name, $datatype){
    $this->definition['fields'][$name] = array(
      'datatype' => $datatype
    );
    $this->save_definition($this->definition);
  }
  
  
  
  /**
   * Gets you total number of fields
   * $preserve_fields - not sure if we will need it i am keeping it for now. It is i think for when we have join statements and fields comparing with other tables.
   */
  function count($query = array(), $preserve_fields = false){
    $q = $this->convert_read_query($query);
    if(!$preserve_fields)
      $q['select'] = array();
    $q['select'][] = '^count(*) as `rows_count`';
    $results = $this->db->select($q);
    
    if(empty($results))
      return 0;
    return $results[0]['rows_count'];
  }
  
  /**
   *  converts and object query to DB read query
   */
  function convert_read_query($object_q){
    $q = array();
    $read_process_order = array('from','select','where','order_by','search','join');
    foreach($read_process_order as $process)
      $this->{"process_input_{$process}"}($q, $object_q);
    return $q;
  }
  
  /**
   * Converts an object query to DB sql
   */
  function read_sql($query){
    $parsed = $this->convert_read_query($query);
    return $this->db->resolve_query($parsed);
  }
  
  /**
   * sends back results from storage 
   * $input can be an one value for pkey or a complete array of query
   *
   *  query definition
   *  where  [field |, operator | , value]
   *  where example [id , '='  , 12]
   *  where example [name , 'like'  , '%12%']
   *  where example [id , 'in'  , '%12%']
   *  and_where example [id , 'in'  , '%12%']
   */
  function read($input=array(),$debug = false){
    //its a pkey make it a valid query and run read on it
    if(!is_array($input))
      return $this->read(array('select'=>array('^*'),
                               'where'=> array( $this->pkey(), '=' , $input)));
    
    $q = $this->convert_read_query($input);
    
    if(isset($input['pagination'])){
      $total = $this->count($input,true);
      $this->{'process_input_pagination'}($q,$input);
      $paginition_response = array(
        'next' => null,
        'previous' => null,
        'total_results' => null,
        'total_pages' => null,
      );
      $paginition_response['total_results'] = $total;
      $paginition_response['total_pages'] = ceil($total/$input['pagination']['per_page']);
      if($input['pagination']['page'] <= 1)
        $paginition_response['previous'] = null;
      else
        $paginition_response['previous'] = ($input['pagination']['page']-1);
      
      if($input['pagination']['page'] >= $paginition_response['total_pages'])
        $paginition_response['next'] = null;
      else
        $paginition_response['next'] = ($input['pagination']['page']+1);
    }
    
    $results = $this->db->select($q);
    $this->attach_related_objects($results,$input);
    
    if(!isset($input['pagination']))
      return $results;
    return array(
      'results' => $results,
      'pagination' => $paginition_response
    );
  }
  
  function attach_related_objects(&$results, $input){
    if(empty($results))
      return $results;
      
    $resolved_fields = $this->resolve_select_fields($input['select']);
    
    if(empty($resolved_fields['relations']))
      return $results;
      
    foreach($resolved_fields['relations'] as $relation => $select_fields)
      $this->attach_single_related_object($relation
                                          ,$select_fields
                                          ,$results);
    
  }
  
  function attach_single_related_object($relation,$select_fields,&$results){  
    $relation_config = $this->get_relation($relation);
    $main_key =  $this->local_key($relation);
    $extracted_main_keys = lako::get('data')->array_extract_key($results, $main_key);
    
    $q = array('select'=>$select_fields);
    switch($relation_config['type']){
      case('1-1'):
      case('1-M'):
      case('M-1'):
        $match_field = $relation_config['path'][1];
        $q['select'][] = $match_field;
        $q['where'] = array($relation_config['path'][1], 'IN' , $extracted_main_keys);
      break;
      case('N-M'):
        $c_table = $relation_config['connection_table'];
        $foreign_main_key = $relation_config['path'][1];
        $related_foreign_key = $relation_config['path'][2];
        $foreign_key = $relation_config['path'][3];
        $related_table = lako::get('objects')->get($relation_config['object'])->table();
        
        $match_field = $this->table().'_'.$main_key;
        $q['select'][] = "^`{$c_table}`.`{$foreign_main_key}` as `{$match_field}`";
        
        $q['join'] = array($c_table
                        ,array("`{$c_table}`.`{$related_foreign_key}`"
                              ,"`{$related_table}`.`{$foreign_key}`")  
                        ,'INNER');
        $q['where'] = array("{$c_table}.{$foreign_main_key}", 'IN' ,$extracted_main_keys);
      break;      
    }
    
    $q['select'] =  array_unique($q['select']);
    $related_reults = lako::get('objects')->get($relation_config['object'])->read($q);
    
    foreach($results as $key => $result){
      $set = lako::get('data')->array_where($related_reults,$match_field,$result[$main_key]);
      if(in_array($relation_config['type'],array('1-1','M-1'))){
        $results[$key][$relation] = null;
        if(!empty($set))
          $results[$key][$relation] = $set[0];
      }else
        $results[$key][$relation] = $set;
    }
    
  }
  
  
  /**
   *  this will resolve and index fields based on relations
   */
  function resolve_select_fields(Array $select){
    $fields = array(
      'main' => array(),
      'relations' => array(),
    );
    foreach($select as $field){
      //value is marked safe
      if($this->db->is_col_safe($field)){
        $fields['main'][] = $field;
        continue;
      }
      
      $may_be_parts = explode('.',$field,2);
      $count_of_parts = count($may_be_parts);
      if($count_of_parts == 1)
        $fields['main'][] = $field;
      elseif($count_of_parts == 2)
        $fields['relations'][$may_be_parts[0]][] = $may_be_parts[1];
    }
    return $fields;
  }
  
  /**
   *  add a from clause to DB query
   */
  function process_input_from(&$q,$input){
    $q['from'] = $this->table();
  }
  
  /**
   *  add a select clause to DB query
   */
  function process_input_join(&$q,$input){
    if(!isset($input['join']))
      return;
      
    $q['join'] = $input['join'];
  }
  
  
  function process_input_select(&$q,$input){
    if(!isset($input['select'])){
      $q['select'] = array('^*');
      return;
    }
    
    //its a string so we send it direct
    if(!is_array($input['select']))
      throw new Exception('Select should be an Array.');
      
    
    //if it is an array
    $fields = $this->resolve_select_fields($input['select']);
    
    //no need to find the needed key for relations, it no relations are queried
    $extra_keys = array();
    if(!empty($fields['relations']))
      $extra_keys = $this->main_keys_for_relations(array_keys($fields['relations']));
    
    $complete_key_set = array_unique(array_merge($fields['main'] , $extra_keys));
    
    foreach($complete_key_set as $field){
      if(!$this->db->is_col_safe($field))
        $q['select'][] = $this->table().'.'.$field;
      else
        $q['select'][] = $field;
    }
    if(empty($q['select']))
      $q['select'] = array('^*');
  }
  
  /**
   * gets you the required main keys for the relations
   */
  function main_keys_for_relations($relations){
    $main_keys = array();
    foreach($relations as $relation)
      $main_keys[] = $this->local_key($relation);
    
    return $main_keys;
  }
  
  
  
  /**
   *  add a from clause to DB query
   */
  function process_input_where(&$q,$input){
    if(!isset($input['where']))
      return;
    $q['where'] = $input['where'];
  }
  
  /**
   *  add a from clause to DB query
   */
  function process_input_search(&$q,$in_input){
    //make a local copy because we going modify
    $input = $in_input;
    // if not set or not properly set
    // we will eventually move to a more stricter input validation but not now.
    if(!isset($input['search']) || (count($input['search']) <= 1))
      return;
      
    //turn into normalized input
    if(!is_array($input['search'][0]))
      $input['search'][0] = array($input['search'][0]);
     
      
      
    //check where is filled or not.
    //if filled, we resolve it to a string and fit in our search with it
    //the reason for resolving into a string is we support many formats so no point re coding for all the conditions.
    if(!isset($q['where']))
      $q['where'] = array();
    else{
      $q['where'] = array(
        array($this->db->resolve_clause_where($q['where'])),
        'AND'
      );
    }
    
    //build where for the search element
    $search_where = array();
    foreach($input['search'][0] as $col){
      $search_where[] = array($col,'LIKE',"%{$input['search'][1]}%");
      $search_where[] = 'OR';
    }
    //unset the last OR
    unset($search_where[count($search_where)-1]);
    
    //set the where
    $q['where'][] = array($this->db->resolve_clause_where($search_where));
  }
  
  /**
   *  add a from clause to DB query
   */
  function process_input_order_by(&$q,$input){
    if(!isset($input['order_by']))
      return;
    $q['order_by'] = $input['order_by'];
  }
  
  
  /**
   *  add a from clause to DB query
   */
  function process_input_pagination(&$q,$input){
    if(!isset($input['pagination']))
      return;
    $per_page = $input['pagination']['per_page'];
    $page = $input['pagination']['page'];
    $offset = ($page-1)*$per_page;
    $limit = $per_page;
    $q['limit'] = array($offset,$limit);
  }
  
  /**
   * Add a new object to storage, You can also store a related object with it, Deep relations also works.
   * 
   * @param $object_data Array form on data.
   * @return Insert Id  on Success false on failure
   * 
   * @todo rollback support
   */
  function insert($in_data){
    $data =  $in_data;
    $response = array();
    $related_objects_data = $this->get_related_data($data);
    //remove all the data of related objects
    lako::get('data')->unset_keys($data, array_keys($related_objects_data));
    //now lets insert the main guy
    $insert_id = $this->db->insert($this->table(), $data);
    $response[$this->name()] = $insert_id;
    
    $data[$this->pkey()] = $insert_id;
    
    $missing_keys = array();
    foreach($related_objects_data as $relation => $rel_data){
      $local_key = $this->local_key($relation);
      if(!isset($data[$local_key]))
        $missing_keys[] = $local_key;
    }
    
    if(!empty($missing_keys)){
      $select = array_unique(array_merge($missing_keys,array_keys($data)));
      $data = $this->read(array('select'  => $select,
                                'where'   => array('id',$insert_id)));
      $data = $data[0];
    }
    
    foreach($related_objects_data as $relation => $rel_data){
      $response[$relation] = $this->save_related($data, $relation, $rel_data);
    }
    
    return $response;
  }
  
  /**
   * Insert related data for object.
   * 
   * @param Mixed $key|$data Value of primary key of data
   * @param String $related_object name of the relation
   * @param Array $data data to store
   * @return Mixed Save Response
   */
  function save_related($pkey_or_data, $relation, $in_data){
    //make local copy of $data, we might be changing it.
    $data = $in_data;
    
    $local_key = $this->local_key($relation);
    $pkey = $this->pkey();
    $may_be_needed_values = $this->get_object_data_carefully($pkey_or_data,array($local_key,$pkey));
    
    if(!$may_be_needed_values)
      throw new Exception("We could not get the required values {$pkey} and {$local_key}.");
      
    $pkey_val = $may_be_needed_values[$pkey];
    $local_key_val = $may_be_needed_values[$local_key];
    
    //dont need that array now, so unset it
    unset($may_be_needed_values);
    
    // we have the required values lets add data
    $relation_config = $this->get_relation($relation);
    switch($relation_config['type']){
      case('1-1'):
        //if this obecj does not hold the foreign key then we add key to the data being saved
        if(!$relation_config['holds_foreign_key'])
          $data[$relation_config['path'][1]] = $local_key_val;
          
        $object = lako::get('objects')->get($relation_config['object']);
        $results = $object->save($data);
        
        if($relation_config['holds_foreign_key']){
          //prepare the object to find required data from it
          $fkey = $relation_config['path'][1];
          $data[$object->pkey()] = $results[$relation_config['object']];
          $may_be_fkey_val = $object->get_object_data_carefully($data,array($fkey));
          if(!$may_be_fkey_val)
            $may_be_fkey_val = $object->get_object_data_carefully($results[$relation_config['object']],array($fkey));
          if(!$may_be_fkey_val)
            throw new Exception("{$fkey} Key does not exists in {$relation_config['object']}");
          
          $store_local_key = $may_be_fkey_val[$fkey];
          
          $this->update(array($local_key => $store_local_key)
                       ,array($this->pkey(),$pkey_val));
        }
        
        return $results;
      break;
      
      case('M-1'):
        // we definitely the holding the foreign key here
        $fkey = $relation_config['path'][1];
        
        $object = lako::get('objects')->get($relation_config['object']);
        $results = $object->save($data);
        $data[$object->pkey()] = $results[$relation_config['object']];
        
        $may_be_fkey_val = $object->get_object_data_carefully($data,array($fkey));
        if(!$may_be_fkey_val)
          $may_be_fkey_val = $object->get_object_data_carefully($results[$relation_config['object']],array($fkey));
        if(!$may_be_fkey_val)
          throw new Exception("{$fkey} Key does not exists in {$relation_config['object']}");
        
        $store_local_key = $may_be_fkey_val[$fkey];
            
        $this->update(array($local_key => $store_local_key)
                     ,array($this->pkey(),$pkey_val));
                       
        return $results;
        break;
        
      case('1-M'):
        //we are definetly not holding the key 
        //and it is array of values
        $fkey = $relation_config['path'][1];
        $responses = array();
        $object = lako::get('objects')->get($relation_config['object']);
        
        foreach($data as $key => $dataset){
          $dataset[$fkey] = $local_key_val;
          $responses[$key] = $object->save($dataset);
        }
        
        return $responses;
        break;
        
      case('N-M'):
        //we are definetly not holding the key 
        //and it is array of values
        $local_cnt_key = $relation_config['path'][1];
        $f_cnt_key = $relation_config['path'][2];
        $fkey = $relation_config['path'][3];
        
        $conenction_tbl = $relation_config['connection_table'];
         
        $object = lako::get('objects')->get($relation_config['object']);
        $responses = array();
        
        foreach($data as $key => $dataset){
          $responses[$key] = $object->save($dataset);
          $dataset[$object->pkey()] = $responses[$key][$object->name()];
          
          $may_be_fkey_val = $object->get_object_data_carefully($dataset,array($fkey));
          if(!$may_be_fkey_val)
            $may_be_fkey_val = $object->get_object_data_carefully($dataset[$object->pkey()],array($fkey));
          if(!$may_be_fkey_val)
            throw new Exception("{$fkey} Key does not exists in {$relation_config['object']}");
           
          try{
            $this->db->insert($conenction_tbl,array(
              $local_cnt_key  => $local_key_val,
              $f_cnt_key      => $may_be_fkey_val[$fkey],
            ));
          }catch(Exception $e){}
        }
        
        return $responses;
        break;
        
    }
  }
  
  
  /**
   *  Get some of the object data smartly if not present get from DB.
   *
   * Its a smart function to save DB calls, it accepts array or a value for pkey. If you send array it means you want to save a DB call e.g. using it in a loop. So it will not call DB instead it tries find values in array or just returns false when not present. If you send pkey it means you don't want to save DB call so it will get just that data from DB and returns.
   * 
   * @param String $pkey | Array $data 
   * @param Array $fields The fields you need from DB
   * @return Boolean false if not found | Array otherwise
   */
  function get_object_data_carefully($pkey_or_data, Array $fields){
    //consider its a pkey value
    if(!is_array($pkey_or_data)){
      $res = $this->read(array(
        'select' => $fields,
        'where'  => array($this->pkey(),$pkey_or_data)
      ));
      //there is no data for this object
      if(empty($res))
        return false;
      return $res[0];
    }
    
    //Now it it is an array
    //Make sure all the field are present in it
    if(lako::get('data')->verify_keys_exists($pkey_or_data,$fields))  
      return $pkey_or_data;
    return false;
  }
  
  
  /**
   * Gets you related data from an object data.
   * 
   * @param Array $data . The data 
   * @return indexed array of related data
   */
  function get_related_data($data){
    $related_data = array();
    foreach($data as $key => $val)
      if(is_array($val) && $this->has_relation($key))
        $related_data[$key] = $val;
        
    return $related_data;
  }
  
  /**
   * Tells you if relation exists for current object
   *
   * @param String $related_object Name of related object
   * @return Bool
   */
  function has_relation($relation){
    return isset($this->definition['relations'][$relation]);
  }
  
  
  /**
   * Get details or configuration for a relation with current object
   *
   * @param String $related_object Optional, Name of related object, if not provided returns all the objects.
   * @return Mixed when have relations False if relation doesn't exists.
   */
  function get_relation($relation = null){
    if(is_null($relation))
      return $this->definition['relations'];
      
    if($this->has_relation($relation))
      return $this->definition['relations'][$relation];
      
    return false;
  }
  
  /**
   * Get local key for a relation
   *
   * @param String $relation Required, Name of related object.
   * @return String $key 
   */
  function local_key($relation){
    $relation = $this->get_relation($relation);
    return $relation['path'][0];
  }
  
  /**
   * Save the data, if pkey is present in data it will be saved against that else it will be inserted.
   * 
   * @param Array $data Data to save
   * @return insert id
   */
  function save($data){
    $pkey = $this->pkey();
    //check if we should just update
    if(isset($data[$pkey])){
      //nothing to update
      if(count($data) == 1)
        return array($this->name()=>$data[$pkey]);
        
      $this->update($data,
                    array($pkey, $data[$pkey]));
      return array($this->name()=>$data[$pkey]);
    }
    return $this->insert($data);
  }
  
  /**
   * Update an Object.
   * 
   * @todo Remove Previous links to 1-M and N-M  relations before saving, this will be done with a function that will just add on top of previously related objects.
   * @param 
   */
  function update($in_data, $where){
    $data = $in_data;
    $related_objects_data = $this->get_related_data($data);
    $relations = array_keys($related_objects_data);
    lako::get('data')->unset_keys($data,$relations);
    
    $this->db->update($this->table()
                      ,$data
                      ,$where);
    
    if(empty($relations))
      return;
      
    //we will try to find already existing sets for 1-1 or M-1  relations
    $fields_grab = array();
    $relations_grab = array();
    foreach($relations as $relation){
      $relation_config = $this->get_relation($relation);
      
      $fields_grab_keys[] = $this->local_key($relation);
      $fields_grab_keys[] = $this->pkey(); 
      
      if(in_array($relation_config['type'], array('1-1','M-1'))){
        $fields_grab_keys[] = $relation.'.'.lako::get('objects')->get($relation_config['object'])->pkey();
        $fields_grab_keys[] = "{$relation}.{$relation_config['path'][1]}";
        $relations_grab[] = $relation;
      }
    }
    $fields_grab_keys = array_unique($fields_grab_keys);
    $rows = $this->read(array('select'  => $fields_grab_keys,
                              'where'   => $where));
                              
    foreach($rows as $row){
      $data_t = array_merge($data,$row);
      $data_r = $this->get_related_data($row);
   
      lako::get('data')->unset_keys($data_t,$relations);
      foreach($relations as $relation){
        $relation_config = $this->get_relation($relation);
        switch($relation_config['type']){
          case('1-1'):
          case('M-1'):
            $related_data = $related_objects_data[$relation];
            if(isset($data_r[$relation]))
              $related_data = array_merge($related_data,$data_r[$relation]);
            $this->save_related($data_t,$relation,$related_data);
          break;
          default:
            $related_data = $related_objects_data[$relation];
            $this->save_related($data_t, $relation, $related_data);
        }
      }
    }
    return;
  }
  
  function delete(){
    
  }
  
}