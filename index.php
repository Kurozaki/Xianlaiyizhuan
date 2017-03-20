<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用入口文件

// 检测PHP环境
if (version_compare(PHP_VERSION, '5.3.0', '<')) die('require PHP > 5.3.0 !');

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG', True);

// 定义应用目录
define('APP_PATH', './Application/');

$allow_origin = array(
    'http://192.168.16.1',
    'http://192.168.16.1:80',
    'http://192.168.16.1:3000',
    'http://192.168.16.1:3002',
    'http://192.168.16.1:63342',
);
if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allow_origin)) {

    //配置信任的跨域来源
    header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
    //配置允许发送认证信息 比如cookies（会话机制的前提）
    header('Access-Control-Allow-Credentials: true');
    //信任跨域有效期，秒为单位
    header('Access-Control-Max-Age: 120');
    //允许的自定义请求头
    header('Access-Control-Allow-Headers: x-request-with,content-type');
    //允许的请求方式
    header('Access-Control-Allow-Methods: GET, POST');
}

// 引入ThinkPHP入口文件
require './ThinkPHP/ThinkPHP.php';

// 亲^_^ 后面不需要任何代码了 就是如此简单