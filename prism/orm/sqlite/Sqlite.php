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
use prism\Request;
use prism\Response;

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

        if (!empty($this->whereParams)) {
            return self::execute($this->whereParams);
        }

        return self::query();
    }

    /**
     * @return mixed
     * 添加数据
     */
    public function save($datas = []) {
        // TODO: Implement add() method.
        $this->sql = 'INSERT INTO ' . $this->table;
        if (!is_array($datas)) {
            $this->sql = $this->sql . ' ' . $datas;
        } else {
            if (!empty($datas)) {
                $params = [];
                $flag   = 0;
                foreach ($datas as $key => $data) {
                    if ($flag === 0) {
                        if (!is_array($data)) {
                            $flag = 1;
                        } else {
                            $flag = 2;
                        }
                    }
                    if ($flag === 1) {
                        $keys[]   = $key;
                        $values[] = $data;
                    }
                    if ($flag === 2) {
                        $keys[]           = $key;
                        $values[]         = $data[0];
                        $params[$data[0]] = $data[1];
                    }
                }
                $this->sql = $this->sql . ' (' . implode(',', $keys) . ') VALUES ("' . implode('","', $values) . '")';

                return self::execute($params);
            }
        }

        return false;
    }

    /**
     * @return mixed
     * 更新
     */
    public function update($fields = []) {
        // TODO: Implement update() method.
        $this->sql = 'UPDATE ' . $this->table . ' SET ';
        if (!is_array($fields)) {
            $this->sql = $this->sql . ' ' . $fields;
        } else {
            if (!empty($fields)) {
                $params = [];
                $flag   = 0;
                $sets   = [];
                foreach ($fields as $key => $field) {
                    if ($flag === 0) {
                        if (!is_array($field)) {
                            $flag = 1;
                        } else {
                            $flag = 2;
                        }
                    }
                    if ($flag === 1) {
                        $sets[] = $key . '="' . $field . '"';
                    }
                    if ($flag === 2) {
                        $sets[]            = $key . '=' . $field[0];
                        $params[$field[0]] = $field[1];
                    }
                }
                if (!empty($this->whereParams)) {
                    array_push($params, $this->whereParams);
                }
                $this->sql = $this->sql . implode(',', $sets) . ' ' . $this->sqlMap['where'];

                return self::execute($params);
            }
        }

        return false;
    }

    /**
     * @return mixed
     * 删除
     */
    public
    function delete($param = []) {
        // TODO: Implement delete() method.
    }

    /**
     * @return mixed
     * 执行sql语句
     */
    public
    function execute($params) {
        // TODO: Implement execute() method.
        return $this->pdo->execute($this->sql, $params);
    }

    /**
     * @return mixed
     * 获取数据结构(数据库就是数据表的结构，url就是json结构)
     */
    public
    function structure($tbl) {
        // TODO: Implement structure() method.
    }

    public
    function query() {
        // TODO: Implement query() method.
        return $this->pdo->query($this->sql);
    }
}