<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/17
 * Time: 21:20
 */

namespace app\index\controller;

use \app\index\controller\Base;
use app\index\model\Element as ElementModel;
use think\Request;

class Element extends Base
{
    /*组件模板模板*/
    public function ElementList()
    {
        /*模板赋值*/
        $this->view->assign('title', '组件模板');
        $this->view->assign('keywords', '组件模板关键字');
        $this->view->assign('description', '组件模板描述');

        /*获取所有组件模板数据 返回对象数组*/
        $Element = ElementModel::all();

        /*获取记录总数*/
        $count = ElementModel::count();

        /*遍历Element表*/
        foreach ($Element as $value) {
            $data = [
                'id' => $value->id,
                'name' => $value->name,
                'alias' => $value->alias,
                'type' => $value->type,
                'status' => $value->status,
                'content' => $value->content,
                'parent_id' => $value->parent_id,
                'des' => $value->des,
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

    public function ElementManage()
    {
// $this->isLogin();//判断用户是否登录
        return $this->fetch('');
    }

    /**
     * 异步加载树型结构数据
     * 1、查询组件分类列表
     * 2、封装节点成json格式数据
     * */
    public function getTreeData()
    {   //方法：获得树数据
        $nodeArr = ElementModel::getzTreeData();

        return json_encode($nodeArr);//以json格式输出

    }

    /*组件模板编辑模板*/
    public function ElementEdit(Request $request)
    {
        /*模板赋值*/
        $this->view->assign('title', '组件模板编辑');
        $this->view->assign('keywords', '组件模板编辑关键字');
        $this->view->assign('description', '组件模板编辑描述');
        //获取传入参数
        $attr = $request->param();
        //使用模型get方法,返回model对象
        $data = ElementModel::get(['child_id' => $attr['cId']]);
        //模板赋值
        $this->view->assign('data', $data);
        /*渲染模板*/
        return $this->fetch();
    }

    /*组件模板添加模板*/
    public function elementAdd($cId)
    {
        /*模板赋值*/
        $this->view->assign('title', '组件模板');
        $this->view->assign('keywords', '组件模板关键字');
        $this->view->assign('description', '组件模板描述');
        $data = ElementModel::where('child_id', $cId)->find();

        return $this->fetch('', ['data' => $data]);

    }

    /*组件模板增加保存*/
    public function elementAddSave(Request $request)
    {
        //获取传入参数
        $attr = $request->param();
        //将字符串转为索引数组
        $attr['file_name'] = explode(',', $attr['file_name']);
        $attr['description'] = explode(';', $attr['description']);
        //使用模型save方法,返回bool值
        $object = new ElementModel;
        //返回bool值
        $result = $object->save($attr);
        $data = $result ? ['flag' => 1] : ['flag' => -1];
        return json_encode($data);//以json格式输出;
    }

    /*组件编辑保存*/
    public function elementEditSave(Request $request)
    {
        //获取传入参数
        $attr = $request->param();
        $id = $attr['id'];
        //使用模型save方法,返回bool值
        $model = new ElementModel;
        $result = $model->isUpdate()->save($attr, ['id' => $id]);
        $data = $result ? ['flag' => 1] : ['flag' => -1];
        return json_encode($data);//以json格式输出;
    }
}









