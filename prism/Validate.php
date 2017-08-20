<?php
/**
 * Created by PhpStorm.
 * User: wangxk1991@gmail.com
 * Date: 2017/5/26
 * Time: 下午8:19
 * Desc:
 */

namespace prism;


use prism\common\PrismCode;

class Validate {
    const PARAM_STRING = 'STRING';
    const PARAM_NUMBER = 'NUMBER';
    const PARAM_TIME   = 'TIME';
    const PARAM_PHONE  = 'PHONE';
    const PARAM_EMAIL  = 'EMAIL';
    const PARAM_MD532  = 'MD532';
    const PARAM_OPTION = 'OPTION';
    const PARAM_JSON   = 'JSON';
    const PARAM_IP     = 'IP';
    const PARAM_URL    = 'URL';

    const VALIDATE_BANKACOOUNT         = "/[\d]{10,}/";
    const VALIDATE_NUMBER_GREATER_ZERO = "/^[1-9]\d*$/";
    const VALIDATE_TIME                =
        "/^\d{4}[\-](0?[1-9]|1[012])[\-](0?[1-9]|[12][0-9]|3[01])(\s+(0?[0-9]|1[0-9]|2[0-3])\:(0?[0-9]|[1-5][0-9])\:(0?[0-9]|[1-5][0-9]))?$/";

    /**
     * @param $value
     * @param $type
     * @param $preg
     *
     * @desc 单个参数类型校验器
     */
    public static function validate($value, $type = Validate::PARAM_STRING, $pattern = '', $errno = '') {
        if ($type == Validate::PARAM_STRING) {
            if ($pattern != '' && $pattern != null) {
                if (!preg_match($pattern, $value)) {
                    return PrismCode::ERR_REQUEST_PARAM_STRING;
                }
            } else {
                if ($value == '' || $value == null) {
                    return PrismCode::ERR_REQUEST_PARAM_STRING;
                }
            }
        } else if ($type == Validate::PARAM_TIME) {
            if ($pattern == '') {
                $pattern = Validate::VALIDATE_TIME;
            }
            if (!preg_match($pattern, $value)) {
                return PrismCode::ERR_REQUEST_PARAM_TIME;
            }

        } else if ($type == Validate::PARAM_PHONE) {
            if (!preg_match("/^1[34578]{1}\d{9}$/", $value)) {
                return PrismCode::ERR_REQUEST_PARAM_PHONE;
            }
        } else if ($type == Validate::PARAM_EMAIL) {
            if (filter_var($value, FILTER_VALIDATE_EMAIL) == false) {
                return PrismCode::ERR_REQUEST_PARAM_EMAIL;
            }
        } else if ($type == Validate::PARAM_NUMBER) {
            if ($pattern != '' && $pattern != null) {
                if (!preg_match($pattern, $value)) {
                    return PrismCode::ERR_REQUEST_PARAM_NUMBER;
                }
            }
            if (!is_numeric($value) || $value > 100000000000 || $value < 0 || strpos($value, '.')) {
                return PrismCode::ERR_REQUEST_PARAM_NUMBER;
            }
        } else if ($type == Validate::PARAM_OPTION) {
            if (!is_array($pattern)) {
                return PrismCode::ERR_REQUEST_PARAM;
            }
            if (!in_array($value, $pattern)) {
                return PrismCode::ERR_REQUEST_PARAM_OPTION;
            }
        } else if ($type == Validate::PARAM_MD532) {
            if (!preg_match("/^[a-z0-9]{32}$/", $value)) {
                return PrismCode::ERR_REQUEST_PARAM_MD532;
            }
        } else if ($type == Validate::PARAM_JSON) {
            json_decode($value);
            if (!(json_last_error() == JSON_ERROR_NONE)) {
                return PrismCode::ERR_REQUEST_PARAM_JSON;
            }
        } else if ($type == Validate::PARAM_IP) {
            if (filter_var($value, FILTER_VALIDATE_IP) === false) {
                return PrismCode::ERR_REQUEST_PARAM_IP;
            }
        } else if ($type == Validate::PARAM_URL) {
            if (filter_var($value, FILTER_VALIDATE_URL) === false) {
                return PrismCode::ERR_REQUEST_PARAM_URL;
            }
        } else {
            if ($pattern != '' && $pattern != null) {
                if (!preg_match($pattern, $value)) {
                    return PrismCode::ERR_REQUEST_PARAM;
                }
            }
        }

        //TODO 做sql防注入的校验
        return PrismCode::SUCCESS;
    }


}