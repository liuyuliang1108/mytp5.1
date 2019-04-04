<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/20
 * Time: 14:09
 */

namespace app\admin\controller;

use think\Controller;
class  System extends Controller
{
    //默认方法
    public function systemBase()
    {
        return $this->fetch();
    }

     //默认方法
    public function systemCategory()
    {
        return $this->fetch();
    }

     //默认方法
    public function systemData()
    {
        return $this->fetch();
    }

     //默认方法
    public function systemShielding()
    {
        return $this->fetch();
    }

     //默认方法
    public function systemLog()
    {
        return $this->fetch('');
    }


}