<?php
/**
 * Created by PhpStorm.
 * User: wangxiaokang@iwaimai.baidu.com
 * Date: 17/3/30
 * Time: 下午4:49
 * Desc:
 */

namespace prism;


class Request {
    private $uri;

    private $input;
    //请求方式
    private $type;

    protected $options;

    protected static $instance;

    public function __construct($options = []) {
        //TODO 增加其它的请求信息存储
//        if (isset($_SERVER['REQUEST_METHOD']) && !strcasecmp($_SERVER['REQUEST_METHOD'], 'POST')) {
//            $this->input = $_POST;
//        } else {
//            $this->input = $_GET;
        // 去除REQUEST数据
//            unset($this->input['REQUEST']);
//        }
        if (!empty($_REQUEST['REQUEST'])) {
            $this->uri = $_REQUEST['REQUEST'];
            unset($_REQUEST['REQUEST']);
        } else {
            $this->uri = "";
        }

        $this->input = $_REQUEST;
        $this->type  = $_SERVER['REQUEST_METHOD'];

    }

    public static function instance($options = []) {
        if (is_null(self::$instance)) {
            self::$instance = new static($options);
        }

        return self::$instance;
    }

    public function getRequestInfo() {
        return ['uri' => $this->uri, 'input' => $this->input, 'type' => $this->type];
    }

    /**
     * @return mixed
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type) {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getUri() {
        return $this->uri;
    }

    /**
     * @param mixed $uri
     */
    public function setUri($uri) {
        $this->uri = $uri;
    }

    /**
     * @return mixed
     */
    public function getInput() {
        return $this->input;
    }

    /**
     * @param mixed $input
     */
    public function setInput($input) {
        $this->input = $input;
    }
}