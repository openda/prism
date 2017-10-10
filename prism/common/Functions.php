<?php
/**
 * Created by PhpStorm.
 * User: wangxk1991@gmail.com
 * Date: 2017/5/26
 * Time: 下午5:49
 * Desc:
 */

namespace prism\common;

class Functions {
    public static function dataType($data = null) {
//        if(is_numeric($data)){
//            return "numeric";
//        }
//        if(is_bool($data)){
//            return "bool";
//        }
//        if(is_float($data)){
//            return "float";
//        }
//        if(is_numeric($data)){
//            return "numeric";
//        }
//        if(is_numeric($data)){
//            return "numeric";
//        }
        return gettype($data);
    }
}
