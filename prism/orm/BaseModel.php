<?php
/**
 * Created by PhpStorm.
 * User: wangxiaokang@iwaimai.baidu.com
 * Date: 17/4/11
 * Time: 下午9:13
 * Desc: 数据库类基础接口
 */

namespace prism\orm;


abstract class BaseModel {
    /**
     * @return mixed
     * 数据库连接
     */
    abstract public function connect();

    /**
     * @return mixed
     * 数据库查询
     */
    abstract public function query();

    /**
     * @return mixed
     * 添加
     */
    abstract public function add();

    /**
     * @return mixed
     * 更新
     */
    abstract public function update();

    /**
     * @return mixed
     * 删除
     */
    abstract public function delete();

    /**
     * @return mixed
     * 执行sql语句
     */
    abstract public function execute();
}