<?php

/**
 * Created by PhpStorm.
 * User: wangxk1991@gmail.com
 * Date: 17/3/28
 * Time: 下午9:01
 * Desc: 系统初始化文件
 */

namespace prism;
use prism\controller;
use prism\Error;

class Prism {
    protected static $_config = [];

    /**
     * 应用初始化
     */
    static public function start() {
        //自动加载函数
        spl_autoload_register('Prism\Prism::auto_load');
        //TODO 设置异常处理
        Error::register();
        //TODO 加载系统配置
        self::$_config = Config::get();
        var_dump(self::$_config);
        //TODO 运行应用
        echo "开始---1";


    }

    static public function auto_load($class) {
        echo '开始自动加载啦';
    }

    private static function createApp() {

    }
}

