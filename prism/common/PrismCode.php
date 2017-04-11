<?php
/**
 * Created by PhpStorm.
 * User: wangxiaokang@iwaimai.baidu.com
 * Date: 17/3/29
 * Time: 下午10:54
 * Desc: 系统码
 */

namespace prism\common;


class PrismCode {
    const SUCCESS = 0;

    // 内部错误区
    const ERR_CONF_PARAM  = 1; //错误配置参数
    const ERR_CONF_NO_APP = 2; //配置文件中未定义app
    const ERR_CONF_PARSE  = 3;//配置文件解析函数不存在

    const ERR_CHECK_RUNTIME = 20; //系统检查错误
    const ERR_CHECK_APPS    = 21; //系统检查错误

    const ERR_CREATE_CONTROLLER = 40;//生成app默认控制器失败
    const ERR_CREATE_ROUTE      = 41;//生成app默认路由失败

    const ERR_REQUEST          = 100;
    const ERR_REQUEST_ROUTE    = 101;
    const ERR_ROUTE_FILE       = 102; //路由表不存在
    const ERR_ROUTE_APP        = 103; //APP不存在
    const ERR_ROUTE_CONTROLLER = 104; //控制器不存在
    const ERR_ROUTE_ACTION     = 105; //操作方法不存在

    const ERR_REQUEST_METHOD        = 109; //请求方式出错
    const ERR_REQUEST_PARAM_INEXIST = 110; //请求参数缺失
    const ERR_REQUEST_PARAM_TIME    = 111;//查询时间有问题
    const ERR_REQUEST_PARAM_PHONE   = 112;//用户手机号格式不正确
    const ERR_REQUEST_PARAM_EMAIL   = 113;//用户email格式不正确
    const ERR_REQUEST_PARAM_MD532   = 114;//用户email格式不正确
    const ERR_REQUEST_PARAM_NUMBER  = 115;//用户email格式不正确
    const ERR_REQUEST_PARAM_STRING  = 116;//用户email格式不正确
    const ERR_REQUEST_PARAM_OPTION  = 117;//用户email格式不正确
    const ERR_REQUEST_PARAM         = 118;//用户请求参数不正确
    const ERR_REQUEST_ACTION_TYPE   = 119;//请求方法类型出错

}
const PRISM_MSG = array(
    PrismCode::SUCCESS => 'success',

    PrismCode::ERR_CHECK_RUNTIME => '运行目录检查错误',
    PrismCode::ERR_CHECK_APPS    => 'APP检查错误',

    PrismCode::ERR_CONF_PARAM => '配置参数错误',
    PrismCode::ERR_CONF_PARSE => '配置文件解析函数不存在',

    PrismCode::ERR_CREATE_CONTROLLER => '生成app默认控制器失败',
    PrismCode::ERR_CREATE_ROUTE      => '生成app默认路由失败',

    PrismCode::ERR_REQUEST          => '请求失败',
    PrismCode::ERR_REQUEST_ROUTE    => '请求路由出错',
    PrismCode::ERR_ROUTE_FILE       => '路由表不存在',
    PrismCode::ERR_ROUTE_APP        => 'APP不存在',
    PrismCode::ERR_ROUTE_CONTROLLER => '控制器不存在',
    PrismCode::ERR_ROUTE_ACTION     => '操作方法不存在',


    PrismCode::ERR_REQUEST_METHOD        => '请求方式出错',
    PrismCode::ERR_REQUEST_PARAM_INEXIST => '请求参数缺失',
    PrismCode::ERR_REQUEST_PARAM_EMAIL   => '邮箱格式不正确',
    PrismCode::ERR_REQUEST_PARAM_PHONE   => '手机号码不正确',
    PrismCode::ERR_REQUEST_PARAM_TIME    => '查询时间格式不正确',
    PrismCode::ERR_REQUEST_PARAM_MD532   => 'MD5格式不正确',
    PrismCode::ERR_REQUEST_PARAM_NUMBER  => '数字格式不正确',
    PrismCode::ERR_REQUEST_PARAM_STRING  => '字符串格式不正确',
    PrismCode::ERR_REQUEST_PARAM_OPTION  => '选项值格式不正确',
    PrismCode::ERR_REQUEST_PARAM         => '用户请求参数不正确',
    PrismCode::ERR_REQUEST_ACTION_TYPE   => '请求方法类型出错',

);