<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$ci = get_instance();

lako::get('config')->add_config('database',array(
  'username' => $ci->db->username,
  'password' => $ci->db->password,
  'database' => $ci->db->database,
  'host'     => $ci->db->hostname,
  'table_prefix'  => '',
  'table_suffix'  => '',
));