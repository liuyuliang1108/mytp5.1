<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/27
 * Time: 15:25
 */

namespace app\admin\controller;

use think\Controller;
use think\facade\Session;

class Base extends Controller
{
    //定义一些公共方法和系统常量
   public function initialize(){
       parent::initialize();
       define('USER_ID',Session::get('user_id'));
    }

    //判断用户是否登录，用在入口位置，category/index
    protected function isLogin(){
        if (empty(USER_ID)) {
            $this->error('用户未登录，无权访问',url('admin/user/login'));
        }
    }

    //防止用户重复登录
    protected function alreadyLogin(){
        if (!empty(USER_ID)) {
            $this->error('用户已登录，请勿重复登录',url('admin/category/index'));
        }
    }
}