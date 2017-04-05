<?php
/**
 * Created by PhpStorm.
 * User: wangxiaokang@iwaimai.baidu.com
 * Date: 17/3/29
 * Time: 下午3:49
 * Desc:
 */

namespace prism\core\exception;

class ClassNotFoundException extends \RuntimeException {
    protected $class;

    public function __construct($message, $class = '') {
        $this->message = $message;
        $this->class   = $class;
    }

    /**
     * 获取类名
     * @access public
     * @return string
     */
    public function getClass() {
        return $this->class;
    }
}