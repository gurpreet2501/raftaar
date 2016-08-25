<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Notifications extends MY_Controller
{
 
    public function index()
    {   
        
        $crud = $this->crud_init('pro_settings', [
            'location_services',
            'text_messaging',
            'available_jobs_notification',
            'job_won_notification',
            'job_lost_notification',
            'job_rescheduled',
            'job_canceled'
            ]);

        $crud->set_primary_key('user_id','pro_settings');

        $crud->fields([
            'location_services',
            'text_messaging',
            'available_jobs_notification',
            'job_won_notification',
            'job_lost_notification',
            'job_rescheduled',
            'job_canceled'
            ]);

        $state_info = $crud->getStateInfo();

        $crud->set_lang_string('form_update_changes','Update notifications details');

        $crud->set_lang_string('update_success_message', 'Notifications updated successfully  '.'<a href="'.site_url().'users/Pro/pro_data/'.$state_info->primary_key.'">Go back</a>');

        $crud->unset_back_to_list();

        $output = $crud->render();
      
        $this->view_custom_temp_crud($output, $state_info->primary_key , 'Notifications'); 


    }

}    
