<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/23
 * Time: 12:16
 */

namespace app\admin\model;


use think\Model;


class CategoryList extends Model
{
    //构造生成器
//数据表中状态字段，status返回值处理
    public function getStatusAttr($value)
    {
        $status = [
            100 => 'login_default',
            101 => 'setStatus',
            11002 => 'AddSave',
            103 => 'EditSave',
            104 => 'Delete',
            105 => 'unDelete',
            106 => 'getTreeData',
            107 => 'idVerify',
            200 => 'view_default',
            201 => 'index',
            202 => 'List',
            21003 => 'Add',
            204 => 'Edit',
            0=>'other',
        ];
        return  $status[$value];
    }

    //构造一个条件查询器，查询结果为对象或由对象组成的数组,返回一个数组
//分类查询方法
    static function selectData($where = "", $flag = 0, $key = 0)
    {

        //$where为空""时，查询结果由对象组成的数组，返回整表数据,并且当$flag=1时，返回对象属性数组
        //$where不为空时，条件查询
        //$flag为0，查询结果为由对象组成的数组；$flag为1，查询结果为对象。$flag为2时，返回对象属性数组
        //$key为0，返回索引数组,$key为特定值(数据表某列数据字段名)时，返回以特定值(数据表某列数据字段名)对应的值作为键名的关联数组
        if ($where == "") {
            //实例化模型
            $model = new CategoryList();

            //调用本模型的all方法
            $result = $model->all();//返回了一个对象数组

            switch ($flag) {
                case 0:
                    {
                        foreach ($result as $object) {

                            if ($key == 0) {
                                //获取原始数据，存入$data中
                                $data[] = $object->getData();
                            } else {
                                //获得特定值字段的值作为数组键名
                                $key = $object->getData($key);

                                //获取原始数据，存入$data中
                                $data[$key] = $object->getData();
                            }
                        }
                        break;
                    }
                case 1:
                    {
                        //初始化 节点数组
                        $data = array();

                        //配置项中获取根节点对象
                        $rootNode = config('rootNode');

                        //节点数组，添加根节点对象
                        $data[] = $rootNode;

                        foreach ($result as $object) {//遍历分类表
                            $node = new \stdClass();//实例化一个新对象
                            $node->id = $object['child_id'];
                            $node->name = $object['name'];

                                $node->open = false;


                            $node->pId = $object['parent_id'];

                            $data[] = $node;//节点数组，添加节点对象
                        }
                        break;
                    }
            }

        } else {//条件查询
            //实例化模型
            $model = new CategoryList();

            //查询条件回调函数
            $where = function ($query) use ($where) {
                $query->where($where);
            };
            if ($flag == 0) {//查询结果为由对象组成的数组
                //调用本模型的all方法
                $result = $model->all($where);//返回了一个对象数组
                if (!count($result)) {
                    $data = 0;

                } else {
                    foreach ($result as $object) {

                        if ($key == 0) {
                            //获取原始数据，存入$data中
                            $data[] = $object->getData();
                        } else {
                            //获得特定值字段的值作为数组键名
                            $key = $object->getData($key);

                            //获取原始数据，存入$data中
                            $data[$key] = $object->getData();
                        }
                    }
                }
            } elseif ($flag == 1) {//查询成功结果为对象，不成功返回null
                //调用本模型的get方法
                $result = $model->get($where);//返回了一个对象
                if ($result) {

                    //获取原始数据，存入$data中
                    $data = $result->getData();
                } else

                    $data = 0;

            } elseif ($flag == 2) {//查询结果为由对象组成的数组
                //调用本模型的all方法，返回对象属性数组
                $result = $model->all($where);//返回了一个对象数组
                if (!count($result)) {
                    $data = 0;

                } else {
                    //初始化 节点数组
                    $data = array();

                    foreach ($result as $object) {//遍历分类表
                        $node = new \stdClass();//实例化一个新对象
                        $node->id = $object['child_id'];
                        $node->name = $object['name'];
                        $node->open = false;
                        $node->pId = $object['parent_id'];

                        $data[] = $node;//节点数组，添加节点对象

                    }
                }
            }
        }
            return $data;
        }


        //构造一个条件编辑器，$where添加一个数据数组，返回成功或失败
        //默认$flag=1，为新增数据模式，$flag=2为删除数据模式，$flag=3为更新模式,$flag=4为编辑模式，$id为更新模式下数据唯一标识字符段,$value为唯一标识值
        static function editData($data, $id, $value, $flag = 1, $type = '=')
        {
            $exist = 0;//定义唯一标识是否存在标记
            //实例化模型
            $model = new CategoryList();
            //先查询唯一标识符是否已存在
            //查询条件回调函数
            $where = $id . $type . $value;

            $where = function ($query) use ($where) {
                $query->where($where);
            };
            //调用本模型的get方法
            $result = $model->get($where);

            //对查询结果进行查询,对象是否存在
            $exist = is_object($result) ? 1 : 0;

            switch ($flag) {
                case 1 :
                    {//增加数据模式
                        if (!$exist) {
                            //调用本模型的create方法,返回对象（object）

                            $result = $model->create($data);

                            $data = $result->getData();

                            return ['exist' => $exist, 'affected' => $data];
                        } else {
                            return ['exist' => $exist];
                        }

                        break;
                    }
                case 2 :
                    {//删除数据模式
                        if ($exist) {
                            //查询条件回调函数
                            $where = $id . $type . $value;
                            $where = function ($query) use ($where) {
                                $query->where($where);
                            };
                            //调用本模型的destroy方法,返回受影响的记录数量（int）
                            $result = $model->destroy($where);

                            //对返回操作结果

                            return ['exist' => $exist, 'affected' => $result];

                        } else {
                            return ['exist' => $exist];
                        }
                        break;
                    }
                case 3 :
                    {//更新操作模式
                        $where = $id . $type . $value;
                        //调用本模型的update方法,返回对象（object）
                        $result = $model->update($data, $where);

                        $data = $result->getData();

                        return ['exist' => $exist, 'affected' => $data];
                        break;
                    }
                case 4 :
                    {//编辑操作模式
                        if (!$exist) {
                            $where = $id . $type . $value;
                            //调用本模型的update方法,返回对象（object）
                            $result = $model->update($data, $where);
                            $data = $result->getData();
                            return ['exist' => $exist, 'affected' => $data];
                        } else {
                            return ['exist' => $exist];
                        }

                        break;
                    }

            }
        }


        //构造一个创建器
//$flag=1为创建控制器，$flag=2为创建带视图view方法,$flag=3为创建模型model，,$flag=4为创建模块module

        static function createTool($data, $flag = 0)
        {
            $module = $data['module'];
            $controller = $data['controller'];
            $model = $data['model'];

            $view = $data['view'];


            $array = [];


            switch ($flag) {
                case 1:
                    {//创建类型：控制器类
                        $array = [$module => [
                            '__file__' => "",
                            '__dir__' => "",
                            'controller' => "'" . $controller . "'",
                            'model' => "",
                            'view' => "",
                        ]
                        ];
                        return buildReplace($array) ? 1 : 0;
                        break;
                    }

                case 2:
                    {//创建类型：方法视图类view


                        $array = [$module => [
                            '__file__' => "",
                            '__dir__' => "",
                            'controller' => "",
                            'model' => "",
                            'view' => "'" . humpToLine($controller) . '/' . $view . "'",
                        ]
                        ];
                        return buildReplace($array) ? 1 : 0;
                        break;


                    }

                case 3:
                    {//创建类型：方法模型类model


                        $array = [$module => [
                            '__file__' => "",
                            '__dir__' => "",
                            'controller' => "",
                            'model' => "'" . $model . "'",
                            'view' => "",
                        ]
                        ];
                        return buildReplace($array) ? 1 : 0;
                        break;


                    }

                case 4:
                    {//创建类型：方法模块类modele


                        $array = [$module => [
                            '__file__' => "",
                            '__dir__' => "",
                            'controller' => "",
                            'model' => "",
                            'view' => "",
                        ]
                        ];
                        return buildReplace($array) ? 1 : 0;
                        break;


                    }
            }
        }


        //构造一个写入文件器
//$flag=1为方法类action，$flag=2为视图类view,$flag=3为模型类model
//$attr=1为插入add，$attr=2为删除del，$attr=3为查询select，$attr为修改更新update

        static function writeTool($data, $flag, $attr)
        {

        }
    }



