<?php
/**
 * Created by PhpStorm.
 * User: wangxk1991@gmail.com
 * Date: 17/4/11
 * Time: 下午9:27
 * Desc: sqlite 基本操作
 */

namespace prism\orm\sqlite;


use const prism\common\PRISM_MSG;
use prism\common\PrismCode;
use prism\Logger;
use prism\orm\BaseModel;
use prism\orm\common\BaseDB;
use prism\orm\common\pdo\PPDO;
use prism\Response;

class Sqlite extends BaseDB implements BaseModel {
    private $pdo;

    public function __construct($linkInfo = [], $test = 0) {
        parent::__construct('sqlite');
        self::connect($linkInfo, $test);
    }


    /**
     * @return mixed
     * 数据库连接
     */
    public function connect($link, $test) {
        // TODO: Implement connect() method.
        $dsn       = sprintf($this->dbConf['link_sql'], $link['dbfile']);
        $this->pdo = new PPDO($dsn, null, null, $test);
    }


    /**
     * @return mixed
     * 数据库查询
     */
    public function select($fileds = '*') {
        // TODO: Implement select() method.
        if (empty($this->sqlMap["from"])) {
            $this->sqlMap["from"] = "FROM " . $this->table;
        }
        $this->sql = 'SELECT ' . $fileds . ' ' . implode(' ', $this->sqlMap);

        if (!empty($this->whereParams)) {
            $rets = $this->pdo->prepare($this->sql, $this->whereParams);
            if ($rets === true) {
                return [];
            }

            return $rets;
        }

        return $this->pdo->query($this->sql);
    }

    /**
     * @return mixed
     * 添加数据
     */
    public function save($datas = []) {
        // TODO: Implement add() method.
        $this->sql = 'INSERT INTO ' . $this->table;
        if (!is_array($datas) && isset($datas)) {
            $this->sql = $this->sql . ' ' . $datas;
        } else {
            if (!empty($datas)) {
                $params = [];
                $flag   = 0;
                $keys   = [];
                $values = [];
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
                $this->sql = $this->sql . ' (' . implode(',', $keys) . ') VALUES (\'' . implode('\',\'', $values) . '\')';

                try {
                    if ($this->pdo->prepare($this->sql, $params) == "00000") {
                        return true;
                    }
                } catch (\Exception $e) {
                    Logger::error("ERR_PDO_EXEC", [$e->getMessage()]);
                    Response::sendException(PrismCode::ERR_PDO_EXEC, PRISM_MSG[PrismCode::ERR_PDO_EXEC], $e);
                }
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
        $params    = [];
        $flag      = 0;
        $sets      = [];
        if (!is_array($fields) && isset($fields)) {
            $this->sql = $this->sql . ' ' . $fields;
        } else {
            if (!empty($fields)) {
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
                        $sets[]            = $key . '=' . $field[0] . '';
                        $params[$field[0]] = $field[1];
                    }
                }
                if (!empty($this->whereParams)) {
                    $params = array_merge($params, $this->whereParams);
                }
                $this->sql = $this->sql . implode(',', $sets) . ' ' . $this->sqlMap['where'];
            }
        }
        try {
            if ($this->pdo->prepare($this->sql, $params) == "00000") {
                return true;
            }
        } catch (\Exception $e) {
            Logger::error("ERR_PDO_EXEC", [$e->getMessage()]);
            Response::sendException(PrismCode::ERR_PDO_EXEC, PRISM_MSG[PrismCode::ERR_PDO_EXEC], $e);
        }

        return false;
    }

    /**
     * @return mixed
     * 删除
     */
    public function delete($params = []) {
        // TODO: Implement update() method.
        if (!empty($params)) {
            $this->sql = 'DELETE FROM ' . $this->table . ' WHERE ' . implode($params);
        } else {
            $this->sql = 'DELETE FROM ' . $this->table . ' ' . $this->sqlMap['where'];
        }

        return $this->pdo->prepare($this->sql, $this->whereParams);
    }

    /**
     * @return mixed
     * 获取数据结构(数据库就是数据表的结构，url就是json结构)
     */
    public function structure($tableName) {
        // TODO: Implement structure() method.
        $this->sql = "PRAGMA table_info($this->table))";
        if (!empty($tableName)) {
            $this->sql = "PRAGMA table_info($tableName)";
        }
        $result = $this->pdo->query($this->sql);
        $info   = [];
        if ($result) {
            foreach ($result as $key => $val) {
                $info[] = [
                    'name'    => $val['name'],
                    'type'    => $val['type'],
                    'notnull' => $val['notnull'], // not null is empty, null is yes
                    'default' => $val['deft_value'],
                    'primary' => (strtolower($val['dey']) == 'pri'),
                    'autoinc' => (strtolower($val['extra']) == 'auto_increment'),
                ];
            }
        }

        return $info;
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
//        // TODO: Implement connect() method.
//        return PPDO::testConnect($dsn, $user, $pwd, 1);
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