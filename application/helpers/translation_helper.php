<?php

 function nameTranslation($name){
       
       $original = [
          'UserIdentification'  => 'User Identification',
          'CompanyProfile'      => 'Company Profile',
          'PersonalProfile'     => 'Personal Profile',
          'BankDetails'         => 'Bank Details',
          'Notifications'       => 'Notifications'
       ];

       foreach ($original as $key => $value) {
           if($name == $key){
                return $value;
                exit;
           }
       }

  }