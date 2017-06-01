<?php
/**
 * Created by PhpStorm.
 * User: wangxk1991@gmail.com
 * Date: 17/4/11
 * Time: 下午9:18
 * Desc: 对外访问的数据模型工厂类
 */

namespace prism;

use prism\orm\mysql\Mysql;
use prism\orm\pgsql\Pgsql;
use prism\orm\sqlite\Sqlite;
use prism\orm\url\Url;

/**
 * @property Sqlite model
 */
class Model {
    public $model;

    public static function load($type = 'mysql', $linkInfo = [], $exception = 1) {
        $type = strtolower($type);
        switch ($type) {
            case 'mysql': {
                return new Mysql($linkInfo, $exception);
            }
                break;
            case 'pgsql': {
                return new Pgsql();
            }
                break;
            case 'url': {
                return new Url();
            }
            //默认是sqlite
            default: {
                if (empty($linkInfo))
                    //返回默认的系统sqlite配置
                    return new Sqlite(['dbfile' => SQLITE_FILE]);
                else
                    return new Sqlite($linkInfo);
            }
        }
    }
}