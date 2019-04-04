<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/20
 * Time: 13:57
 */

namespace app\admin\controller;

use think\Controller;
class Picture extends Controller
{
    //默认方法
    public function pictureList()
    {
        return $this->fetch();
    }
}