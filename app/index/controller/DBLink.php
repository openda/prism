<?php
/**
 * Created by PhpStorm.
 * User: wangxk1991@gmail.com
 * Date: 2017/5/25
 * Time: 下午10:02
 * Desc: 数据库链接资源
 */

namespace app\index\controller;


use app\common\AppCode;
use prism\Config;
use prism\Controller;
use prism\Model;

class DBLink extends Controller {
    public function getDBLink($db_type) {
//        $user = Model::load('sqlite')->table('dblink');
//        //查询该类型的数据库连接方式是否存在
//        if ($user->where('db_type = ?', array(trim($db_type)))->select()) {
//            return AppCode::APP_USER_EXISTED;
//        }
        $db_type     = strtolower($db_type);
        $dataSources = Config::get("data_source");

        if (!array_key_exists($db_type, $dataSources)) {
            return AppCode::DATA_SOURCE_INEXISTED;
        }
        foreach ($dataSources[$db_type]['link_info'] as $dataSource) {
            $tmp['key']   = $dataSource[0];
            $tmp['value'] = $dataSource[2];
            $tmp['desc']  = $dataSource[1];

            $this->result['data']['template'][] = $tmp;
        }
        $this->result['data']['type'] = $db_type;

        return $this->result;
    }

    public function addDBLink($db_type, $link_info) {
//        $user = Model::load('sqlite')->table('dblink');
//        //查询该类型的数据库连接方式是否存在
//        if ($user->where('db_type = ?', array(trim($db_type)))->select()) {
//            return AppCode::APP_USER_EXISTED;
//        }
        $db_type     = strtolower($db_type);
        $dataSources = Config::get("data_source");
        if (!array_key_exists($db_type, $dataSources)) {
            return AppCode::DATA_SOURCE_INEXISTED;
        }
        foreach ($dataSources[$db_type]['link_info'] as $dataSource) {
            $tmp['key']   = $dataSource[0];
            $tmp['value'] = $dataSource[2];
            $tmp['desc']  = $dataSource[1];

            $this->result['data']['template'][] = $tmp;
        }
        $this->result['data']['type'] = $db_type;

        return $this->result;
    }
}