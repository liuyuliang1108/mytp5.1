<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/17
 * Time: 21:20
 */

namespace app\index\controller;

use \app\index\controller\Base;
use \app\index\model\CategoryList;
use app\index\model\Blog as BlogModel;
use think\Request;

class Blog extends Base
{

    public function blogManage()
    {

        return $this->fetch('');
    }

    /**
     * 异步加载树型结构数据
     * 1、查询组件分类列表
     * 2、封装节点成json格式数据
     * */
    public function getTreeData()
    {   //方法：获得树数据
        $nodeArr = BlogModel::getzTreeData();

        return json_encode($nodeArr);//以json格式输出

    }

    //博客分类内容列表

    /**
     * AJAX分页
     */
    public function blogSelect(Request $request)
    {
        $cId = 1111;
        $result = $request->param();
        if (array_key_exists('cId', $result)) {
            $cId = $result['cId'];
        }
        //构造查询条件
        $map = ['child_id' => $cId];

        $model = new BlogModel;
        //查询总文章数量
        $count = $model->where($map)->count();
        //如果该分类暂无文章，返回空
        if (empty($count)) {
            $data = '';
            return $data;
        } else {

            //设置获取每页的文章条数
            $limits = 2;

            //获取当前页码，默认为第1页
            $Nowpage = array_key_exists('page', $result) ? $result['page'] : 1;

            //计算本页起始文章数
            $start = ($Nowpage - 1) * $limits;

            //获取本页所需文章所有数据
            $data = $model->where($map)->limit($start, $limits)->all();


            //循环输出原始数据
            foreach ($data as $key => $value) {
                $data[$key] = $data[$key]->getdata();
            }

            $this->assign('limits', $limits); //每页字符数
            $this->assign('count', $count); //总字符

            if (array_key_exists('page', $result)) {

                return json($data);
            }
        }


        $this->assign('child_id', $cId); //本类id
        return $this->fetch('', ['data' => $data]);
    }

    /**
     * AJAX分页
     */
    public function articleShow(Request $request)
    {
        //初始返回参数
        $status = 0;
        $result = '';

        //获取文章编号，查询出内容
        $data = $request->param();
        $child = $data['child_id'];
        $id = $data['order_id'];

        //构造查询条件
        $map = [
            'child_id' => $child,
            'order_id' => $id,
        ];

        $model = new BlogModel();
        //查询此文章数据
        $result = $model->where($map)->find();
        if (empty($result)) {
            $status = 0;
            $result = '暂无查询记录';
            return ['status' => $status, 'result' => $result];
        } else {
            $status = 1;
            $value = $result->getData();
            //将文章内容以分页符为标记，分隔为数组
            $string = $value['content'];
            $result = explode("|page|", $string);

            //计算总页数
            $count = count($result);

            //获取当前页码
            $Nowpage = array_key_exists('page', $data) ? $data['page'] : 1;

            //输出当前页数据
            $result = $result[$Nowpage - 1];

            $this->assign('limits', 1); //每页显示一个分页符内容
            $this->assign('count', $count); //总页数

            if (array_key_exists('page', $data)) {

                return json(['status' => $status, 'result' => $result]);
            }
            return $this->fetch('', ['data' => $value]);


        }
    }

    public function articleEdit(Request $request)
    {
        //获取文章编号，查询出内容
        $data = $request->param();
        $cId = $data['child_id'];
        $orderId = $data['order_id'];

        //查询此文章数据
        $result = BlogModel::get(['child_id' => $cId, 'order_id' => $orderId]);


        if (empty($result)) {
            $status = 0;
            $result = '暂无查询记录';
            return ['status' => $status, 'result' => $result];
        } else {
            $status = 1;

            return $this->fetch('', ['data' => $result]);

        }
    }

    public function articleDel(Request $request)
    {
        //获取文章编号，查询出内容
        $data = $request->param();
        $child = $data['child_id'];
        $id = $data['order_id'];

        //构造查询条件
        $map = [
            'child_id' => $child,
            'order_id' => $id,
        ];

        //使用模型update方法,将is_delete字段修改为1
        BlogModel::update(['is_delete' => 1], $map);

        //使用模型destroy方法,软删除,修改delete_time字段
        $data = BlogModel::destroy($map);
        return $data;


    }

    //添加博客
    public function articleAdd($cId)
    {

        $data = CategoryList::get(['child_id' => $cId]);
        return $this->fetch('', ['data' => $data]);
    }

    //保存博客
    public function articleSave(Request $request)
    {
        $data = $request->param();
        //数据处理
        $data['title'] = trim($data['title']);
        $data['child_id'] = trim($data['child_id']);
        $data['content'] = $data['content'];
        //将字符串转为索引数组
        $data['keywords'] = explode(',', $data['keywords']);

        //当新增以及所在分类关系改变时，自动生成order
        if (array_key_exists('id', $data)) { //存在id键为更新操作
            $result = BlogModel::get($data['id']);
            $cId = $result->child_id;
            if (!$cId == $data['child_id']) {   //判断更新操作是否更新了分类关系
                //自动获取order_id
                $model = new BlogModel;
                $orderId = $model->where(['child_id' => $data['child_id']])->max('order_id');
                $data['order_id'] = $orderId ? $orderId + 1 : 1;//三元表达式，如果此类中不存在，则序号为1
            } else {
                $data['order_id'] = $result->order_id;
            }
        } else {
            //自动获取order_id
            $model = new BlogModel;
            $orderId = $model->where(['child_id' => $data['child_id']])->max('order_id');
            $data['order_id'] = $orderId ? $orderId + 1 : 1;//三元表达式，如果此类中不存在，则序号为1
        }

        $data['url'] = '?child_id=' . $data['child_id'] . '&order_id=' . $data['order_id'];
        $data['status'] = trim($data['status']);


        if (array_key_exists('id', $data)) {//调用本模型中的更新方法

            $model = new BlogModel;
            $result = $model->isUpdate()->save($data);

            $data = $result ? ['flag' => 1] : ['flag' => -1];

        } else {                                     //调用本模型中的新增方法
            $model = new BlogModel;
            $result = $model->save($data);
            $data = $result ? ['flag' => 1] : ['flag' => -1];
        }
        return json($data);//以json格式输出;

    }
}