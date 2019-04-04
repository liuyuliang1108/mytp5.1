<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/20
 * Time: 14:12
 */

namespace app\admin\controller;

use think\Controller;
class Charts extends Controller
{
    //折线图
    public function charts1()
    {
        return $this->fetch();
    }

     //时间轴折线图
    public function charts2()
    {
        return $this->fetch();
    }

     //区域图
    public function charts3()
    {
        return $this->fetch();
    }

     //柱状图
    public function charts4()
    {
        return $this->fetch();
    }

     //饼状图
    public function charts5()
    {
        return $this->fetch();
    }

     //3D柱状图
    public function charts6()
    {
        return $this->fetch();
    }

     //3D饼状图
    public function charts7()
    {
        return $this->fetch();
    }



}