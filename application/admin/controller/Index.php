<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/15
 * Time: 17:36
 */

namespace app\admin\controller;

use think\Controller;

class Index extends Controller
{

    //后台首页
    public function index()
    {
        return $this->fetch();
    }

    //后台登录页面
    public function login()
    {

        return $this->fetch();
    }

    //后台欢迎页面
    public function welcome()
    {

        return $this->fetch();
    }

}