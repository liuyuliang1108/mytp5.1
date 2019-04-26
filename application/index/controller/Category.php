<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/23
 * Time: 11:42
 */

namespace app\index\controller;

use think\facade\Build;
use app\index\controller\Base;
use \app\index\model\Common as CommonModel;
use app\index\model\Category as CategoryModel;
use think\Request;

class Category extends Base //分类管理控制器
{

    /**
     * @name categoryManage
     * @decs 分类管理首页展示
     * @abstract 申明变量/类/方法
     * @access public protected static
     * 关联数据库：
     * @return mixed
     * User: yliang_liu
     * Created on: 2019/4/22 16:13
     */
    public function categoryManage()
    {
        // $this->isLogin();//判断用户是否登录
        return $this->fetch('');
    }

    public function categoryAdd($cId)//
    {

        $data = CategoryModel::where('child_id', $cId)->find();

        /*获取所有公共模板数据 返回对象数组*/
        //以类型分类，获取二维对象数组
        $model = new CommonModel;
        $list = [];
        $typeNub = $model->max('type');
        for ($i = 1; $i <= $typeNub; $i++) {
            $list[$i] = CommonModel::all(['type' => $i]);
        }
        $this->view->assign('list', $list);
        return $this->fetch('', ['data' => $data]);


    }

    public function categoryEdit($cId = 11)
    {

        $data = CategoryModel::where('child_id', $cId)->find();

        return $this->fetch('', ['data' => $data]);

    }


    //删除分类
    public function delete(Request $request)
    {
        $cId = $request->param('id');    //接收前端Ajax传过来的数据

        $result = CategoryModel::destroy(['child_id' => $cId]);

        //如果删除成功，封装json数据
        $data = $result ? ['flag' => 1] : ['flag' => -1];

        return json_encode($data);//以json格式输出
    }

    public function CategoryAddSave(Request $request)//新增方法，以及修改方法//依赖注入request对象
    {
        //获取请求参数
        $data = $request->param();

        //数据处理

        //如果类型为方法型，生成url地址，
        if ($data['type'] == 3) {
            $data['url'] = '/' . $data['module'] . '/' . humpToLine($data['controller']) . '/' . $data['action'] . '.html';
        } else {
            $data['url'] = "javascript:;";
        }

        $data['postman'] = '/' . $data['module'] . '/' . humpToLine($data['controller']) . '/' . $data['action'] . '.html';
        $data['parent_id'] = intval($data['parent_id']);

        //当新增以及父级关系改变时，自动生成order
        if (array_key_exists('id', $data)) { //存在id键为更新操作
            $result = CategoryModel::get($data['id']);
            $pId = $result->parent_id;
            if (!$pId == $data['parent_id']) {   //判断更新操作是否更新了父级关系
                //自动获取order
                $model = new CategoryModel;
                $order = $model->where(['parent_id' => $data['parent_id']])->max('order');
                $data['order'] = $order ? $order + 1 : 1;//三元表达式，如果此类中不存在，则序号为1
            }else{
                $data['order']=$result->order;
            }
        } else {
            //自动获取order
            $model = new CategoryModel;
            $order = $model->where(['parent_id' => $data['parent_id']])->max('order');
            $data['order'] = $order ? $order + 1 : 1;//三元表达式，如果此类中不存在，则序号为1
        }

        //判断排序有几位数
        $nub = strlen($data['order'] ) ;
        //根据父节点，和排序生成子节点
        $data['child_id'] = $data['parent_id'] * 10 * $nub + $data['order'];
        //驼峰转下划线法得到视图文件名
        $data['view'] = humpToLine($data['action']);

        //将字符串转为索引数组
        if (!array_key_exists('id', $data)) {
            if (array_key_exists('status', $data)) {
                $data['status'] = substr($data['status'], 0, -1);
            }else{
                $data['status'] = '0';
            }
        }
        $data['status'] = explode('|', $data['status']);
        //并将字符型数组转成整型数组
        foreach ($data['status'] as $key => $value) {
            $data['status'][$key] = (int)$value;
        }

        if (array_key_exists('id', $data)) {//调用本模型中的更新方法
            $category = new CategoryModel;
            $result = $category->isUpdate()->save($data);

            $data = $result ? ['flag' => 1] : ['flag' => -1];

        } else {                                     //调用本模型中的新增方法
            $category = new CategoryModel;
            $result = $category->save($data);
            //新增成功后生成模板文件
            if ($result) {

                switch ($data['type']) {
                    case 0:
                        {
                            //创建模块module
                             CategoryModel::createTool($data, 4);
                            break;
                        }
                    case 1:
                        {
                            //创建默认控制器
                            self::buildController($data['module'], $data['controller'], $data['model']);
                            break;
                        }
                    case 2:
                        {
                            //创建默认模型model
                            self::buildModel($data['module'], $data['model']);
                            //并创建对应控制器
                            if ($data['controller']) {
                                self::buildController($data['module'], $data['controller'], $data['model']);
                            }
                            break;
                        }
                }

                if ($data['type'] == 1) {
                        $build = include APP_PATH . 'build.php';
                        //生成文件
                        Build::run($build);
                }


                if ($data['type'] == 3) {
                    $model = $data['model'];
                    //获取控制器中文名
                    $result = CategoryModel::get(['child_id' => $data['parent_id']]);
                    $name = $result->name;
                    if ($data['status'][0] == 10000) {
                        //创建默认无视图方法
                        self::buildAction($data['module'], $data['controller'], $model, $data['action'], $name);
                    } elseif ($data['status'][0] == 20000) {
                        self::buildAction($data['module'], $data['controller'], $model, $data['action'], $name);
                        self::buildTpl($data['module'], $data['controller'], $data['action'], $name, 0);
                    } else {
                        //获取传入公共模板信息
                        $list = CommonModel::all(['index' => $data['status']]);
                        foreach ($list as $key => $value) {

                            $action = $value->value;
                            $attr = $value->attr;
                            switch ($value->getData('type')) {
                                case 1:
                                    {
                                        self::buildAction($data['module'], $data['controller'], $model, $action, $name);
                                        break;
                                    }
                                case 2:
                                    {
                                        self::buildAction($data['module'], $data['controller'], $model, $action, $name);
                                        self::buildTpl($data['module'], $data['controller'], $action, $name, $attr);
                                        if ($value->value=='List') {
                                            self::replaceActionAttr($data['module'], $data['controller'], $model, $action, $name);
                                            self::replaceTplAttr($data['module'], $data['controller'], $model, $action, $name, $attr);
                                        }
                                        break;
                                    }
                            }
                        }
                    }
                }
            }


            $data = $result ? ['flag' => 1] : ['flag' => -1];
        }
        return json_encode($data);//以json格式输出
    }





    /**
     * 异步加载树型结构数据
     * 1、查询博客分类列表
     * 2、封装节点成json格式数据
     * */
    public function getTreeData()
    {   //方法：获得树数据
        $nodeArr = CategoryModel::getzTreeData();

        return json_encode($nodeArr);//以json格式输出

    }

}

