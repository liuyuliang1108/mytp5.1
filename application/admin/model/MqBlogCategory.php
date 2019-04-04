<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/21
 * Time: 17:21
 */

namespace app\admin\model;

use think\View;
use think\Model;

class MqBlogCategory extends Model
//模型创建完成后，会自动获取当前数据表名称$table,表中所有字段信息$field,
//主键$pk和数据库配置信息$connection,同时会自动继承基类Model中所有属性和方法，
//protected类型在本模型中使用，public类型还可以在控制器使用，静态方法大多直接用在控制器，进行curd操作
{


    //构造一个条件编辑器，$where添加一个数据数组，返回成功或失败
    //默认$flag=1，为新增数据模式，$flag=2为删除数据模式，$flag=3为更新模式,$flag=4为编辑模式，$id为更新模式下数据唯一标识字符段,$value为唯一标识值
    static function editData($data, $id,$value, $flag = 1, $type = '=')
    {
        $exist = 0;//定义唯一标识是否存在标记
        //实例化模型
        $model = new MqBlogCategory();
        //先查询唯一标识符是否已存在
        //查询条件回调函数
        $where = $id . $type . $value;

        $where = function ($query) use ($where) {
            $query->where($where);
        };
        //调用本模型的get方法
        $result = $model->get($where);

        //对查询结果进行查询,对象是否存在
        $exist=is_object($result) ?  1 : 0;

        switch ($flag) {
            case 1 :
                {//增加数据模式
                    if (!$exist) {
                        //调用本模型的create方法,返回对象（object）
                        $result = $model->create($data);
                       return ['exist' => $exist, 'affected' => $result];
                    }else{
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
                        return ['exist' => $exist, 'affected' => $result];
                    break;
                }
            case 4 :
                {//编辑操作模式
                    if (!$exist) {
                        $where = $id . $type . $value;
                        //调用本模型的update方法,返回对象（object）
                        $result = $model->update($data, $where);
                        return ['exist' => $exist, 'affected' => $result];
                    }else{
                        return ['exist' => $exist];
                    }

                    break;
                }

    }
    }

//构造一个条件查询器，查询结果为对象或由对象组成的数组,返回一个数组
//分类查询方法
    static function selectData($where = "", $flag = 0, $key = 0)
    {
        //$where为空""时，查询结果由对象组成的数组，返回整表数据,并且当$flag=1时，返回对象属性数组
        //$flag为0，查询结果为由对象组成的数组；$flag为1，查询结果为对象。
        //$key为0，返回索引数组,$key为特定值(数据表某列数据字段名)时，返回以特定值(数据表某列数据字段名)对应的值作为键名的关联数组
        if ($where == "") {
            //实例化模型
            $model = new MqBlogCategory();

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

        } elseif (!$where == "") {//条件查询
            //实例化模型
            $model = new MqBlogCategory();

            //查询条件回调函数
            $where = function ($query) use ($where) {
                $query->where($where);
            };
            if ($flag == 0) {//查询结果为由对象组成的数组
                //调用本模型的all方法
                $result = $model->all($where);//返回了一个对象数组

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

            } elseif ($flag == 1) {//查询结果为对象
                //调用本模型的get方法
                $result = $model->get($where);//返回了一个对象

                //获取原始数据，存入$data中
                $data = $result->getData();
            }
        }
        return $data;
    }

}