<?php

return [
        "USER" => [
            "TYPE" => [
                "ADMIN" => 1,
                "SIMPLE_USER" => 2,
                "ENTREPRENEUR" => 3,
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
        'blog_url' =>  "/images/blog/",
        'resource_url' =>  "/images/resources/",
        'profile_url' =>  "/images/profile/",
        'profile_cover_url' =>  "/images/profile-cover/",
        'resume_url' =>  "/images/resume/",
        // 'default_video_icon' =>  "/images/challenge/",
        // 'pages_img_url' => "/upload/page/",
        
        'assets_url' => "/",

        'email_template_tag' => ['{user_name}', '{email}', '{password}','{name}','{message}'],
        'salary_type' => [1 => 'Hourly', 2 => 'Weekly', 3 => 'Monthly', 4 => 'Yearly', 5 => 'Project base'],
        'job_type' => [1 => 'Full time', 2 => 'Part time', 3 => 'Temporary', 4 => 'Commission', 5 => 'Internship'],
        'job_status' => [0 => 'Pending', 1 => 'Active', 2 => 'Rejected', 3 => 'Closed'],
	    'privilege'  => [1 => 'Employer Management', 2 => 'Employee Management', 3 => 'Jobs Management',4 => 'Sponsor Jobs Management', 5 => 'Business Category Management', 6 => 'Job Title Management', 7 => 'BLog Management', 8 => 'CMS Management', 9 => 'Email Templates', 10 => 'Statistics', 11 => 'Price Setting and Payment Log'],
        'appointment_status' => [0 => 'Pending', 1 => 'Approved', 2 => 'Rejected'],
        'imageSizeLimit' => 51200, /* 50MB*/
        'imageSizeLimit_byte' => 52428800, /* 50MB*/
        'imageSizeLimit_byte_video' => 314572800, /* 300MB*/
        'rpp' => 10, /* Record per page */
        "SHIFT" => [
            "1" => [
                "Mon" => [1 => "flaticon-sun",2 => "flaticon-sun-1"],
            ],
            "2" => [
                "Tue" => [1 => "flaticon-sun",2 => "flaticon-sun-1"],
            ],
            "3" => [
                "Wed" => [1 => "flaticon-sun",2 => "flaticon-sun-1"],
            ],
            "4" => [
                "Thu" => [1 => "flaticon-sun",2 => "flaticon-sun-1"],
            ],
            "5" => [
                "Fri" => [1 => "flaticon-sun",2 => "flaticon-sun-1"],
            ],
            "6" => [
                "Sat" => [1 => "flaticon-sun",2 => "flaticon-sun-1"],
            ],
            "7" => [
                "Sun" => [1 => "flaticon-sun",2 => "flaticon-sun-1"],
            ],
        ],
    ];
