<?php
/**
 * Created by PhpStorm.
 * User: wangxk1991@gmail.com
 * Date: 17/4/11
 * Time: 下午9:13
 * Desc: 数据库类基础接口
 */

namespace prism\orm;


interface BaseModel {
    /**
     * @return mixed
     * 数据库连接
     */
    public function connect($link);

    /**
     * @return mixed
     * 数据库查询
     */
    public function select($fileds = '*');

    /**
     * @return mixed
     * 添加
     */
    public function add($data = []);

    /**
     * @return mixed
     * 更新
     */
    public function update($field = []);

    /**
     * @return mixed
     * 删除
     */
    public function delete($param = []);

    /**
     * @return mixed
     * 执行sql语句
     */
    public function execute();

    public function query();

    /**
     * @return mixed
     * 获取数据结构(数据库就是数据表的结构，url就是json结构)
     */
    public function structure($tbl);
}