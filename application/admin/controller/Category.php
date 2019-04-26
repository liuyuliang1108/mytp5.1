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
use \app\admin\model\Common as CommonModel;
use app\admin\model\CategoryList;
use think\Request;

class Category extends Base //分类管理控制器
{


    public function index()
    {
        $this->isLogin();//判断用户是否登录
        $data = CategoryList::selectData();

        $data = getTree($data, 0);

        $this->view->assign('title', '后台管理系统v1.0');

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

    public function select($cId = 11, $flag = 0)//flag为0是查询操作，为1是新增查询操作
    {

        $data =CategoryList::where('child_id',$cId)->find();
        if ($flag == 0) {
            return $this->fetch('', ['data' => $data]);
        } else {
            /*获取所有公共模板数据 返回对象数组*/
            //以类型分类，获取二维对象数组
            $model = new CommonModel;
            $list=[];
            $typeNub=$model->max('type');
            for ($i=1;$i<=$typeNub;$i++) {
                $list[$i]=CommonModel::all(['type'=>$i]);
            }
            $this->view->assign('list', $list);
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
        //获取请求参数
        $data = $request->param();

        //数据处理
        if ($data['type']==3) {
            $data['url'] = $data['module'] . '/' .humpToLine($data['controller']) . '/' . $data['action'] . '.html';
        }else{
            $data['url']="javascript:;";
        }

        $data['postman'] = '/' . $data['module'] . '/' . humpToLine($data['controller']) . '/' . $data['action'] . '.html';
        $data['parent_id'] = intval($data['parent_id']);
        //自动获取order
        $model = new CategoryList;
        $order=$model->where(['parent_id'=>$data['parent_id']])->max('order');
        if ($order) {
            $data['order']=$order+1;

        }else{
            $data['order']=1;
        }
        $data['child_id'] = $data['parent_id'] * 100 + $data['order'];
        $data['view'] = humpToLine($data['action']);
        //将字符串转为索引数组
        $data['status']=substr($data['status'],0, -1) ;
        $data['status'] = explode ( '|', $data['status'] );
        //并将字符型数组转成整型数组
        foreach($data['status'] as $key=>$value)
        {
            $data['status'][$key]=(int)$value;
        }

        if (array_key_exists('id', $data)) {//调用本模型中的更新方法
            $category =new CategoryList;
            $result = $category->isUpdate()->save($data);

        }else {//调用本模型中的新增方法
            $category = new CategoryList;
            $result = $category->save($data);
            if (!$result) {

            } else {
                $result = [];
                switch ($data['type']) {

                    case 0:
                        {
                            //创建模块module
                            $result['create'] = CategoryList::createTool($data, 4);
                            break;
                        }
                    case 1:
                        {
                            //创建默认控制器
                            self::buildController($data['module'], $data['controller'],$data['model']);
                            break;
                        }
                    case 2:
                        {
                            //创建默认模型model
                            self::buildModel($data['module'], $data['model']);
                            //并创建对应控制器
                            if ($data['controller']) {
                                self::buildController($data['module'], $data['controller'],$data['model']);
                            }
                            break;
                        }
                    case 3:
                        {//创建方法
                            break;
                        }
                    case 4:
                        {//创建分类
                            break;
                        }
                }

                if ($data['type'] == 1) {
                    if ($result['create']) {
                        $build = include APP_PATH . 'build.php';
                        //生成文件
                        Build::run($build);
                    }
                }else{
                    $result = ['flag' => 1];
                }



                if ($data['type']==3) {
                    $model=$data['model'];
                    //获取控制器中文名
                    $result=CategoryList::get(['child_id'=>$data['parent_id']]);
                    $name=$result->name;
                    if ($data['status'][0]==10000) {
                        //创建默认无视图方法
                        self::buildAction($data['module'],$data['controller'],$model, $data['action'],$name);
                    }elseif ($data['status'][0]==20000){
                        self::buildAction($data['module'],$data['controller'],$model, $data['action'],$name);
                        self::buildTpl($data['module'], $data['controller'],$data['action'],$name,0);
                    }else{
                        //获取传入公共模板信息
                        $list = CommonModel::all(['index'=>$data['status']]);
                        foreach ($list as $key=>$value){

                            $action=$value->value;
                            $attr=$value->attr;
                            switch ($value->getData('type')){
                                case 1:{
                                    self::buildAction($data['module'],$data['controller'],$model, $action,$name);
                                    break;
                                }
                                case 2:{
                                    self::buildAction($data['module'],$data['controller'],$model, $action,$name);
                                    self::buildTpl($data['module'], $data['controller'],$action,$name,$attr);
                                    if ($value->value=='List') {
                                        self::replaceActionAttr($data['module'],$data['controller'],$model, $action,$name);
                                    }
                                    break;
                                }
                            }
                        }
                    }
                    }
            }


            if ($result) {
                $result = ['flag' => 1];

            } else {
                $result = ['flag' => -1];
            };
        }
        return json_encode($result);//以json格式输出
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
}

