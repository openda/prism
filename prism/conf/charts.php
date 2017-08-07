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
    'common' => [
        ['name' => 'title', 'desc' => '图标标题', 'type' => 'string'],
        ['name' => 'subtitle', 'desc' => '子标题', 'type' => 'string'],
        ['name' => 'remark', 'desc' => '图表备注说明', 'type' => 'string'],
    ],
    'detail' => [
        'xAxis' => [
            ['name' => 'name', 'desc' => 'x轴单位名称'],
            ['name' => 'scale', 'desc' => '坐标轴是否弹性计算间隔', 'value' => 'true|false', 'type' => 'boolean'],
            ['name' => 'type', 'desc' => 'X轴类型', 'type' => 'enum', 'value' => 'value|category|time'],
            ['name' => 'data', 'desc' => '类目名称', 'type' => 'array']//仅在type为category时有效
        ],
        'yAxis' => [
            ['name' => 'name', 'desc' => 'x轴单位名称'],
            ['name' => 'scale', 'desc' => '坐标轴是否弹性计算间隔', 'value' => 'true|false', 'type' => 'boolean'],
            ['name' => 'type', 'desc' => 'X轴类型', 'type' => 'enum', 'value' => 'value|category|time'],
            ['name' => 'data', 'desc' => '类目名称', 'type' => 'array']//仅在type为category时有效
        ],
    ],
    'series' => [
        ['name' => 'name', 'desc' => '序列名称', 'type' => 'string'],
        ['name' => 'data', 'desc' => '具体数据', 'type' => 'array']
    ],

    //common，detail，series配置项默认是1
    'charts'=>[
        'histogram' => [
            "name"   => "柱状图",
            "detail" => 1,
            'series' => 1
        ],
        'piechart'  => [
            'name'   => "饼状图",
            "detail" => 0,
            'series' => 1
        ],
        'linechart' => [
            'name' => "折线图",
            "detail"    => 1,
            'series'    => 1
        ],
        'table'     => [
            'name' => "表格",
            "detail"    => 0,
            'series'    => 1
        ]
    ]

];