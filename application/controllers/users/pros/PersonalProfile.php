<?php defined('BASEPATH') OR exit('No direct script access allowed');

class PersonalProfile extends MY_Controller
{
 
    public function index()
    {   
        
        $crud = $this->crud_init('technicians', ['company_name', 'ein_number', 'working_radius_miles','verified']);

        $crud->set_primary_key('user_id','technicians');

        $crud->fields(
            'first_name',
            'last_name',
            'social_security_number',
            'years_in_business',
            'trade_license_number',
            'avg_rating'
        );
       
        $state_info = $crud->getStateInfo();

        $crud->set_lang_string('form_update_changes','Update personal profile');

        $crud->set_lang_string('update_success_message', 'Personal profile updated successfully  '.'<a href="'.site_url().'users/Pro/pro_data/'.$state_info->primary_key.'">Go back</a>');

        $crud->unset_back_to_list();

        $output = $crud->render();

        $this->view_custom_temp_crud($output, $state_info->primary_key , 'Personal Profile');  

    }

}    
