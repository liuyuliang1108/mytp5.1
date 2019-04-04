<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/20
 * Time: 15:22
 */

namespace app\admin\controller;

use app\admin\model\MqNotice;
use think\Controller;
class Notice extends Controller
{
    //默认方法
    public function noticeManage()
    {
        return $this->fetch();
    }

    /**
     * 异步查询数据,返回json格式
     * */
    public function noticeDatas(Request $request){

        $aoData = $request->param('aoData');

        $queryArr = DataTablesUtil::getQueryPageProperty($aoData);

        $criteria = $queryArr[DataTablesUtil::CRITERIA];

        //查询条件
        $condition = array();
        $condition['delete_flag'] = '0';
        if(!empty($criteria['title'])){
            if(!empty($criteria['accurateFlg']) && $criteria['accurateFlg'] == 1){
                $condition['title'] = $criteria['title'];
            }else{
                $condition['title'] = ['like','%'.$criteria['title'].'%'];
            }
        }

        //查询当前条件的总数
        $count = Notice::where($condition)->count();


        //查询当前条件的结果集，并分页和排序
        $list = Notice::where($condition)
            ->order($queryArr[DataTablesUtil::ORDER])
            ->limit($queryArr[DataTablesUtil::LIMIT])
            ->select();


        $json = DataTablesUtil::getJsonPage($queryArr[DataTablesUtil::S_ECHO],$count,$list);
        return json_decode($json);
    }
    public function functionOne(){
        return 666;
    }
}