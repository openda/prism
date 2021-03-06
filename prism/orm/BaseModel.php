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
     * @param $link
     * @param $exception 是否抛出异常
     *
     * @return mixed
     * 数据库连接
     */
    public function connect($link, $exception);

    /**
     * @return mixed
     * 数据库查询
     */
    public function select($fileds = '*');

    /**
     * @return mixed
     * 数据库查询
     */
    public function query($sql = "");

    /**
     * @return mixed
     * 添加
     */
    public function save($datas = []);

    /**
     * @return mixed
     * 更新
     */
    public function update($fields = []);

    /**
     * @return mixed
     * 删除
     */
    public function delete($params = []);

    /**
     * @return mixed
     * 获取数据结构(数据库就是数据表的结构，url就是json结构)
     */
    public function structure($tbl);

    /**
     * @param $dsn
     * @param $user
     * @param $pwd
     * @param $other
     *
     * @return mixed
     * @测试数据库实例是否能连接
     */
//    public function testConnection($dsn, $user, $pwd, $other);

    /**
     * @return mixed
     * @测试数据库实例是否能连接
     */
    public function getConnection();
}