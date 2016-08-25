<?php defined('BASEPATH') OR exit('No direct script access allowed');

class UserIdentification extends MY_Controller
{
 
    public function index()
    {   
        
        $crud = $this->crud_init('users', ['account_status', 'email', 'phone']);
        $crud->fields(
            'account_status',
            'email',
            'phone'
        );
        
        $state_info = $crud->getStateInfo();

        $crud->set_lang_string('form_update_changes','Update Identification details');

        $crud->set_lang_string('update_success_message', 'Identification details updated successfully  '.'<a href="'.site_url().'users/Pro/pro_data/'.$state_info->primary_key.'">Go back</a>');

        $crud->unset_back_to_list();

        $output = $crud->render();
      
        $this->view_custom_temp_crud($output, $state_info->primary_key , 'User Identification'); 

    }

}    
