<?php
/**
 * Created by PhpStorm.
 * User: wangxiaokang@iwaimai.baidu.com
 * Date: 17/3/29
 * Time: 下午2:50
 * Desc:
 */

namespace prism;


use prism\exception\ClassNotFoundException;

class Log {
    const LOG    = 'log';
    const ERROR  = 'error';
    const INFO   = 'info';
    const SQL    = 'sql';
    const NOTICE = 'notice';
    const ALTER  = 'alter';
    const DEBUG  = 'debug';

    // 日志信息
    protected static $log = [];
    // 配置信息
    protected static $config = [];
    // 错误信息
    protected static $type = [];
    // 日志写入驱动
    protected static $driver;

    public static function init($config = []) {
        $type         = isset($config['type']) ? $config['type'] : 'File';
        $class        = false !== strpos($type, '\\') ? $type : '\\Prism\\Log\\driver' . ucwords($type);
        self::$config = $config;
        unset($config['type']);
        if (class_exists($class)) {
            self::$driver = new $class($config);
        } else {
            throw new ClassNotFoundException('class not found ' . $class, $class);
        }
        APP::$debug && Log::record('[ LOG ] INIT ' . $type, 'info');
    }

    /**
     * 记录调试信息
     *
     * @param mixed  $msg  调试信息
     * @param string $type 信息类型
     *
     * @return void
     */
    public static function record($msg, $type = 'log') {
        self::$log[$type][] = $msg;
        if (IS_CLI && count(self::$log[$type]) > 100) {
            // 命令行下面日志写入改进
            self::save();
        }
    }

    /**
     * 清空日志信息
     */
    public static function clear() {
        self::$log = [];
    }

    /**
     * @return bool
     * 保存日志信息
     */
    public static function save() {
        if(!empty(self::$log)){
            if(!is_null(self::$driver)){
                self::init(Config::get('log'));
            }
        }

        return true;

    }

}