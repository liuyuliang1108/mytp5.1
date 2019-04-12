<?php
namespace app\admin\controller;
use \app\admin\controller\Base;
use app\admin\model\Config;
use think\Request;
use think\Db;
class ConfigManage extends Base
{
    /*配置项列表*/
    public function configList()
    {
        $this->view->assign('title','配置项列表');
        $this->view->assign('keywords','配置项列表');
        $this->view->assign('description','配置项列表展示实例');

        $this->view->count=Config::count();
        $this->view->assign('count',$this->view->count);

            $list=Config::all();//获取所有配置项列表

        $this->view->assign('list',$list);
        return $this->fetch();
    }
    /*config添加*/
    public function configAdd()
    {
        /*渲染模板*/
        return $this->fetch();
    }

    /*config项目增加*/
    public function configAddSave(Request $request)
    {
        //获取传入参数
        $attr = $request->param();

        //使用模型save方法,返回bool值
        $object= new Config;
        $data=$object->save($attr);

            return $data;
    }

    /*config停用*/
    public function configStop(Request $request)
    {

        //获取传入参数
        $attr = $request->param();
        $id=$attr['id'];
        //使用D方法更新
        $data= Db::table('config')->where(['id'=>$id])->update(['status'=>0]);
        return $data;
    }

    /*config启用*/
    public function configStart(Request $request)
    {

        //获取传入参数
        $attr = $request->param();
        $id=$attr['id'];
        //使用D方法更新
        $data= Db::table('config')->where(['id'=>$id])->update(['status'=>1]);
        return $data;
    }

    /*config编辑*/
    public function configEdit(Request $request)
    {
        //获取传入参数
        $attr = $request->param();
        $id=$attr['id'];
        //使用模型get方法,返回model对象
        $result=Config::get(['id'=>$id]);
        //模板赋值
        $this->view->assign('config_info',$result->getData());
        /*渲染模板*/
        return $this->fetch();
    }
    /*config角色编辑*/
    public function configEditSave(Request $request)
    {
        //获取传入参数
        $attr = $request->param();
        $id=$attr['id'];
        //使用模型save方法,返回bool值
        $object= new Config;
        $data=$object->isUpdate()->save($attr,['id'=>$id]);
        return $data;
    }

    /*Config删除*/
    public function configDelete(Request $request)
    {

        //获取传入参数
        $attr = $request->param();
        $id=$attr['id'];
        //使用模型update方法,返回model对象,软删除
        $data=Config::destroy(['id'=>$id]);
        //使用D方法软删除
        $data= Db::table('config')->where('id', $id)->update(['is_delete' => 1]);
        return $data;
    }

    /*Config删除恢复*/
    public function unDelete()
    {
        //使用D方法软删除恢复
        $data= Db::table('config')->where('is_delete', 1)->update(['delete_time' => 0]);
        return $data;
    }

    /*git管理*/
    public function gitManage(Request $request)
    {
        $this->view->assign('title','git管理');
        $this->view->assign('keywords','git管理');
        $this->view->assign('description','git管理展示实例');

        //获取传入参数
        $attr = $request->param();
        $id=$attr['id'];

        $list=Config::get($id);//获取传入配置项信息

        $this->view->assign('vo',$list);
        return $this->fetch();
    }
}