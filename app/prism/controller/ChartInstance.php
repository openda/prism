<?php
/**
 * Created by PhpStorm.
 * User"=> wangxk1991@gmail.com
 * Date"=> 2017/5/29
 * Time"=> 下午9"=>20
 * Desc"=> 图表实例资源（虚拟资源）
 */

namespace app\prism\controller;


use app\common\AppCode;
use app\prism\BaseController;
use prism\Response;

class ChartInstance extends BaseController {
    public function getChartInstance($chart_info) {
        $test['chart_options'] = [
            "title"  => [
                "dataType" => "string",
                "label"    => "标题"
            ],
            "xAxis"  => [
                "type" => [
                    "dataType"  => "enum",
                    "enumArray" => [
                        'value'    => '数值轴',
                        'category' => '类目轴',
                        'time'     => '时间轴'
                    ],
                ],
                "name" => [
                    "dataType" => 'string',
                    "label"    => 'x轴名称'
                ]
            ],
            "yAxis"  => [
                "type" => [
                    "dataType"  => "enum",
                    "enumArray" => [
                        'value'    => '数值轴',
                        'category' => '类目轴',
                        'time'     => '时间轴'
                    ],
                ],
                "name" => [
                    "dataType" => 'string',
                    "label"    => 'y轴名称'
                ]
            ],
            "series" => [
                "dataType"        => 'array',
                "arrayType"       => 'custom',
                "customStructure" => [
                    "name" => [
                        "dataType" => 'string',
                        "label"    => '数据项名称'
                    ],
                    "type" => [
                        "dataType"  => 'enum',
                        "enumArray" => [
                            'line' => '折线图',
                            'bar'  => '柱状/条形图',
                            'pie'  => '饼图'
                        ],
                        "label"     => '数据项图表类型'
                    ],
                    "data" => [
                        "dataType"  => 'userData',
                        "label"     => '数据项对数据列',
                        "chart_map" => [
                            //目前只支持单库操作
                            "dblink_id" => "",
                            "columns"   => ["expression_1", "expression_2"],//列名或者带有函数的列
                            "from"      => ["table_1", "table_2"],//表名
                            "where"     => [
                                "clause_1" => [
                                    "left"     => [
                                        "columns" => ["a", "b", "c"] //列名或者带有函数的列
                                    ],
                                    "operator" => "",//运算符：["=","!=","<>",">","<",">=","<=","!<","!>","in","not","like","between","all","any","exists"]
                                    "right"    => [
                                        "type"    => "columns",//column:代表单列或者多列或者具体数值等；subquery：子查询；
                                        "columns" => [//如果type是columns，则该值为数组；如果是subquery，则该值是chart_map的数据结构

                                        ]
                                    ]
                                ],
                                "relation" => [
                                    //运算符：and,or
                                    ["clause_1", "clause_2", "and"],//后面可以有多个
                                ]
                            ],
                            "group"     => ["expression_1", "expression_2"],
                            "having"    => [
                                "clause_1" => [
                                    "left"     => [
                                        "columns" => ["a", "b", "c"] //列名或者带有函数的列
                                    ],
                                    "operator" => "",//运算符：["=","!=","<>",">","<",">=","<=","!<","!>","in","not","like","between","all","any","exists"]
                                    "right"    => [
                                        "type"    => "columns",//column:代表单列或者多列或者具体数值等；subquery：子查询；
                                        "columns" => [//如果type是columns，则该值为数组；如果是subquery，则该值是chart_map的数据结构

                                        ]
                                    ]
                                ],
                                "relation" => [
                                    //运算符：and,or
                                    ["clause_1", "clause_2", "and"],//后面可以有多个
                                ]
                            ],
                            "order"     => [
                                ["expression_1", "asc"],
                                ["expression_2", "desc"]
                            ]
                        ]
                    ]
                ]
            ],
        ];

        $chartInfos["chart_map"] = [
            //目前只支持单库操作
            "dblink_id"   => "",
            "expressions" => ["expression_1", "expression_2"],//列名或者带有函数的列
            "from"        => ["table_1", "table_2"],//表名
            "where"       => [
                "clause_1" => [
                    "left"     => [
                        "expressions" => ["a", "b", "c"] //列名或者带有函数的列
                    ],
                    "operator" => "",//运算符：["=","!=","<>",">","<",">=","<=","!<","!>","in",","not-in","like","not-like","between","not-between","is","is-not"]
                    "right"    => [
                        "type"        => "expressions",//expressions:代表单列或者多列或者具体数值等；subquery：子查询；
                        "logic"       => "",//逻辑运算符all，any，exists，not exists，没有则该项不存在
                        "expressions" => [//如果type是columns，则该值为数组；如果是subquery，则该值是chart_map的数据结构

                        ]
                    ]
                ],
                "relation" => [
                    //运算符：and,or,not；数组元素必须按照逻辑顺序排好序。
                    "logic_1" => ["clause_1", "clause_2", "and"],
                    "logic_2" => ["clause_2", "logic_1", "or"],
                    "logic_3" => ["logic_2", "not"],
                    //后面可以有多个
                ]
            ],
            "group"       => ["expression_1", "expression_2"],//注意：某些数据库支持group by all语法，如果有all关键字，则all关键字包含在expression中
            "having"      => [
                "clause_1" => [
                    "left"     => [
                        "columns" => ["a", "b", "c"] //列名或者带有函数的列
                    ],
                    "operator" => "",//运算符：["=","!=","<>",">","<",">=","<=","!<","!>","in","not","like","between","all","any","exists"]
                    "right"    => [
                        "type"    => "columns",//column:代表单列或者多列或者具体数值等；subquery：子查询；
                        "columns" => [//如果type是columns，则该值为数组；如果是subquery，则该值是chart_map的数据结构

                        ]
                    ]
                ],
                "relation" => [
                    //运算符：and,or
                    ["clause_1", "clause_2", "and"],//后面可以有多个
                ]
            ],
            "order"       => [
                ["expression_1", "asc"],
                ["expression_2", "desc"]
            ]
        ];
        if (empty($chartInfos['columns']) || empty($chartInfos['from'])) {
            return AppCode::ERR_USER_SQL_CLAUSE;
        }
        $this->makeSql($chartInfos);

        return $this->result;
    }

    private function makeSql($chartInfos) {
        $sql     = "SELECT FROM";
        $clauses = ["columns", "from", "where", "group", "having", "order", 0];
        foreach ($clauses as $clause) {
            if (array_key_exists($chartInfos, $clause) && !empty($chartInfos[$clause])) {
                $sql .= $this->$clause[$chartInfos[$clause]];
            }
        }
    }

    private function columns($columns) {
        if (!empty($columns) && is_array($columns)) {
            return join(',', $columns);
        }

        return "";
    }

    private function from($froms) {
        if (!empty($froms) && is_array($froms)) {
            return join(',', $froms);
        }

        return "";
    }

    private function where($wheres) {
        $relations = [];
        $clauses   = [];
        if (!empty($wheres) && is_array($wheres)) {
            foreach ($wheres as $key => $where) {
                if (strpos($key, "clause_") !== false) {
                    $tmpSql[] = "(" . join(',', $where['left']['columns']) . ")";
                    if (in_array(strtolower($where['operator']), ["is", "is-not"])) {
                        $tmpSql[]      = str_replace("-", " ", $where['operator']) . " NULL";
                        $clauses[$key] = join(" ", $tmpSql);
                        continue;
                    }
                    if (in_array(strtolower($where['operator']), ["=", "!=", "<>", ">", "<", ">=", "<=", "!<", "!>", "in", "like"])) {
                        $tmpSql[] = $where['operator'];
                    }
                    if (in_array(strtolower($where['operator']), ["not-in", "not-like"])) {
                        $tmpSql[] = str_replace("-", " ", $where['operator']);
                    }
                    if ($where['right']['type'] == "columns") {
                        if (in_array(strtolower($where['right']['logic']), ["all", "any", "exists"])) {
                            $tmpSql[] = $where['right']['logic'];
                        }
                        $tmpSql[] = "(" . join(",", $where['right']['expression']) . ")";
                    }
                    if ($where['right']['type'] == "subquery") {
                        $tmpSql[] = $this->makeSql($where['right']['expression']);
                    }
                    $clauses[$key] = join(" ", $tmpSql);
                }
            }
            $whereNums = count($wheres['relation']);
            for ($i = 1; $i < $whereNums + 1; $i++) {
                $relations[''];
            }
            while (count($relations) < count($wheres["relation"])) {
                foreach ($wheres["relation"] as $key => $relation) {
                    if (strpos($relation[0], "clause_") !== false && strpos($relation[1], "clause_") !== false) {
                        $relations[$key] = $clauses[$relations[0]] . " $relation[2] " . $clauses[$relation[1]];
                    } else if ((strpos($relation[0], "clause_") !== false && strpos($relation[1], "logic_") !== false) && !empty($relations[$relation[1]])) {
                        $relations[$key] = $clauses[$relations[0]] . " $relation[2] " . $relations[$relation[1]];
                    } else if ((strpos($relation[1], "clause_") !== false && strpos($relation[0], "logic_") !== false) && !empty($relations[$relation[0]])) {
                        $relations[$key] = $clauses[$relations[1]] . " $relation[2] " . $relations[$relation[0]];
                    } else if ((strpos($relation[1], "logic_") !== false && strpos($relation[0], "logic_") !== false) &&
                        !empty($relations[$relation[0]]) && !empty($relations[$relation[1]])) {
                        $relations[$key] = $relations[$relations[1]] . " $relation[2] " . $relations[$relation[0]];
                    } else if ((strpos($relation[1], "clause_") !== false && strpos($relation[0], "logic_") !== false)) {
                        $relations[$key] = $clauses[$relations[1]] . " $relation[2] " . $relations[$relation[0]];
                    }
                    if (array_key_exists($key, $relations)) {
                        continue;
                    }

                }
            }
        }

        return "";
    }

    private function group($groups) {
        if (!empty($groups) && is_array($groups)) {
            return "group by " . join(",", $groups);
        }

        return "";
    }

    private function having($havings) {

    }

    private function order($orders) {
        if (!empty($orders) && is_array($orders)) {
            $sqlTmp = [];
            foreach ($orders as $order) {
                $sqlTmp[] = "$order[0] $order[1]";
            }

            return "order by " . join(",", $sqlTmp);
        }

        return "";
    }
}