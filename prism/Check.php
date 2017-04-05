<?php
/**
 * Created by PhpStorm.
 * User: wangxiaokang@iwaimai.baidu.com
 * Date: 17/3/30
 * Time: 下午5:10
 * Desc: 系统检查类
 */

namespace prism;


use const prism\common\ERR_MSG;
use prism\common\ErrCode;
use prism\core\exception\ErrorException;

class Check {
    const RUNTIME_AUTH       = 0775;
    const DEFAULT_CONTROLLER =
        "<?php 
namespace prism;

use const prism\\common\\ERR_MSG;
use prism\\common\\ErrCode;

class IndexController extends Controller{
    public function index() {
        Response::send(ErrCode::SUCCESS, ERR_MSG[ErrCode::SUCCESS]);
    }
}";
    const DEFAULT_ROUTE      = '<?php 
return [
    "index"=>"app/index/controller/Index.php",
];';


    public static function run($type = [], $check = null) {
        if (in_array('runtime', $type)) {
            self::checkRuntime();
        }
        if (in_array('app', $type)) {
            self::checkApp();
            self::checkController();
        }
        //路由检查
        if (in_array('route', $type)) {
            self::checkRoute($check);
        }
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
                Response::send(ErrCode::ERR_CHECK_RUNTIME, ERR_MSG[ErrCode::ERR_CHECK_RUNTIME], $e);
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
                Response::sendException(ErrCode::ERR_CHECK_RUNTIME, ERR_MSG[ErrCode::ERR_CHECK_RUNTIME], $e);
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
                Response::sendException(ErrCode::ERR_CHECK_APPS, ERR_MSG[ErrCode::ERR_CHECK_APPS], $e);
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
                        Response::sendException(ErrCode::ERR_CHECK_APPS, ERR_MSG[ErrCode::ERR_CHECK_APPS], $e);
                    }
                }
                // 创建每个app的默认控制器Index,如果已经存在控制器则不创建默认的控制器
                if (!glob("$app/controller/*Controller.php")) {
                    try {
                        $indexController = fopen(APP_PATH . "$app/controller/Index.php", 'w');
                        $code            = Check::DEFAULT_CONTROLLER;
                        fwrite($indexController, $code);
                        fclose($indexController);
                    } catch (ErrorException $e) {
                        Response::sendException(ErrCode::ERR_CREATE_CONTROLLER, ERR_MSG[ErrCode::ERR_CREATE_CONTROLLER], $e);
                    }
                }
                // 创建每个app的路由文件
                if (!glob("$app/route.php")) {
                    try {
                        $indexController = fopen(APP_PATH . "$app/route.php", 'w');
                        $code            = Check::DEFAULT_ROUTE;
                        fwrite($indexController, $code);
                        fclose($indexController);
                    } catch (ErrorException $e) {
                        Response::sendException(ErrCode::ERR_CREATE_ROUTE, ERR_MSG[ErrCode::ERR_CREATE_ROUTE], $e);
                    }
                }
            }
        } else {
            throw new ErrorException(ErrCode::ERR_CONF_NO_APP, ERR_MSG[ErrCode::ERR_CONF_NO_APP], __FILE__, __LINE__);
        }
    }


    /**
     * @param Route|null $check
     *
     * @throws ErrorException
     *
     * 检查路由
     */
    private static function checkRoute(Route $route = null) {
        if (!is_null($route)) {
            // 获取路由表
            $routeConfig = Config::get('route');
            $routeInfo   = $route->getRoute();
            if (!empty($routeInfo)) {
                $routeTmp = trim($routeInfo['app'], '/') . '/' . trim($routeInfo['controller'], '/');
                if (key_exists($routeTmp, $routeConfig)) {
                    $routeConfig = $routeConfig["$routeTmp"];
                    // 判断请求方式是否出错
                    if (strpos(strtoupper($routeConfig[1]), strtoupper($route->getRequest()->getType())) === false) {
                        Response::sendError(ErrCode::ERR_REQUEST_METHOD, ERR_MSG[ErrCode::ERR_REQUEST_METHOD]);
                    }
                    // 判断请求参数
                    $inputs = $route->getRequest()->getInput();
                    foreach ($routeConfig[2] as $param => $input) {
                        if (key_exists(trim($param), $inputs)) {
                        }else{
                            Response::sendError(ErrCode::ERR_REQUEST_PARAM_INEXIST, ERR_MSG[ErrCode::ERR_REQUEST_PARAM_INEXIST]);
                        }
                    }
                }
            }

            return true;
        } else {
            //TODO 添加http请求错误相关的异常和日志
            throw new ErrorException(ErrCode::ERR_REQUEST_ROUTE, ERR_MSG[ErrCode::ERR_REQUEST_ROUTE], __FILE__, __LINE__);

        }
    }
}