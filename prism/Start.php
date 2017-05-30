<?php
/**
 * Created by PhpStorm.
 * User: wangxk1991@gmail.com
 * Date: 17/3/28
 * Time: 下午6:21
 * Desc: 公共入口文件
 */
error_reporting(E_ALL);
// 记录开始运行时间
$GLOBALS['_beginTime'] = microtime(true);
$GLOBALS['_loginID']   = rand(10000, 99999); //定义全局访问ID
//记录内存使用情况
define('MEMORY_LIMIT_ON', function_exists('memory_get_usage'));

// 版本信息
const PRISM_VERSION = '1.0.0';

// 常量定义
define('EXT', '.php');
define('DS', DIRECTORY_SEPARATOR);

defined('APP_STATUS') or define('APP_STATUS', ''); // 应用状态 加载对应的配置文件
defined('APP_DEBUG') or define('APP_DEBUG', true); // 是否调试模式
defined('ROOT_PATH') or define('ROOT_PATH', str_replace('\\', '/', realpath(dirname(__FILE__) . '/../')) . DS);
defined('PRISM_PATH') or define('PRISM_PATH', __DIR__ . '/');
defined('APP_PATH') or define('APP_PATH', dirname($_SERVER['SCRIPT_FILENAME']) . '/app/');

defined('SQLITE_FILE') or define('SQLITE_FILE', PRISM_PATH . 'db/prism.db');//定义sqlite数据库文件地址
defined('CORE_PATH') or define('CORE_PATH', realpath(PRISM_PATH . 'core') . '/'); //系统目录
defined('CONF_PATH') or define('CONF_PATH', PRISM_PATH . 'conf/'); //定义配置目录
defined('APP_CONF') or define('APP_CONF', APP_PATH . DS); //定义APP配置目录
defined('DEFAULT_APP') or define('DEFAULT_APP', 'index'); //定义APP配置目录
defined('CONF_PATH') or define('ORM_PATH', PRISM_PATH . 'orm/'); //定义ORM目录
defined('EXTEND_PATH') or define('EXTEND_PATH', PRISM_PATH . 'extend' . DS); //自定义扩展类库目录
defined('VENDOR_PATH') or define('VENDOR_PATH', PRISM_PATH . 'vendor/'); //定义第三方扩展目录
defined('RUNTIME_PATH') or define('RUNTIME_PATH', ROOT_PATH . 'runtime');
defined('LOG_PATH') or define('LOG_PATH', RUNTIME_PATH . '/logs'); // 应用日志目录
defined('TEMP_PATH') or define('TEMP_PATH', RUNTIME_PATH . '/temp'); // 应用缓存目录
defined('DATA_PATH') or define('DATA_PATH', RUNTIME_PATH . '/data'); // 应用数据目录
defined('CACHE_PATH') or define('CACHE_PATH', RUNTIME_PATH . '/cache'); // 应用模板缓存目录

defined('CONF_PARSE') or define('CONF_PARSE', '');    // 配置文件解析方法
defined('SESSION_ENABLE') or define('SESSION_ENABLE', 'on'); // 应用状态 加载对应的配置文件
// 环境常量
define('IS_CLI', PHP_SAPI == 'cli' ? true : false);
define('IS_WIN', strpos(PHP_OS, 'WIN') !== false);

require_once PRISM_PATH . 'Loader.php';
//自动注册
\prism\Loader::register();

//系统开始
\prism\Prism::run();

// 开始执行应用
\prism\App::run();