<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Technician extends MY_Controller
{
 
    public function index()
    {
        $crud = $this->crud_init('users', ['id','email', 'phone','account_status']);
        $crud->field_type('account_closed', 'true_false', array('Open', 'Closed'));
        $crud->where('role', 'Technician')
             ->where('password!=', '__FIXT__')
             ->fields([
            'email','phone','password','verification_status','manage_profile' , 'account_status'
            ]);
        
        $crud->display_as('id','Name');
        $crud->set_primary_key('user_id','technicians');
        $crud->set_relation('id','technicians','{first_name} {last_name}');
                
        $crud->unset_read();  

        // $crud->required_fields('password');
        
        // Creating an empty user
        if($crud->getState()=='add'){
           $details = array(
             'role'  => 'technician',
             'table' => 'technicians',
             'url'   => 'technician'
           );     
          $this->create_empty_user($details);
        }  
               
        if($crud->getState()=='read')
           $crud->field_type('password', 'hidden');    

        $crud->callback_field('manage_profile','Crud_helper::technician_profile');   
        $crud->callback_field('verification_status','Crud_helper::tech_verification');   
        $crud->callback_after_update(array($this,'delete_technician_token'));      
        $this->set_password_field($crud, 'bcrypt_password_callback');
        $this->view_crud($crud->render(), 'Technician');   

    }

    public function tech_verification_status()
    {
        $crud = $this->crud_init('technicians', ['first_name','last_name']);
        // $crud->set_relation('added_by','users','id');
        $crud->unset_texteditor(true);
        $crud->unset_export(true);
        $crud->fields('verified'); 
        $crud->unset_back_to_list();
        $crud->unset_print(true);
        $crud->unset_read(true);
        $crud->field_type('user_id', 'hidden');
        $crud->field_type('invite_code', 'hidden');
        $crud->field_type('added_by', 'hidden');
        $crud->columns('verified');
        $this->view_crud($crud->render(), 'edit_verification_status','crud-pop-up');   
    } 

    function delete_technician_token($post_array, $primary_key){
    
      if($post_array['account_status'] == 'ACTIVE')
        return true;
      
      $this->db->where('user_id', $primary_key);
      $this->db->delete('user_sessions');
      return true;  
    }

    public function edit_technician()
    {
        $crud = $this->crud_init('technicians', ['first_name','last_name']);
        // $crud->display_as('added_by', 'PRO/Admin');
        // $crud->set_relation('added_by','users','{email} - {phone}');
        $crud->unset_edit_fields('created_at','updated_at','verified','avg_rating','added_by','scheduled_jobs_count','completed_jobs_count');
        // $crud->set_relation('added_by','users','id');
        $crud->set_field_upload('profile_image','../images/');
        $crud->set_field_upload('driver_license_image','../images/');
        $crud->unset_texteditor(true);
        $crud->unset_export(true);
        $crud->unset_back_to_list();
        $crud->unset_print(true);
        $crud->unset_read(true);
        $crud->field_type('user_id', 'hidden');
        $crud->field_type('invite_code', 'hidden');
        $this->view_crud($crud->render(), 'edit_technician','crud-pop-up');   
    }

    
    public function edit_personal_info()
    {
        $crud = $this->crud_init('technicians', ['first_name','last_name']);
        $crud->unset_edit_fields('pickup_jobs','verified','added_by','is_used','invite_code','avg_rating','scheduled_jobs_count','completed_jobs_count','created_at','updated_at');
        $crud->set_field_upload('profile_image','../images/');
        $crud->set_field_upload('driver_license_image','../images/');
        $crud->set_field_upload('trade_license_image','../images/');
        $crud->unset_texteditor(true);
        $crud->unset_export(true);
        $crud->unset_back_to_list();
        $crud->unset_print(true);
        $crud->unset_read(true);
        $crud->field_type('user_id', 'hidden');
        $crud->field_type('invite_code', 'hidden');
        $this->view_crud($crud->render(), 'Personal Profile','crud-pop-up');   

    }


}    
