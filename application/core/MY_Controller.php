<?php

class MY_Controller extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('grocery_CRUD');

        // if not logged in redirect to login page
        if (!$this->tank_auth->is_logged_in()){
            redirect('/auth/login/');
        }
    }

    public function crud_init($table, $col = Null)
    {
        $crud = new grocery_CRUD();
        $crud->set_table($table)->unset_print()->unset_export()->unset_texteditor(true);

        if ($col !== null){
            $crud->columns($col);
        }

        return $crud;
    }



    /* Load curd view
    */
    function view_crud($output, $heading = '', $base = 'base')
    {
        if  (!property_exists($output, 'css_files')){
            $output->css_files = [];
        }
        if  (!property_exists($output, 'js_files')){
            $output->js_files = [];
        }
        if  (!property_exists($output, 'output')){
            $output->output = '';
        }
         
        $output->heading = $heading;
        
         if(empty($output->template))
            $output->template = 'crud';
        $this->load->view($base, $output);
    }

    /* Load curd view
    */
    function view_custom_temp_crud($output, $id , $heading = '', $base = 'base')
    {

        $comName = lako::get('objects')->get('pros')->read([
            
            'select'  =>  ['company_name'],
            'where'   =>   ['user_id' , $id]

        ]);
       
        $output->companyName = $comName[0]['company_name'] ? $comName[0]['company_name'] : 'unnamed';

        $output->id = $id;

        if  (!property_exists($output, 'css_files')){
            $output->css_files = [];
        }
        if  (!property_exists($output, 'js_files')){
            $output->js_files = [];
        }
        if  (!property_exists($output, 'output')){
            $output->output = '';
        }
         
        $output->heading = $heading;
        
         if(empty($output->template))
            $output->template = 'crud-breadcrum';
        
        $this->load->view($base, $output);
    }

 

    // load view non Crud view
    public function view($title, $msg='work in progress')
    {
        $output = new stdClass();
        $output->output = $msg;
        $this->view_crud($output, $title);
    }

    public function custom_view($title, $template, $msg='work in progress')
    {
        $output = new stdClass();
        $output->output = $msg;
        $output->template = $template;
        $this->view_crud($output, $title);
    }

    /* Tank auth's password hash function
    */
    public function hash_password($password)
    {
        $hasher = new PasswordHash(
            $this->config->item('phpass_hash_strength', 'tank_auth'),
            $this->config->item('phpass_hash_portable', 'tank_auth')
        );

        return $hasher->HashPassword($password);
    }

    public function checkpassword($your_pass, $database_pass)
    {
        $hasher = new PasswordHash(
            $this->config->item('phpass_hash_strength', 'tank_auth'),
            $this->config->item('phpass_hash_portable', 'tank_auth')
        );
       
        return $hasher->CheckPassword($your_pass,$database_pass);
    }

    public function tank_auth_password_callback($post)
    {
        if (isset($post['password']) and !empty($post['password']))
            $post['password'] = $this->hash_password($post['password']);
        else
            unset($post['password']);
        
        return $post;
    }

  //Generating Random String of 6 characters
    public function generate_random_string(){
        $length = 6;
        $randomString = substr(str_shuffle("|+-)(*&^%$#@!0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
        return $randomString;
    }
    
    //creating empty user and then opening it in edit mode
    function create_empty_user($details){
       
       $this->delete_empty_users();
   
       $data = array(
         'email'        => '' ,
         'role'         => $details['role'],
         'phone'        => '',
         'password'     => '__FIXT__',
         'created_at'   => date( "Y-m-d H:i:s")

        );
       
        $this->db->insert('users', $data);  
        
        $user_id =  $this->db->insert_id();

        if($details['role']=='technician'){ 
          $this->db->insert($details['table'], array('user_id' => $user_id, 'invite_code' => $this->generate_random_string()));  
        }else{  
          $this->db->insert($details['table'], array('user_id' => $user_id));  
        }  
        
        redirect('users/'.$details['url'].'/index/edit/'.$user_id);
        exit;    
    }



    //Deleting users having password default pasword __FIXT__ and account is 1 day old
    public function delete_empty_users(){

       $query = $this->db->query('SELECT id, password, created_at FROM users');

        foreach ($query->result() as $row)
        {    
            $date_diff = (StrToTime(date("Y-m-d H:i:s")) - StrToTime($row->created_at))/3600;     
            if($row->password=='__FIXT__' && $date_diff>24){
                $this->db->delete('users', array('id' => $row->id));
                $this->db->delete('technicians', array('user_id' => $row->id));
                $this->db->delete('customers', array('user_id' => $row->id));
                $this->db->delete('pros', array('user_id' => $row->id));
            } 
        }
    }



    //creating empty job and then opening it in edit mode
    function create_empty_job($details){
       
       $this->delete_empty_jobs();

       $data = array(
         'created_at' => date("Y-m-d H:i:s"),
         'status' => $details['job_status'] 
        );

        $this->db->insert('jobs', $data);  

        $job_id =  $this->db->insert_id();
        
        redirect('jobs/'.$details['url'].'/index/edit/'.$job_id);
            
    }

    //Deleting empty jobs  and their associated job_appliancse
    public function delete_empty_jobs(){

       $query = $this->db->query('SELECT id, customer_id FROM jobs');

       
        foreach ($query->result() as $row)
        {    
           if($row->customer_id==0)
                $this->db->delete('jobs', array('id' => $row->id));
                // $this->db->delete('job_appliances', array('job_id' => $row->id));
        }
    }
    
    public function bcrypt_password_callback($post)
    {
        if (isset($post['password']) and !empty($post['password']))
            $post['password'] = $this->bcrypt($post['password']);
        else
            unset($post['password']);
        
        return $post;
    }

    public function set_password_field(&$curd, $callback_name, $col_name='password')
    {
        $curd->change_field_type('password','password')
             ->callback_before_insert([$this, $callback_name])
             ->callback_before_update([$this, $callback_name])
             ->callback_field($col_name, [$this, 'empty_password_input']);

        return $this;
    }

    public function empty_password_input($value, $id, $obj)
    {
        return '<input id="field-password" class="form-control" name="'. $obj->name .'" type="password" value="" maxlength="'. $obj->max_length . '">';
    }

    // Generate Laravel's password hash
    // bcrypt library is required
    public function bcrypt($password)
    {
        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);

        if ($hash === false)
            throw new RuntimeException('Bcrypt hashing not supported.');
        
        return $hash;
    }

    /**
    * Set validation rules for curd
    */ 
    public function set_rules(&$crud, $index)
    {
        $this->config->load('form_validation', true);

        foreach ($this->config->item($index, 'form_validation') as $value)
        {
            extract($value);
            $crud->set_rules($field, $label, $rules);
        }
    }

    /**
     * Set Fields to Boolean value, and use custom name Yes and No.
     */ 
    public function set_bool(&$crud, Array $fields)
    {
        foreach ($fields as $field)
            $crud->field_type($field, 'true_false', ['No', 'Yes']);
        
        return $this;
    }

    public function is_insert_req()
    {
        return in_array($this->grocery_crud->getState(), ['insert','insert_validation']);
    }

    public function is_update_req()
    {
        return in_array($this->grocery_crud->getState(), ['update', 'update_validation']);
    }

    public function view_technician_callback($value, $id) {
        $count = $this->db->from('users')
                          ->where('id', $id)
                          // ->where('verified', 1)
                          ->get()->result_array();
       
       return count($count) == 0 
              ? 'User Is not verified' 
              : $this->load->view('common/bootstrap-model', ['id' => $id], true);

    }
   


    // --------------------------
    // Helper Methods for images
    // --------------------------
    
    public function image_field(&$crud, $field, $resize = False)
    {
        $crud->set_field_upload($field, API_UPLOAD_ROOT);
        $crud->callback_after_upload(function($files, $fi) use ($resize){
            $image = $files[0];

            if ($resize)
            {
                $this->load->library('image_moo');
                $img_path = $fi->upload_path . '/' . $image->name;
                $this->image_moo->load($img_path)->resize(150, 150)->save($img_path, true);
            }

            $files[0]->url = API_URL .'images/' . $image->name;
        });
    }

    public function fetch_pro(){

    } 
    

}