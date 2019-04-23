<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/17
 * Time: 21:53
 */

namespace app\index\model;
use think\Model;
use think\model\concern\SoftDelete;
class Element extends Model
{
    //引用软删除
    use SoftDelete;

    //定义表中删除字段，记录删除时间
    protected $deleteTime = 'delete_time';

    //定义默认非被软删除的值
    protected $defaultSoftDelete = 0;

    //设置当前表默认日期时间显示格式
//    protected $dateFormat ='Y/m/d';

    //自动写入时间戳
    protected $autoWriteTimestamp =true;

    //定义操作自动完成属性
//    protected $insert = ['status'=>1];

    //字段类型转换
    protected $type =[
        //   'id'=>'integer',
    ];
    // 设置json类型字段
    protected $json = [
        //'file_name',
        //'description',
    ];
    //数据表中状态字段，status返回值处理
    public function getStatusAttr($value)
{
    $status = [
        0 => '已停用',
        1 => '已启用',
    ];
    return $status[$value];
}
    //定义关联方法
  /*  public function teacher()
    {
        //班级表 与 教师teacher表 一对一 关联
        return $this ->hasOne('Teacher');
    }*/
    //定义关联方法
   /* public function student()
    {
        //班级表 与 学生student表 一对多 关联
        return $this ->hasMany('student');
    }*/

    static function getzTreeData(){

        //实例化模型
        $model = new Element();

        //调用本模型的all方法
        $result = $model->all();//返回了一个对象数组


        //初始化 节点数组
       $data = array();

       //配置项中获取根节点对象
       $rootNode = config('rootNode');

       //节点数组，添加根节点对象
       $data[] = $rootNode;

       foreach ($result as $object) {//遍历分类表
           $node = new \stdClass();//实例化一个新对象
           $node->id = $object['child_id'];
           $node->name = $object['name'].'|'.$object['alias'];

           $node->open = false;

           $node->pId = $object['parent_id'];

           $data[] = $node;//节点数组，添加节点对象
       }
      return $data;
   }
}