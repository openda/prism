<?php
/**
 * Created by PhpStorm.
 * User: wangxk1991@gmail.com
 * Date: 17/4/11
 * Time: 下午9:27
 * Desc:
 */

namespace prism\orm\sqlite;


use prism\orm\BaseModel;
use prism\orm\common\BaseDB;
use prism\orm\common\pdo\PPDO;

class Sqlite extends BaseDB implements BaseModel {
    private $pdo;

    public function __construct($linkInfo = []) {
        parent::__construct('sqlite');
        self::connect($linkInfo);
    }


    /**
     * @return mixed
     * 数据库连接
     */
    public function connect($link) {
        // TODO: Implement connect() method.
        $dsn = sprintf($this->dbConf['link_sql'], $link['dbfile']);

        $this->pdo = new PPDO($dsn, null, null);
    }


    /**
     * @return mixed
     * 数据库查询
     */
    public function select($fileds = '*') {
        // TODO: Implement select() method.
        $this->sql = 'SELECT ' . $fileds . ' ' . implode(' ', $this->sqlMap);

        return self::query();
    }

    /**
     * @return mixed
     * 添加
     */
    public function add($data = []) {
        // TODO: Implement add() method.
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
     * 执行sql语句
     */
    public function execute() {
        // TODO: Implement execute() method.
        return $this->pdo->execute($this->sql);
    }

    /**
     * @return mixed
     * 获取数据结构(数据库就是数据表的结构，url就是json结构)
     */
    public function structure($tbl) {
        // TODO: Implement structure() method.
    }

    public function query() {
        // TODO: Implement query() method.
        return $this->pdo->query($this->sql, $this->whereParams);
    }
}