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
                'length' => $value->length,
                'price' => $value->price,
                'status' => $value->status,
                'create_time' => $value->create_time,
                /*用关联方法teacher属性方法访问teacher表中数据*/
                'teacher' => isset($value->teacher->name) ? $value->teacher->name : '<span style="color:red;">未分配</span>'
            ];

            /*每次关联查询结果保存到数组$commonList中*/
            $commonList[] = $data;
        }
    }
}
