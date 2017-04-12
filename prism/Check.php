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
    const PARAM_STRING = 'STRING';
    const PARAM_NUMBER = 'NUMBER';
    const PARAM_TIME   = 'TIME';
    const PARAM_PHONE  = 'PHONE';
    const PARAM_EMAIL  = 'EMAIL';
    const PARAM_MD532  = 'MD532';
    const PARAM_OPTION = 'OPTION';

    const VALIDATE_BANKACOOUNT         = "/[\d]{10,}/";
    const VALIDATE_NUMBER_GREATER_ZERO = "/^[1-9]\d*$/";
    const VALIDATE_TIME                =
        "/^\d{4}[\-](0?[1-9]|1[012])[\-](0?[1-9]|[12][0-9]|3[01])(\s+(0?[0-9]|1[0-9]|2[0-3])\:(0?[0-9]|[1-5][0-9])\:(0?[0-9]|[1-5][0-9]))?$/";

    const RUNTIME_AUTH       = 0775;
    const DEFAULT_CONTROLLER =
        "<?php 
namespace app\\index\\controller;

use prism\\Controller;

class Index extends Controller{
    public function show() {
        return 'Hello Prism';
    }
}";
    const DEFAULT_ROUTE      = '<?php 
return [
    "index"=>"app/index/controller/Index.php",
];';


    public static function run($type = [], $check = [], $config = []) {
        Logger::debug('REQUEST_PARAM_INEXIST', ['sss']);
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
                Response::send(PrismCode::ERR_CHECK_RUNTIME, PRISM_MSG[PrismCode::ERR_CHECK_RUNTIME], $e);
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
                Response::sendException($e);
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
            $class     = '';
            $routeInfo = $route->getRoute();
            $inputs    = $route->getInputs();
            if (!empty($routeInfo)) {
                if (!is_dir(APP_PATH . $routeInfo['app'])) {
                    Response::sendError(PrismCode::ERR_ROUTE_APP, PRISM_MSG[PrismCode::ERR_ROUTE_APP]);
                }
                if (!file_exists(APP_PATH . $routeInfo['app'] . DS . 'controller' . DS . ucfirst($routeInfo['controller']) . '.php')) {
                    Response::sendError(PrismCode::ERR_ROUTE_CONTROLLER, PRISM_MSG[PrismCode::ERR_ROUTE_CONTROLLER]);
                }
                $routeTmp = trim($routeInfo['app'], '/') . '/' . trim($routeInfo['controller'], '/');
                if (array_key_exists($routeTmp, $config)) {
                    $routeConfig = $config["$routeTmp"];
                    $class       = $routeConfig[0];
                    // 判断请求方式是否出错
                    if (strpos(strtoupper($routeConfig[1]), strtoupper($route->getRequest()->getType())) === false) {
                        Response::sendError(PrismCode::ERR_REQUEST_METHOD, PRISM_MSG[PrismCode::ERR_REQUEST_METHOD]);
                    }
                    // 校验请求参数
                    foreach ($routeConfig[2] as $param => $input) {
                        try {
                            $validate = self::validate($inputs[$param], strtoupper($input[0]), !isset($input[2]) ? '' : $input[2]);
                            if ($input[1] == 1 && $validate != 0) {
                                Response::sendError($validate, PRISM_MSG[$validate]);
                            }
                            if ($input[1] == 0 && $validate != 0 && (!empty($inputs[$param]) || !isset($inputs[$param]))) {
                                Response::sendError($validate, PRISM_MSG[$validate]);
                            }
                        } catch (ErrorException $e) {
                            Response::sendException($e);
                        }
                    }
                }
            }
            $routeInfo['class']     = $class;
            $routeInfo['inputs']    = $inputs;
            $routeInfo['namespace'] = join('\\', [Config::get('app_namespace'), $routeInfo['app'], 'controller', ucfirst($routeInfo['controller'])]);

            return $routeInfo;
        } else {
            //TODO 添加http请求错误相关的异常和日志
            Response::sendError(PrismCode::ERR_REQUEST_ROUTE, PRISM_MSG[PrismCode::ERR_REQUEST_ROUTE]);
        }

        return true;
    }

    /**
     * @param $value
     * @param $type
     * @param $preg
     *
     * @desc 单个参数类型校验器
     */
    public static function validate($value, $type = Check::PARAM_STRING, $pattern = '', $errno = '') {
        if ($type == Check::PARAM_STRING) {
            if ($pattern != '' && $pattern != null) {
                if (!preg_match($pattern, $value)) {
                    return PrismCode::ERR_REQUEST_PARAM_STRING;
                }
            } else {
                if ($value == '' || $value == null) {
                    return PrismCode::ERR_REQUEST_PARAM_STRING;
                }
            }
        } else if ($type == Check::PARAM_TIME) {
            if ($pattern == '') {
                $pattern = Check::VALIDATE_TIME;
            }
            if (!preg_match($pattern, $value)) {
                return PrismCode::ERR_REQUEST_PARAM_TIME;
            }

        } else if ($type == Check::PARAM_PHONE) {
            if (!preg_match("/^1[34578]{1}\d{9}$/", $value)) {
                return PrismCode::ERR_REQUEST_PARAM_PHONE;
            }
        } else if ($type == Check::PARAM_EMAIL) {
            if (filter_var($value, FILTER_VALIDATE_EMAIL) == false) {
                return PrismCode::ERR_REQUEST_PARAM_EMAIL;
            }
        } else if ($type == Check::PARAM_NUMBER) {
            if ($pattern != '' && $pattern != null) {
                if (!preg_match($pattern, $value)) {
                    return PrismCode::ERR_REQUEST_PARAM_NUMBER;
                }
            }
            if (!is_numeric($value) || $value > 100000000000 || $value < 0 || strpos($value, '.')) {
                return PrismCode::ERR_REQUEST_PARAM_NUMBER;
            }
        } else if ($type == Check::PARAM_OPTION) {
            if (!is_array($pattern)) {
                return PrismCode::ERR_REQUEST_PARAM;
            }
            if (!in_array($value, $pattern)) {
                return PrismCode::ERR_REQUEST_PARAM_OPTION;
            }
        } else if ($type == Check::PARAM_MD532) {
            if (!preg_match("/^[a-z0-9]{32}$/", $value)) {
                return PrismCode::ERR_REQUEST_PARAM_MD532;
            }
        } else {
            if ($pattern != '' && $pattern != null) {
                if (!preg_match($pattern, $value)) {
                    return PrismCode::ERR_REQUEST_PARAM;
                }
            } else {
                return PrismCode::ERR_REQUEST_PARAM;
            }
        }

        return PrismCode::SUCCESS;
    }
}