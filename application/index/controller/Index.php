<?php
namespace app\index\controller;
use think\facade\Request;
use think\Controller;
use think\facade\Config;
class Index extends Controller
{

public function index()
    {


    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}
