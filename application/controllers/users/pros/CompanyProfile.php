<?php defined('BASEPATH') OR exit('No direct script access allowed');

class CompanyProfile extends MY_Controller
{
 
    public function index()
    {   
        
        $crud = $this->crud_init('pros', ['user_id','company_name', 'ein_number', 'working_radius_miles','verified']);
        $crud->field_type('user_id', 'hidden');
        $crud->set_primary_key('user_id','pros');
        $crud->fields(
            'company_name',
            'ein_number',
            'working_radius_miles',
            'city',
            'state',
            'zip',
            'address',
            'address_2',
            'number_of_employees',
            'is_pre_registered',
            'verified'
        );

        $state_info = $crud->getStateInfo();

        $crud->set_lang_string('form_update_changes','Update company profile');

        $crud->set_lang_string('update_success_message', 'Company profile updated successfully  '.'<a href="'.site_url().'users/Pro/pro_data/'.$state_info->primary_key.'">Go back</a>');
        
        $crud->unset_back_to_list();

        $output = $crud->render();
      
        $this->view_custom_temp_crud($output, $state_info->primary_key , 'Company Profile'); 

    }

}    
