<?php
/**
 * Created by PhpStorm.
 * User: wangxk1991@gmail.com
 * Date: 17/3/27
 * Time: 下午10:14
 * Desc: 对外暴露的入口文件
 */

// 检测PHP环境
if (version_compare(PHP_VERSION, '5.6.0', "<")) die('require PHP > 5.6.0 !');

// 开启调试模式，部署阶段可以设置成false
define('APP_DEBUG', true);

// 引入Prism入口文件
require './prism/Start.php';

// 亲^_^ 后面不需要任何代码了 就是如此简单
