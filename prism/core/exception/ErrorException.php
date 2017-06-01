<?php

/**
 * Created by PhpStorm.
 * User: wangxk1991@gmail.com
 * Date: 17/3/29
 * Time: 下午2:33
 * Desc: Prism错误异常
 */

namespace prism\core\exception;

use prism\Exception;

class ErrorException extends Exception {
    /**
     * @var integer $errLevel
     * 保存错误级别
     */
    protected $errLevel;

    public function __construct($errNo, $errMsg = '', $file, $line, array $context = []) {
        $this->errNo  = $errNo;
        $this->errMsg = $errMsg;
        $this->line   = $line;
        $this->file   = $file;

        empty($context) || $this->setData('Error Context', $context);
    }

    /**
     * @return int|string 错误级别
     */
    public function getErrLevel() {
        return $this->errLevel;
    }
}