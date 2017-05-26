<?php
/**
 * Created by PhpStorm.
 * User: wangxk1991@gmail.com
 * Date: 17/4/8
 * Time: 下午4:05
 * Desc:
 */

namespace app\common;


class AppCode {
    const SUCCESS                    = 0;
    const ERR_RETURN_RESULT_STRUCTER = 1001; //控制器返回的数据结构有问题
    const ERR_GENERATE_UNIQUE_ID     = 1002; //生成唯一id出错

    const APP_USER_EXISTED  = 1100;
    const APP_USER_INISTED  = 1101;
    const ERR_USER_PASSWORD = 1102;
    const ERR_UPDATE_USER   = 1103;

    const DATA_SOURCE_INEXISTED  = 1104;
    const DB_LINK_SAVE_FAILED    = 1105;
    const DB_LINK_CONNECT_FAILED = 1106;

    const ERR_DBLINK_PARAM_HOST   = 1111;
    const ERR_DBLINK_PARAM_PORT   = 1112;
    const ERR_DBLINK_PARAM_DBNAME = 1113;
    const ERR_DBLINK_PARAM_USER   = 1114;
}
const APP_MSG = [
    AppCode::SUCCESS => 'success',

    AppCode::ERR_RETURN_RESULT_STRUCTER => '返回结果结构有误',
    AppCode::ERR_GENERATE_UNIQUE_ID     => '生成唯一id出错',

    AppCode::APP_USER_EXISTED  => "用户已经存在",
    AppCode::APP_USER_INISTED  => "用户不存在，请注册",
    AppCode::ERR_USER_PASSWORD => "密码不正确",
    AppCode::ERR_UPDATE_USER   => "用户信息更新失败",

    AppCode::DATA_SOURCE_INEXISTED  => "数据源配置信息不存在",
    AppCode::DB_LINK_SAVE_FAILED    => "数据库连接实例存储失败",
    AppCode::DB_LINK_CONNECT_FAILED => "数据库实例连接失败",

    AppCode::ERR_DBLINK_PARAM_HOST   => "数据库连接实例host出错",
    AppCode::ERR_DBLINK_PARAM_PORT   => "数据库连接实例port出错",
    AppCode::ERR_DBLINK_PARAM_DBNAME => "数据库连接实例dbname出错",
    AppCode::ERR_DBLINK_PARAM_USER   => "数据库连接实例user出错",
];