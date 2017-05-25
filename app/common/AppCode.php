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

    const APP_USER_EXISTED  = 1100;
    const APP_USER_INISTED  = 1101;
    const ERR_USER_PASSWORD = 1102;
    const ERR_UPDATE_USER   = 1103;

    const DATA_SOURCE_INEXISTED = 1104;
}
const APP_MSG = [
    AppCode::SUCCESS => 'success',

    AppCode::ERR_RETURN_RESULT_STRUCTER => '返回结果结构有误',

    AppCode::APP_USER_EXISTED  => "用户已经存在",
    AppCode::APP_USER_INISTED  => "用户不存在，请注册",
    AppCode::ERR_USER_PASSWORD => "密码不正确",
    AppCode::ERR_UPDATE_USER   => "用户信息更新失败",

    AppCode::DATA_SOURCE_INEXISTED => "数据源配置信息不存在",
];