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
            $commonList[] = $data;
        }

        /*模板赋值*/
        $this->view->assign('commonList', $commonList);
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
        $object = new CommonModel;
        //自动获取order
        $order=$object->whereBetween('index',[$attr['type']*10000+$attr['attr']*1000,$attr['type']*10000+$attr['attr']*2000])
                        ->max('index');

        if ($order) {
            $attr['order']=$order;

        }else{
            $attr['order']=$attr['type']*10000+$attr['attr']*1000;
        }
        $attr['index']=$attr['order']+1;

    //使用模型save方法,返回bool值
        $object = new CommonModel;
    //返回bool值
        $data = $object->save($attr);
    //创建基础文件
        if ($attr['type']==1) {
            self::buildBaseAction($attr['value']);
        }elseif($attr['type']==2){
            self::buildBaseAction($attr['value']);
            self::buildBaseTpl($attr['value']);
        }
        if ($data) {
    //json格式输出
            return json_encode($object);
        } else {
            return null;
        }
    }
}

