<?php
/**
 * Created by PhpStorm.
 * User: wangxiaokang@iwaimai.baidu.com
 * Date: 17/3/29
 * Time: 下午9:31
 * Desc:
 */

namespace prism;

use const prism\common\ERR_MSG;
use prism\common\ErrCode;

class  Response {
    const HTTP_VERSION = "HTTP/1.1";

    /**
     * @param int   $code
     * @param       $msg
     * @param array $data
     *
     * 返回结果
     */
    public static function send($code = ErrCode::SUCCESS, $msg = ERR_MSG[ErrCode::SUCCESS], $data = []) {
        $content = [
            'err_no'  => $code,
            'err_msg' => $msg,
            'data'    => $data
        ];
        self::output($content);
    }

    public static function sendException($code = ErrCode::SUCCESS, $msg = ERR_MSG[ErrCode::SUCCESS], $e = []) {
        $content = [
            'err_no'  => $code,
            'err_msg' => $msg,
        ];
        !APP_DEBUG ?: $content['exception'] = $e;
        self::output($content);
        exit();
    }

    public static function sendError($code = ErrCode::SUCCESS, $msg = ERR_MSG[ErrCode::SUCCESS]){
        $content = [
            'err_no'  => $code,
            'err_msg' => $msg,
        ];
        self::output($content);
        exit();
    }
    /**
     * @param array $data
     *
     * 方便测试用的
     */
    public static function outputPage($data = []) {
        $content = [
            'data' => $data
        ];
        //输出结果
        header("Content-Type: application/json");
        echo self::encodeJson($content);
    }

    public static function output($content = []) {
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
}