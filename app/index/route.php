<?php
return [
    "index"  => [
        "controller" => "index/controller/Index.php",
        "method"     => [
            "get" => [
                "cp"     => ['id' => ['string', 0]],
                "action" => "get"
            ]
        ],
    ],
    "user"   => [
        "controller" => "index/controller/User.php",
        "method"     => [
            "get"  => [
                "cp"     => ["user_name" => ["string", 1], "password" => ["string", 1]],
                "action" => "login"
            ],
            "put"  => [
                "cp"     => ["user_name" => ["string", 1], "password" => ["string", 1], "email" => ["string", 0], "phone" => ["string", 0]],
                "action" => "addUser"
            ],
            "post" => [
                "cp"     => ["password" => ["string", 0], "email" => ["string", 0], "phone" => ["string", 0]],
                "action" => "updateUser"
            ],
        ],
    ],
    "dblink" => [
        "controller" => "index/controller/DBLink.php",
        "method"     => [
            "get" => [
                "cp"     => ["db_type" => ["string", 1]],
                "action" => "getDBLink"
            ],
            "put" => [
                "cp"     => ["db_type" => ["option", 1, [1, 2, 3, 4]], "link_info" => ["json", 1]],
                "action" => "addDBLink"
            ],
        ],
    ],
];