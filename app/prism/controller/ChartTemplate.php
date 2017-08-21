<?php
/**
 * Created by PhpStorm.
 * User: wangxk1991@gmail.com
 * Date: 2017/5/29
 * Time: 下午6:06
 * Desc: 图表模板（虚拟资源）
 */

namespace app\prism\controller;


use app\prism\BaseController;
use prism\Config;

class ChartTemplate extends BaseController {

    public function getChartTemplate($chart_type) {
        $chartsInfos = Config::get("charts_info");
        $charts      = [];
        $chartInfo   = [];

        if (empty($chart_type)) {
            foreach ($chartsInfos['charts'] as $key => $chartsInfo) {
                $charts[]    = ["name" => $chartsInfo['name'], "type" => $key];
            }
            $chartInfo['common'] = $chartsInfos['common'];
            $chartInfo['detail'] = $chartsInfos['detail'];
            $chartInfo['series'] = $chartsInfos['series'];
        } else {
            $charts[] = ["name" => $chartsInfos['charts'][$chart_type]["name"], "type" => $chart_type];
            if (!isset($chartsInfos['charts'][$chart_type]['common']) || $chartsInfos['charts'][$chart_type]['common'] == 1) {
                $chartInfo['common'] = $chartsInfos["common"];
            }

            if (!isset($chartsInfos['charts'][$chart_type]['detail']) || $chartsInfos['charts'][$chart_type]['detail'] == 1) {
                $chartInfo['detail'] = $chartsInfos["detail"];
            }

            if (!isset($chartsInfos['charts'][$chart_type]['series']) || $chartsInfos['charts'][$chart_type]['series'] == 1) {
                $chartInfo['series'] = $chartsInfos["series"];
            }
        }
        $this->result['data'] = ["charts" => $charts, "chart_info" => $chartInfo];

        return $this->result;
    }
}