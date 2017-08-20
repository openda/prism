<?php
/**
 * Created by PhpStorm.
 * User: wangxk1991@gmail.com
 * Date: 2017/5/25
 * Time: 下午10:02
 * Desc: 数据库链接资源
 */

namespace app\prism\controller;


use app\common\AppCode;
use app\common\Functions;
use app\prism\BaseController;
use prism\Config;
use prism\Model;
use prism\Response;
use prism\Session;

class DBLink extends BaseController {
    /**
     * @param $db_type
     *
     * @return array|int
     * @desc 获取数据库连接模板
     */
    public function getDBLink() {
        //获取session中的用户信息
        $userID  = Session::get('user_info')['user_id'];
        $dbLink  = Model::load('sqlite')->table('dblink');
        $dbLinks = $dbLink->where('user_id = ? and status =?', array(trim($userID), 1))->select("db_type , db_id");

        if (!empty($dbLinks)) {
            if (count($dbLinks) == 1) {
                $this->result['data'][] = array('db_type' => $dbLinks['db_type'], 'db_id' => $dbLinks['db_id']);
            } else {
                foreach ($dbLinks as $dbLink) {
                    $this->result['data'][] = array('db_type' => $dbLink['db_type'], 'db_id' => $dbLink['db_id']);
                }
            }


        }

        return $this->result;
    }

/**
 * @param $db_type
 * @param $link_info
 * @param $brief
 *
 * @return array|int
 * @desc 添加数据库链接实例
 */
public
function addDBLink($db_type, $link_info) {
    $methodMap   = [
        "mysql"  => "connectMysql",
        "pgsql"  => "connectPgsql",
        "sqlite" => "connectSqlite",
    ];
    $dataSources = Config::get("data_source");
    if (!array_key_exists(strtolower($db_type), $dataSources)) {
        return AppCode::DATA_SOURCE_INEXISTED;
    }
    $dataSource = $dataSources[$db_type];

    $linkInfo = json_decode($link_info, true);
    //TODO 判断是数据库连接实例连接数据是否成功
    $connect = call_user_func_array([$this, $methodMap[$db_type]], [$dataSource, $linkInfo]);
    if ($connect) {
        return $connect;
    }

    //获取session中的用户信息
    $userInfo = Session::get('user_info');

    $now = date("Y-m-d H:i:s");
    //对用户数据库连接密码进行加密

    $linkInfo['password'] = Functions::encrypt($linkInfo['password'], 'E', $this->encryptStr);
    $dbLink               = Model::load('sqlite')->table('dblink');
    $data['db_id']        = Functions::GenIDS(3, $db_type);
    $data['user_id']      = $userInfo['user_id'];
    $data['db_type']      = $db_type;
    $data['link_info']    = json_encode($linkInfo, true);
    $data['create_time']  = $now;
    $data['update_time']  = $now;
    $data['status']       = 1;
    if (!$dbLink->save($data)) {
        return AppCode::DB_LINK_SAVE_FAILED;
    }

    return $this->result;
}

/**
 * @param $db_type
 * @param $link_info
 *
 * @return array|int|mixed
 * @desc 测试数据库连接实例是否可用
 */
public
function testDBLink($db_type, $link_info) {
    $methodMap   = [
        "mysql"  => "connectMysql",
        "pgsql"  => "connectPgsql",
        "sqlite" => "connectSqlite",
    ];
    $dataSources = Config::get("data_source");
    if (!array_key_exists(strtolower($db_type), $dataSources)) {
        return AppCode::DATA_SOURCE_INEXISTED;
    }
    $dataSource = $dataSources[$db_type];

    $linkInfo = json_decode($link_info, true);
    //TODO 判断是数据库连接实例连接数据是否成功
    $connect = call_user_func($methodMap[$db_type], $dataSource, $linkInfo);

    if ($connect) {
        return $connect;
    }

    return $this->result;
}

/**
 * @param $dataSource
 * @param $linkInfo
 *
 * @desc 检查mysql实例是否正确
 */
private
function connectMysql($dataSource, $linkInfo) {
    $errMap = [
        'host'   => AppCode::ERR_DBLINK_PARAM_HOST,
        'port'   => AppCode::ERR_DBLINK_PARAM_PORT,
        'dbname' => AppCode::ERR_DBLINK_PARAM_DBNAME,
        'user'   => AppCode::ERR_DBLINK_PARAM_USER,
    ];
    foreach ($dataSource['link_info'] as $infos) {
        if (!(isset($infos[3]) && $infos[3] == 1)) {
            if (Functions::validate($linkInfo[$infos[0]], $infos[2]) > 0) {
                return $errMap[$infos[0]];
            }
        }
    }
    if (Model::load('mysql', $linkInfo, 0)->getConnection() == null) {
        return AppCode::DB_LINK_CONNECT_FAILED;
    }

    return 0;
}

/**
 * @param $dataSource
 * @param $linkInfo
 *
 * @desc 检查pgsql实例是否正确
 */
private
function connectPgsql($dataSource, $linkInfo) {
    $errMap = [
        'host'   => AppCode::ERR_DBLINK_PARAM_HOST,
        'port'   => AppCode::ERR_DBLINK_PARAM_PORT,
        'dbname' => AppCode::ERR_DBLINK_PARAM_DBNAME,
        'user'   => AppCode::ERR_DBLINK_PARAM_USER,
    ];
    foreach ($dataSource['link_info'] as $infos) {
        if (!Functions::validate($linkInfo[$infos[0]], $infos[2]) > 0) {
            return $errMap[$infos[0]];
        }
    }
    if (Model::load('pgsql', $linkInfo, 0)->getConnection() == null) {
        return AppCode::DB_LINK_CONNECT_FAILED;
    }

    return 0;
}

/**
 * @param $dataSource
 * @param $linkInfo
 *
 * @desc 检查sqlite实例是否正确
 */
private
function connectSqlite($dataSource, $linkInfo) {
    $errMap = [
        'host'   => AppCode::ERR_DBLINK_PARAM_HOST,
        'port'   => AppCode::ERR_DBLINK_PARAM_PORT,
        'dbname' => AppCode::ERR_DBLINK_PARAM_DBNAME,
        'user'   => AppCode::ERR_DBLINK_PARAM_USER,
    ];
    foreach ($dataSource['link_info'] as $infos) {
        if (!Functions::validate($linkInfo[$infos[0]], $infos[2]) > 0) {
            return $errMap[$infos[0]];
        }
    }
    if (Model::load('sqlite', $linkInfo, 0)->getConnection() == null) {
        return AppCode::DB_LINK_CONNECT_FAILED;
    }

    return 0;
}
}