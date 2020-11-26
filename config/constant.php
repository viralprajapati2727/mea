<?php

return [
        "USER" => [
            "TYPE" => [
                "ADMIN" => 1,
                "SIMPLE_USER" => 2,
                "ENTREPRENUER" => 3,
                "STAFF" => 4,
            ],
            "STATUS" => [
                "Pending" => 0,
                "Active" => 1,
                "Deactive" => 2,
            ],
        ],
        'admin_email' => ['viralprajapatilive2727@gmail.com'],
        'contact_email' => 'viralprajapatilive2727@gmail.com',
        'business_category_url' =>  "/images/business-category/",
        // 'default_video_icon' =>  "/images/challenge/",
        // 'previous_url' => url()->previous(),

        // 'pages_img_url' => "/upload/page/",
        
        // 'video_url' => "/videos/",
        'assets_url' => "/",

        'email_template_tag' => ['{user_name}', '{email}', '{password}','{name}','{message}'],

        'privilege'  => [1 => 'Employer Management', 2 => 'Employee Management', 3 => 'Jobs Management',4 => 'Sponsor Jobs Management', 5 => 'Business Category Management', 6 => 'Job Title Management', 7 => 'BLog Management', 8 => 'CMS Management', 9 => 'Email Templates', 10 => 'Statistics', 11 => 'Price Setting and Payment Log'],
        'position' => [1 => 'Employed', 2 => 'Unemployed', 3 => 'Student'],
        'imageSizeLimit' => 51200, /* 50MB*/
        'imageSizeLimit_byte' => 52428800, /* 50MB*/
        'imageSizeLimit_byte_video' => 314572800, /* 300MB*/
        'rpp' => 10, /* Record per page */
    ];
