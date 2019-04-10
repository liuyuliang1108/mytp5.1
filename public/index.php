<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]
namespace think;
error_reporting(E_ALL);
ini_set('display_errors', '1');
// 加载基础文件
require __DIR__ . '/../thinkphp/base.php';

// 定义框架应用根目录
define('ROOT_PATH', __DIR__);

define('__ROOT__','http://tp5.1.com');

// 定义应用目录
define('APP_PATH', __DIR__ . '/../application/');
// 支持事先使用静态方法设置Request对象和Config对象

// 执行应用并响应
Container::get('app')->run()->send();
// [ 应用入口文件 ]

