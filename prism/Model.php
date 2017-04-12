<?php
/**
 * Created by PhpStorm.
 * User: wangxiaokang@iwaimai.baidu.com
 * Date: 17/4/11
 * Time: 下午9:18
 * Desc: 对外访问的数据模型工厂类
 */

namespace prism;

use prism\orm\mysql\Mysql;
use prism\orm\pgsql\Pgsql;
use prism\orm\sqlite\Sqlite;
use prism\orm\URL\Url;

/**
 * @property Sqlite model
 */
class Model {
    public $model;

    public static function load($type = 'MYSQL') {
        $type = strtoupper($type);
        switch ($type) {
            case 'MYSQL': {
                return new Mysql();
            }
                break;
            case 'PGSQL': {
                return new Pgsql();
            }
                break;
            case 'URL': {
                return new Url();
            }
            //默认是sqlite
            default: {
                return new Sqlite();
            }
        }
    }
}