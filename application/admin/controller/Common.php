<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/17
 * Time: 21:20
 */

namespace app\admin\controller;

use \app\admin\controller\Base;
use app\admin\model\Common as CommonModel;
use think\Request;

class Common extends Base
{
    /*公共模板列表模板*/
    public function commonList()
    {
        /*模板赋值*/
        $this->view->assign('title', '公共模板列表');
        $this->view->assign('keywords', '公共模板列表关键字');
        $this->view->assign('description', '公共模板列表描述');

        /*获取所有公共模板列表数据 返回对象数组*/
        $common = CommonModel::all();

        /*获取记录总数*/
        $count = CommonModel::count();

        /*遍历common表*/
        foreach ($common as $value) {
            $data = [
                'id' => $value->id,
                'name' => $value->name,
                'value' => $value->value,
                'type' => $value->type,
                'index' => $value->index,
                'attr' => $value->attr,
                'status' => $value->status,
                'create_time' => $value->create_time,

            ];

            /*每次关联查询结果保存到数组$commonList中*/
            $list[] = $data;
        }

        /*模板赋值*/
        $this->view->assign('list', $list);
        $this->view->assign('count', $count);

        /*渲染模板*/
        return $this->fetch();
    }

    /*公共模板添加模板*/
    public function CommonAdd()
    {
        /*模板赋值*/
        $this->view->assign('title', '公共模板');
        $this->view->assign('keywords', '公共模板关键字');
        $this->view->assign('description', '公共模板描述');
        /*渲染模板*/
        return $this->fetch();
    }

    /*公共模板增加保存*/
    public function CommonAddSave(Request $request)
    {
        //获取传入参数
        $attr = $request->param();
        //数据处理
        $model = new CommonModel;
        //自动获取order
        $order = $model->whereBetween('index', [$attr['type'] * 10000 + $attr['attr'] * 1000, $attr['type'] * 10000 + $attr['attr'] * 2000])
            ->max('index');

        if ($order) {
            $attr['order'] = $order;

        } else {
            $attr['order'] = $attr['type'] * 10000 + $attr['attr'] * 1000;
        }
        $attr['index'] = $attr['order'] + 1;

        //使用模型save方法,返回bool值
        $model = new CommonModel;
        //返回bool值
        $data = $model->save($attr);
        //创建基础文件
        if ($attr['type'] == 1) {
            self::buildBaseAction($attr['value']);
        } elseif ($attr['type'] == 2) {
            self::buildBaseAction($attr['value']);
            self::buildBaseTpl($attr['value']);
        }
        if ($data) {
            //json格式输出
            return json_encode($model);
        } else {
            return null;
        }
    }

    /**
     * @name CommonEdit
     * @decs 公共模板编辑模板
     * @abstract 申明变量/类/方法
     * @access public protected static
     * 关联数据库：
     * @param Request $request
     * @return mixed
     * User: yliang_liu
     * Created on: 2019/4/19 11:27
     */
    public function CommonEdit(Request $request)
    {
        /*模板赋值*/
        $this->view->assign('title', '公共模板编辑');
        $this->view->assign('keywords', '公共模板编辑关键字');
        $this->view->assign('description', '公共模板编辑描述');
        //获取传入参数
        $attr = $request->param();
        $id = $attr['id'];
        //使用模型get方法,返回model对象
        $data = CommonModel::get(['id' => $id]);
        //模板赋值
        $this->view->assign('info', $data);
        /*渲染模板*/
        return $this->fetch();
    }

    /**
     * @name CommonEditSave
     * @decs 公共模板编辑保存
     * @abstract 申明变量/类/方法
     * @access public protected static
     * 关联数据库：
     * @param Request $request
     * @return bool
     * User: yliang_liu
     * Created on: 2019/4/19 11:29
     */
    public function CommonEditSave(Request $request)
    {
        //获取传入参数
        $attr = $request->param();
        //数据处理
        $model = new CommonModel;
        //自动获取order
        $order = $model->whereBetween('index', [$attr['type'] * 10000 + $attr['attr'] * 1000, $attr['type'] * 10000 + $attr['attr'] * 2000])
            ->max('index');

        if ($order) {
            $attr['order'] = $order;

        } else {
            $attr['order'] = $attr['type'] * 10000 + $attr['attr'] * 1000;
        }
        $attr['index'] = $attr['order'] + 1;

        $id = $attr['id'];
        //使用模型save方法,返回bool值
        $model = new CommonModel;
        $data = $model->isUpdate()->save($attr, ['id' => $id]);
        return $data;
    }

    /**
     * @name CommonDelete
     * @decs
     * @abstract 申明变量/类/方法
     * @access public
     * 关联数据库：CommonModel
     * @param Request $request
     * @return bool
     * User: yliang_liu
     * Created on: 2019/4/19 10:34
     */
    public function CommonDelete(Request $request)
    {
        //获取传入参数
        $attr = $request->param();
        $id = $attr['id'];
        //使用模型update方法,返回model对象,软删除
        $data = CommonModel::destroy(['id' => $id]);
        //使用删除事件同时将is_delete字段修改为1
        return $data;
    }

    /**
     * @name setStatus
     * @decs 设定状态
     * @abstract 申明变量/类/方法
     * @access public protected static
     * 关联数据库：
     * @param Request $request
     * User: yliang_liu
     * Created on: 2019/4/19 14:01
     */
    public function setStatus(Request $request)
    {
        //获取传入公共模板id
        $id = $request->param('id');

        //查询数据表
        $model=CommonModel::get($id);
        //启用和禁用状态处理
        if ($model->status == '已启用') {
            CommonModel::update(['status' => 0], ['id' => $id]);
        } else {
            CommonModel::update(['status' => 1], ['id' => $id]);
        }
    }
}



