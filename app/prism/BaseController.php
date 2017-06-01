<?php
/**
 * Created by PhpStorm.
 * User: wangxk1991@gmail.com
 * Date: 2017/5/27
 * Time: 下午2:37
 * Desc: 基类
 */

namespace app\prism;


use prism\Controller;

class BaseController extends Controller {
    protected $dbTypeMap = [
        "1"   => "mysql",
        "2"   => "pgsql",
        "3"   => "sqlite",
        "4"   => "oracle",
        "5"   => "db2",
        "6"   => "sqlserver",
        "10"  => "mongodb",
        "11"  => "hive",
        "12"  => "mem",
        "13"  => "redis",
        "14"  => "hive",
        "15"  => "hbase",
        "100" => "url",
    ];

    protected $dbLinkParams = ['host', 'port', 'dbname', 'user', 'file', 'url'];
}