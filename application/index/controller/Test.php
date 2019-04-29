<?php
/**
 * Created by PhpStorm.
 * User: yliang_liu
 * Date: 2019/4/29
 * Time: 7:55
 */

namespace app\index\controller;

use \app\index\controller\Base;
class Test extends Base
{
public function index(){
    return $this->fetch();
}
}