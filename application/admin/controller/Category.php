<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/23
 * Time: 11:42
 */

namespace app\admin\controller;

use think\facade\Build;
use app\admin\controller\Base;
use app\admin\model\CategoryList;
use think\Request;

class Category extends Base //分类管理控制器
{


    public function index()
    {
        $this->isLogin();//判断用户是否登录
        $data = CategoryList::selectData();

        $data = getTree($data, 0);

        $this->view->assign('title','后台管理系统v1.0');

        return $this->fetch('', ['menu' => $data]);
    }

    public function welcome()
    {

        return $this->fetch('');
    }

    public function categoryManage()
    {
        $this->isLogin();//判断用户是否登录


        return $this->fetch('');
    }

    public function add()
    {

        return $this->fetch();
    }

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


    //删除分类
    public function delete()
    {
        $cId = intval(input('get.id'));    //接收前端Ajax传过来的数据
        $id = 'child_id';
        //  $arr = json_decode($data, true);
        // $cId = $arr['id'];
        $result = CategoryList::editData('', $id, $cId, 2);
        $data = $result['affected'];
        //如果删除成功，封装json数据
        if ($data) {
            $response = array(
                'flag' => 1,
                'errmsg' => 'success',
                'data' => true
            );
        } else {
            $response = array(
                'flag' => -1,
                'errmsg' => 'fail',
                'data' => false
            );
        }

        return json_encode($response);//以json格式输出
    }

    public function addSave(Request $request)//新增方法，以及修改方法//依赖注入request对象
    {

        $data = $request->post();
        $data['url'] = humpToLine($data['controller']) . '/' . $data['action'] . '.html';
        $data['postman'] = '/'.$data['module'].'/'.humpToLine($data['controller']) . '/' . $data['action'] . '.html';
        $id = 'child_id';
        $data['parent_id'] = intval($data['parent_id']);
        $data['order'] = intval($data['order']);
        $data[$id] = $data['parent_id'] * 10 + $data['order'];
        $value = $data[$id];

        $data['view'] = humpToLine($data['action']);

        if (array_key_exists('demo-radio', $data)){
            $radio=$data['demo-radio'];
            unset($data['demo-radio']);
        }


        if (!array_key_exists('edit', $data)) {//增加模式

            //调用本模型中的新增方法
            $result = CategoryList::editData($data, $id, $value, 1);

            if ($result['exist']) {
                return '该child_id已存在，重新输入';
            } else {    //调用本模型中的创建方法

                if (!empty($data['action'])) {
                    if ($radio == 'index') {    //创建index方法

                        //创建控制器
                        $result['create'] = CategoryList::createTool($data, 1);


                    } elseif ($radio == 'server') { //创建不带视图的方法

                        //创建控制器
                        $result['create'] = CategoryList::createTool($data, 1);

                    } else {                                 //创建带视图view的方法

                            //创建控制器
                        $result['create'] = CategoryList::createTool($data, 1);
                        $build = include APP_PATH . 'build.php';
                            $result = Build::run($build);

                        //创建视图
                        $result['create'] = CategoryList::createTool($data, 2);

                    }
                } elseif (empty($data['controller']) && !empty($data['model'])) {

                    //创建模型model
                    $result['create'] = CategoryList::createTool($data, 3);


                } elseif (empty($data['controller']) && empty($data['model'])) {
                    //创建模块module
                    $result['create'] = CategoryList::createTool($data, 4);
                }


                if ($result['create']) {
                    $build = include APP_PATH . 'build.php';
                    //生成文件
                    Build::run($build);
                }





                if (array_key_exists('affected', $result) ) {
                    $result[]=['flag' => 1];

                }else {$result[]=['flag' => -1];}
                return json_encode($result);//以json格式输出

            }





        } else {                  //修改update模式
            switch ($data['edit']) {

                case 1:
                    {

                        //修改基本信息
                        $flag = 3;
                        unset($data['edit']);

                        $result = CategoryList::editData($data, $id, $value, $flag);

                        if (array_key_exists('affected', $result) ) {
                            $result['flag']=1;

                        }else {
                            $result['flag']=-1;
                        }

                        return json_encode($result);//以json格式输出
                        break;
                    }

                case 2:
                    {

                        //修改节点等信息
                        $flag = 4;

                        unset($data['edit']);
                        $result = CategoryList::editData($data, $id, $value, $flag);

                        if (array_key_exists('affected', $result) ) {
                            $result['flag']=1;

                        }else {
                            $result['flag']=-1;
                        }
                        return json_encode($result);//以json格式输出
                        break;
                    }

            }

        }

    }


    public function admin()
    {
        //分类后台管理页面

        //默认进入查看第一条信息
        $where = 'child_id=11';
        $result = CategoryList::selectData($where, 1);

        $column = array_keys($result);
        //渲染模板

        return $this->fetch('', ['data' => $result, 'column' => $column]);
    }


    /**
     * 异步加载树型结构数据
     * 1、查询博客分类列表
     * 2、封装节点成json格式数据
     * */
    public function getTreeData()
    {   //方法：获得树数据
        $nodeArr = CategoryList::selectData("", 1);

        return json_encode($nodeArr);//以json格式输出

    }

    public function addVerify($parent_id, $order)
    {
        //验证child_id是否存在

        $id = 'child_id';
        //$arr = json_decode($data, true);

        $cId = intval($parent_id) * 10 + intval($order);

        // $cId=$arr['child_id'];
        $where = $id . '=' . $cId;
        $result = CategoryList::selectData($where, 1);
        if ($result) {
            $result = ['flag' => -1];

        } else {
            $result = ['flag' => 1];
        };
        return json_encode($result);//以json格式输出

    }

    public function test()
    {
        $array = ['ttt' => [
            '__file__' => "",
            '__dir__' => "",
            'controller' => "",
            'model' => "'" . 'zzz' . "'",
            'view' => "",
        ]
        ];
        return buildReplace($array) ? 1 : 0;
    }

    public function test2()
    {
        //  相关的知识点： 正表达式： preg_match_all -- 进行全局正则表达式匹配 preg_replace -- 执行正则表达式的搜索和替换 文件的读和写 file_put_contents--文件写入函数 file_get_contents()--文件

//执行配置文件的修改操作
        $filename = APP_PATH . 'build.php';
        dump($filename);
//echo "<pre>";
//var_dump($_POST);
//echo "</pre>";

        $string = file_get_contents($filename);
//DB_HOST    localhost
        $data = ['__dir__' => 'hello'];
        foreach ($data as $key => $val) {

            //定义正则来查找内容，这里面的key为form表单里面的name
            $yx = "/'$key'\s*=>\s*\[.*/";

            //将内容匹配成对应的key和修改的值
            $re = "'$key'   => ['$val'],";

            //替换内容
            $string = preg_replace($yx, $re, $string);
        }


//写入成功
        file_put_contents($filename, $string);

        $string = file_get_contents($filename);

    }

    public function test4()
    {
        $build = include APP_PATH . 'build.php';
        dump($build);
        $result = Build::run($build);
        dump($result);
    }
}

