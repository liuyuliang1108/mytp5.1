<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/20
 * Time: 11:22
 */

namespace app\admin\controller;

use think\Controller;


class Admin extends Controller //管理员控制器
{
    //管理员管理
    public function adminRole()
    {
        return $this->fetch();
    }
    //权限管理
    public function adminPermission()
    {
        return $this->fetch();
    }
    //管理员列表
    public function adminList()
    {
        return $this->fetch();
    }
    //添加管理员
    public function adminAdd()
    {
        return $this->fetch();
    }

    //新建网站角色
    public function adminRoleAdd()
    {
        return $this->fetch();
    }
    //密码编辑
    public function adminPasswordEdit()
    {
        return $this->fetch();
    }
    //添加角色增加
    public function roleAdd()
    {
        return $this->fetch();
    }

}