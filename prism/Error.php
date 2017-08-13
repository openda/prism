<?php
/**
 * Created by PhpStorm.
 * User: wangxk1991@gmail.com
 * Date: 17/3/29
 * Time: 下午9:20
 * Desc: Prism 错误类
 */

namespace prism;


use const prism\common\PRISM_MSG;
use prism\common\PrismCode;
use prism\core\exception\ErrorException;

class Error {
    /**
     *
     */
    public static function register() {
        set_error_handler([__CLASS__, 'appError']);
        set_exception_handler([__CLASS__, 'appException']);
        register_shutdown_function([__CLASS__, 'appShutdown']);
    }

    /**
     * Exception Handler
     *
     * @param  \Exception|\Throwable $e
     */
    public static function appException($e) {
        if (!is_null($error = error_get_last()) && self::isFatal($error['type'])) {
            Response::sendException(PrismCode::EXCEPTION, PRISM_MSG[PrismCode::EXCEPTION], $e);
        }
    }

    /**
     * Error Handler
     *
     * @param  integer $errno 错误编号
     * @param  integer $errstr 详细错误信息
     * @param  string  $errfile 出错的文件
     * @param  integer $errline 出错行号
     * @param array    $errcontext
     *
     * @throws ErrorException
     */
    public static function appError($errno, $errstr, $errfile = '', $errline = 0, $errcontext = []) {
        if (!is_null($error = error_get_last()) && self::isFatal($error['type'])) {
            $exception = new ErrorException($errno, $errstr, $errfile, $errline, $errcontext);
            Response::sendException(PrismCode::ERROR, PRISM_MSG[PrismCode::ERROR], $exception);
        }
    }

    /**
     * Shutdown Handler
     */
    public static function appShutdown() {
        if (!is_null($error = error_get_last()) && self::isFatal($error['type'])) {
            // 将错误信息托管至prism\ErrorException
            $exception = new ErrorException($error['type'], $error['message'], $error['file'], $error['line']);

            self::appException($exception);
        }

    }

    /**
     * 确定错误类型是否致命
     *
     * @param  int $type
     *
     * @return bool
     */
    protected static function isFatal($type) {
        return in_array($type, [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE]);
    }

}