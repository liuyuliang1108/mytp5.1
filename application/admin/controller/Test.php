<?php
namespace app\admin\controller;
use \app\admin\controller\Base;
use think\Request;
class Test extends Base
{
//测试输出专用
    public function output()
    {

        system('start explorer C:\phpStudy\PHPTutorial\php\php-7.0.12-nts');




    }
}

/** 打开windows的计算器 */
//exec('start C:WindowsSystem32calc.exe');