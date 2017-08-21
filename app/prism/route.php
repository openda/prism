<?php
//action 若为空，则默认是请求方式为action
return [
    "index"          => [
        "controller" => "prism/controller/Index.php",
        "method"     => [
            "get" => [
                "cp"     => [],
                "action" => "show"
            ]
        ],
    ],
    "prism"          => [
        "controller" => "prism/controller/Prism.php",
        "method"     => [
            "put" => [
                "cp"     => [],
                "action" => "init"
            ]
        ],
    ],
    "user"           => [
        "controller" => "prism/controller/User.php",
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
    "dblink"         => [
        "controller" => "prism/controller/DBLink.php",
        "method"     => [
            "get"  => [
                "cp"     => [],
                "action" => "getDBLink"
            ],
            "put"  => [
                "cp"     => ["db_type" => ["option", 1, ["mysql", "sqlite", "pgsql"]], "link_info" => ["json", 1]],
                "action" => "addDBLink"
            ],
            "post" => [
                "cp"     => ["db_type" => ["option", 1, ["mysql", "sqlite", "pgsql"]], "link_info" => ["json", 1]],
                "action" => "testDBLink"
            ],
        ],
    ],
    "dblinktemplate" => [
        "controller" => "prism/controller/DBLinkTemplate.php",
        "method"     => [
            "get" => [
                "cp"     => ["db_type" => ["string", 1]],
                "action" => "getDBLinkTemplate"
            ],
        ],
    ],
    "userdb"         => [
        "controller" => "prism/controller/UserDB.php",
        "method"     => [
            "get" => [
                "cp"     => ["db_link_id" => ["string", 1], "db_name" => ["string", 0], "table_name" => ["string", 0]],
                "action" => "getUserDB"
            ],
        ],
    ],
    "charttemplate"  => [
        "controller" => "prism/controller/ChartTemplate.php",
        "method"     => [
            "get" => [
                "cp"     => ["chart_type" => ["option", 0, ["histogram", "piechart", "linechart", "table"]]],
                "action" => "getChartTemplate"
            ],
        ],
    ],
    "chartinstance"  => [
        "controller" => "prism/controller/ChartInstance.php",
        "method"     => [
            "get" => [
                "cp"     => [
                    "db_link_id" => ["string", 1],
                    "chart_type" => ["option", 1, ["histogram", "piechart", "linechart", "table"]],
                    "chart_info" => ["json", 1]],
                "action" => "getChartInstance"
            ],
        ],
    ],
    "report"         => [
        "controller" => "prism/controller/Report.php",
        "method"     => [
            "put"    => [
                "cp"     => [
                    "db_link_id"  => ["string", 1], "report_info" => ["json", 1], "report_brief" => ["string", 0],
                    "report_type" => ["option", 1, ["histogram", "piechart", "linechart", "table"]],
                ],
                "action" => "addReport"
            ],
            "post"   => [
                "cp"     => ["report_id"   => ["string", 1], "report_info" => ["json", 0], "report_brief" => ["string", 0], "share_link" => ["url", 0],
                             "report_type" => ['option', 0, []]],
                "action" => "updateReport"
            ],
            "delete" => [
                "cp"     => ["report_id" => ["string", 1]],
                "action" => "deleteReport"
            ],
        ],
    ],
    "dashboard"      => [
        "controller" => "prism/controller/DashBoard.php",
        "method"     => [
            "put"    => [
                "cp"     => ["dash_name" => ["string", 1], "dash_info" => ["json", 1], "report_ids" => ["string", 0], "dash_brief" => ["string", 0], "share_link" => ["url", 0]],
                "action" => "addDashBoard"
            ],
            "post"   => [
                "cp"     => ["dash_id" => ["string", 1], "dash_name" => ["string", 1], "dash_info" => ["json", 1], "report_ids" => ["string", 0], "dash_brief" => ["string", 0], "share_link" => ["url", 0]],
                "action" => "updateDashBoard"
            ],
            "delete" => [
                "cp"     => ["report_id" => ["string", 1]],
                "action" => "deleteDashBoard"
            ],
        ],
    ],
];