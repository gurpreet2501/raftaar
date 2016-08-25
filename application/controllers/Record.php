<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Record extends CI_Controller
{
  function __construct(){
  	parent::__construct();
  	$this->load->database();
  }	

public function view_spout($id){
  
  	 $output= (object)[];
  	 $query = $this->db->query("SELECT * FROM spouts WHERE id =".$id);
     $record = $query->first_row();
     $output->record = $record;
  	 $output->js_files = [];
  	 $output->css_files = [];
  	 $output->heading = 'View Spout';
  	 $output->template = 'record/view';
 	 $this->load->view('base', $output);
  }

public function view_reply($id){
  
  	 $output= (object)[];
  	 $query = $this->db->query("SELECT * FROM replies WHERE id =".$id);
     $record = $query->first_row();
     $output->record = $record;
  	 $output->js_files = [];
  	 $output->css_files = [];
  	 $output->heading = 'View Reply';
  	 $output->template = 'record/view';
 	 $this->load->view('base', $output);
  }

}
