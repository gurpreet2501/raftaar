<?php defined('BASEPATH') OR exit('No direct script access allowed');

class BankDetails extends MY_Controller
{
 
    public function index()
    {   
        
        $crud = $this->crud_init('pros', [
            'bank_name',
            'bank_routing_number',
            'bank_account_number',
            'bank_account_type']
        );

        $crud->field_type('user_id', 'hidden');
        
        $crud->set_primary_key('user_id','pros');

        $state_info = $crud->getStateInfo();

        $crud->set_lang_string('form_update_changes','Update bank details');

        $crud->set_lang_string('update_success_message', 'Bank details updated successfully  '.'<a href="'.site_url().'users/Pro/pro_data/'.$state_info->primary_key.'">Go back</a>');


        $crud->fields(
            'bank_name',
            'bank_routing_number',
            'bank_account_number',
            'bank_account_type'
        );
        
        $crud->unset_back_to_list();
         
        $output = $crud->render(); 

        $this->view_custom_temp_crud($output, $state_info->primary_key , 'Bank Details');  

    }

}    
