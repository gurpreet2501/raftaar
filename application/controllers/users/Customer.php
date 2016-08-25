<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends MY_Controller
{
 
    public function index()
    {
        $crud = $this->crud_init('users', ['id', 'email', 'phone','account_status']);
        $crud->field_type('account_closed', 'true_false', array('Open', 'Closed'));
        $crud->where('role', 'Customer')
             ->where('password!=', '__FIXT__')
             ->fields([
            'email','phone','password', 'manage_profile' ,'account_status' ,'manage_addresses'
            ]);
        
        $crud->unique_fields('phone','email');
        $crud->required_fields('phone','email');

        $crud->display_as('id','Name');     
        $crud->set_primary_key('user_id','customers');
        $crud->set_relation('id','customers','{first_name} {last_name}');

        $crud->unset_read();
        
        if($crud->getState()=='add'){
         // Customer Details 
            $details = array(
               'role'  => 'customer',
               'table' => 'customers',
               'url'   => 'customer'
            );  
        // Creating an empty user
            $this->create_empty_user($details);
        }

        if($crud->getState()=='read')
           $crud->field_type('password', 'hidden');      
       
        $crud->callback_field('manage_profile','Crud_helper::customer_profile');             
        $crud->callback_field('manage_addresses','Crud_helper::manage_addresses');             
        $this->set_password_field($crud, 'bcrypt_password_callback');
        $crud->callback_after_update(array($this,'delete_customer_token')); 
        $this->view_crud($crud->render(), 'Customer'); 

    }

    public function customer_addresses($id){
     
        $crud = $this->crud_init('customer_addresses', ['address']);
        $crud->where('user_id',$id);
        $crud->field_type('user_id', 'hidden', $id); 
        $crud->fields('address','address_2','city','state','zip','user_id','default');
        $crud->callback_after_insert(array($this, 'set_customer_default_address'));
        $crud->callback_after_update(array($this, 'set_customer_default_address'));
        $this->view_crud($crud->render(), 'Customer Addresses'); 
    }
    
    /* 
      @param1: $user_id  Int
      @param1: $id       Int 
      @param1: $value    Bool (0,1) 
    */
    public function set_address_field_value($user_id, $id, $value){
    
        $array = array('id' => $id, 'user_id' => $user_id);
        $this->db->where($array); 
        $this->db->update('customer_addresses',array('default' => $value));
    }
    
    public function set_address_field_value_to_zero($user_id){

        $this->db->where('user_id=', $user_id);
        $this->db->update('customer_addresses', array('default' => 0));      
    }

    
    public function set_customer_default_address($post_array, $primary_key){
       
        if(!$post_array['default'])
            return true;

        // Set field values to zero
        $this->set_address_field_value_to_zero($post_array['user_id']);

        $this->set_address_field_value($post_array['user_id'], $primary_key, $post_array['default']);

    }


    public function delete_customer_token($post_array, $primary_key){

        if($post_array['account_status'] == 'ACTIVE')
          return true;
      
        $this->db->where('user_id', $primary_key);
        $this->db->delete('user_sessions');
        return true;  

    }

    public function edit_customer()
    {   
        $crud = $this->crud_init('customers', ['first_name','last_name']);
        $crud->unset_texteditor(true);
        $crud->unset_export(true);
        $crud->unset_back_to_list();
        $crud->unset_print(true);
        $crud->unset_read(true);
        $crud->field_type('user_id', 'hidden');
        $this->view_crud($crud->render(), 'edit_customer','crud-pop-up');   
        // $crud->css_files[] = base_url('assets/fancy-box-edit.css');
    }
    


}    
