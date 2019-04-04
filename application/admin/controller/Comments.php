<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/20
 * Time: 15:22
 */

namespace app\admin\controller;

use think\Controller;
class Comments extends Controller
{
    //默认方法
    public function feedbackList()
    {
        return $this->fetch();
    }
}