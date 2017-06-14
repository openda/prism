<?php
/**
 * Created by PhpStorm.
 * User: wangxk1991@gmail.com
 * Date: 17/4/4
 * Time: 上午9:01
 * Desc: APP 基础配置
 */
return [
    // app命名空间
    'app_namespace'           => 'app',

    // APP 定义
    'apps'                    => ['prism'],
    // 默认APP
    'default_app'             => 'prism',
    // 默认controller
    'default_resource'        => 'index',
    // 默认action
    'default_request_method'  => 'GET',
    //app公共自定义配置
    'app_common_config_files' => [
        'app_log' => APP_CONF . 'log.php',
    ],

    //加密文件
    'encrypt_file'=>APP_PATH . '.encrypt',

    // 定义前端
    'fe_name'                 => 'prism_fe',
    'fe_path'                 => '',
];