<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/16
 * Time: 21:44
 */

namespace app\admin\model;
use think\Model;
use think\model\concern\SoftDelete;
class Grade extends Model
{   //班级表
    //引用软删除
    use SoftDelete;
    //定义表中删除字段，记录删除时间
    protected $deleteTime = 'delete_time';
    //定义默认非被软删除的值
    protected $defaultSoftDelete = 0;

    //设置当前表默认日期时间显示格式
    protected $dateFormat ='Y/m/d';
    //自动写入时间戳
    protected $autoWriteTimestamp =true;

    //定义操作自动完成属性
    protected $insert = ['status'=>1];

    //字段类型转换
    protected $type =[
     //   'id'=>'integer',
    ];
    // 设置json类型字段
    protected $json = [
        //'file_name',
        //'description',
    ];
    //定义关联方法
    public function teacher()
    {
        //班级表 与 教师teacher表 一对一 关联
        return $this ->hasOne('Teacher');
    }
    //定义关联方法
    public function student()
    {
        //班级表 与 学生student表 一对多 关联
        return $this ->hasMany('student');
    }

}