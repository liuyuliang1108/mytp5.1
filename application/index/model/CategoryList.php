<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/23
 * Time: 12:16
 */

namespace app\index\model;


use think\Model;


class CategoryList extends Model
{
    // 设置json类型字段
    protected $json = [
        'status',
    ];

    static function getzTreeData()
    {
        //实例化模型
        $model = new CategoryList();

        //调用本模型的all方法
        $result = $model->all();//返回了一个对象数组

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
        return $data;
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



