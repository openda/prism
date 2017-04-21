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
use prism\Response;

class PPDO {
    private $pdo;

    public function __construct($dsn, $user, $password) {
        try {

            $this->pdo = new \PDO($dsn, $user, $password);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
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

    public function execute($sql = '', $params) {
        try {
            $sth = $this->pdo->prepare($sql);
            foreach ($params as $key => $param) {
                if (is_numeric($key)) {
                    $sth->bindValue($key + 1, $param);
                } else {
                    $sth->bindValue($key, $param);
                }
            }
            $sth->execute();
            $sth->setFetchMode(\PDO::FETCH_ASSOC);
            return $sth->fetchAll();
        } catch (\PDOException $e) {
            Response::sendException(PrismCode::ERR_PDO_EXEC, PRISM_MSG[PrismCode::ERR_PDO_EXEC], $e);
        }

        return false;
    }

    public function exec($sql = '') {
        try {
            return $this->pdo->exec($sql);
        } catch (\PDOException $e) {
            Response::sendException(PrismCode::ERR_PDO_EXEC, PRISM_MSG[PrismCode::ERR_PDO_EXEC], $e);
        }

        return false;
    }
}