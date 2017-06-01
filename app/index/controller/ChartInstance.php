<?php
/**
 * Created by PhpStorm.
 * User: wangxk1991@gmail.com
 * Date: 2017/5/29
 * Time: 下午9:20
 * Desc:
 */

namespace app\index\controller;


use app\index\BaseController;

class ChartInstance extends BaseController {
    public function getChartInstance($db_link_id, $chart_type, $chart_info) {
        $chartInstanceInfo = [];

        $this->result['chart_instance_info'] = $chartInstanceInfo;

        return $this->result;
    }
}