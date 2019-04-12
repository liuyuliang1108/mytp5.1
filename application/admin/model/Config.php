<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/2
 * Time: 22:36
 */

namespace app\admin\model;

use think\Model;
use think\model\concern\SoftDelete;
class Config extends Model
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $defaultSoftDelete = 0;
//构造生成器

//数据表中类型字段，type返回值处理
    public function getTypeAttr($value)
    {
        $type = [
            0 => 'win',
            1 => 'linux',
        ];
        return $type[$value];
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