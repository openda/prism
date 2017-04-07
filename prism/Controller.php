<?php
/**
 * Created by PhpStorm.
 * User: wangxk1991@gmail.com
 * Date: 17/3/29
 * Time: 上午10:57
 * Desc: 控制器基类
 */

namespace prism;


class Controller {

    /**
     * @var \prism\Request Request实例
     */
    protected $request;

    /**
     * 前置操作方法列表
     * @var array $beforeActionList
     * @access protected
     */
    protected $beforeActionList = [];

    /**
     * 架构函数
     *
     * @param Request $request Request对象
     *
     * @access public
     */
    public function __construct() {
        // 控制器初始化
        $this->_initialize();
    }

    // 初始化
    protected function _initialize() {
    }

}
