<?php
/**
 * Created by PhpStorm.
 * User: wangxk1991@gmail.com
 * Date: 2017/5/27
 * Time: 上午10:22
 * Desc: 用户数据资源（虚拟资源）
 */

namespace app\prism\controller;


use app\common\AppCode;
use app\common\Functions;
use app\prism\BaseController;
use prism\Model;
use prism\Response;

class UserDB extends BaseController {
    /**
     * @param $db_link_id
     * @param $db_name
     * @param $tbl_name
     *
     * @return int | array
     * @desc 库名和表名都不存在查询所有库名并返回库名，库名存在表名不存在的时候查询该库下的所有的表名并返回表名，两者都存在的时候返回表的字段
     */
    public function getUserDB($db_link_id, $db_name, $table_name) {
        $data   = [];
        $dbLink = Model::load('sqlite')->table('dblink');

        $dbLinkInstance = $dbLink->where("db_id = ?", [$db_link_id])->select();
        if (!$dbLinkInstance) {
            return AppCode::DB_LINK_INSTANCE_INEXISTED;
        }
        $dbType = $dbLinkInstance["db_type"];
//        $dataSource = Config::get("data_source")[$this->dbTypeMap[$dbLinkInstance["db_type"]]];
        $linkInfo = json_decode($dbLinkInstance['link_info'], true);

        //获取用户数据库连接实例
        $linkInfo['password'] = Functions::encrypt($linkInfo['password'],'D', $this->encryptStr);
        $userDBInstance       = Model::load(strtolower($dbType), $linkInfo, 0);

        if ($userDBInstance->getConnection() !== null) {
            if (empty($db_name) || !isset($db_name)) {
                $sql = 'show databases;';
                $dbs = $userDBInstance->query($sql);
                if (!empty($dbs)) {
                    foreach ($dbs as $db) {
                        $data[] = [
                            'db_name' => $db["Database"],
                            'tables'  => null,
                        ];
                    }
                }
            } else if ((empty($table_name) || !isset($table_name)) && (!empty($db_name))) {
                $tables = null;
                $sql    = 'show tables from ' . $db_name;
                $tbls   = $userDBInstance->query($sql);
                if (!empty($tbls)) {
                    foreach ($tbls as $tbl) {
                        $tables[] = [
                            "table_name"      => $tbl['Tables_in_prism'],
                            "table_structure" => null,
                        ];
                    }
                    $data[0]['db_name'] = $db_name;
                    $data[0]['tables']  = $tables;
                }
            } else {
                $tableStructure = null;
                $sql            = 'describe ' . $db_name . "." . $table_name;
                $tblStructures  = $userDBInstance->query($sql);
                if (!empty($tblStructures)) {
                    foreach ($tblStructures as $tblStructure) {
                        $tableStructure[] = [
                            "field" => $tblStructure['Field'],
                            "type"  => $tblStructure['Type'],
                        ];
                    }
                    $data[0]['db_name'] = $db_name;
                    $data[0]['tables']  = [
                        "table_name"      => $table_name,
                        "table_structure" => $tableStructure,
                    ];
                }
            }
        } else {
            return AppCode::DB_LINK_CONNECT_FAILED;
        }
        $this->result['data'] = $data;

        return $this->result;
    }
}