<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jobs_helper{
  
  function __construct(){}
  
  //**** Customer Profile Management ****//

     public static function manage_job_addresses($value='', $pkey=0){
       
       $CI = &get_instance();
      
        return '<a href="'.site_url("jobs/".$CI->uri->segment(2)."/job_addresses/edit/{$pkey}").'" class="fancy iframe">Click here to Manage Job Addresses</a>';

     }

    public static function job_appliances($value='', $pkey=0){
        
        $CI = &get_instance();

        return '<a href="'.site_url("jobs/".$CI->uri->segment(2)."/manage_job_appliances/{$pkey}").'" class="fancy iframe">Click here to Manage Job Appliances</a>';
    }


    public static function tech_dropdown($value='', $pkey=0){
        
        return '<div id="pro-technicians">Please select the company first</div>';

    }



}