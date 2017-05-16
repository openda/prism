<?php
return [
    "user" => [
        "controller" => "index/controller/Index.php",
        "method"     => [
            "post" => [
                "cp"     => ['id' => ['string', 0]],
                "action" => "post"
            ],
            "get"  => [
                "cp"     => ['id' => ['string', 0]],
                "action" => "get"
            ]
        ],
    ],
];