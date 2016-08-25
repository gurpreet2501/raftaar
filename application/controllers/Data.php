<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Data extends MY_Controller
{
   function __construct(){
    parent::__construct();
      $this->load->database();
   }

    public function index(){
        // $this->view('Dashboard');
        redirect('/data/spouts');

    }

    function change_date_format($value = '', $primary_key = null)
      {
        // date_default_timezone_set("Asia/Calcutta");
        $datetime1 = date_create(date("Y-m-d H:i:s"));
        $datetime2 = date_create($value);
        $interval = date_diff($datetime1, $datetime2);

        $yrs_text = ($interval->y == 1) ? ' year' : ' years';
        $month_text = ($interval->m == 1) ? ' month' : ' months';
        $days_text = ($interval->d == 1) ? ' day' : ' days';
        $hrs_text = ($interval->h == 1) ? ' hr' : ' hours';
        $min_text = ($interval->i == 1) ? ' min' : ' mins';
        $sec_text = ($interval->s == 1) ? ' sec' : ' secs';

        $date = $interval->y ? $interval->y.$yrs_text :
         ($interval->m ? $interval->m.$month_text :
         ($interval->d ? $interval->d. $days_text :
         ($interval->h ? $interval->h.$hrs_text :
         ($interval->i ? $interval->i.$min_text :$interval->s.$sec_text)
         )));

        return $date;
      }

    public function spouts()
    {
        // date_default_timezone_set("Asia/Calcutta");
        $crud = $this->crud_init('spouts',['text','image','score','user_id','created_at']);
        $crud->set_theme('datatables');
        if($state == 'edit'){
             $query = $this->db->query("SELECT id FROM spouts WHERE id =".$this->uri->segment(4));
             $record_id = $query->first_row()->id;
             if(!$record_id)
                redirect(404);
        }
        // $crud->order_by('updated_at','desc');
        $crud->order_by('created_at','DESC');
        $crud->callback_column('image',array($this,'image_url_display'));
        $crud->callback_column('created_at',array($this,'change_date_format'));
        $crud->unset_delete();
        $crud->unset_read();
        $crud->add_action('View', '', 'record/view_spout','ui-icon-plus');
        $crud->add_action('Delete','', 'data/delete','delete-pop-up ui-icon-minus');
        if($crud->getState() == 'delete')
          $crud->callback_field('city','Admin_helper::delete_spout');

        $this->view_crud($crud->render(), 'Spouts');

    }

    public function reported_spouts()
    {

        $crud = $this->crud_init('reports', ['object_id', 'object_type','reason_type']);
        $crud->unset_edit();
        $crud->unset_read();
        $crud->unset_add();
        $crud->unset_delete();
        $crud->callback_column('detail', array($this,'view_spout'));
        $crud->set_primary_key('object_id');
        $crud->add_action('Delete','', 'data/delete','delete-pop-up ui-icon-minus');
        $crud->set_theme('datatables');
        $crud->where('object_type','spout');
        $crud->display_as('object_id','Spout Details');
        $crud->set_relation('object_id','spouts','
          <strong>Text: </strong>{text} - <br>
          <strong>Score: </strong>{score}<br>
          <strong>Latitude: </strong>{latitude}<br>
          <strong>Longitude: </strong>{longitude}<br>
          <strong>Image: </strong><div>_|{image}|_</div>
          ');
        $crud->callback_column('object_id',array($this,'_callback_webpage_url'));
        $crud->field_type('object_type','hidden','Spout');
        $crud->field_type('created_at', 'hidden', date("Y-m-d H:i:s"));
        $crud->field_type('updated_at', 'hidden');
        $crud->order_by('created_at','DESC');
        if($crud->getState()== 'update')
          $crud->field_type('updated_at', 'hidden', date("Y-m-d H:i:s"));

        $this->view_crud($crud->render(), 'Spout Reports');
    }

    function view_spout($value, $primary_key=null){
      return "<a class='btn btn-success' target='_BLANK' href='spouts/edit/".$primary_key->object_id."'>View Spout</a>";
    }

    public function reported_replies()
    {
        $crud = $this->crud_init('reports', ['object_id','object_type','reason_type']);
        $crud->set_theme('datatables');
        $crud->unset_edit();
        $crud->unset_add();
        $crud->unset_delete();
        $crud->set_theme('datatables');
        $crud->set_primary_key('object_id');
        $crud->where('object_type','reply');
        $crud->add_action('Delete','', 'data/delete_reply','delete-pop-up ui-icon-minus');
        $crud->callback_column('detail', array($this,'view_reply'));
        $crud->display_as('object_id','Reply Details');
        $crud->set_relation('object_id','replies','
          <strong>Text: </strong>{text} - <br>
          <strong>Score: </strong>{score}<br>
          <strong>Image: </strong><div>_|{image}|_</div>
          ');
          $crud->order_by('created_at','DESC');
        // $crud->callback_column('object_id', array($this, 'view_reply'));
        $crud->display_as('object_id','Reply Id');
        $crud->field_type('object_type','hidden','Reply');
        $crud->field_type('created_at', 'hidden', date("Y-m-d H:i:s"));
        $crud->field_type('updated_at', 'hidden');
        if($crud->getState()== 'update')
          $crud->field_type('updated_at', 'hidden', date("Y-m-d H:i:s"));

        $this->view_crud($crud->render(), 'Replies Report');
    }

    function view_reply($value, $primary_key=null){
      return "<a class='btn btn-success' target='_BLANK' href='replies/edit/".$primary_key->object_id."'>View Reply</a>";
    }

    function image_url_display($value = '', $primary_key = null){
      if(!$value)
        return "No Image";

      return "<img width='40%' src='".$value."' />";


    }


    public function delete($key) {
      //Check javascript delete_spout.js in assets folder
      Admin_helper::delete_spout($key);
    }

    public function delete_reply($key) {
      //Check javascript delete_spout.js in assets folder
      Admin_helper::delete_reply($key);
    }

    function update_reply_count($reply_id){

          //Decrementing reply count by 1 after successful deletion of spout reply
          $query = $this->db->get_where('replies', array('id' => $reply_id));
          if(!isset($query->result()[0]->spout_id))
            return;

          $spout_query = $this->db->get_where('spouts', array('id' => $query->result()[0]->spout_id));
          $this->db->set('replies_count', $spout_query->result()[0]->replies_count-1, FALSE);
          $this->db->where('id', $query->result()[0]->spout_id);
          $this->db->update('spouts');

    }

    public function user_action($action, $type , $user_id, $record_id){

      date_default_timezone_set(date_default_timezone_get());

      // date_default_timezone_set('Asia/Kolkata');
      $date = date('Y-m-d H:i:s', strtotime('+5 days'));

      if($action == 'Blocked'){
        $this->db->set('status', $action);
        $this->db->where('id', $user_id);
        $this->db->update('users');
        if($type == 'spouts'){
          $this->db->delete('spouts', array('id' => $record_id));
          $this->db->delete('reports', array('object_id' => $record_id, 'object_type' => 'spout' ));
          $this->db->delete('replies', array('spout_id' => $record_id));
        }
        else{
          $this->update_reply_count($record_id);
          $this->db->delete('replies', array('id' => $record_id));
          $this->db->delete('reports', array('object_id' => $record_id, 'object_type' => 'reply' ));
        }
      }

      if($action == 'Suspend'){
        $this->db->set('suspended_until', $date);
        $this->db->where('id', $user_id);
        $this->db->update('users');
      }

      if($action == 'Just_delete'){

        if($type == 'spouts'){
          $this->db->delete('spouts', array('id' => $record_id));
          $this->db->delete('reports', array('object_id' => $record_id, 'object_type' => 'spout' ));
          $this->db->delete('replies', array('spout_id' => $record_id));
        }
        else{
          $this->update_reply_count($record_id);
          $this->db->delete('replies', array('id' => $record_id));
          $this->db->delete('reports', array('object_id' => $record_id, 'object_type' => 'reply' ));
        }

      }

      echo "Action Performed Successfully";
    }
    public function blocked_users()
    {
        $crud = $this->crud_init('users',['device_id','apn_token','status']);
        $crud->fields('status');
        $crud->unset_add();
        $crud->unset_delete();
        $crud->where('status','Blocked');
        $this->view_crud($crud->render(), 'Users');
    }


    function encrypt_password_callback($post_array, $primary_key = null)
    {

        $hasher = new PasswordHash(
            $this->config->item('phpass_hash_strength', 'tank_auth'),
            $this->config->item('phpass_hash_portable', 'tank_auth')
        );

        $post_array['password'] = $hasher->HashPassword($password);

        return $post_array;
    }

    public function replies()
    {
        $crud = $this->crud_init('replies',['text','image','score','user_id','created_at']);
        if($crud->getState() == 'edit'){
             $query = $this->db->query("SELECT id FROM replies WHERE id =".$this->uri->segment(4));
             $record_id = $query->first_row()->id;
             if(!$record_id)
                redirect(404);
        }
        $crud->unset_read();
        $crud->unset_delete();
        $crud->add_action('View', '', 'record/view_reply','ui-icon-plus');
        $crud->add_action('Delete','', 'data/delete_reply','delete-pop-up ui-icon-minus');
        $crud->order_by('created_at','DESC');
        $crud->set_theme('datatables');
        $crud->callback_column('image',array($this,'image_url_display'));
        $crud->callback_column('created_at',array($this,'change_date_format'));
        $this->view_crud($crud->render(), 'replies');
    }

    public function reply_votes()
    {
        $crud = $this->crud_init('reply_votes',[]);
        $crud->order_by('created_at','DESC');
        $this->view_crud($crud->render(), 'Reply Votes');
    }

    public function reports()
    {
        $crud = $this->crud_init('reports',[]);
        $crud->order_by('created_at','DESC');
        $this->view_crud($crud->render(), 'Reports');
        $crud->set_theme('datatables');
    }




}
