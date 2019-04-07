<?php
namespace app\admin\controller;
use \app\admin\controller\Base;
use think\Request;
class Test extends Base
{
//测试输出专用
    public function output(Request $request)
    {
$sever=$request->server();
dump($sever);

    }
}