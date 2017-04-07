<?php
/**
 * Created by PhpStorm.
 * User: wangxk1991@gmail.com
 * Date: 17/3/29
 * Time: 上午11:10
 * Desc: app执行类
 */

namespace prism;

use const prism\common\ERR_MSG;
use prism\common\ErrCode;
use prism\core\exception\ErrorException;

class App {

    protected static $namespace;
    protected static $init = false;

    public static $debug = true;

    public static function run(Request $request = null) {
        is_null($request) && $request = Request::instance();
        try {
            self::init();
            // APP检查
            Check::run(['app']);

            // 加载路由信息
            if (is_file(APP_PATH . 'route.php')) {
                Config::load(APP_PATH . 'route.php', 'route');
            } else {
                Response::sendException(ErrCode::ERR_REQUEST_ROUTE);

                return false;
            }
            $config = Config::get();

            $route = new Route($request);
            $route->setDefault([
                'app'        => empty($config['default_app']) ? 'index' : $config['default_app'],
                'controller' => empty($config['default_controller']) ? 'index' : $config['default_controller'],
                'action'     => empty($config['default_action']) ? 'index' : $config['default_action'],
            ]);
            //路由解析
            $route->parse();
            // 路由检查
            $routes = Check::run(['route'], $route, $config['route']);
            if (!empty($routes['inputs']) && !empty($routes['class']) && !empty($routes['action'])) {
                Response::setContent(self::invoke($routes), 1);
            }
//            Response::send();
        } catch (ErrorException $e) {
            Response::sendException($e);
        }

        return true;
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
            $method   = $class->getmethod($route['action']);
            if ($method->isPrivate() || $method->isConstructor() || $method->isStatic() || $method->isDestructor()) {
                Response::sendError(ErrCode::ERR_REQUEST_ACTION_TYPE, ERR_MSG[ErrCode::ERR_REQUEST_ACTION_TYPE]);
            }

            return $method->invokeArgs($instance, $route['inputs']);

        } catch (\ReflectionException $e) {
            Response::sendException($e);
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
                    Response::sendException(new \InvalidArgumentException('method param miss:' . $name));
                }
            }
        }

        return $args;
    }
}