<?php
/**
 * Created by PhpStorm.
 * User: wangxk1991@gmail.com
 * Date: 17/4/18
 * Time: 下午9:03
 * Desc: 基础的数据库操作基类（非用于url和excel等其它类型数据源）
 */

namespace prism\orm\common;


use const prism\common\PRISM_MSG;
use prism\common\PrismCode;
use prism\Config;
use prism\Response;

class BaseDB {
    protected   $sql    = '';
    protected   $sqlMap = [
        "from"  => "",
        "where" => "",
        "order" => "",
        "limit" => "",
    ];
    protected $dbConf;

    public function __construct($dataType = 'sqlMapite') {
        $this->dbConf = Config::get('data_source')[strtolower($dataType)];
        if (empty($this->dbConf)) {
            Response::sendError(PrismCode::ERR_CONF_DATASOURCE, PRISM_MSG[PrismCode::ERR_CONF_DATASOURCE]);
        }
    }

    public function from($tableName = '') {
        $this->sqlMap["from"] = "FROM " . $tableName;

        return $this;
    }

    public function where($where = '1=1') {
        $this->sqlMap["where"] = "WHERE " . $where;

        return $this;
    }

    public function order($order = 'id DESC') {
        $this->sqlMap["order"] = "ORDER BY " . $order;

        return $this;
    }

    public function limit($limit = [0, 10]) {
        $this->sqlMap["limit"] = "LIMIT " . join(',', $limit);

        return $this;
    }

//    public function like($like = []) {
//        $this->sqlMap["like"] = $like[0] . ' LIKE ' . $like[1];
//
//        return $this;
//    }
}
