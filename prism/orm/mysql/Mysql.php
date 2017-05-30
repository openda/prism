<?php
/**
 * Created by PhpStorm.
 * User: wangxk1991@gmail.com
 * Date: 17/4/11
 * Time: 下午10:09
 * Desc:
 */

namespace prism\orm\mysql;


use prism\Logger;
use prism\orm\BaseModel;
use prism\orm\common\BaseDB;
use prism\orm\common\pdo\PPDO;

class Mysql extends BaseDB implements BaseModel {
    private $pdo;

    public function __construct($linkInfo = [], $exception = 1) {
        parent::__construct('mysql');
        self::connect($linkInfo, $exception);
    }

    /**
     * @param $link
     * @param $test 是否是测试链接
     *
     * @return mixed
     * 数据库连接
     */
    public function connect($link, $exception) {
        // TODO: Implement connect() method.
        $dsn       = sprintf($this->dbConf['link_sql'], $link['host'], $link['port'], $link['dbname']);
        $this->pdo = new PPDO($dsn, $link['user'], $link['password'], $exception);
    }

    /**
     * @return mixed
     * 数据库查询
     */
    public function select($fileds = '*') {
        // TODO: Implement select() method.
    }

    /**
     * @return mixed
     * 添加
     */
    public function save($data = []) {
        // TODO: Implement save() method.
    }

    /**
     * @return mixed
     * 更新
     */
    public function update($field = []) {
        // TODO: Implement update() method.
    }

    /**
     * @return mixed
     * 删除
     */
    public function delete($param = []) {
        // TODO: Implement delete() method.
    }

    /**
     * @return mixed
     * 获取数据结构(数据库就是数据表的结构，url就是json结构)
     */
    public function structure($tbl) {
        // TODO: Implement structure() method.
    }

//    /**
//     * @param $dsn
//     * @param $user
//     * @param $pwd
//     * @param $other
//     *
//     * @return mixed
//     * @测试数据库实例是否能连接
//     */
//    public function testConnection($dsn, $user, $pwd, $other) {
//        // TODO: Implement testConnection() method.
//        if ($this->pdo === null) {
//            return false;
//        }
//
//        return true;
//    }

    /**
     * @return mixed
     * @测试数据库实例是否能连接
     */
    public function getConnection() {
        // TODO: Implement getConnection() method.
        return $this->pdo->getPDO();
    }

    /**
     * @return mixed
     * 数据库查询
     */
    public function query($sql = "") {
        // TODO: Implement query() method.
        return $this->pdo->query($sql);
    }
}