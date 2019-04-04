<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/20
 * Time: 14:32
 */

namespace app\admin\controller;


use app\admin\model\CategoryList;
use app\admin\model\Content;
use app\admin\model\MqBlogCategory;
use think\Controller;
use think\Request;

use think\Db;

class BlogCategory extends Controller
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
$result=$request->post();
$data['title']=trim($result['title']);
$data['order']=trim($result['order']);
$data['child_id']=trim($result['child_id']);
$data['content']=$result['content'];
$data['url']='?child='.$data['child_id'].'&id='.$data['order'];
        $data['status']=trim($result['status']);



//调用本模型中的新增方法
        $result = Content::editData($data, 1);


        if (array_key_exists('affected', $result) ) {
            $result[]=['flag' => 1];

        }else {$result[]=['flag' => -1];}

        return json_encode($result);//以json格式输出;
    }

    //添加博客
    public function addContent($cId)
    {

        $where = 'child_id=' . $cId;

        $data = Content::selectData($where, 1);
        return $this->fetch('', ['data' => $data]);
    }

//博客分类内容列表
    /**
     * AJAX分页
     */
    public function contentList(Request $request)
    {
        $cId=1111;
        $result = $request->get();
        if (array_key_exists('cId',$result)) {
            $cId = $result['cId'];
        }

        $where = 'child_id=' . $cId;
        $data = Content::selectData($where, 0);

        //计算总数量
        $count=count($data);

        if (!is_array($data)) { //记录为0

            $data='';

            return $data;

        }else{  //查询到记录


            //设置获取每页的条数
            $limits = 1;

            //当前页码
            $Nowpage = array_key_exists('page',$result) ? $result['page']: 1;

            //计算起始条数
            $start= ($Nowpage-1)*$limits;


                //截取当前页的数据
                $data=array_slice($data,$start,$limits);





            $this->assign('limits', $limits); //每页字符数
            $this->assign('count', $count); //总字符

            if (array_key_exists('page',$result)) {


                return $data;
            }
        }


        $this->assign('child_id', $cId); //本类id
       return $this->fetch('',['data'=>$data]);
    }

    //博客内容展示
    /**
     * AJAX分页
     */
    public function contentShow(Request $request)
    {
        //获取文章编号，查询出内容
        $result = $request->get();
        $child=$result['child'];
        $id=$result['id'];
        $where = 'child_id=' . $child.' and order_id='.$id;
        $data = Content::selectData($where, 1);


        //当前页码
        $Nowpage = array_key_exists('page',$result) ? $result['page'] : 1;

        //设置获取每页的汉字数
        $limits = 5;
        //每页字符数
        $limits =$limits*3;
        //计算总页面字符长度
        $count = strlen($data['content']);

        //计算起始字符数
        $start= ($Nowpage-1)*$limits;
        //截取当前页的字符
        $data['content'] = substr($data['content'],$start,$limits);


        $this->assign('limits', $limits); //每页字符数
        $this->assign('count', $count); //总字符

        if (array_key_exists('page',$result)) {

           return json($data);
        }
           return $this->fetch('',['data'=>$data]);


    }



    //删除分类
    public function categoryDel($data)
    {

        $id = 'child_id';
        $arr = json_decode($data, true);
        $cId=$arr['id'];
        $result = MqBlogCategory::editData('', $id,$cId,2);
        $data=$result['affected'];
        return json_encode($data);//以json格式输出
    }



    //博客分类编辑
    public function blogCategoryEdit($cId=1,$flag=0)
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
                $cId=$child_id;
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
        $where='child_id LIKE '."'11%'";
        $nodeArr = CategoryList::selectData($where,2);

        return json_encode($nodeArr);//以json格式输出

    }


public function test(){
    $result=MqBlogCategory::selectOne($id=1);
    dump($result);
}

    public function test1(){
        $one=11;
        $where='child_id='.$one;
     //   $result = MqBlogCategory::selectData();
        $data=['child_id'=>221];
       $result = MqBlogCategory::editData($data,'child_id',2);
        dump($result);
    }

}