<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Get_pro_technicians extends MY_Controller
{
    public function index()
    {
      $this->db->select(array('user_id','first_name','last_name'));
      $this->db->from('technicians');
      $this->db->where('user_id!=', $_POST['id']);
      $this->db->where('added_by', $_POST['id']);
      $query = $this->db->get();
      if(count($query->result())): ?>
        <select  id="field-technician_id" class="form-control chosen-select chzn-done" name="technician_id">
            <?php foreach($query->result() as $key => $value): ?>
            <option value="<?=$value->user_id?>">
                <?=$value->first_name?>-<?=$value->last_name?>
            </option>
            <?php endforeach;?>  
        </select>
     <? else:
        echo "No technicians found";
        endif;
    }
}    