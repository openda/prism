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
    const ERR_FILE_INEXISTED         = 1003;//加载文件不存在

    const APP_USER_EXISTED  = 1100;
    const APP_USER_INISTED  = 1101;
    const ERR_USER_PASSWORD = 1102;
    const ERR_UPDATE_USER   = 1103;

    const DATA_SOURCE_INEXISTED      = 1104;
    const DB_LINK_SAVE_FAILED        = 1105;
    const DB_LINK_CONNECT_FAILED     = 1106;
    const DB_LINK_INSTANCE_INEXISTED = 1107;

    const ERR_DBLINK_PARAM_HOST   = 1111;
    const ERR_DBLINK_PARAM_PORT   = 1112;
    const ERR_DBLINK_PARAM_DBNAME = 1113;
    const ERR_DBLINK_PARAM_USER   = 1114;

    const ERR_REPORT_INEXISTED = 1115;
    const ERR_UPDATE_REPORT    = 1116;
    const ERR_DELETE_REPORT    = 1117;

    const ERR_DASHBOARD_INEXISTED = 1118;
    const ERR_UPDATE_DASHBOARD    = 1119;
    const ERR_DELETE_DASHBOARD    = 1120;

    const ERR_USER_SQL_CLAUSE         = 1121;
    const ERR_USER_SQL_WHERE_RELATION = 1122;

    const ERR_CREATE_DB_DSN = 1131;
//    const ERR_DBLINK_PARAM_USER   = 1114;

}
const APP_MSG = [
    AppCode::SUCCESS => 'success',

    AppCode::ERR_RETURN_RESULT_STRUCTER => '返回结果结构有误',
    AppCode::ERR_GENERATE_UNIQUE_ID     => '生成唯一id出错',
    AppCode::ERR_FILE_INEXISTED         => '加载文件不存在',

    AppCode::APP_USER_EXISTED  => "用户已经存在",
    AppCode::APP_USER_INISTED  => "用户不存在，请注册",
    AppCode::ERR_USER_PASSWORD => "密码不正确",
    AppCode::ERR_UPDATE_USER   => "用户信息更新失败",

    AppCode::DATA_SOURCE_INEXISTED      => "数据源配置信息不存在",
    AppCode::DB_LINK_SAVE_FAILED        => "数据库连接实例存储失败",
    AppCode::DB_LINK_CONNECT_FAILED     => "数据库实例连接失败",
    AppCode::DB_LINK_INSTANCE_INEXISTED => "数据库配置实例不存在",

    AppCode::ERR_DBLINK_PARAM_HOST   => "数据库连接实例host出错",
    AppCode::ERR_DBLINK_PARAM_PORT   => "数据库连接实例port出错",
    AppCode::ERR_DBLINK_PARAM_DBNAME => "数据库连接实例dbname出错",
    AppCode::ERR_DBLINK_PARAM_USER   => "数据库连接实例user出错",

    AppCode::ERR_REPORT_INEXISTED => "报表不存在",
    AppCode::ERR_UPDATE_REPORT    => "报表更新失败",
    AppCode::ERR_DELETE_REPORT    => "报表删除失败",

    AppCode::ERR_DASHBOARD_INEXISTED => "仪表盘不存在",
    AppCode::ERR_UPDATE_DASHBOARD    => "仪表盘更新失败",
    AppCode::ERR_DELETE_DASHBOARD    => "仪表盘删除失败",

    AppCode::ERR_USER_SQL_CLAUSE         => "sql clause出错，无法生成正确的sql",
    AppCode::ERR_USER_SQL_WHERE_RELATION => "sql数组中的where子句中的relation部分出错",

    AppCode::ERR_CREATE_DB_DSN => "生成数据库连接dsn出错",
];