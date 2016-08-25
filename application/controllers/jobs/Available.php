<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Available extends MY_Controller
{
    public function index()
    {
        $crud = $this->crud_init('jobs', ['contact_name','request_date', 'status']);
        $user_format = '{email} - {phone}';
        $crud->where('status','open')
             ->where('customer_id!=', 0);
        $crud->set_relation('pro_id', 'users', $user_format, ['role'=>'pro'])
             ->set_relation('technician_id','users', $user_format, ['role'=>'technician'])
             ->set_relation('customer_id','users', $user_format, ['role'=>'customer'])
             ->set_relation('service_id','services','name')
             ->set_relation('locked_by','users', $user_format, ['role'=>'pro'])
             ->set_relation('time_slot_id','time_slots','start {start}, end {end}');
       
        $crud->fields('request_date','time_slot_id' , 'customer_id' ,'contact_name', 'phone' , 'latitude', 'longitude', 'service_id', 'service_type',
          'title' ,'customer_notes','job_addresses' ,'job_appliances');             

        $crud->display_as([
            'technician_id' => 'Technician',
            'time_slot_id'  => 'Time Slot',
            'customer_id'   => 'Customer',
            'service_id'    => 'Service',
            'pro_id'        => 'Company'
        ]);
       
       // $crud->fields('job_appliances','job_addresses');
        //display in order
        $crud->unset_fields('status','locked_on','locked_by','started_at','finished_at','total_cost','updated_at','pro_id','technician_id','created_at','warranty');

       if($crud->getState()=='add'){
          
          $details = array(
          'job_status'  => 'Open',
          'url'         => 'available'  
          );

          $this->create_empty_job($details);        
       } 
   
       $crud->callback_field('job_appliances','Jobs_helper::job_appliances');  
       $crud->callback_field('job_addresses','Jobs_helper::manage_job_addresses');  

       $this->view_crud($crud->render(), 'Jobs');
    }

     public function job_addresses(){
        $crud = $this->crud_init('job_customer_addresses', ['address']);
        $crud->set_primary_key('job_id','job_customer_addresses');
        $crud->where('job_id', $id);
        $crud->field_type('job_id','hidden');
        $crud->field_type('updated_at','hidden');
        $crud->field_type('created_at','hidden');
        $crud->unset_back_to_list();
        $this->view_crud($crud->render(), 'Job Customer Addresses');
    }

     public function manage_job_appliances($id){
        $crud = $this->crud_init('job_appliances', ['power_source','description']);
        $crud->set_primary_key('job_id','job_customer_addresses');
        $crud->where('job_id', $id);
        $crud->set_relation('appliance_id','appliance_types','name',array('_soft_deleted' => 0));
        $crud->field_type('job_id','hidden', $id);
        $crud->field_type('updated_at','hidden');
        $crud->field_type('created_at','hidden');
        $this->view_crud($crud->render(), 'Manage Job Appliances');
    }

}