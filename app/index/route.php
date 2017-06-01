<?php
return [
    "index"         => [
        "controller" => "index/controller/Index.php",
        "method"     => [
            "get" => [
                "cp"     => [],
                "action" => "getMysql"
            ]
        ],
    ],
    "user"          => [
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
    "dblink"        => [
        "controller" => "index/controller/DBLink.php",
        "method"     => [
            "get" => [
                "cp"     => ["db_type" => ["string", 1]],
                "action" => "getDBLink"
            ],
            "put" => [
                "cp"     => ["db_type" => ["option", 1, ["mysql", "sqlite"]], "link_info" => ["json", 1]],
                "action" => "addDBLink"
            ],
        ],
    ],
    "userdb"        => [
        "controller" => "index/controller/UserDB.php",
        "method"     => [
            "get" => [
                "cp"     => ["db_link_id" => ["string", 1], "db_name" => ["string", 0], "table_name" => ["string", 0]],
                "action" => "getUserDB"
            ],
        ],
    ],
    "charttemplate" => [
        "controller" => "index/controller/ChartTemplate.php",
        "method"     => [
            "get" => [
                "cp"     => ["chart_type" => ["option", 1, ["histogram", "piechart", "linechart", "table"]]],
                "action" => "getChartTemplate"
            ],
        ],
    ],
    "chartinstance" => [
        "controller" => "index/controller/ChartInstance.php",
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
    "report"        => [
        "controller" => "index/controller/Report.php",
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
    "dashboard"     => [
        "controller" => "index/controller/DashBoard.php",
        "method"     => [
            "put"    => [
                "cp"     => ["dash_name" => ["string", 1], "dash_info" => ["json", 1], "report_ids" => ["string", 0], "dash_brief" => ["string", 0], "share_link" => ["url", 0]],
                "action" => "addDashBoard"
            ],
            "post"   => [
                "cp"     => ["dash_id" => ["string", 1],"dash_name" => ["string", 1], "dash_info" => ["json", 1], "report_ids" => ["string", 0], "dash_brief" => ["string", 0], "share_link" => ["url", 0]],
                "action" => "updateDashBoard"
            ],
            "delete" => [
                "cp"     => ["report_id" => ["string", 1]],
                "action" => "deleteDashBoard"
            ],
        ],
    ],
];