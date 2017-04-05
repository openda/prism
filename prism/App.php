<?php
/**
 * Created by PhpStorm.
 * User: wangxk1991@gmail.com
 * Date: 17/3/29
 * Time: 上午11:10
 * Desc: app执行类
 */

namespace prism;

use prism\common\ErrCode;
use prism\core\exception\ErrorException;

class App {

    protected static $namespace;
    protected static $init = false;

    public static $debug = true;

    public static function run(Request $request = null) {
        is_null($request) && $request = Request::instance();
        try {
            $config = self::init();
            // APP检查
            Check::run(['app']);

            // 加载路由信息
            if (is_file(APP_PATH . 'route.php')) {
                Config::load(APP_PATH . 'route.php', 'route');
            } else {
                Response::sendException(ErrCode::ERR_REQUEST_ROUTE);

                return false;
            }
            $route = new Route($request);
            $route->setDefault([
                'app'        => empty($config['default_app']) ? 'index' : $config['default_app'],
                'controller' => empty($config['default_controller']) ? 'index' : $config['default_controller'],
                'action'     => empty($config['default_action']) ? 'index' : $config['default_action'],
            ]);
            //路由解析
            $route->parse();
            // 路由检查
            Check::run(['route'], $route);

        } catch (ErrorException $e) {

        }
        $response = new Response();

        return $response;
    }

    /**
     * 初始化
     */
    public static function init() {
        // 初始化app
        $config = self::initApp();
        // 注册应用命名空间
        self::$namespace = $config['app_namespace'];
        Loader::addNamespace($config['app_namespace'], APP_PATH);
        if (!empty($config['root_namespace'])) {
            Loader::addNamespace($config['root_namespace']);
        }
        // 设置系统时区
        date_default_timezone_set($config['default_timezone']);

        //TODO 监听app启动

        return Config::get();
    }

    /**
     * @param string $app
     *
     * @return mixed
     *
     * 初始化APP
     */
    private static function initApp($app = '') {
        if (empty(self::$init)) {
            // 加载初始化文件
            if (is_file(APP_PATH . $app . 'init' . EXT)) {
                include APP_PATH . $app . 'init' . EXT;
            } elseif (is_file(RUNTIME_PATH . $app . 'init' . EXT)) {
                include RUNTIME_PATH . $app . 'init' . EXT;
            } else {
                // 加载app基础配置信息
                Config::load(APP_CONF . 'config.php');
            }
            self::$init = true;
        }

        return Config::get();
    }
}