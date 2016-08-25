<?php 

$config = [
    'create_admin' => [
        [
            'field'   => 'password', 
            'label'   => 'Password', 
            'rules'   => 'required|min_length[4]|max_length[20]'
        ],
        [
            'field'   => 'email', 
            'label'   => 'Email', 
            'rules'   => 'required|is_unique[tank_auth_users.email]|valid_email'
        ],
        [
            'field'   => 'username', 
            'label'   => 'Username', 
            'rules'   => 'required|is_unique[tank_auth_users.username]|min_length[4]|max_length[20]'
        ]
    ],
    'create_pro' => [
        [
            'field'   => 'password', 
            'label'   => 'Password', 
            'rules'   => 'required|min_length[4]|max_length[20]'
        ],
        [
            'field'   => 'email', 
            'label'   => 'Email', 
            'rules'   => 'required|is_unique[users.email]|valid_email'
        ],
        [
            'field'   => 'username', 
            'label'   => 'Username', 
            'rules'   => 'required|is_unique[users.username]|min_length[4]|max_length[20]'
        ],
        [
            'field'   => 'first_name', 
            'label'   => 'Fist Name', 
            'rules'   => 'required'
        ],
        [
            'field'   => 'last_name', 
            'label'   => 'Last Name', 
            'rules'   => 'required'
        ]
    ],
    'create_tech' => [
        [
            'field'   => 'first_name', 
            'label'   => 'Fist Name', 
            'rules'   => 'required'
        ],
        [
            'field'   => 'last_name', 
            'label'   => 'Last Name', 
            'rules'   => 'required'
        ],
        [
            'field'   => 'phone', 
            'label'   => 'Phone', 
            'rules'   => 'required|is_unique[users.phone]'
        ]

    ]

];