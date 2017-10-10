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

    public function __construct($dsn, $user, $password, $exception) {
        try {
            Logger::debug("数据库连接信息是：", [$dsn, $user, $password, $exception]);
            $this->pdo = new \PDO($dsn, $user, $password);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);

        } catch (\PDOException $e) {
            if ($exception) {
                Logger::error("ERR_PDO_CONNECT", [$dsn, $e->getMessage()]);
                Response::sendException(PrismCode::ERR_PDO_CONNECT, PRISM_MSG[PrismCode::ERR_PDO_CONNECT], $e);
            } else {
                Logger::error("ERR_PDO_CONNECT_TEST", [$dsn, $e->getMessage()]);

                return null;
            }
        }
    }

    public function query($sql = '') {
        try {
            $fetchAll = $this->pdo->query($sql)->fetchAll(\PDO::FETCH_NAMED);
            Logger::info("QUERY_SQL", [$sql, $fetchAll]);

            return $fetchAll;
        } catch (\PDOException $e) {
            Logger::error("ERR_PDO_QUERY", $e->getMessage());
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
            $fetchAll = $sth->fetchAll(\PDO::FETCH_NAMED);
            //注，不能通过fetchAll中获取执行结果，应该是errorCode中来获取
            if ($sth->errorCode() == "00000") {
                Logger::info("PREPARE_SQL", [$sql, $params, $fetchAll]);
                if (empty($fetchAll)) {
                    return true;
                }
                if (count($fetchAll) == 1) {
                    return $fetchAll[0];
                }

                return $fetchAll;
            }
        } catch (\PDOException $e) {
            Logger::error("ERR_PDO_EXEC", [$sql, $e->getMessage()]);
            Response::sendException(PrismCode::ERR_PDO_EXEC, PRISM_MSG[PrismCode::ERR_PDO_EXEC], $e);
        }
        Logger::info("PREPARE_SQL", [$sql, $params, $sth->errorInfo()]);

        return false;
    }

    public function execute($sql = '') {
        try {
            Logger::info("EXECUTE_SQL", [$sql]);

            return $this->pdo->execute($sql);
        } catch (\PDOException $e) {
            Logger::error("ERR_PDO_EXEC", [$sql, $e->getMessage()]);
            Response::sendException(PrismCode::ERR_PDO_EXEC, PRISM_MSG[PrismCode::ERR_PDO_EXEC], $e);
        }

        return false;
    }

    public function exec($sql = '') {
        try {
            Logger::info("EXECUTE_SQL", [$sql]);

            return $this->pdo->exec($sql);
        } catch (\PDOException $e) {
            Logger::error("ERR_PDO_EXEC", [$sql, $e->getMessage()]);
            Response::sendException(PrismCode::ERR_PDO_EXEC, PRISM_MSG[PrismCode::ERR_PDO_EXEC], $e);
        }

        return false;
    }

    public function getPDO() {
        return $this->pdo;
    }

}