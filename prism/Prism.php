<?php

/**
 * Created by PhpStorm.
 * User: wangxk1991@gmail.com
 * Date: 17/3/28
 * Time: 下午9:01
 * Desc: 系统核心功能文件
 */

namespace prism;

use const app\common\APP_MSG;
use const prism\common\PRISM_MSG;
use prism\common\PrismCode;
use prism\core\exception\ErrorException;

class Prism {
    const RUNTIME_AUTH       = 0775;
    const DEFAULT_CONTROLLER =
        '<?php 
namespace app\\%s\\controller;

use prism\\Controller;

class Index extends Controller{

    public function show() {
        $this->result["data"]="Welcome to Prism World!";
        
        return $this->result;
    }
}';
    const DEFAULT_ROUTE      = '<?php 
return [
    "index"         => [
        "controller" => "%s/controller/Index.php",
        "method"     => [
            "get" => [
                "cp"     => [],
                "action" => "show"
            ]
        ],
    ],
];';
    const DEFAULT_CONFIG     =
        '<?php 
return [

];';

    static public function run() {
        // 加载系统配置文件
        \prism\Config::set(include CONF_PATH . 'config.php');

        $configFiles = \prism\Config::get('prism_common_config_files');

        if (!empty($configFiles)) {
            foreach ($configFiles as $name => $configFile) {
                // 加载系统配置文件
                \prism\Config::load($configFile, $name);
            }
        }
        // 系统缓存检查
        self::checkRuntime();
    }

    /**
     * 检查运行文件夹是否存在，不存在则进行初始化工作
     */
    public static function checkRuntime() {
        // TODO 优化此处的路径获取方式
        if (is_dir(RUNTIME_PATH)) {
            try {
                if (!is_dir(LOG_PATH)) {
                    File::makeDir(LOG_PATH, Prism::RUNTIME_AUTH);
                }
                if (!is_dir(TEMP_PATH)) {
                    File::makeDir(TEMP_PATH, Prism::RUNTIME_AUTH);
                }
                if (!is_dir(DATA_PATH)) {
                    File::makeDir(DATA_PATH, Prism::RUNTIME_AUTH);
                }
                if (!is_dir(CACHE_PATH)) {
                    File::makeDir(CACHE_PATH, Prism::RUNTIME_AUTH);
                }
            } catch (ErrorException $e) {
                Logger::error("RUNTIME_INIT_FAILED", [$e->getMessage()]);
                Response::sendException(PrismCode::ERR_CHECK_RUNTIME, PRISM_MSG[PrismCode::ERR_CHECK_RUNTIME], $e);
            }
        } else {
            try {
                //注意第一次创建runtime文件夹不要使用File::makeDir函数，会导致报错不准确
                File::makeDir(RUNTIME_PATH, Prism::RUNTIME_AUTH);
                File::makeDir(LOG_PATH, Prism::RUNTIME_AUTH);
                File::makeDir(TEMP_PATH, Prism::RUNTIME_AUTH);
                File::makeDir(DATA_PATH, Prism::RUNTIME_AUTH);
                File::makeDir(CACHE_PATH, Prism::RUNTIME_AUTH);
            } catch (ErrorException $e) {
                Logger::error("RUNTIME_INIT_FAILED", [$e->getMessage()]);
                Response::sendException(PrismCode::ERR_CHECK_RUNTIME, PRISM_MSG[PrismCode::ERR_CHECK_RUNTIME], $e);
            }
        }
    }

    /**
     * 检查app是否创建，未创建则创建
     */
    public static function checkApp() {
        // TODO 优化此处的路径获取方式
        if (!is_dir(APP_PATH)) {
            try {
                File::makeDir(APP_PATH, Prism::RUNTIME_AUTH);
//                mkdir(APP_PATH, Prism::RUNTIME_AUTH);
                // linux下为了防止umask导致权限设置小于系统设定，故显式设置runtime文件夹的权限
//                if (!IS_WIN) {
//                    File::recursiveChmod(APP_PATH, Prism::RUNTIME_AUTH, Prism::RUNTIME_AUTH);
//                }
            } catch (ErrorException $e) {
                Response::sendException(PrismCode::ERR_CHECK_APPS, APP_MSG[PrismCode::ERR_CHECK_APPS], $e);
            }
        }
        $apps = Config::get('apps');
        if (!empty($apps)) {
            foreach ($apps as $app) {
                if (!is_dir(APP_PATH . "$app")) {
                    try {
                        File::makeDir(APP_PATH . "$app", Prism::RUNTIME_AUTH);
                        File::makeDir(APP_PATH . "$app/controller", Prism::RUNTIME_AUTH);
                        File::makeDir(APP_PATH . "$app/service", Prism::RUNTIME_AUTH);
                        File::makeDir(APP_PATH . "$app/model", Prism::RUNTIME_AUTH);
                        File::makeDir(APP_PATH . "$app/conf", Prism::RUNTIME_AUTH);
                    } catch (ErrorException $e) {
                        Response::sendException(PrismCode::ERR_CHECK_APPS, APP_MSG[PrismCode::ERR_CHECK_APPS], $e);
                    }
                }
                // 创建每个app的默认控制器Index,如果已经存在控制器则不创建默认的控制器
                if (!glob(APP_PATH . "$app/controller/Index.php")) {
                    try {
                        $code = sprintf(Prism::DEFAULT_CONTROLLER, $app);
                        File::write(APP_PATH . "$app/controller/Index.php", $code);

                        $code = Prism::DEFAULT_CONFIG;
                        File::write(APP_PATH . "$app/conf/config.php", $code);

                        $code = sprintf(Prism::DEFAULT_ROUTE, $app);
                        File::write(APP_PATH . "$app/route.php", $code);
                    } catch (ErrorException $e) {
                        Response::sendException(PrismCode::ERR_CHECK_APPS, APP_MSG[PrismCode::ERR_CHECK_APPS], $e);
                    }

                }
            }
        } else {
            Response::sendError(PrismCode::ERR_CONF_NO_APP, PRISM_MSG[PrismCode::ERR_CONF_NO_APP]);
        }
    }


    /**
     * @param Route|null $check
     *
     * @throws ErrorException
     *
     * 检查路由
     */
    public static function checkRoute(Route $route = null, $config = []) {
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
                    $action = empty($routeConfig["method"][$route->getMethod()]['action']) ? $route->getMethod() : $routeConfig["method"][$route->getMethod()]['action'];
                    if(!empty($routeConfig['method'][$route->getMethod()])){
                        // 校验请求参数
                        foreach ($routeConfig['method'][$route->getMethod()]['cp'] as $param => $input) {
                            try {

                                if ($input[1] == 1) {
                                    $validate = Validate::validate($inputs[trim($param)], strtoupper(trim($input[0])), !isset($input[2]) ? '' : $input[2]);
                                    if ($validate != 0) {
                                        Logger::error("ERR_REQUEST_PARAM_VALIDATE", [$param]);
                                        Response::sendError($validate, PRISM_MSG[$validate]);
                                    }
                                    //过滤参数中的空格
                                    $inputs[$param] = trim($inputs[$param]);
                                }
                                if ($input[1] == 0 && (!empty($inputs[$param]) || isset($inputs[$param]))) {
                                    $validate = Validate::validate($inputs[trim($param)], strtoupper(trim($input[0])), !isset($input[2]) ? '' : $input[2]);
                                    if ($validate != 0) {
                                        Logger::error("ERR_REQUEST_PARAM_VALIDATE", [$param, $inputs[$param]]);
                                        Response::sendError($validate, PRISM_MSG[$validate]);
                                    }
                                    //过滤参数中的空格
                                    $inputs[$param] = trim($inputs[$param]);
                                }

                            } catch (ErrorException $e) {
                                Logger::error("ERR_REQUEST_PARAM_VALIDATE", [$e->getMessage()]);
                                Response::sendException(PrismCode::ERR_REQUEST_PARAM_VALIDATE, PRISM_MSG[PrismCode::ERR_REQUEST_PARAM_VALIDATE], $e);
                            }
                        }
                    }else{
                        Logger::error("REQUEST_TYPE_INEXIST", [$route->getMethod()]);
                        Response::sendError(PrismCode::ERR_REQUEST_TYPE_INEXIST, PRISM_MSG[PrismCode::ERR_REQUEST_TYPE_INEXIST]);
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