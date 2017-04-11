<?php
/**
 * Created by PhpStorm.
 * User: wangxiaokang@iwaimai.baidu.com
 * Date: 17/4/11
 * Time: 下午9:18
 * Desc:
 */

namespace prism;


use prism\orm\BaseModel;
use prism\orm\mysql\Mysql;
use prism\orm\pgsql\Pgsql;
use prism\orm\sqlite\Sqlite;

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

            }
            //默认是sqlite
            default: {
                return new Sqlite();
            }
        }
    }
}