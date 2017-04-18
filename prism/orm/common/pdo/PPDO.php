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
            $sth = $this->pdo->prepare($sql);
            $sth->execute();
            return $this->pdo->query($sql)->fetch();
        } catch (\PDOException $e) {
            Response::sendException(PrismCode::ERR_PDO_QUERY, PRISM_MSG[PrismCode::ERR_PDO_QUERY], $e);
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