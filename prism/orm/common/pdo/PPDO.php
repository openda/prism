<?php
/**
 * Created by PhpStorm.
 * User: wangxk1991@gmail.com
 * Date: 17/4/12
 * Time: 下午9:39
 * Desc: prism pdo基类
 */

namespace prism\orm\common\pdo;

use const prism\common\PRISM_MSG;
use prism\common\PrismCode;
use prism\Logger;
use prism\Response;

class PPDO {
    private $pdo;

    public function __construct($dsn, $user, $password) {
        try {

            $this->pdo = new \PDO($dsn, $user, $password);
//            $this->pdo->exec("SET NAMES utf8;");
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            Logger::error("ERR_PDO_CONNECT", [$dsn, $e->getMessage()]);
            Response::sendException(PrismCode::ERR_PDO_CONNECT, PRISM_MSG[PrismCode::ERR_PDO_CONNECT], $e);
        }
    }

    public function query($sql = '') {
        try {
            return $this->pdo->query($sql)->fetchAll();
        } catch (\PDOException $e) {
            Response::sendException(PrismCode::ERR_PDO_QUERY, PRISM_MSG[PrismCode::ERR_PDO_QUERY], $e);
        }

        return false;
    }

    public function prepare($sql = '', $params) {
        try {
            //预编译语句
            $sth = $this->pdo->prepare($sql);
            //绑定参数
            if (!empty($params)) {
                foreach ($params as $key => $param) {
                    //使用"?"方式进行预编译
                    if (is_numeric($key)) {
                        $sth->bindValue($key + 1, $param);
                    } else {
                        //使用":param"方式进行预编译
                        $sth->bindValue($key, $param);
                    }
                }
            }
            //执行sql语句
            $sth->execute();
            $fetchAll = $sth->fetchAll();
            //注，不能通过fetchAll中获取执行结果，应该是errorCode中来获取
            Logger::info("PREPARE_SQL", [$sql, $params, $sth->errorInfo()]);
            if ($sth->errorCode() == "00000") {
                if (empty($fetchAll)) {
                    return true;
                }
                return $fetchAll;
            }
        } catch (\PDOException $e) {
            Logger::error("ERR_PDO_CONNECT", $e->getMessage());
            Response::sendException(PrismCode::ERR_PDO_EXEC, PRISM_MSG[PrismCode::ERR_PDO_EXEC], $e);
        }

        return false;
    }

    public
    function execute($sql = '') {
        try {
            Logger::info("EXECUTE_SQL", [$sql]);

            return $this->pdo->execute($sql);
        } catch (\PDOException $e) {
            Logger::error("ERR_PDO_CONNECT", $e->getMessage());
            Response::sendException(PrismCode::ERR_PDO_EXEC, PRISM_MSG[PrismCode::ERR_PDO_EXEC], $e);
        }

        return false;
    }

    public
    function exec($sql = '') {
        try {
            Logger::info("EXECUTE_SQL", [$sql]);

            return $this->pdo->exec($sql);
        } catch (\PDOException $e) {
            Logger::error("ERR_PDO_CONNECT", $e->getMessage());
            Response::sendException(PrismCode::ERR_PDO_EXEC, PRISM_MSG[PrismCode::ERR_PDO_EXEC], $e);
        }

        return false;
    }
}