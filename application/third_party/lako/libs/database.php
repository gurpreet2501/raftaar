<?php

/**
 *  Handles database interaction
 */
class lako_database extends lako_lib_base{
  protected $version = '0.0.1';
  protected $pdo = null;
  protected $required_query_inputs = array();
  protected $prefix_clauses = array();
  
  function __construct($config = array()){
    if(empty($config))
      $config = lako::get('config')->get('database');
    parent::__construct($config);
    
    $this->required_query_inputs = array(
      'select' => array('from')
    );
    $this->prefix_clauses = array(
      'select'  => 'SELECT',
      'from'    => 'FROM',
      'where'   => 'WHERE',
      'group_by'=> 'GROUP BY',
      'order_by'=> 'ORDER BY',
      'limit'   => 'LIMIT'
    );
    
    //connect on load
    $this->connect();
  }
  
  /**
   * connect to database
   */
  function connect(){
    $this->pdo = new PDO("mysql:host={$this->config['host']};dbname={$this->config['database']}", 
                        $this->config['username'], 
                        $this->config['password']);
                        
    $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }
  
  /**
   *  verify if the required inputs are present for each kind of query
   */
  function is_query_valid($q, $type){
    foreach($this->required_query_inputs[$type] as $input)
      if(!isset($q[$input]))
        throw new Exception("{$type} Query is missing {$input}.");
    return true;
  }
  /**
   *  safegaurd input before running queries
   */
  function safe_val($val){
    return is_numeric($val)? $val : $this->pdo->quote($val);
  }
  
  /**
   * Check if the user has already sent us signal that col value is escaped
   */
  function is_col_safe($col){
    return ($col{0} == '^');
  }
  
  function safe_col($col){
    if($this->is_col_safe($col))
      return substr($col, 1);
    $dot_replaced = str_replace('.','`.`',$col);
    return "`{$dot_replaced}`";
  }
  
  
  /**
   * Accepts valid lako_select_query
   * 
      lako_read_query
      lako_db_where
      where (a=b) and ((s=d) || (g=f))
      where = [a,=,b]
      or_where = ['((s=d) || (g=f))']
      
      where parser
        if one argument then just add as it is
        if 2 add as = operator
        if 3 consider 2nd as operator
        
      select
        lako_db_select
        array(
          'object.*'
        )
        
      from
        lako_db_from
        table
      
      join
        from table left join "query or table" on condition
        join = array(left , string|lako_db_query , lako_db_where)
        
      limit
        lako_db_limit
        offset 
   */
  function select($query){
    $q = $this->resolve_query($query,'select');
    return $this->get_results($q);
  }
  
  /**
   * Prefixes some of the resolved clauses, needed to maintain re-usability
   */
  function prefix_clause($type,$text){
    if(!isset($this->prefix_clauses[$type]) || (trim($text) == ''))
      return $text;
    return "{$this->prefix_clauses[$type]} {$text}";
  }
  
  /**
   *  makes query into a runnable sql statement
   *  in complete
   */
  function resolve_query($query,$type){
    $this->is_query_valid($query,$type);
    $clause_order = array('select','from','join','where','group_by','order_by','limit');
    $q = '';
    foreach($clause_order as $clause){
      $query_clause = isset($query[$clause])? $query[$clause]: null;
      $resolved_clause = $this->{'resolve_clause_'.$clause}($query_clause);
      $resolved_clause = $this->prefix_clause($clause,$resolved_clause);
      $q .=  $resolved_clause;
      $q .=  ' ';
    }
    return $q;
  }
  
  /**
   * Return results from a query
   */
  function get_results($q){
    //file_put_contents(lako::get_config('lako_path').'/q.sql',$q.';'.PHP_EOL.PHP_EOL,FILE_APPEND);
    
    $resource = $this->pdo->prepare($q);
    $resource->execute();
    if(!$resource)
      throw new Exception("Invalid query {$q}.");
    return $resource->fetchAll(PDO::FETCH_ASSOC);
  }
  
  /**
   * Return results from a query
   */
  function execute($q){
    $resource = $this->pdo->prepare($q);
    $resource->execute();
    return $resource->rowCount();
  }
  
  /**
   *  Incomplete
   */
  function resolve_clause_group_by($clause){
    if(is_null($clause))
      return '';
    $q = '';
    $escaped = array();
    foreach($clause as $col)
      $escaped[] = $this->safe_col($col);
    return $q.' '.implode(', ', $escaped);
  }
  
  function resolve_clause_order_by($clause){
    if(is_null($clause) || empty($clause))
      return '';
    
    if(!is_array($clause[0]))
      return $this->resolve_clause_order_by(array($clause));
    $q = '';
    $escaped = array();
    foreach($clause as $ob_clause){
      $single = $this->safe_col($ob_clause[0]);
      if(isset($ob_clause[1]))
        $single .= ' '.$ob_clause[1];
      $escaped[] = $single;
    }
    return $q .implode(', ',$escaped);
  }
  
  function resolve_clause_limit($clause){
    if(is_null($clause) || empty($clause))
      return '';
    return ''.implode(', ', $clause);
  }
  
  function resolve_clause_select($clause){
    $q = '';
    if(is_null($clause))
      return $q.'*';
    $escaped = array();
    foreach($clause as $key => $col)
      $escaped[$key] = $this->safe_col($col);
    return $q.' '.implode(', ',$escaped);
  }
  
  function resolve_clause_from($clause){
    $q = '';
    if(is_null($clause))
      throw new Exception('FROM clause is missing from query.');
    return $q.' '.$this->safe_col($clause);
  }
  
  /**
   * Incomplete
   */
  function resolve_clause_join($clause){
    if(is_null($clause) ||  empty($clause))
      return '';
    //this function handles multiple, if its not then make it multiple
    if(!is_array($clause[0]))
      return $this->resolve_clause_join(array($clause));
    $q = '';
    foreach($clause as $l_clause){
      if(isset($l_clause[2]))
        $q .= "{$l_clause[2]} ";
      $q .= 'JOIN ';
      $q .= $this->safe_col($l_clause[0]).' ';
      
      if(isset($l_clause[1]))
        $q .= 'ON '.$this->resolve_clause_where($l_clause[1], false); 
      
      $q .= PHP_EOL.' '; 
    }
    return $q;
  }
  
  /**
   * incomplete
   * where can be of 4 types
   * [col , operator , val] - simplest form
   * [col , val] - assumes the operator is '='
   * ['statement'] - assumes the statement is constructed by user itself
   * 'statement' - assumes the statement is constructed by user itself
   * 'and|or' assumes its a glue between multiple where statements
   */
  function resolve_clause_where($clause, $escape=true){
    if(is_null($clause) || empty($clause))
      return '';
      
    $q = '';
    
    //user made the where himself no need to escape just let it go
    if(!is_array($clause))
      return $q .$clause;
      
    //if user did not want the multiple where statements, we make it for him
    
    if(!is_array($clause[0]))
      return $this->resolve_clause_where(array($clause), $escape);
    
    foreach($clause as $where){
      
      if(!is_array($where)){
        $q .= $where .' ';
        continue;
      }
      
      switch(count($where)){
        case(3):
          $q .= '('.$this->resolve_simple_where($where, $escape).')'. ' ';
        break;
        case(2):
          $q .= '('.$this->resolve_simple_where(array($where[0],'=',$where[1]), $escape).')'. ' ';
        break;
        case(1):
          $q .= "({$where[0]}) ";
        break;
        default:
          throw new Exception('Invalid parameters for where clause.'.print_r($where,true));
      }
    }
    return $q;
  }
  
  /**
   *  resolves simple where
   */
  function resolve_simple_where($where, $escape=true){  
    $escaped_col = $escape? $this->safe_col($where[0]) : $where[0];
    
    //special case for 'IN' operator
    if($where[1] == 'IN'){
      $escaped_val = array();
      foreach($where[2] as $val)
        $escaped_val[] = $this->safe_val($val);
        
      $escaped_val = '('.implode(', ',$escaped_val).')';
    }else
      $escaped_val = $escape? $this->safe_val($where[2]) : $where[2];
    
    return "{$escaped_col} {$where[1]} {$escaped_val}";
  }
  
  /**
   * Insert Query
   */
  function insert($table, $data = array()){
    if(empty($data))
      return false;
    $q = $this->build_insert_query($table, $data);
    $this->execute($q);
    return $this->pdo->lastInsertId();
  }
  
  function build_insert_query($table, $data = array()){
    if(empty($data))
      throw new Exception("Inserting into {$table} but data was empty");
    $safe_tbl = $this->safe_col($table);
    
    $q = "INSERT INTO {$safe_tbl} SET";
    $inserts = array();
    
    foreach($data as $col => $val){
      $safe_col = $this->safe_col($col);
      $safe_val = $this->safe_val($val);
      $inserts[] = "{$safe_col}={$safe_val}";
    } 
    return $q.' '.implode(', ',$inserts);
  }
  
  /**
   * update if key present or insert
   */
  function save($table, $data = array(), $pkey){
    if(isset($data[$pkey])){
      $where = array($pkey, $data[$pkey]); //dont want to udpate key its useless
      $to_update = $data;
      unset($to_update[$pkey]);
      $this->update($table, $to_update, $where);
      return $data[$pkey];
    }
    //else just do simple insert
    return $this->insert($table, $data);
  }
  
  
  /**
   * Insert Query
   */
  function update($table, $data = array(), $where = array()){
    if(empty($data))
      return false;
      
    $update_query = $this->build_update_query($table, $data, $where);
    
    return $this->execute($update_query);
  }
  
  function build_update_query($table, $data = array(), $where = array()){
    if(empty($data))
      throw new Exception("Updating {$table} but data was empty");
    
    $safe_tbl = $this->safe_col($table);
    
    $q = "UPDATE {$safe_tbl} SET";
    $inserts = array();
    foreach($data as $col => $val){
      $safe_col = $this->safe_col($col);
      $safe_val = $this->safe_val($val);
      $inserts[] = "{$safe_col}={$safe_val}";
    }

    $q .= ' '.implode(', ',$inserts);

    if(!empty($where) && $where != '')
      $q .= ' WHERE '.$this->resolve_clause_where($where);
      
    return $q;
  }
  
  /**
   * Insert Query
   */
  function delete($table, $where = array()){
    $q = $this->build_delete_query($table, $where);
    return $this->execute($q);
  }
  
  function build_delete_query($table, $where = array()){
    $safe_tbl = $this->safe_col($table);
    $q = "DELETE FROM {$safe_tbl}";
    if(!empty($where) && $where != '')
      $q .= ' WHERE '.$this->resolve_clause_where($where);
    return $q;
  }
  
}
  