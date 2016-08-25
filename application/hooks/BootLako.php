<?php


class BootEloquent{
  function boot(){
    
    require_once __DIR__.'\..\..\..\..\..\third_party\ag-soft\init_lako.php';

    $ci = get_instance();
    $ci->load->database();

    $capsule = new Capsule;
    $capsule->addConnection(array(
        'driver'    => $ci->db->dbdriver,
        'host'      => $ci->db->hostname,
        'database'  => $ci->db->database,
        'username'  => $ci->db->username,
        'password'  => $ci->db->password,
        'charset'   => $ci->db->char_set,
        'collation' => $ci->db->dbcollat,
        'prefix'    => $ci->db->dbprefix
    ));
    $capsule->setEventDispatcher(new Dispatcher(new Container));
    $capsule->setAsGlobal();
    $capsule->bootEloquent();
  }
}
