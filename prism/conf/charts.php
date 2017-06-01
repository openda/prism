<?php
/**
 * Created by PhpStorm.
 * User: wangxk1991@gmail.com
 * Date: 2017/5/29
 * Time: 下午8:37
 * Desc: 图表模板配置信息
 */
return [
    //配置原则：['连接关键字','关键字对外显示','连接值的数据类型']
    'histogram' => [
        "name"       => "柱状图",
        "chart_info" => []
    ],
    'piechart'  => [
        'name'       => "饼状图",
        'chart_info' => [],
    ],
    'linechart' => [
        'link_info' => "折线图",
        'link_sql'  => [],
    ],
    'table'     => [
        'link_info' => "表格",
        'link_sql'  => [],
    ]
];