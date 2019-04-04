<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/20
 * Time: 13:57
 */

namespace app\admin\controller;

use think\Controller;
class Member extends Controller
{
    //会员列表
    public function memberList()
    {
        return $this->fetch();
    }
    //删除的会员
    public function memberDel()
    {
        return $this->fetch();
    }

    //等级管理
    public function memberLevel()
    {
        return $this->fetch();
    }

    //积分管理
    public function memberScoreOperation()
    {
        return $this->fetch();
    }

    //浏览记录
    public function memberRecordBrowse()
    {
        return $this->fetch();
    }

    //下载记录
    public function memberRecordDownload()
    {
        return $this->fetch();
    }

//分享记录
    public function memberRecordShare()
    {
        return $this->fetch();
    }
}