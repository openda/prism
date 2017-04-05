<?php
/**
 * Created by PhpStorm.
 * User: wangxiaokang@iwaimai.baidu.com
 * Date: 17/3/29
 * Time: 下午10:54
 * Desc:
 */

namespace prism\common;


class ErrCode {
    const SUCCESS = 0;

    // 内部错误区
    const ERR_CONF_PARAM  = 1; //错误配置参数
    const ERR_CONF_NO_APP = 2; //配置文件中未定义app
    const ERR_CONF_PARSE  = 3;//配置文件解析函数不存在

    const ERR_CHECK_RUNTIME = 20; //系统检查错误
    const ERR_CHECK_APPS    = 21; //系统检查错误

    const ERR_CREATE_CONTROLLER = 40;//生成app默认控制器失败
    const ERR_CREATE_ROUTE      = 41;//生成app默认路由失败

    const ERR_REQUEST               = 60;
    const ERR_REQUEST_ROUTE         = 61;
    const ERR_ROUTE_FILE            = 62; //路由表不存在
    const ERR_REQUEST_METHOD        = 63; //请求方式出错
    const ERR_REQUEST_PARAM_INEXIST = 64; //请求参数缺失

}
const ERR_MSG = array(
    ErrCode::SUCCESS => 'success',

    ErrCode::ERR_CHECK_RUNTIME => '运行目录检查错误',
    ErrCode::ERR_CHECK_APPS    => 'APP检查错误',

    ERRCODE::ERR_CONF_PARAM => '配置参数错误',
    ErrCode::ERR_CONF_PARSE => '配置文件解析函数不存在',

    ErrCode::ERR_CREATE_CONTROLLER => '生成app默认控制器失败',
    ErrCode::ERR_CREATE_ROUTE      => '生成app默认路由失败',

    ErrCode::ERR_REQUEST               => '请求失败',
    ErrCode::ERR_REQUEST_ROUTE         => '请求路由出错',
    ErrCode::ERR_REQUEST_METHOD        => '请求方式出错',
    ErrCode::ERR_REQUEST_PARAM_INEXIST => '请求参数缺失',
    ErrCode::ERR_ROUTE_FILE            => '路由表不存在',

);