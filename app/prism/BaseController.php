<?php
/**
 * Created by PhpStorm.
 * User: wangxk1991@gmail.com
 * Date: 2017/5/27
 * Time: 下午2:37
 * Desc: 基类
 */

namespace app\prism;


use const app\common\APP_MSG;
use app\common\AppCode;
use prism\Config;
use prism\Controller;
use prism\File;
use prism\Logger;
use prism\Response;

class BaseController extends Controller {
    protected $encryptStr;
    protected $dbTypeMap = [
        "1"   => "mysql",
        "2"   => "pgsql",
        "3"   => "sqlite",
        "4"   => "oracle",
        "5"   => "db2",
        "6"   => "sqlserver",
        "10"  => "mongodb",
        "11"  => "hive",
        "12"  => "mem",
        "13"  => "redis",
        "14"  => "hive",
        "15"  => "hbase",
        "100" => "url",
    ];

    protected $dbLinkParams = ['host', 'port', 'dbname', 'user', 'file', 'url'];

    public function _initialize() {
        parent::_initialize(); // TODO: Change the autogenerated stub
        /**
         * 获取加密串，如果加密串不存在，则返回错误信息
         */
        $encryptFile      = Config::get('encrypt_file');
        $this->encryptStr = File::loadFile($encryptFile);
        if ($this->encryptStr == AppCode::ERR_FILE_INEXISTED) {
            Logger::error("ERR_NO_INIT_");
            Response::sendError(AppCode::ERR_FILE_INEXISTED, APP_MSG[AppCode::ERR_FILE_INEXISTED]);
        }
    }
}