<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/20
 * Time: 14:05
 */

namespace app\admin\controller;

use think\Controller;
class Page extends Controller
{
    //默认方法
    public function index()
    {
        return $this->fetch();
    }
}