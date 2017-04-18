<?php
/**
 * Created by PhpStorm.
 * User: wangxk1991@gmail.com
 * Date: 17/3/29
 * Time: 下午9:31
 * Desc:
 */

namespace prism;

use const prism\common\PRISM_MSG;
use prism\common\PrismCode;

class  Response {
    private static $_output = []; // 随时打印所用

    private static $_content;// 输出结果数据

    const HTTP_VERSION = "HTTP/1.1";

    /**
     * @param int   $code
     * @param       $msg
     * @param array $data
     *
     * 返回结果（专门用于输出控制器结果）
     */
    public static function send($content = []) {
//        if (count(self::$_content) == 1 && is_array(self::$_content)) {
//            self::$_content = self::$_content[0];
//        }
//        $content = [
//            'code' => $data['code'],
//            'msg'  => $data['mag'],
//            'data' => empty($data['data']) ? self::$_content : $data
//        ];
        // 如果又随时打印数据，则不打印结果数据
        if (empty(self::$_output)) {
            self::output($content);
        }
    }

    public static function sendException($code, $msg, $e = []) {
        $e       = empty($e) ? new \Exception() : $e;
        $content = [
            'code'      => $code,
            'msg'       => $msg,
            'exception' => $e->getMessage(),
            'file'      => $e->getFile(),
            'line'      => $e->getLine(),
            'trace'     => $e->getTrace(),
        ];
        self::output($content);
        exit();
    }

    public static function sendError($code = PrismCode::SUCCESS, $msg = PRISM_MSG[PrismCode::SUCCESS]) {
        $content = [
            'code' => $code,
            'msg'  => $msg,
        ];
        self::output($content);
        exit();
    }

    /**
     * @param array $data
     *
     * 方便测试用的
     */
    public static function outputPage($data = [], $exit = 0) {
        $content         = ['data' => $data];
        self::$_output[] = $data;
        //输出结果
        header("Content-Type: application/json");
        echo self::encodeJson($content);
        if ($exit) {
            exit();
        }
    }

    private
    static function output($content = []) {
        //输出结果
        $content_type = 'application/json';
        //        $content_type = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : 'application/json';
        if (strpos($content_type, 'application/json') !== false) {
            header("Content-Type: application/json");
            echo self::encodeJson($content);
        } else if (strpos($content_type, 'application/xml') !== false) {
            header("Content-Type: application/xml");
            echo self::encodeXml($content);
        } else {
            header("Content-Type: text/html");
            echo self::encodeHtml($content);
        }
    }

//json格式
    private static function encodeJson($responseData) {
        return json_encode($responseData);
    }

//xml格式
    private static function encodeXml($responseData) {
        $xml = new \SimpleXMLElement('<?xml version="1.0"?><rest></rest>');
        foreach ($responseData as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $k => $v) {
                    $xml->addChild($k, $v);
                }
            } else {
                $xml->addChild($key, $value);
            }
        }

        return $xml->asXML();
    }

//html格式
    private static function encodeHtml($responseData) {
        $html = "<table border='1'>";
        foreach ($responseData as $key => $value) {
            $html .= "<tr>";
            if (is_array($value)) {
                foreach ($value as $k => $v) {
                    $html .= "<td>" . $k . "</td><td>" . $v . "</td>";
                }
            } else {
                $html .= "<td>" . $key . "</td><td>" . $value . "</td>";
            }
            $html .= "</tr>";
        }
        $html .= "</table>";

        return $html;
    }

    /**
     * @return array
     */
    public static function getOutput(): array {
        return self::$_output;
    }

    /**
     * @param array $output
     */
    public static function setOutput(array $output, $clear = 0) {
        if ($clear == 1) {
            self::$_output = [];
        }
        self::$_output[] = $output;
    }

    /**
     * @return array
     */
    public static function getContent(): array {
        return self::$_content;
    }

    /**
     * @param array $content
     */
    public static function setContent($content, $clear = 0) {
        if ($clear == 1) {
            self::$_content = [];
        }
        self::$_content[] = $content;
    }
}