<?php
/**
 * Created by PhpStorm.
 * User: wangxk1991@gmail.com
 * Date: 17/3/29
 * Time: 上午11:10
 * Desc: app执行类
 */

namespace prism;

use const app\common\APP_MSG;
use const prism\common\PRISM_MSG;
use prism\common\PrismCode;
use prism\core\exception\ErrorException;

class App {

    protected static $namespace;
    protected static $init = false;

    public static $debug = true;

    public static function run(Request $request = null) {
        Logger::info("PRISM_START");
        is_null($request) && $request = Request::instance();
        try {
            //初始化app
            self::init();
            // APP检查
            Prism::checkApp();

            $route = new Route($request);
            $route->setDefault([
                'app'      => empty($config['default_app']) ? 'index' : $config['default_app'],
                'method'   => empty($config['default_request_method']) ? 'get' : $config['default_request_method'],
                'resource' => empty($config['default_resource']) ? 'index' : $config['default_resource'],
            ]);
            //路由解析

            $route->parse();
            Logger::debug("路由信息：", ["路由：" => $route->getRoute(), "参数" => $route->getInputs()]);
            // 加载路由文件
            if (is_file(APP_PATH . $route->getRoute()['app'] . '/route.php')) {
                Config::load(APP_PATH . $route->getRoute()['app'] . '/route.php', 'route');
            } else {
                Response::sendError(PrismCode::ERR_ROUTE_APP_FILE_INEXISTED, PRISM_MSG[PrismCode::ERR_ROUTE_APP_FILE_INEXISTED]);
            }
//            Logger::debug("加载路由文件：", [Config::get('route')]);
            $config = Config::get();
            // 路由检查，顺带做参数校验
            $routes = Prism::checkRoute($route, $config['route']);
            Logger::debug("路由检查完毕：", $routes);
            if (!empty($routes['class']) && !empty($routes['action']) && !empty($routes['app'])) {
                $ret = self::invoke($routes);
                if (!is_array($ret) && array_key_exists($ret, APP_MSG)) {
                    Logger::debug("执行action：", ["code" => $ret, "msg" => APP_MSG[$ret]]);
                    Response::send([
                        "code" => $ret,
                        "msg"  => APP_MSG[$ret],
                    ]);
                } else {
                    Response::send($ret);
                }
            } else {
                Logger::error("ERR_REQUEST_ROUTE", [$routes['app'], $routes['class'], $routes['action']]);
                Response::sendError(PrismCode::ERR_REQUEST_ROUTE, PRISM_MSG[PrismCode::ERR_REQUEST_ROUTE]);
            }
        } catch (ErrorException $e) {
            Logger::error("ERR_APP_RUN", $e->getMessage());
            Response::sendException(PrismCode::ERR_APP_RUN, PRISM_MSG[PrismCode::ERR_APP_RUN], $e);
        }
        Logger::info("PRISM_END");

        return true;
    }

    /**
     * 初始化
     */
    public static function init() {
        // 初始化app
        $config = self::initApp();
        // 设置系统时区
        date_default_timezone_set($config['default_timezone']);
        // 注册应用命名空间
        self::$namespace = $config['app_namespace'];
        Loader::addNamespace($config['app_namespace'], APP_PATH);
        if (!empty($config['root_namespace'])) {
            Loader::addNamespace($config['root_namespace']);
        }

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
                //加载app公共自定义配置
                $commonConfFiles = Config::get('app_common_config_files');
                if (!empty($commonConfFiles)) {
                    foreach ($commonConfFiles as $name => $commonConfFile) {
                        Config::load($commonConfFile, $name);
                    }
                }
                //加载app独立配置
                $apps = Config::get('apps');
                if (!empty($apps)) {
                    foreach ($apps as $app) {
                        Config::load(APP_PATH . "$app/conf/config.php", $app);
                    }
                } else {
                    Response::sendError(PrismCode::ERR_CONF_NO_APP, PRISM_MSG[PrismCode::ERR_CONF_NO_APP]);
                }

            }
            self::$init = true;
        }

        return Config::get();
    }

    /**
     * 调用反射执行类的实例化 支持依赖注入
     * @access public
     *
     * @param string $class 类名
     * @param array  $vars 变量
     *
     * @return mixed
     */
    public static function invoke($route = []) {
        try {
            $class       = new \ReflectionClass($route['namespace']);
            $constructor = $class->getConstructor();
            if ($constructor) {
                $args = self::bindParams($constructor, $route['inputs']);
            } else {
                $args = [];
            }
            $instance = $class->newInstanceArgs($args);
            //TODO 判断action是否存在的处理机制需要改进
            $methods = $class->getMethods();
            foreach ($methods as $method) {
                if ($method->getName() == $route['action']) {
                    $method = $class->getmethod($route['action']);
                    if ($method->isPrivate() || $method->isConstructor() || $method->isStatic() || $method->isDestructor()) {
                        Response::sendError(PrismCode::ERR_REQUEST_ACTION_TYPE, PRISM_MSG[PrismCode::ERR_REQUEST_ACTION_TYPE]);
                    }
//                    self::bindParams($method, $route['inputs']);
//                    return $method->invokeArgs($instance, $route['inputs']);
                    $reflectMethod = new \ReflectionMethod($instance, $route['action']);
                    $args          = self::bindParams($reflectMethod, $route['inputs']);

                    return $reflectMethod->invokeArgs($instance, $args);
                }
            }
            Response::sendError(PrismCode::ERR_ROUTE_ACTION, PRISM_MSG[PrismCode::ERR_ROUTE_ACTION]);
        } catch (\ReflectionException $e) {
            Response::sendException(PrismCode::ERR_ROUTE_REFLECTION_FAILED, PRISM_MSG[PrismCode::ERR_ROUTE_REFLECTION_FAILED], $e);
        }

        return false;
    }


    /**
     * 绑定参数
     * @access public
     *
     * @param \ReflectionMethod|\ReflectionFunction $reflect 反射类
     * @param array                                 $vars 变量
     *
     * @return array
     */
    private static function bindParams($reflect, $vars = []) {
        if (empty($vars)) {
            $vars = Request::instance()->getInput();
        }
        $args = [];
        // 判断数组类型 数字数组时按顺序绑定参数
        reset($vars);
        $type = key($vars) === 0 ? 1 : 0;
        if ($reflect->getNumberOfParameters() > 0) {
            $params = $reflect->getParameters();
            foreach ($params as $param) {
                $name  = $param->getName();
                $class = $param->getClass();
                if ($class) {
                    $className = $class->getName();
                    $bind      = Request::instance()->$name;
                    if ($bind instanceof $className) {
                        $args[] = $bind;
                    } else {
                        if (method_exists($className, 'invoke')) {
                            $method = new \ReflectionMethod($className, 'invoke');
                            if ($method->isPublic() && $method->isStatic()) {
                                $args[] = $className::invoke(Request::instance());
                                continue;
                            }
                        }
                        $args[] = method_exists($className, 'instance') ? $className::instance() : new $className;
                    }
                } elseif (1 == $type && !empty($vars)) {
                    $args[] = array_shift($vars);
                } elseif (0 == $type && isset($vars[$name])) {
                    $args[] = $vars[$name];
                } elseif ($param->isDefaultValueAvailable()) {
                    $args[] = $param->getDefaultValue();
                } else {
                    Response::sendException(PrismCode::ERR_REQUEST_PARAM_INEXIST, APP_MSG[PrismCode::ERR_REQUEST_PARAM_INEXIST],
                        new \InvalidArgumentException('method param miss:' . $name));
                }
            }
        }

        return $args;
    }
}