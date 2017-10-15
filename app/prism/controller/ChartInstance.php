<?php
/**
 * Created by PhpStorm.
 * User"=> wangxk1991@gmail.com
 * Date"=> 2017/5/29
 * Time"=> 下午9"=>20
 * Desc"=> 图表实例资源（虚拟资源）
 */

namespace app\prism\controller;


use const app\common\APP_MSG;
use app\common\AppCode;
use app\common\Functions;
use app\prism\BaseController;
use prism\Model;
use prism\Response;

class ChartInstance extends BaseController {
    public function getChartInstance($chart_info) {
        $chartInfos = json_decode($chart_info, true);
//        $chartInfos = [
//            //目前只支持单库操作
//            "dblink_id"   => "DB_00067221",
//            "expressions" => ["user_name", "real_name"],//列名或者带有函数的列
//            "from"        => ["cy_admin"],//表名
//            "where"       => [
//                "clause_1" => [
//                    "left"     => [
//                        "expressions" => ["id"] //列名或者带有函数的列
//                    ],
//                    "operator" => "=",//运算符：["=","!=","<>",">","<",">=","<=","!<","!>","in",","not-in","like","not-like","between","not-between","is","is-not"]
//                    "right"    => [
//                        "type"        => "expressions",//expressions:代表单列或者多列或者具体数值等；subquery：子查询；
//                        "logic"       => "",//逻辑运算符all，any，exists，not exists，没有则该项不存在
//                        "expressions" => [//如果type是expressions，则该值为数组；如果是subquery，则该值是chart_map的数据结构
//                                          7
//                        ]
//                    ]
//                ],
//                "relation" => [
//                    //运算符：and,or,not；数组元素必须按照逻辑顺序排好序。
//                    //                    "logic_1" => ["clause_1", "clause_2", "and"],
//                    //                    "logic_2" => ["clause_2", "logic_1", "or"],
//                    //                    "logic_3" => ["logic_2", "not"],
//                    //                    "logic_4"=>["clause_1"]
//
//                    //后面可以有多个，如果只有一个分句，则这个数组可以为空或者为logic_4形式一样
//                ]
//            ],
//                        "group"       => [],//注意：某些数据库支持group by all语法，如果有all关键字，则all关键字包含在expression中
//                        "having"      => [
//                            "clause_1" => [
//                                "left"     => [
//                                    "expressions" => ["a", "b", "c"] //列名或者带有函数的列
//                                ],
//                                "operator" => "",//运算符：["=","!=","<>",">","<",">=","<=","!<","!>","in",","not-in","like","not-like","between","not-between","is","is-not"]
//                                "right"    => [
//                                    "type"        => "expressions",//expressions:代表单列或者多列或者具体数值等；subquery：子查询；
//                                    "logic"       => "",//逻辑运算符all，any，exists，not exists，没有则该项不存在
//                                    "expressions" => [//如果type是columns，则该值为数组；如果是subquery，则该值是chart_map的数据结构
//
//                                    ]
//                                ]
//                            ],
//                            "relation" => [
//                                //运算符：and,or,not；数组元素必须按照逻辑顺序排好序。
//                                "logic_1" => ["clause_1", "clause_2", "and"],
//                                "logic_2" => ["clause_2", "logic_1", "or"],
//                                "logic_3" => ["logic_2", "not"],
//                                "logic_4"=>["clause_1"]
//                                //后面可以有多个，如果只有一个分句，则这个数组可以为空或者为logic_4形式一样
//                            ]
//                        ],
//            "order"       => [
//                ["id", "asc"],
//            ]
//        ];
        if (empty($chartInfos['expressions']) || empty($chartInfos['from'])) {
            return AppCode::ERR_USER_SQL_CLAUSE;
        }
        $sql      = $this->makeSql($chartInfos);
        $dbLink   = Model::load('sqlite')->table('dblink');
        $linkInfo = $dbLink->where('db_id = ? and status =?', array(trim($chartInfos['dblink_id']), 1))->select("db_type , db_id , link_info");
        if (!empty($linkInfo)) {
            $jsonLinkInfo = json_decode($linkInfo['link_info'], true);
            $jsonLinkInfo['password'] = Functions::encrypt($jsonLinkInfo['password'], 'D', $this->encryptStr);
            $DBL                      = Model::load($linkInfo['db_type'], $jsonLinkInfo, true);
            if ($DBL->getConnection() == null) {
                return AppCode::DB_LINK_CONNECT_FAILED;
            } else {
                $this->result['data'] = $DBL->query($sql);
            }
        }

        return $this->result;
    }

    private function makeSql($chartInfos) {
        $sql     = "SELECT ";
        $clauses = ["expressions", "from", "where", "group", "having", "order"];
        foreach ($clauses as $clause) {
            if (array_key_exists($clause, $chartInfos) && !empty($chartInfos[$clause])) {
                $sql .= call_user_func_array([$this, $clause], [$chartInfos[$clause]]);
            }
        }

        return $sql;
    }

    private function expressions($expressions = ["*"]) {
        if (!empty($expressions) && is_array($expressions)) {
            return join(',', $expressions);
        }

        return "";
    }

    private function from($froms) {
        if (!empty($froms) && is_array($froms)) {
            return " FROM " . join(',', $froms);
        }

        return "";
    }

    private function where($wheres) {
        $relations = [];
        $clauses   = [];
        if (!empty($wheres) && is_array($wheres)) {
            foreach ($wheres as $key => $where) {
                if (strpos($key, "clause_") !== false) {
                    $tmpSql[] = "(" . join(',', $where['left']['expressions']) . ")";
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
                    if ($where['right']['type'] == "expressions") {
                        if (in_array(strtolower($where['right']['logic']), ["all", "any", "exists"])) {
                            $tmpSql[] = $where['right']['logic'];
                        }
                        $tmpSql[] = "(" . join(",", $where['right']['expressions']) . ")";
                    }
                    if ($where['right']['type'] == "subquery") {
                        $tmpSql[] = $this->makeSql($where['right']['expressions']);
                    }
                    $clauses[$key] = join(" ", $tmpSql);
                }
            }
            if (!empty($wheres['relation'])) {
                foreach ($wheres["relation"] as $key => $relation) {
                    if (count($relation) == 3) {
                        if (strpos($relation[0], "clause_") !== false && strpos($relation[1], "clause_") !== false) {
                            $relations[$key] = $clauses[$relations[0]] . " $relation[2] " . $clauses[$relation[1]];
                        } else if ((strpos($relation[0], "clause_") !== false && strpos($relation[1], "logic_") !== false) && !empty($relations[$relation[1]])) {
                            $relations[$key] = $clauses[$relations[0]] . " $relation[2] " . $relations[$relation[1]];
                        } else if ((strpos($relation[1], "clause_") !== false && strpos($relation[0], "logic_") !== false) && !empty($relations[$relation[0]])) {
                            $relations[$key] = $clauses[$relations[1]] . " $relation[2] " . $relations[$relation[0]];
                        } else if ((strpos($relation[1], "logic_") !== false && strpos($relation[0], "logic_") !== false) &&
                            !empty($relations[$relation[0]]) && !empty($relations[$relation[1]])) {
                            $relations[$key] = $relations[$relations[1]] . " $relation[2] " . $relations[$relation[0]];
                        } else {
                            Response::sendError(AppCode::ERR_USER_SQL_WHERE_RELATION, APP_MSG[AppCode::ERR_USER_SQL_WHERE_RELATION]);
                        }
                    } elseif (count($relation) == 2) {
                        if (strpos($relation[0], "clause_") !== false) {
                            $relations[$key] = " $relation[1] (" . $clauses[$relation[0]] . ")";
                        }
                        if (strpos($relation[0], "logic_") !== false && !empty($relations[$relation[0]])) {
                            $relations[$key] = " $relation[1] (" . $relations[$relation[0]] . ")";
                        }
                    } else {
                        $relations[$key] = $clauses[$relation[0]];
                    }
                }
            } else {
                $relations = $clauses;
            }
        }

        return " WHERE " . join(" ", $relations);
    }

    private function group($groups) {
        if (!empty($groups) && is_array($groups)) {
            return " GROUP BY " . join(",", $groups);
        }

        return "";
    }

    private function having($havings) {
        $relations = [];
        $clauses   = [];
        if (!empty($wheres) && is_array($wheres)) {
            foreach ($wheres as $key => $where) {
                if (strpos($key, "clause_") !== false) {
                    $tmpSql[] = "(" . join(',', $where['left']['expressions']) . ")";
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
                    if ($where['right']['type'] == "expressions") {
                        if (in_array(strtolower($where['right']['logic']), ["all", "any", "exists"])) {
                            $tmpSql[] = $where['right']['logic'];
                        }
                        $tmpSql[] = "(" . join(",", $where['right']['expressions']) . ")";
                    }
                    if ($where['right']['type'] == "subquery") {
                        $tmpSql[] = $this->makeSql($where['right']['expressions']);
                    }
                    $clauses[$key] = join(" ", $tmpSql);
                }
            }
            if (!empty($wheres['relation'])) {
                foreach ($wheres["relation"] as $key => $relation) {
                    if (count($relation) == 3) {
                        if (strpos($relation[0], "clause_") !== false && strpos($relation[1], "clause_") !== false) {
                            $relations[$key] = $clauses[$relations[0]] . " $relation[2] " . $clauses[$relation[1]];
                        } else if ((strpos($relation[0], "clause_") !== false && strpos($relation[1], "logic_") !== false) && !empty($relations[$relation[1]])) {
                            $relations[$key] = $clauses[$relations[0]] . " $relation[2] " . $relations[$relation[1]];
                        } else if ((strpos($relation[1], "clause_") !== false && strpos($relation[0], "logic_") !== false) && !empty($relations[$relation[0]])) {
                            $relations[$key] = $clauses[$relations[1]] . " $relation[2] " . $relations[$relation[0]];
                        } else if ((strpos($relation[1], "logic_") !== false && strpos($relation[0], "logic_") !== false) &&
                            !empty($relations[$relation[0]]) && !empty($relations[$relation[1]])) {
                            $relations[$key] = $relations[$relations[1]] . " $relation[2] " . $relations[$relation[0]];
                        } else {
                            Response::sendError(AppCode::ERR_USER_SQL_CLAUSE, APP_MSG[AppCode::ERR_USER_SQL_CLAUSE]);
                        }
                    } elseif (count($relation) == 2) {
                        if (strpos($relation[0], "clause_") !== false) {
                            $relations[$key] = " $relation[1] (" . $clauses[$relation[0]] . ")";
                        }
                        if (strpos($relation[0], "logic_") !== false && !empty($relations[$relation[0]])) {
                            $relations[$key] = " $relation[1] (" . $relations[$relation[0]] . ")";
                        }
                    } else {
                        $relations[$key] = $clauses[$relation[0]];
                    }
                }
            } else {
                $relations = $clauses;
            }
        }

        return " HAVING " . join(" ", $relations);
    }

    private function order($orders) {
        if (!empty($orders) && is_array($orders)) {
            $sqlTmp = [];
            foreach ($orders as $order) {
                $sqlTmp[] = "$order[0] $order[1]";
            }

            return " ORDER BY " . join(",", $sqlTmp);
        }

        return "";
    }
}