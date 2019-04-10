<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/20
 * Time: 14:32
 */

namespace app\admin\controller;

use app\admin\controller\Base;
use app\admin\model\CategoryList;
use app\admin\model\Content;
use app\admin\model\MqBlogCategory;
use think\Controller;
use think\Request;

use think\Db;

class BlogCategory extends Base
{


    public function select($cId = 11, $flag = 0)//flag为0是查询操作，为1是新增查询操作
    {
        if ($flag == 0) {
            $where = 'child_id=' . $cId;
            $data = CategoryList::selectData($where, 1);
            return $this->fetch('', ['data' => $data]);
        } else {
            $where = 'child_id=' . $cId;
            $data = CategoryList::selectData($where, 1);
            return $this->fetch('add', ['data' => $data]);
        }

    }

//博客分类
    public function blogCategory()
    {
        return $this->fetch();
    }

    //保存博客
    public function saveContent(Request $request)
    {
        $result = $request->post();
        $data['title'] = trim($result['title']);
        $data['order_id'] = trim($result['order_id']);
        $data['child_id'] = trim($result['child_id']);
        $data['content'] = $result['content'];
        $data['url'] = '?child=' . $data['child_id'] . '&id=' . $data['order_id'];
        $data['status'] = trim($result['status']);


//调用本模型中的新增方法
        $result = Content::editData($data, 1);


        if (array_key_exists('affected', $result)) {
            $result[] = ['flag' => 1];

        } else {
            $result[] = ['flag' => -1];
        }

        return json_encode($result);//以json格式输出;
    }

    //添加博客
    public function addContent($cId)
    {

        $where = 'child_id=' . $cId;

        $data = CategoryList::selectData($where, 1);
        return $this->fetch('', ['data' => $data]);
    }

//博客分类内容列表

    /**
     * AJAX分页
     */
    public function contentList(Request $request)
    {
        $cId = 1111;
        $result = $request->get();
        if (array_key_exists('cId', $result)) {
            $cId = $result['cId'];
        }
        //构造查询条件
        $map = [
            'child_id' => $cId];
        $model = new Content;
        //查询总文章数量
        $count = $model->where($map)->count();
        if (empty($count)) {
            $data = '';
            return $data;
        } else {

            //设置获取每页的文章条数
            $limits = 2;

            //当前页码
            $Nowpage = array_key_exists('page', $result) ? $result['page'] : 1;

            //计算起始文章条数
            $start = ($Nowpage - 1) * $limits;
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

    //博客内容展示

    /**
     * AJAX分页
     */
    public function contentShow(Request $request)
    {
        //初始返回参数
        $status = 0;
        $result = '';

        //获取文章编号，查询出内容
        $data = $request->param();
        $child = $data['child'];
        $id = $data['id'];

//构造查询条件
        $map = [
            'child_id' => $child,
            'order_id' => $id,
        ];

        $model = new Content;
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
            //当前页码
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


    //删除分类
    public function categoryDel($data)
    {

        $id = 'child_id';
        $arr = json_decode($data, true);
        $cId = $arr['id'];
        $result = MqBlogCategory::editData('', $id, $cId, 2);
        $data = $result['affected'];
        return json_encode($data);//以json格式输出
    }


    //博客分类编辑
    public function blogCategoryEdit($cId = 1, $flag = 0)
    {

        //url get获取为字符串，转换成整数型
        $cId = intval($cId);
        if ($flag == 1) {
            {//编辑模式
                $parentId = $_POST['sel_Sub'];
                $order = $_POST['class-rank'];
                $name = trim($_POST['class-val']);
                $remark = trim($_POST['class-demo']);
                $child_id = $parentId * 10 + $order;
                $value = $child_id;
                $cId = $child_id;
                $data = ['child_id' => $child_id, 'name' => $name, 'remark' => $remark, 'parent_id' => $parentId, 'order' => $order];
                $id = 'child_id';
                $result = MqBlogCategory::editData($data, $id, $value, 3);
                if ($result['exist']) {
                    echo '已存在,并ok';
                } else {
                    echo 'ok';
                }
            }
        }
        //查看模式
//调用模型中的分类查询方法
        $where = 'child_id=' . $cId;

        $result = MqBlogCategory::selectData($where, 1);

//调用模型中的获取分类方法

        $resultList = MqBlogCategory::selectData();
        return $this->fetch('', ['category' => $result, 'categoryList' => $resultList]);


    }


    /**
     * 异步加载树型结构数据
     * 1、查询博客分类列表
     * 2、封装节点成json格式数据
     * */
    public function getTreeData()
    {   //方法：获得树数据
        $where = 'child_id LIKE ' . "'11%'";
        $nodeArr = CategoryList::selectData($where, 2);

        return json_encode($nodeArr);//以json格式输出

    }


    public function test()
    {
        $result = MqBlogCategory::selectOne($id = 1);
        dump($result);
    }

    public function test1()
    {
        $one = 11;
        $where = 'child_id=' . $one;
        //   $result = MqBlogCategory::selectData();
        $data = ['child_id' => 221];
        $result = MqBlogCategory::editData($data, 'child_id', 2);
        dump($result);
    }

}