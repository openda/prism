<?php
/**
 * Created by PhpStorm.
 * User: wangxk1991@gmail.com
 * Date: 17/4/4
 * Time: 上午9:35
 * Desc: 数据源配置信息
 */
return [
    'charset' => 'utf8',
    //配置原则：['连接关键字','关键字对外显示','连接值的数据类型','不校验（空或者0：校验，1：不校验）']
    'mysql'   => [
        'link_info' => [['host', '域名', 'ip'], ['port', '端口', 'numberber'], ['dbname', '数据库', 'string', 1], ['user', '用户名', 'string'], ['password', '密码', 'string']],
        'link_sql'  => ['mysql:host=%s;port=%s;dbname=%s', 'mysql:host=%s;port=%s;'],
    ],
    'pgsql'   => [
        'link_info' => [['host', '域名', 'ip'], ['port', '端口', 'number'], ['dbname', '数据库', 'string'], ['user', '用户名', 'string'], ['password', '密码', 'string']],
        'link_sql'  => 'pgsql:host=$s;port=%s;dbname=%s;user=%s;password=%s',
    ],
    'sqlite'  => [
        'link_info' => [['dbfile', '数据库文件', 'file']],
        'link_sql'  => 'sqlite:%s',
    ],
    'url'     => [
        'link_info' => [['host', 'url地址', 'url']],
        'link_sql'  => '',
    ]
];