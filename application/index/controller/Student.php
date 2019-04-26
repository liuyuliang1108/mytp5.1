<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/17
 * Time: 21:20
 */
namespace app\index\controller;

use \app\index\controller\Base;
use app\index\model\Student as StudentModel;
use think\Request;

class Student extends Base
{

    /*学生管理模板*/
    public function studentList()
    {
        /*模板赋值*/
        $this->view->assign('title', '学生管理');
        $this->view->assign('keywords', '学生管理关键字');
        $this->view->assign('description', '学生管理描述');

        /*获取所有学生管理数据 返回对象数组*/
        $list = StudentModel::paginate(5);

        /*获取记录总数*/
        $count = StudentModel::count();

         //给结果集对象数组中的每个模板对象添加班级关联数据
        foreach ($list as $value){
            $value->grade=$value->grade->name;
        }
        /*模板赋值*/
        $this->view->assign('list', $list);
        $this->view->assign('count', $count);

        /*渲染模板*/
        return $this->fetch();
    }
}
