<?php
/**
 * Created by PhpStorm.
 * User: wangxk1991@gmail.com
 * Date: 17/4/7
 * Time: 下午6:00
 * Desc: 系统日志信息
 */

return [
    "NOTICE" => [
        "PRISM_START"  => "========================  prism start  ========================",
        "PRISM_END"    => "========================  prism end  ========================",
        "EXECUTE_SQL"  => "执行的SQL是：【%s】",
        "QUERY_SQL"    => "执行的SQL是：【%s】, 执行结果是：【%s】",
        "PREPARE_SQL"  => "执行的SQL是：【%s】, 执行参数是：【%s】，执行结果是：【%s】",
        "SESSION_INIT" => "session初始化 , 配置信息是【%s】",
    ],
    "ERROR"  => [
        "ERR_REQUEST"                => "请求失败，失败原因：【%s】",
        "RUNTIME_INIT_FAILED"        => "运行目录初始化失败,失败原因：【%s】",
        "ERR_REQUEST_ROUTE"          => "请求路由出错，路由详情：【app:%s; class:%s; action:%s】",
        "ERR_APP_RUN"                => "APP运行出错，错误原因：【%s】",
        "REQUEST_PARAM_INEXIST"      => '请求参数缺失，缺失参数：【%s】',
        "ERR_REQUEST_PARAM_VALIDATE" => "请求参数出错，错误参数：【%s】",
        "ERR_PDO_CONNECT"            => "PDO连接数据库失败，连接信息：【%s】，错误原因：【%s】",
        "ERR_PDO_QUERY"              => "PDO查询数据库失败，错误原因：【%s】",
        "ERR_PDO_EXEC"               => "PDO执行SQL语句失败，执行的SQL是：【%s】，错误原因：【%s】",
        "ERR_PDO_CONNECT_TEST"       => "PDO实例连接失败，连接信息：【%s】，错误原因：【%s】",
    ],
    "WARN"   => [],
    "DEBUG"  => [
        "DEBUG" => "调试 => [ %s ]"
    ]
];