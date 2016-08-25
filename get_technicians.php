<?php

class Technician extends CI_controller{
    public function index(){

      $this->db->select('user_id');
      $this->db->from('technicians');
      $this->db->where('added_by',60);
      $query = $this->db->get();
      foreach($query->result()

    }
}