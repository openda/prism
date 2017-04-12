<?php
/**
 * Created by PhpStorm.
 * User: wangxiaokang@iwaimai.baidu.com
 * Date: 17/4/4
 * Time: 上午9:35
 * Desc: 数据源配置信息
 */
return [
    'charset' => 'utf8',
    //配置原则：['连接关键字','关键字对外显示','连接值的数据类型']
    'mysql'   => [
        'link_info' => [['host', '域名', 'ip'], ['port', '端口', 'num'], ['dbname', '数据库', 'str'], ['', '用户名', 'str'], ['', '密码', 'str']],
    ],
    'pgsql'   => [
        'link_info' => [['host', '域名', 'ip'], ['port', '端口', 'num'], ['dbname', '数据库', 'str'], ['', '用户名', 'str'], ['', '密码', 'str']],
    ],
    'sqlite'  => [
        'link_info' => [['', '数据库文件', 'file'], ['', '用户名'], ['', '密码']],
    ],
    'url'     => [
        'link_info' => [['host', 'url地址', 'url']],
    ]
];