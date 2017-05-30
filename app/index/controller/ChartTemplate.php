<?php
/**
 * Created by PhpStorm.
 * User: wangxk1991@gmail.com
 * Date: 2017/5/29
 * Time: 下午6:06
 * Desc: 图表模板（虚拟资源）
 */

namespace app\index;


use prism\Config;

class ChartTemplate extends BaseController {

    public function getChartTemplate($chart_type) {
        $chartsInfos = Config::get("charts_info");
        $charts      = [];
        $chartInfo   = [];

        if (empty($chart_type)) {
            foreach ($chartsInfos as $key => $chartsInfo) {
                $charts[]    = ["name" => $chartsInfo, "type" => $key];
                $chartInfo[] = $chartsInfo;
            }
        } else {
            $charts[]    = ["name" => $chartsInfos[$chart_type]["name"], "type" => $chart_type];
            $chartInfo[] = $chartsInfos["chart_info"];
        }
        $this->result['data'] = ["charts" => $charts, "chart_info" => $chartInfo];

        return $this->result;
    }
}