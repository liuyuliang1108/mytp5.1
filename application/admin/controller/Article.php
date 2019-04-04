<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/20
 * Time: 13:47
 */

namespace app\admin\controller;

use think\Config;
use think\Controller;

class Article extends Controller
{
    public function articleList()
    {
        return $this->fetch();
    }

    public function articleAdd()
    {
        return $this->fetch();
    }

    public function articleClass()
    {
        return $this->fetch();
    }
}