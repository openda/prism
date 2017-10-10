<?php
/**
 * Created by PhpStorm.
 * User: wangxk1991@gmail.com
 * Date: 17/3/29
 * Time: 上午10:57
 * Desc: 控制器基类
 */

namespace prism;


use const app\common\APP_MSG;
use app\common\AppCode;

class Controller {

    /**
     * @var \prism\Request Request实例
     */
    protected $request;

    protected $result = [];
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
        $this->result['code'] = AppCode::SUCCESS;
        $this->result['msg']  = APP_MSG[AppCode::SUCCESS];
        // 控制器初始化
        $this->_initialize();
    }

    // 初始化
    public function _initialize() { }

    public function __destruct() {
        // TODO: Implement __destruct() method.
        if (!array_key_exists('code', $this->result) || !array_key_exists('msg', $this->result)) {
            $this->result         = [];
            $this->result['code'] = AppCode::ERR_RETURN_RESULT_STRUCTER;
            $this->result['msg']  = APP_MSG[AppCode::ERR_RETURN_RESULT_STRUCTER];
        }
    }

}
