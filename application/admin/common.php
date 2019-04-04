<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 后台应用公共文件


function auto_upload_layout(){
    return
        "{include file='base/_meta' /}
{include file='base/_header' /}"
        ;}



function getTree($data,$pid){
    if (!is_array($data) || empty($data) ) return false;
    $tree = array();

    foreach ($data as $key => $value) {
        if ($value['parent_id'] == $pid) {//当相等时，说明此数组为上个数组的子目录
            $value['parent_id'] = getTree($data,$value['child_id']);//将子数组的内容遍历后赋给上级数组的pid键，html页面上循环时用到此内容
            $tree[] = $value;
            unset($data[$key]); //删除遍历过的数组数据
        }
    }
    return $tree;
}

/*
 * 下划线转驼峰
 */
function convertUnderline($str)
{
    $str = preg_replace_callback('/([-_]+([a-z]{1}))/i',function($matches){
        return strtoupper($matches[2]);
    },$str);
    return $str;
}

/*
 * 驼峰转下划线
 */
function humpToLine($str){
    //判断是小驼峰还是大驼峰 以大写开头的大驼峰
    if (preg_match('/^[A-Z]+/',$str)) {
        //判断有几个驼峰，
        if (preg_match_all('/[A-Z]/',$str)==1) {//只有一个只需大写换成小写
            $str=strtolower($str);
        }else{//先全部替换_小写，再去掉首的_
            //执行一个正则表达式搜索并且使用一个回调进行替换
            $str = preg_replace_callback('/([A-Z]{1})/',function($matches){
                return '_'.strtolower($matches[0]);
            },$str);
            $str=substr($str,1);
        }
    }else{//小驼峰直接替换
        //执行一个正则表达式搜索并且使用一个回调进行替换
        $str = preg_replace_callback('/([A-Z]{1})/',function($matches){
            return '_'.strtolower($matches[0]);
        },$str);
    }

    return $str;
}

/*
 * 替换文件内容，传入二维数组
 */

 function buildReplace($array)
{
    //  相关的知识点： 正表达式： preg_match_all -- 进行全局正则表达式匹配 preg_replace -- 执行正则表达式的搜索和替换 文件的读和写 file_put_contents--文件写入函数 file_get_contents()--文件

//执行配置文件的修改操作
    $filename = APP_PATH . 'build.php';



    $string = file_get_contents($filename);

//dump($array);
     foreach ($array as $key => $val) {
        // dump($key);
        // dump($val);

         //定义正则来查找内容，
         $yx = "/'.*'\s*=>\s*\[\s/";

         //将内容匹配成对应的key和修改的值
         $re = "'$key'     => [ ";

         //替换内容
         $string = preg_replace($yx, $re, $string);

         foreach ($val as $target => $value) {

             //定义正则来查找内容，
             $yx = "/'$target'\s*=>\s*\[.*/";

             //将内容匹配成对应的key和修改的值
             $re = "'$target'   => [$value],";

             //替换内容
             $string = preg_replace($yx, $re, $string);


         }

     }

    if(file_put_contents($filename,$string)){
        return 1;//写入成功
    }else{
        return 0;//写入失败
    };



}
