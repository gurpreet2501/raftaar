<?php defined('BASEPATH') OR exit('No direct script access allowed');

class CreateJob extends CI_Controller
{
  public function index(){
    $this->db->insert('jobs', [
      'request_date' => date('Y-m-d', strtotime($stop_date . ' +1 day')),
      'time_slot_id' => '1',
      'customer_id' => '16',
      'contact_name' => 'John Doe',
      'phone' => '9901112333',
      'latitude' => '40.7034947',
      'longitude' => '-74.0598721',
      'pro_id' => '0',
      'technician_id' => '',
      'created_at' => '2015-10-29 00:00:00',
      'status' => 'Open',
      'service_type' => 'Install',
      'title' => '',
      'customer_notes' => ''
    ]);
  }
}
