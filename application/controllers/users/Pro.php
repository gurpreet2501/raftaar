<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Pro extends MY_Controller
{

    public function index()
    {   

        $crud = $this->crud_init('users', ['id' ,'email', 'phone','account_status','created_at']);
        $crud->set_theme('datatables');
        $crud->where('role', 'pro')
             ->where('password!=', '__FIXT__')
             ->fields([
            'email','phone','password','account_status', 'company_profile','personal_profile','manage_technician', 'verification_status', 
            ]);
        
        $crud->add_action('View', '', '/users/Pro/pro_data','ui-icon-plus');

        $crud->order_by('created_at','desc');

        $crud->unset_read(); 

        $crud->unset_add(); 

        $crud->unset_edit(); 

        $crud->unset_delete(); 
        
        $crud->display_as('id','Name');     

        $crud->set_primary_key('user_id','technicians');

        $crud->set_relation('id','technicians','{first_name} {last_name}');

        $crud->unique_fields('phone','email');

        // Creating an empty user
        if($crud->getState()=='add'){
           $details = array(
             'role'  => 'pro',
             'table' => 'pros',
             'url'   => 'pro'
           );     
          $this->create_empty_user($details);
        }  
               
        if($crud->getState()=='read')
           $crud->field_type('password', 'hidden');    

        $crud->callback_field('name','Crud_helper::pro_name');   
        $crud->callback_field('company_profile','Crud_helper::pro_profile');   
        $crud->callback_field('personal_profile','Crud_helper::personal_profile');   
        $crud->callback_field('verification_status','Crud_helper::pro_verification');   
        $crud->callback_field('manage_technician', 'Crud_helper::manage_technician');   
        // $crud->callback_field('manage_technician', array($this,'manage_technician'));   
        $crud->callback_after_update(array($this,'delete_tokens_on_account_close'));        
        $this->set_password_field($crud, 'bcrypt_password_callback');
        $this->view_crud($crud->render(), 'Pro/Businesses');   
    }

    function delete_tokens_on_account_close($post_array, $primary_key){

      if($post_array['account_status'] == 'ACTIVE')
        return true;
      
      $ids = array();  
      $this->db->select('user_id');
      $this->db->from('technicians');
      $this->db->where('added_by', $primary_key);
      $query = $this->db->get();
      // Removing token of the pro 
      array_push($ids, $primary_key); 
    
      $results = count($query->result());

      if($results){

        foreach($query->result() as $value){
          array_push($ids, $value->user_id);
        } 
      }  
      
      $this->db->where_in('user_id', $ids);
      $this->db->delete('user_sessions');

      return true;  

    }

    public function edit_pro()
    {
        $crud = $this->crud_init('pros', ['first_name','last_name']);
        // $crud->set_relation('added_by','users','id');
        $crud->unset_texteditor(true);
        $crud->unset_export();
        $crud->unset_fields('verified','created_at','updated_at');
        $crud->unset_back_to_list();
        $crud->set_field_upload('company_logo','../images/');
        $crud->unset_print(true);
        $crud->unset_read(true);
        $crud->field_type('user_id', 'hidden');
        $crud->field_type('invite_code', 'hidden');
        $crud->field_type('added_by', 'hidden');
        $this->view_crud($crud->render(), 'Manage Company Profile','crud-pop-up');   
    }

    public function pro_verification_status()
    {
        $crud = $this->crud_init('pros', ['first_name','last_name']);
        // $crud->set_relation('added_by','users','id');
        $crud->unset_texteditor(true);
        $crud->unset_export();
        $crud->fields('verified');
        $crud->unset_back_to_list();
        $crud->unset_print(true);
        $crud->unset_read(true);
        $crud->field_type('user_id', 'hidden');
        $crud->field_type('invite_code', 'hidden');
        $crud->field_type('added_by', 'hidden');
        $crud->columns('verified');
        $this->view_crud($crud->render(), 'Pro verification Status','crud-pop-up');   
    }

    public function manage_technician($id){
      
      $crud = $this->crud_init('technicians', ['first_name','last_name']);     
      $crud->where('added_by',$id);
      $crud->fields('first_name','last_name','is_used','pickup_jobs','apple_device_token','profile_image','driver_license_image','social_security_number','years_in_business','trade_license_number','trade_license_image' ,'manage_user_profile');
      // $crud->fields('manage_user_profile');
      $crud->display_as('is_used','Invite Code Used');
      $crud->callback_field('manage_user_profile','Crud_helper::manage_users_table');
      $crud->set_field_upload('profile_image','../images/');
      $crud->set_field_upload('driver_license_image','../images/');
      $crud->set_field_upload('trade_license_image','../images/');
      $crud->unset_edit_fields('created_at','updated_at','verified','avg_rating','added_by','scheduled_jobs_count','completed_jobs_count');
      $crud->unset_delete();
      $crud->unset_read();
      $crud->field_type('user_id', 'hidden');
      $crud->field_type('verified', 'hidden');
      $crud->field_type('added_by','hidden',$id);
      $this->view_crud($crud->render(), 'Manage Technicians','crud-pop-up');    
    }

    public function users_table($id=null){
      
      $crud = $this->crud_init('users', ['first_name','last_name']);   
      $crud->unset_fields('created_at','updated_at','role');  
      $this->set_password_field($crud, 'bcrypt_password_callback');
      $crud->where('id',$id);
      $crud->unset_back_to_list();
      $this->view_crud($crud->render(), 'Manage user','crud-pop-up');    
    }

    public function technicians($id=null){
      
      $crud = $this->crud_init('technicians', ['first_name','last_name']);   
      $crud->unset_fields('created_at','updated_at','role');  
      $this->set_password_field($crud, 'bcrypt_password_callback');
      $crud->where('id',$id);
      $crud->unset_back_to_list();
      $this->view_crud($crud->render(), 'Manage user','crud-pop-up');    
    }

    public function pro_data($id=null){
      
      $data = lako::get('objects')->get('users')->read([
        'select'  =>  ['^*','technicians.^*','pro_settings.^*','pros.^*', 'services.^*','quickblox_accounts.^*','technicians.pro_profile.^*','pros.zipcodes.^*','user_cards.^*'],
        'where'   =>  ['id', $id]
      ]);  
     
      // Get pro technicians
      $tech =  lako::get('objects')->get('technicians')->read([
        'select'  =>  ['^*'],
        'where'   =>  ['added_by', $data[0]['id']]
      ]);  
      
       
      $crud = $this->crud_init('technicians', ['first_name','last_name','user_id' ]);   

      $crud->display_as('user_id','Email');

      $crud->set_primary_key('user_id','technicians');

      $crud->set_relation('user_id','users','email');

      $crud->set_theme('datatables');

      $crud->where('added_by',$id);

      $crud->unset_add();
      
      $crud->add_action('View', '', '/users/Pro/tech_data','ui-icon-plus');
      
      $crud->unset_read(); 
      $crud->unset_delete(); 
      $crud->unset_edit(); 
      $crud->unset_export(); 

      $crud->unset_fields('created_at','updated_at','role');  
      
      $output = $crud->render();
            
      $output->data = $data[0];

      $output->template = 'pro_data';
      
      $this->load->view('base', $output);
   
    }

   public function tech_data($id=null){
      
      $data = lako::get('objects')->get('technicians')->read([
        'select'  =>  ['^*','users.^*','pro_profile.^*'],
        'where'   =>  ['user_id', $id]
      ]);  
    
      // Get pro technicians

      $output = (object)[]; 

      $output->data = $data[0];

      $output->template = 'tech_data';

      $output->css_files = [];

      $output->js_files = [];

      $this->load->view('base', $output);
   
    }


}
