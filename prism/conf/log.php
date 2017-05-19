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
        "PRISM_START" => "========================  prism start  ========================",
        "PRISM_END"   => "========================  prism end  ========================",
    ],
    "ERROR"  => [
        "ERR_REQUEST"                => "请求失败，失败原因：【%s】",
        "RUNTIME_INIT_FAILED"        => "运行目录初始化失败,失败原因：【%s】",
        "ERR_REQUEST_ROUTE"          => "请求路由出错，路由详情：【app:%s; class:%s; action:%s】",
        "REQUEST_PARAM_INEXIST"      => '请求参数缺失，缺失参数：【%s】',
        "ERR_REQUEST_PARAM_VALIDATE" => "请求参数出错，错误参数：【%s】",
    ],
    "WARN"   => [],
    "DEBUG"  => [
        "DEBUG" => "调试 : [ %s ]"
    ]
];