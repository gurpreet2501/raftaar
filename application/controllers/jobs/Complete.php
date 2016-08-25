<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Complete extends MY_Controller
{
    public function index()
    {
        $crud = $this->crud_init('jobs', ['contact_name','request_date', 'status']);
        $user_format = '{email} - {phone}';
        $crud->where('status','Complete')
             ->where('customer_id!=', 0);
        $crud->set_relation('pro_id', 'users', $user_format, ['role'=>'pro'])
             ->set_relation('technician_id','users', $user_format, ['role'=>'technician'])
             ->set_relation('customer_id','users', $user_format, ['role'=>'customer'])
             ->set_relation('service_id','services','name')
             ->set_relation('locked_by','users', $user_format, ['role'=>'pro'])
             ->set_relation('time_slot_id','time_slots','start {start}, end {end}');

        $crud->unset_add();
        $crud->unset_edit();
        
        $crud->display_as([
            'technician_id' => 'Technician',
            'time_slot_id'  => 'Time Slot',
            'customer_id'   => 'Customer',
            'service_id'    => 'Service',
            'pro_id'        => 'Company'
        ]);
       
        //display in order
        $crud->unset_fields('status','locked_on','locked_by','started_at','finished_at','total_cost','updated_at','pro_id','technician_id','created_at','warranty');

       // if($crud->getState()=='add'){
          
       //    $details = array(
       //    'job_status'  => 'Complete',
       //    'url'         => 'complete'  
       //    );

       //    // $this->create_empty_job($details);        
       // } 
   
       $crud->callback_field('job_appliances','Crud_helper::job_appliances');  

       $this->view_crud($crud->render(), 'Jobs');
    }
}