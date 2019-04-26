<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/17
 * Time: 21:20
 */
namespace app\index\controller;

use \app\index\controller\Base;
use app\index\model\Teacher as TeacherModel;
use think\Request;

class Teacher extends Base
{
    /*教师管理模板*/
    public function teacherList()
    {
        /*模板赋值*/
        $this->view->assign('title', '教师管理');
        $this->view->assign('keywords', '教师管理关键字');
        $this->view->assign('description', '教师管理描述');

        /*获取所有教师管理数据 返回对象数组*/
        $teacher = TeacherModel::all();

        /*获取记录总数*/
        $count = TeacherModel::count();

        /*遍历teacher表*/
        foreach ($teacher as $value) {
            $data = [
                'id' => $value->id,
                'name' => $value->name,
                'degree' => $value->degree,
                'school' => $value->school,
                'mobile' => $value->mobile,
                'hiredate' => $value->hiredate,
                'status' => $value->status,
                /*用关联方法teacher属性方法访问teacher表中数据*/
                'grade' => isset($value->grade->name) ? $value->grade->name : '未分配'
            ];

            /*每次关联查询结果保存到数组$list中*/
            $list[] = $data;
        }
        /*模板赋值*/
        $this->view->assign('list', $list);
        $this->view->assign('count', $count);

        /*渲染模板*/
        return $this->fetch();
    }

}
