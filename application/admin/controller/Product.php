<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/20
 * Time: 13:57
 */

namespace app\admin\controller;

use think\Controller;
class Product extends Controller
{
    //默认方法

    //品牌管理
    public function productBrand()
    {
        return $this->fetch();
    }

    //分类管理
    public function productCategory()
    {
        return $this->fetch();
    }

    //分类添加
    public function productCategoryAdd()
    {
        return $this->fetch();
    }

    //产品管理
    public function productList()
    {
        return $this->fetch();
    }
    //产品管理
    public function noticeMange()
    {
        return $this->fetch();
    }

    //产品管理
    public function productNotice()
    {
        return $this->fetch();
    }


}