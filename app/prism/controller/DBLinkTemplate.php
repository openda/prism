<?php
/**
 * Created by PhpStorm.
 * User: wangxk1991@gmail.com
 * Date: 2017/8/16
 * Time: 下午10:34
 * Desc: 数据库连接模板（虚拟资源）
 */

namespace app\prism\controller;


use app\common\AppCode;
use app\prism\BaseController;
use prism\Config;

class DBLinkTemplate extends BaseController {
    /**
     * @param $db_type
     *
     * @return array|int
     * @desc 获取数据库连接模板
     */
    public function getDBLinkTemplate($db_type) {
        $dataSources = Config::get("data_source");

        if (!array_key_exists(strtolower($db_type), $dataSources)) {
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