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
        'USD' => '$',
        'draft_upload_url' => "/upload/draft-upload/Dancero".date('Ym'),
        'profile_url' =>  "/upload/user/{userSlug}/profile/",
        'profile_thumb_url' =>  "/upload/user/{userSlug}/profile/thumbnail/",
        'gallery_url' =>  "/upload/user/{userSlug}/gallery/",
        'gallery_thumb_url' =>  "/upload/user/{userSlug}/gallery/thumbnail/",
        'default_profile_url' =>  "/upload/user/",
        'event_gallery_url' =>  "/upload/event/{eventSlug}/event_gallery/",
        'event_gallery_thumb_banner_url' => "/upload/event/{eventSlug}/event_gallery/thumbnail/banner/",
        'event_gallery_thumb_url' =>  "/upload/event/{eventSlug}/event_gallery/thumbnail/",
        'event_ticket_qrcode_local' => "upload/event_qrcode/",
        'event_ticket_qrcode' =>  "/upload/event/{eventSlug}/event_qrcode/",
        'event_sponser_banner_url' =>  "/upload/event/{eventSlug}/event_sponser/",
        'event_sponser_banner_thumb_url' =>  "/upload/event/{eventSlug}/event_sponser/thumbnail/",
        'entry_video_url' =>  "/upload/entry/{entrySlug}/",
        'entry_video_thumb_image_url' =>  "/upload/entry/{entrySlug}/thumbnail/",
        'default_event_url' =>  "/upload/event/",
        'default_event_thumb_url' =>  "/upload/event/thumbnail/",
        'dance_type_url' =>   "/upload/dance_music_type/",
        'professional_type_url' =>   "/upload/professional_type/",
        'event_type_url' =>  "/upload/event_type/",
        'challenge_url' =>  "/upload/challenge/{challengeSlug}/coverImage/",
        'challenge_thumb_url' =>  "/upload/challenge/{challengeSlug}/coverImage/thumbnail/",
        'feed_gallery_url' =>  "/upload/feed/{feedSlug}/",
        'feed_gallery_thumb_url' =>  "/upload/feed/{feedSlug}/thumbnail/",
        'entry_qrcode_local' => "upload/entry_qrcode/",
        'entry_qrcode' =>  "/upload/entry/{entrySlug}/entry_qrcode/",
        'default_challenge_url' =>  "/upload/challenge/",
        'default_video_icon' =>  "/upload/challenge/",
        // 'previous_url' => url()->previous(),

        'pages_img_url' => "/upload/page/",
        
        'video_url' => "/videos/",
        'assets_url' => "/",

        'email_template_tag' => ['{user_name}', '{email}', '{password}','{name}','{message}'],

        'privilege'  => [1 => 'Employer Management', 2 => 'Employee Management', 3 => 'Jobs Management',4 => 'Sponsor Jobs Management', 5 => 'Business Category Management', 6 => 'Job Title Management', 7 => 'BLog Management', 8 => 'CMS Management', 9 => 'Email Templates', 10 => 'Statistics', 11 => 'Price Setting and Payment Log'],
        'position' => [1 => 'Employed', 2 => 'Unemployed', 3 => 'Student'],
        'imageSizeLimit' => 51200, /* 50MB*/
        'imageSizeLimit_byte' => 52428800, /* 50MB*/
        'imageSizeLimit_byte_video' => 314572800, /* 300MB*/
        'rpp' => 10, /* Record per page */
    ];
