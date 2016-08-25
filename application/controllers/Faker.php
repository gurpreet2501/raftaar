<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Faker extends CI_Controller  
{
  // const API_URL = 'localhost:8080/v1/r';
  const PASS = 'admin1234';
  const API_URL = 'http://api.fixdrepair.com/v1/r';

  private function makeCall($data){
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => self::API_URL,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 300,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => $data,
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) 
      return false;
    else
      return $response;
  }
  
  public function pro($techCount = 1)
  {
    $faker = Faker\Factory::create('en_US');

    
    $raw = $this->makeCall([
      'object' => 'users',
      'api' => 'signup',
      'data[email]' => $faker->email,
      'data[phone]' => rand(1000000000,9999999999),
      'data[password]' => 'admin1234',
      'data[technicians][first_name]' => $faker->firstName,
      'data[technicians][last_name]' => $faker->lastName,
      'data[technicians][trade_license_number]' => strtoupper($faker->bothify('?#???####?###?')),
      'data[technicians][years_in_business]' => rand(2,99),
      'data[technicians][profile_image]' => '@'.realpath($faker->image('./tmp', 640, 480, 'people')),
      'data[pros][city]'      => $faker->city,
      'data[pros][state]'     => $faker->state,
      'data[pros][address]'   => $faker->streetName,
      'data[pros][address_2]' => $faker->streetAddress,
      'data[pros][zip]'       => $faker->postcode,
      'data[pros][hourly_rate]' => rand(20, 40),
      'data[pros][ein_number]' => strtoupper($faker->bothify('????#######?')),
      'data[pros][insurance_policy]' => strtoupper($faker->bothify('????#######?')),
      'data[pros][company_name]' => $faker->company,
      'data[pros][bank_name]' => $faker->company,
      'data[pros][bank_account_number]' => strtoupper($faker->bothify('????#######?')),
      'data[pros][bank_routing_number]' => strtoupper($faker->bothify('########')),
      'data[pros][bank_account_type]' => 'Business',
      'data[pros][company_logo]' => '@'.realpath($faker->image('./tmp', 640, 480, 'technics')),
      'data[services][]' => '4',
      'with_token' => '1'
    ]);

    $resp = $this->decode($raw);
      
    echo "
      <h1>Pro User</h1>
      <Email></Email>: {$data['data[email]']} <br />
      Phone: {$data['data[phone]']} <br />
      Pass: {$data['data[password]']} <br />
      ID: {$resp['RESPONSE']['users']['id']} <br />
      Token: {$resp['RESPONSE']['token']} <br />
    ";
    
    for($i=1 ; $i<=$techCount ; $i++){
      //$this->tech($resp['RESPONSE']['users']['id']);
    }
  }
  
  function tech($token=''){
    $faker = Faker\Factory::create('en_US');
  
    if(!$token && !isset($_GET['token']))
      die('Missing token');

    if(isset($_GET['token']))
      $token = $_GET['token'];
    
    $data = [
      'object' => 'technician',
      'api' => 'register',
      'token' => $token,
      'first_name' => $faker->firstName,
      'last_name' => $faker->lastName,
      'email' => $faker->email,
      'phone' => rand(1000000000,9999999999),
      'pickup_jobs' => '1',
      'profile_image' => '@'.realpath($faker->image('./tmp', 640, 480, 'people')),
    ];
    
    $raw = $this->makeCall($data);    
    $resp = $this->decode($raw);
  

    $data2 = [
      'object'      => 'update_by_code',
      'api'         => 'technician',
      'invite_code' => $resp['RESPONSE']['technicians']['invite_code'],
      'password'    => 'admin1234',
      'services[]' => '3',
      'years_in_business'       => rand(2,99),
      'trade_license_number'    => $faker->randomNumber,
      'social_security_number'  => $faker->randomNumber
    ];
    
    $raw2 = $this->makeCall($data2);
    $resp2 = $this->decode($raw2);
    
    echo "
      <h1>Tech User</h1>
      Email: {$resp2['RESPONSE']['users']['email']} <br />
      Phone: {$data['phone']} <br />
      Pass: {$data2['password']} <br />
      ID: {$resp2['RESPONSE']['users']['id']} <br />
      Token: {$resp2['RESPONSE']['token']} <br />
    ";    
  }
  
  
  function tokens(){
    $q = $this->db->query("SELECT `u`.*,`t`.`token` from `users` as `u` 
    left join `user_sessions` as `t` on `u`.`id`=`t`.`user_id`");
    
    foreach($q->result() as $row)
    {
      echo '<br />Role : '. $row->role;
      echo '<br />ID    : '. $row->id;
      echo '<br />Email : '. $row->email;
      echo '<br />Phone : '. $row->phone;
      echo '<br />Token : '. $row->token;
      echo '<hr />';
    }
  }
  
  
  function job(){
    $faker = Faker\Factory::create('en_US');
    
    if(!isset($_GET['token']))
      die('Missing token');
    $token = $_GET['token'];
    
    $data = [
      'api' => 'create',
      'object' => 'jobs',
      'token' => $token,
      'data[request_date]' => date('Y-m-d', strtotime("+".rand(1,7)." day")),
      'data[time_slot_id]' => rand(1,7),
      'data[contact_name]' => $faker->name,
      'data[phone]' => $faker->bothify('########'),
      'data[latitude]' => $faker->latitude,
      'data[longitude]' => $faker->longitude,
      'data[service_id]' => $faker->randomElement([1,2,3,4,5]),
      'data[service_type]' => $faker->randomElement(['Install','Repair']),
      'data[title]' => $faker->realText(100),
      'data[customer_notes]' => $faker->realText(200),
      'data[job_appliances][0][appliance_id]' => rand(1,5),
      'data[job_appliances][0][description]' => $faker->realText(200),
      'data[job_appliances][0][power_source]' => $faker->randomElement(['Electric','Gas','Other']),
      'data[job_appliances][0][brand_name]' => $faker->company,
      'data[job_appliances][0][model_number]' => strtoupper($faker->bothify('??##???###')),
      
      'data[job_customer_addresses][zip]' => $faker->postcode,
      'data[job_customer_addresses][city]' => $faker->city,
      'data[job_customer_addresses][state]' => $faker->city,
      'data[job_customer_addresses][address]' => $faker->streetName,
      'data[job_customer_addresses][address_2]' => $faker->streetAddress
    ];
    
    $raw = $this->makeCall($data);
    
    if($raw === false){
      echo 'Failed!, try again';
      return;
    }
    
    $resp = $this->decode($raw); 
    echo "
      <h1>Job</h1>
      ID: {$resp['RESPONSE'][0]['id']} <br />
    ";
  }


  function customer()
  {
    $faker = Faker\Factory::create('en_US');
    $data = [
      'object' => 'customer',
      'api' => 'register',
      'email' => $faker->email,
      'phone' => rand(1000000000,9999999999),
      'password' => self::PASS,
      'first_name' => $faker->firstName,
      'last_name' => $faker->lastName,
      'city' => $faker->city,
      'state' => $faker->city,
      'zip' => $faker->postcode,
      'address' => $faker->streetName,
      'address_2' => $faker->streetAddress,
      'services[]' => '1',
      'services[]' => '2',
    ];

    $raw = $this->makeCall($data);    
    $resp = $this->decode($raw);

    echo "
      <h1>Pro User</h1>
      Email: {$data['email']} <br />
      Phone: {$data['phone']} <br />
      Pass: {$data['password']} <br />
      ID: {$resp['RESPONSE']['id']} <br />
    ";
  }


  private function decode($raw)
  {
    if($raw === false)
      exit('Failed!, try again');
    
    $resp = json_decode($raw, true);    
    if(!$resp)
      exit('Failed: False response');

    if($resp['STATUS'] != 'SUCCESS')
      exit('Errors: <pre>' . print_r($resp['ERRORS'], true));

    return $resp;
  }
}
