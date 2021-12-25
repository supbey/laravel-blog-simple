<?php

return [
    'name' => "My Blog",
    'title' => "My Blog title",
    'subtitle' => 'My Blog subtitle',
    'description' => 'My Blog description',
    'author' => 'supbey',
    'page_image' => 'home-bg.jpg',
    'posts_per_page' => 5,
    'rss_size' => 25,
    'uploads' => [
        'storage' => 'public',
        'webpath' => '/storage/uploads',
    ],
    'contact_email'=>env('MAIL_FROM'),
];


