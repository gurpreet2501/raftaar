<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_helper{
  
  function __construct(){}
  
  //**** Customer Profile Management ****//

  public static function delete_spout($primary_key){
     $CI = get_instance();

     $CI->load->database();
     
     $query = $CI->db->query("SELECT user_id FROM spouts WHERE id =".$primary_key);
    
     $user_id = $query->first_row()->user_id;

     if(!$user_id || !$primary_key){
        echo "Unable to perform action. ";
        return;
     }

     $CI->load->view('admin/user_action',array(
      'record_id'  => $primary_key,
      'user_id'   => $user_id,
      'type'      => 'spouts'
      ));
  }

  public static function delete_reply($primary_key){
     $CI = get_instance();

     $CI->load->database();
   
     $query = $CI->db->query("SELECT user_id FROM replies WHERE id =".$primary_key);

     $user_id = $query->first_row()->user_id;

     if(!$user_id || !$primary_key){
        echo "Unable to perform action. ";
        return;
     }
     
     $CI->load->view('admin/user_action',array(
      'record_id'  => $primary_key,
      'user_id'   => $user_id,
      'type'     => 'replies'

      ));
  }

  public static function technician_profile($value='', $pkey=0){
    
    $CI = get_instance();
    // Reading id of the record to edit
    $plan_attached = $CI->db->select('id')
                         ->from('technicians')
                         ->where('user_id', $pkey)->get()->result_array();
    
    $id = $plan_attached[0]['id'];                      

    return '<a href="'.site_url("users/technician/edit_technician/index/edit/{$id}").'" class="fancy iframe">Manage Personal Profile</a>
            <br/>
            <br/>
             <b>NOTE: Profile Management allows you to edit the details like First Name, Last Name , City etc</b>';
  }
  public static function pro_profile($value='', $pkey=0){
    
    $CI = get_instance();
    
    // Reading id of the record to edit
    $plan_attached = $CI->db->select('id')
                         ->from('pros')
                         ->where('user_id', $pkey)->get()->result_array();
    
    $id = $plan_attached[0]['id'];                      

    return '<a href="'.site_url("users/pro/edit_pro/edit/{$id}").'" class="fancy iframe">Manage Company Profile</a>';
  }

  public static function manage_technician($value='', $pkey=0){
    
      return '<a href="'.site_url("users/pro/manage_technician/".$pkey).'"  class="fancy iframe">Manage Technicians</a>';
  }

  public static function manage_users_table($value='', $pkey=0){
    $CI = get_instance();
    $plan_attached = $CI->db->select('user_id')
                         ->from('technicians')
                         ->where('id', $pkey)->get()->result_array();
    
    $id = $plan_attached[0]['user_id'];  
    
      return '<a href="'.site_url("users/pro/users_table/edit/".$id).'"  class="fancy iframe">Manage Profile</a>';
  }

  public static function warranty_plans_info($value='', $pkey=0){
    
      return '<p><strong>DRAFT</strong>: Plan will not be shown to customers and cannot be purchased. Choose this if you are still thinking on the plan terms.<br/>
      <strong>ACTIVE</strong>: Plan will be shown to customers and can be purchased. Once active a plan terms are frozen and cannot be changed. It can only be retired.<br/>
      <strong>RETIRED</strong>: Plan will no longer be shown to customers and cannot be purchased. Customers who already baught the plan will not be affected. Once a plan is retired you cannot make it active. It will stay retired.</p>';
  }


  public static function display_order_info($value='', $pkey=0){
    
      return 'Smaller number listed at higher position';
  }

 

  public static function pro_verification($value='', $pkey=0){
     $CI = get_instance();
    // Reading id of the record to edit
    $plan_attached = $CI->db->select('id, verified')
                         ->from('pros')
                         ->where('user_id', $pkey)->get()->result_array();
    
    $id = $plan_attached[0]['id'];                      
    $verified = $plan_attached[0]['verified'];                      

    return ($verified) ? 'Verified' : '<a href="'.site_url("users/pro/pro_verification_status/edit/{$id}").'" class="fancy iframe">Change Verification Status</a>';
  }

  public static function tech_verification($value='', $pkey=0){
     $CI = get_instance();
    // Reading id of the record to edit
    $plan_attached = $CI->db->select('id, verified')
                         ->from('technicians')
                         ->where('user_id', $pkey)->get()->result_array();
    
    $id = $plan_attached[0]['id'];                      
    $verified = $plan_attached[0]['verified'];                      

    return ($verified) ? 'Verified' : '<a href="'.site_url("users/technician/tech_verification_status/edit/{$id}").'" class="fancy iframe">Change Verification Status</a>';
  }

  public static function personal_profile($value='', $pkey=0){
    $CI = get_instance();
    $plan_attached = $CI->db->select('id')
                         ->from('technicians')
                         ->where('user_id', $pkey)->get()->result_array();
    
    $id = $plan_attached[0]['id']; 

     return '<a href="'.site_url("users/technician/edit_personal_info/index/edit/{$id}").'" class="fancy iframe">Manage Personal Profile</a>
            <br/>
            <br/>
             <b>NOTE: Profile Management allows you to edit the details like First Name, Last Name , City etc</b>';


  }


  public static function manage_addresses($value='', $pkey=0){
    
    $CI = get_instance();
    $plan_attached = $CI->db->select('id')
                         ->from('customers')
                         ->where('user_id', $pkey)->get()->result_array();
    
    $id = $plan_attached[0]['id']; 

      return '<a href="'.site_url("users/customer/customer_addresses/{$id}").'" class="fancy iframe">Manage Customer Addresses</a>
            <br/>
            <br/>
             <b>NOTE: Address Management allows you to edit and add address fields and also allows to to set the default address of customer</b>';

  }
  
}
