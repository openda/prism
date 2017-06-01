<?php
/**
 * Created by PhpStorm.
 * User: wangxk1991@gmail.com
 * Date: 17/3/30
 * Time: 下午5:10
 * Desc: 系统检查类
 */

namespace prism;


use const prism\common\PRISM_MSG;
use prism\common\PrismCode;
use prism\core\exception\ErrorException;

class Check {

    const RUNTIME_AUTH       = 0775;
    const DEFAULT_CONTROLLER =
        '<?php 
namespace app\\index\\controller;

use prism\\Controller;

class Index extends Controller{
    public function get() {
        $this->result[data]=>"Welcome to Prism World!";
        return $this->result;
    }
}';
    const DEFAULT_ROUTE      = '<?php 
return [
    "index"=>"app/index/controller/Index.php",
];';


    public static function run($type = [], $check = [], $config = []) {
        if (in_array('runtime', $type)) {
            self::checkRuntime();
        }
        if (in_array('app', $type)) {
            self::checkApp();
            self::checkController();
        }
        //路由检查
        if (in_array('route', $type)) {
            return self::checkRoute($check, $config);
        }

        return true;
    }

    /**
     * 检查运行文件夹是否存在，不存在则进行初始化工作
     */
    private static function checkRuntime() {
        // TODO 优化此处的路径获取方式
        if (is_dir(RUNTIME_PATH)) {
            try {
                if (!is_dir(LOG_PATH)) {
                    mkdir(LOG_PATH, Check::RUNTIME_AUTH);
                }
                if (!is_dir(TEMP_PATH)) {
                    mkdir(TEMP_PATH, Check::RUNTIME_AUTH);
                }
                if (!is_dir(DATA_PATH)) {
                    mkdir(DATA_PATH, Check::RUNTIME_AUTH);
                }
                if (!is_dir(CACHE_PATH)) {
                    mkdir(CACHE_PATH, Check::RUNTIME_AUTH);
                }
            } catch (ErrorException $e) {
                Response::sendException(PrismCode::ERR_CHECK_RUNTIME, PRISM_MSG[PrismCode::ERR_CHECK_RUNTIME], $e);
                Logger::error("RUNTIME_INIT_FAILED", [$e->getMessage()]);
            }
        } else {
            try {
                mkdir(RUNTIME_PATH, Check::RUNTIME_AUTH);
                // linux下为了防止umask导致权限设置小于系统设定，故显式设置runtime文件夹的权限
                if (!IS_WIN) {
                    File::recursiveChmod(RUNTIME_PATH, Check::RUNTIME_AUTH, Check::RUNTIME_AUTH);
                }
                mkdir(LOG_PATH, Check::RUNTIME_AUTH);
                mkdir(TEMP_PATH, Check::RUNTIME_AUTH);
                mkdir(DATA_PATH, Check::RUNTIME_AUTH);
                mkdir(CACHE_PATH, Check::RUNTIME_AUTH);
            } catch (ErrorException $e) {
                Response::sendException(PrismCode::ERR_CHECK_RUNTIME, PRISM_MSG[PrismCode::ERR_CHECK_RUNTIME], $e);
                Logger::error("RUNTIME_INIT_FAILED", [$e->getMessage()]);
            }
        }
        // linux下为了防止umask导致权限设置小于系统设定，故显式设置runtime文件夹的权限
        if (!IS_WIN) {
            File::recursiveChmod(RUNTIME_PATH, Check::RUNTIME_AUTH, Check::RUNTIME_AUTH);
        }
    }

    /**
     * 检查app是否创建，未创建则创建
     */
    private static function checkApp() {
        // TODO 优化此处的路径获取方式
        if (!is_dir(APP_PATH)) {
            try {
                mkdir(APP_PATH, Check::RUNTIME_AUTH);
                // linux下为了防止umask导致权限设置小于系统设定，故显式设置runtime文件夹的权限
                if (!IS_WIN) {
                    File::recursiveChmod(APP_PATH, Check::RUNTIME_AUTH, Check::RUNTIME_AUTH);
                }
            } catch (ErrorException $e) {
                Response::sendException($e);
            }
        }
        // linux下为了防止umask导致权限设置小于系统设定，故显式设置runtime文件夹的权限
        if (!IS_WIN) {
            File::recursiveChmod(APP_PATH, Check::RUNTIME_AUTH, Check::RUNTIME_AUTH);
        }
    }

    /**
     * 给每个app创建默认的控制器
     */
    private static function checkController() {
        $apps = Config::get('apps');
        if (!empty($apps)) {
            foreach ($apps as $app) {
                if (!is_dir(APP_PATH . "$app")) {
                    try {
                        mkdir(APP_PATH . "$app", Check::RUNTIME_AUTH);
                        mkdir(APP_PATH . "$app/controller", Check::RUNTIME_AUTH);
                        mkdir(APP_PATH . "$app/service", Check::RUNTIME_AUTH);
                        mkdir(APP_PATH . "$app/model", Check::RUNTIME_AUTH);
                    } catch (ErrorException $e) {
                        Response::sendException($e);
                    }
                }
                // 创建每个app的默认控制器Index,如果已经存在控制器则不创建默认的控制器
                if (!glob(APP_PATH . "$app/controller/Index.php")) {
                    try {
                        $indexController = fopen(APP_PATH . "$app/controller/Index.php", 'w');
                        $code            = Check::DEFAULT_CONTROLLER;
                        fwrite($indexController, $code);
                        fclose($indexController);
                    } catch (ErrorException $e) {
                        Response::sendException($e);
                    }
                }
            }
        } else {
            throw new ErrorException(PrismCode::ERR_CONF_NO_APP, PRISM_MSG[PrismCode::ERR_CONF_NO_APP], __FILE__, __LINE__);
        }
    }


    /**
     * @param Route|null $check
     *
     * @throws ErrorException
     *
     * 检查路由
     */
    private static function checkRoute(Route $route = null, $config = []) {
        if (!is_null($route)) {
            // 获取路由表
            $action    = '';
            $class     = '';
            $namespace = '';
            $inputs    = $route->getInputs();
            $routeInfo = $route->getRoute();
            if (!empty($routeInfo)) {
                if (!is_dir(APP_PATH . $routeInfo['app'])) {
                    Response::sendError(PrismCode::ERR_ROUTE_APP, PRISM_MSG[PrismCode::ERR_ROUTE_APP]);
                }
                $routeTmp = trim($routeInfo['resource'], '/');
                if (array_key_exists($routeTmp, $config)) {
                    $routeConfig = $config["$routeTmp"];
                    $class       = $routeConfig["controller"];
                    $namespace   = str_replace("/", "\\", substr($class, 0, -4));
//                    // 判断请求方式是否出错
//                    if (!array_key_exists($route->getRequest()->getType(), $routeConfig["method"])) {
//                        Response::sendError(PrismCode::ERR_REQUEST_METHOD, PRISM_MSG[PrismCode::ERR_REQUEST_METHOD]);
//                    }
                    $action = empty($routeConfig["method"][$route->getMethod()]['action']) ? $route->getMethod() : $routeConfig["method"][$route->getMethod()]['action'];
                    // 校验请求参数
                    foreach ($routeConfig['method'][$route->getMethod()]['cp'] as $param => $input) {
                        try {
                            $validate = Validate::validate($inputs[trim($param)], strtoupper(trim($input[0])), !isset($input[2]) ? '' : $input[2]);
                            if ($input[1] == 1 && $validate != 0) {
                                Logger::error("ERR_REQUEST_PARAM_VALIDATE", [$param]);
                                Response::sendError($validate, PRISM_MSG[$validate]);
                            }
                            if ($input[1] == 0 && $validate != 0 && (!empty($inputs[$param]) || isset($inputs[$param]))) {
                                Logger::error("ERR_REQUEST_PARAM_VALIDATE", [$param]);
                                Response::sendError($validate, PRISM_MSG[$validate]);
                            }
                            //过滤参数中的空格
                            $inputs[$param] = trim($inputs[$param]);
                        } catch (ErrorException $e) {
                            Logger::error("ERR_REQUEST_PARAM_VALIDATE", [$e->getMessage()]);
                            Response::sendException(PrismCode::ERR_REQUEST_PARAM_VALIDATE, PRISM_MSG[PrismCode::ERR_REQUEST_PARAM_VALIDATE], $e);
                        }
                    }
                }
            }
            $routeInfo['action']    = $action;
            $routeInfo['class']     = $class;
            $routeInfo['inputs']    = $inputs;
            $routeInfo['namespace'] = join('\\', [Config::get('app_namespace'), $namespace]);

            return $routeInfo;
        } else {
            //TODO 添加http请求错误相关的异常和日志
            Logger::error("ERR_REQUEST_ROUTE");
            Response::sendError(PrismCode::ERR_REQUEST_ROUTE, PRISM_MSG[PrismCode::ERR_REQUEST_ROUTE]);
        }

        return true;
    }

}