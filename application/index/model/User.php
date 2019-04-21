<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/2
 * Time: 22:36
 */

namespace app\index\model;

use think\Model;
use think\model\concern\SoftDelete;
class User extends Model
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $defaultSoftDelete = 0;
//构造生成器
//数据表中角色字段，role返回值处理
    public function getRoleAttr($value)
    {
        $role = [
            0 => '超级管理员',
            1 => '管理员',
        ];
        return $role[$value];
    }

    //数据表中状态字段，status返回值处理
    public function getStatusAttr($value)
    {
        $status = [
            0 => '已停用',
            1 => '已启用',
        ];
        return $status[$value];
    }
}