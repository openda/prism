<?php
return [
    "index" => [
        "controller" => "index/controller/Index.php",
        "method"     => [
            "get"  => [
                "cp"     => ['id' => ['string', 0]],
                "action" => "get"
            ]
        ],
    ],
    "user" => [
        "controller" => "index/controller/User.php",
        "method"     => [
            "get"  => [
                "cp"     => ['id' => ['string', 0]],
                "action" => "get"
            ]
        ],
    ],
];