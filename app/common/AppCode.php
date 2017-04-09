<?php
/**
 * Created by PhpStorm.
 * User: wangxiaokang@iwaimai.baidu.com
 * Date: 17/4/8
 * Time: 下午4:05
 * Desc:
 */

namespace app\common;


class AppCode {
    const SUCCESS                    = 0;
    const ERR_RETURN_RESULT_STRUCTER = 1001; //控制器返回的数据结构有问题
}
const APP_MSG = [
    AppCode::SUCCESS => 'success',

    AppCode::ERR_RETURN_RESULT_STRUCTER => '返回结果结构有误',
];