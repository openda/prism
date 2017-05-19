<?php

/**
 * Created by PhpStorm.
 * User: wangxk1991@gmail.com
 * Date: 17/3/28
 * Time: 下午9:01
 * Desc: 系统初始化文件
 */

namespace prism;

class Prism {
    protected static $_config = [];

    static public function run() {
        // 加载系统配置文件
        \prism\Config::set(include CONF_PATH . 'config.php');

        // 加载系统日志文件
        \prism\Config::set(include CONF_PATH . 'log.php', 'prism_log');

//        // 加载数据源配置文件
        \prism\Config::set(include CONF_PATH . 'datasource.php', 'data_source');

        // 系统缓存检查
        \prism\Check::run(['runtime']);
    }
}

