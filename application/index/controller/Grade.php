<?php

namespace app\index\controller;

use \app\index\controller\Base;
use app\index\model\Grade as GradeModel;
use think\Request;


class Grade extends Base
{
    /*班级列表模板*/
    public function gradeList()
    {
        /*模板赋值*/
        $this->view->assign('title', '班级列表');
        $this->view->assign('keywords', '班级列表关键字');
        $this->view->assign('description', '班级列表描述');

        /*获取所有班级列表数据 返回对象数组*/
        $grade = GradeModel::all();

        /*获取记录总数*/
        $count = GradeModel::count();

        /*遍历grade表*/
        foreach ($grade as $value) {
            $data = [
                'id' => $value->id,
                'name' => $value->name,
                'length' => $value->length,
                'price' => $value->price,
                'status' => $value->status,
                'create_time' => $value->create_time,
                /*用关联方法teacher属性方法访问teacher表中数据*/
                'teacher' => isset($value->teacher->name) ? $value->teacher->name : '未分配'
            ];

            /*每次关联查询结果保存到数组$gradeList中*/
            $list[] = $data;
        }

        /*模板赋值*/
        $this->view->assign('list', $list);
        $this->view->assign('count', $count);

        /*渲染模板*/
        return $this->fetch();
    }

    /*设定状态*/
    public function setStatus(Request $request)
    {
        //获取传入班级id
        $id = $request->param('id');

        //查询数据表
        $result = GradeModel::get($id);

        //启用和禁用状态处理
        if ($result->status == '已启用') {
            GradeModel::update(['status' => 0], ['id' => $id]);
        } else {
            GradeModel::update(['status' => 1], ['id' => $id]);
        }
    }

    /*班级添加模板*/
    public function gradeAdd()
    {
        /*模板赋值*/
        $this->view->assign('title', '班级添加');
        $this->view->assign('keywords', '班级添加关键字');
        $this->view->assign('description', '班级添加描述');

        /*渲染模板*/
        return $this->fetch();
    }

    /*班级添加保存*/
    public function gradeAddSave(Request $request)
    {
        //获取传入参数
        $attr = $request->param();

        //将字符串转为索引数组
       // $attr['file_name'] = explode(',', $attr['file_name']);
        // $attr['description'] = explode(';', $attr['description']);


        $object = new GradeModel;
        //使用模型save方法,返回bool值
        $data = $object->save($attr);

        if ($data) {
            //json格式输出
            return json_encode($object);
        } else {
            return null;
        }


    }


    /*班级编辑模板*/
    public function gradeEdit(Request $request)
    {
        /*模板赋值*/
        $this->view->assign('title', '班级编辑');
        $this->view->assign('keywords', '班级编辑关键字');
        $this->view->assign('description', '班级编辑描述');

        //获取传入班级id
        $grade_id = $request->param('id');

        //使用模型get方法,返回model对象
        $result = GradeModel::get(['id' => $grade_id]);

        //模板赋值
        $this->view->assign('grade_info', $result);

        /*渲染模板*/
        return $this->fetch();
    }

    /*班级编辑保存*/
    public function gradeEditSave(Request $request)
    {
        //获取传入参数
        $attr = $request->param();
        //去掉表单中为空的数据，即未修改数据
        foreach ($attr as $key=>$value){
            if (!empty($value)) {
                $attr[$key]=$value;
            }
        }
        $id = $attr['id'];

        //将字符串转为索引数组
        $attr['file_name'] = explode(',', $attr['file_name']);
        $attr['description'] = explode(';', $attr['description']);



        $object = new GradeModel;
        //使用模型save方法,返回bool值
        $data = $object->isUpdate()->save($attr, ['id' => $id]);

        return $data;
    }

    /*班级删除*/
    public function gradeDelete(Request $request)
    {
        //获取传入班级id
        $id = $request->param('id');

        //使用模型update方法,将is_delete字段修改为1
        GradeModel::update(['is_delete' => 1], ['id' => $id]);

        //使用模型destroy方法,软删除,修改delete_time字段
        $data = GradeModel::destroy(['id' => $id]);

        return $data;
    }

    /*班级删除恢复*/
    public function unDelete()
    {
        //软删除恢复
        $list = GradeModel::withTrashed()->select(['is_delete' => 1]);

        $data = 0;
        foreach ($list as $grade) {
            $grade->restore();
            $grade->is_delete = 0;
            $data += $grade->save();
        }

        return $data;
    }


}

