<?php
/**
 * Created by PhpStorm.
 * User: wangxk1991@gmail.com
 * Date: 17/3/29
 * Time: 下午10:54
 * Desc: 系统码
 */

namespace prism\common;


class PrismCode {
    const SUCCESS = 0;

    // 内部错误区
    const ERR_CONF_PARAM              = 101; //错误配置参数
    const ERR_CONF_NO_APP             = 102; //配置文件中未定义app
    const ERR_CONF_PARSE              = 103;//配置文件解析函数不存在
    const ERR_CONF_PRISMLOG_EXISTED   = 104;//系统配置信息prism_log已经存在
    const ERR_CONF_DATASOURCE_EXISTED = 105;//系统配置信息data_source已经存在
    const ERR_CONF_APPLOG_EXISTED     = 106;//系统配置信息app_log已经存在
    const ERR_CONF_DATASOURCE         = 110;//数据源配置出错

    const ERR_CHECK_RUNTIME = 120; //系统检查错误
    const ERR_CHECK_APPS    = 121; //系统检查错误

    const ERR_CREATE_CONTROLLER = 140;//生成app默认控制器失败
    const ERR_CREATE_ROUTE      = 141;//生成app默认路由失败

    const ERR_APP_RUN = 160; //APP运行出错

    const ERR_REQUEST          = 299;
    const ERR_REQUEST_ROUTE    = 201;
    const ERR_ROUTE_FILE       = 202; //路由表不存在
    const ERR_ROUTE_APP        = 203; //APP不存在
    const ERR_ROUTE_CONTROLLER = 204; //控制器不存在
    const ERR_ROUTE_ACTION     = 205; //操作方法不存在

    const ERR_REQUEST_METHOD         = 209; //请求方式出错
    const ERR_REQUEST_PARAM_INEXIST  = 210; //请求参数缺失
    const ERR_REQUEST_PARAM_TIME     = 211;//查询时间有问题
    const ERR_REQUEST_PARAM_PHONE    = 212;//用户手机号格式不正确
    const ERR_REQUEST_PARAM_EMAIL    = 213;//用户email格式不正确
    const ERR_REQUEST_PARAM_MD532    = 214;//用户email格式不正确
    const ERR_REQUEST_PARAM_NUMBER   = 215;//用户email格式不正确
    const ERR_REQUEST_PARAM_STRING   = 216;//用户email格式不正确
    const ERR_REQUEST_PARAM_OPTION   = 217;//用户email格式不正确
    const ERR_REQUEST_PARAM          = 218;//用户请求参数不正确
    const ERR_REQUEST_ACTION_TYPE    = 219;//请求方法类型出错
    const ERR_REQUEST_PARAM_VALIDATE = 220;//参数校验出错

    const ERR_PDO_CONNECT = 301; //PDO连接失败
    const ERR_PDO_QUERY   = 302; //PDO查询失败
    const ERR_PDO_EXEC    = 303; //PDO执行sql失败
}
const PRISM_MSG = array(
    PrismCode::SUCCESS => 'success',

    PrismCode::ERR_CHECK_RUNTIME => '运行目录检查错误',
    PrismCode::ERR_CHECK_APPS    => 'APP检查错误',

    PrismCode::ERR_CONF_PARAM              => '配置参数错误',
    PrismCode::ERR_CONF_NO_APP             => '配置文件中未定义app',
    PrismCode::ERR_CONF_PARSE              => '配置文件解析函数不存在',
    PrismCode::ERR_CONF_PRISMLOG_EXISTED   => '系统配置信息prism_log已经存在',
    PrismCode::ERR_CONF_DATASOURCE_EXISTED => '系统配置信息data_source已经存在',

    PrismCode::ERR_CREATE_CONTROLLER => '生成app默认控制器失败',
    PrismCode::ERR_CREATE_ROUTE      => '生成app默认路由失败',

    PrismCode::ERR_APP_RUN => 'APP运行出错',

    PrismCode::ERR_REQUEST          => '请求失败',
    PrismCode::ERR_REQUEST_ROUTE    => '请求路由出错',
    PrismCode::ERR_ROUTE_FILE       => '路由表不存在',
    PrismCode::ERR_ROUTE_APP        => 'APP不存在',
    PrismCode::ERR_ROUTE_CONTROLLER => '控制器不存在',
    PrismCode::ERR_ROUTE_ACTION     => '操作方法不存在',


    PrismCode::ERR_REQUEST_METHOD         => '请求方式出错',
    PrismCode::ERR_REQUEST_PARAM_INEXIST  => '请求参数缺失',
    PrismCode::ERR_REQUEST_PARAM_EMAIL    => '邮箱格式不正确',
    PrismCode::ERR_REQUEST_PARAM_PHONE    => '手机号码不正确',
    PrismCode::ERR_REQUEST_PARAM_TIME     => '查询时间格式不正确',
    PrismCode::ERR_REQUEST_PARAM_MD532    => 'MD5格式不正确',
    PrismCode::ERR_REQUEST_PARAM_NUMBER   => '数字格式不正确',
    PrismCode::ERR_REQUEST_PARAM_STRING   => '字符串格式不正确',
    PrismCode::ERR_REQUEST_PARAM_OPTION   => '选项值格式不正确',
    PrismCode::ERR_REQUEST_PARAM          => '用户请求参数不正确',
    PrismCode::ERR_REQUEST_ACTION_TYPE    => '请求方法类型出错',
    PrismCode::ERR_REQUEST_PARAM_VALIDATE => '参数校验出错',

    PrismCode::ERR_PDO_CONNECT => 'PDO连接数据库失败',
    PrismCode::ERR_PDO_QUERY   => 'PDO查询失败',
    PrismCode::ERR_PDO_EXEC    => 'PDO执行sql失败',

    PrismCode::ERR_CONF_DATASOURCE => '数据源配置出错',
);