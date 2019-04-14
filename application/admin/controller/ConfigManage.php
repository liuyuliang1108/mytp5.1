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
        $this->view->assign('title', '配置项列表');
        $this->view->assign('keywords', '配置项列表');
        $this->view->assign('description', '配置项列表展示实例');

        $this->view->count = Config::count();
        $this->view->assign('count', $this->view->count);

        $list = Config::all();//获取所有配置项列表

        $this->view->assign('list', $list);
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
        //将字符串转为索引数组
        $attr['file_name'] = explode ( ',', $attr['file_name'] );
        $attr['description'] = explode ( ';', $attr['description'] );
        //使用模型save方法,返回bool值
        $object = new Config;
        //返回bool值
        $data = $object->save($attr);
        if ($data) {
            //json格式输出
            return json_encode($object);
        }else{
            return null ;
        }


    }

    /*config停用*/
    public function configStop(Request $request)
    {

        //获取传入参数
        $attr = $request->param();
        $id = $attr['id'];
        //使用D方法更新
        $data = Db::table('config')->where(['id' => $id])->update(['status' => 0]);
        return $data;
    }

    /*config启用*/
    public function configStart(Request $request)
    {

        //获取传入参数
        $attr = $request->param();
        $id = $attr['id'];
        //使用D方法更新
        $data = Db::table('config')->where(['id' => $id])->update(['status' => 1]);
        return $data;
    }

    /*config编辑*/
    public function configEdit(Request $request)
    {
        //获取传入参数
        $attr = $request->param();
        $id = $attr['id'];
        //使用模型get方法,返回model对象
        $result = Config::get(['id' => $id]);
        //模板赋值
        $this->view->assign('config_info', $result);
        /*渲染模板*/
        return $this->fetch();
    }

    /*config角色编辑*/
    public function configEditSave(Request $request)
    {
        //获取传入参数
        $attr = $request->param();
        //将字符串转为索引数组
        $attr['file_name'] = explode ( ',', $attr['file_name'] );
        $attr['description'] = explode ( ';', $attr['description'] );
        $id = $attr['id'];
        //使用模型save方法,返回bool值
        $object = new Config;
        $data = $object->isUpdate()->save($attr, ['id' => $id]);
        return $data;
    }

    /*Config删除*/
    public function configDelete(Request $request)
    {

        //获取传入参数
        $attr = $request->param();
        $id = $attr['id'];
        //使用模型update方法,返回model对象,软删除
        $data= Config::destroy(['id' => $id]);
        //使用删除事件同时将is_delete字段修改为1
        return $data;
    }

    /*Config删除恢复*/
    public function unDelete()
    {
        //软删除恢复
        $list = Config::withTrashed()->select(['is_delete' => 1]);
        $data=0;
        foreach ($list as $config){
            $config->restore();
            $config->is_delete=0;
            $data+=$config->save();
        }

        return $data;
    }


    /*git管理*/
    public function gitManage(Request $request)
    {
        $this->view->assign('title', 'git管理');
        $this->view->assign('keywords', 'git管理');
        $this->view->assign('description', 'git管理展示实例');

        //获取传入参数
        $attr = $request->param();
        $id = $attr['id'];

        $list = Config::get($id);//获取传入配置项信息

        $this->view->assign('vo', $list);
        return $this->fetch();
    }

    public function initWinLoad($id)
    {

        //获取传入参数
        $list = Config::get($id);//获取传入配置项信息
        //第一步根据参数explorer打开目录
        //对啊 为啥要写.bat呢 直接运行把，不如
        $name=$list['name'];
        $dir=$list['dir'];
        $version=$list['version'];
        $description=$list['description'];
        $type=$list['type'];
        $workEnv=$list['work_env'];
        $fileName=$list['file_name'];

        $somecontent = "cd $dir";
        $somecontent.="&& git clone git@github.com:liuyuliang1108/myconfig.git tmpgit";
        //移到tmpgit内文件，移到出来，再把文件夹复制出来，再删除目录
        $somecontent.="&& move tmpgit\*.* ./";
        $somecontent.="&& attrib -h tmpgit\.git";
        $somecontent.="&& move tmpgit\.git ./";
        $somecontent.="&& attrib +h .git";
        $somecontent.="&& rd tmpgit";
        $somecontent.="&& git reset --hard HEAD";
        //修改.gitignore文件
        $somecontent.="&& echo.>>.gitignore";
        //遍历配置文件名,写入git管理目录
        foreach ($fileName as $value){
            $somecontent.="&& echo !$value >>.gitignore";
        }
        //修改README.md文件
        $somecontent.="&& echo.>>README.md";
        $somecontent.="&& echo ---------------------------------------------------------->>README.md";
        $somecontent.="&& echo @config-title : $name>>README.md";
        $somecontent.="&& echo @config-work_env : $workEnv>>README.md";
        $somecontent.="&& echo @version : v$version>>README.md";
        $somecontent.="&& echo @version : v$version>>README.md";
        $somecontent.="&& echo @type : $type>>README.md";
        $desc= implode(';',$description);
        $somecontent.="&& echo @description : $desc>>README.md";

        $somecontent.="&& git add .";
        $somecontent.="&& git commit -m 'v$version,$desc'";
        $somecontent.="&& git push -u origin master:$type-$name-$workEnv";

        echo '<br>执行命令'.$somecontent;
        $result=exec($somecontent,$output);
        var_dump($result);
        dump($output);
        echo "正在打开目录 $dir ";
        exec("start explorer $dir");
        //第一步根据参数编辑README.md文件

    }

    /*config工作环境编辑*/
    public function copyToGit($id)
    {
        //使用模型get方法,返回model对象
        $result = Config::get(['id' => $id]);
        //模板赋值
        $this->view->assign('config_info', $result);
        /*渲染模板*/
        return $this->fetch();
    }
    public function copyGit($copyId,$id){

        //使用模型get方法,返回model对象
        $copy=Config::get(['id' => $copyId]);
        $list = Config::get(['id' => $id]);
        $name=$list['name'];
        $dir=$list['dir'];
        $version=$list['version'];
        $description=$list['description'];
        $type=$list['type'];
        $workEnv=$list['work_env'];
        $fileName=$list['file_name'];
        $copyType=$copy['type'];
        $copyName=$copy['name'];
        $copyWorkEnv=$copy['work_env'];
        if ( $type=='win') {
            $somecontent = "cd $dir";
            $somecontent.="&& git clone -b $copyType-$copyName-$copyWorkEnv git@github.com:liuyuliang1108/myconfig.git tmpgit";
            //移到tmpgit内文件，移到出来，再把文件夹复制出来，再删除目录
            $somecontent.="&& move tmpgit\*.* ./";
            $somecontent.="&& attrib -h tmpgit\.git";
            $somecontent.="&& move tmpgit\.git ./";
            $somecontent.="&& attrib +h .git";
            $somecontent.="&& rd tmpgit";
            $somecontent.="&& git reset --hard HEAD";

            //修改README.md文件
            $somecontent.="&& echo.>>README.md";
            $somecontent.="&& echo ---------------------------------------------------------->>README.md";
            $somecontent.="&& echo @config-title : $name>>README.md";
            $somecontent.="&& echo @config-work_env : $workEnv>>README.md";
            $somecontent.="&& echo @version : v$version>>README.md";
            $somecontent.="&& echo @version : v$version>>README.md";
            $somecontent.="&& echo @type : $type>>README.md";
            $desc= implode(';',$description);
            $somecontent.="&& echo @description : $desc>>README.md";

            //copy提交后再比较修改，再提交
            $somecontent.="&& git add . ";
            $somecontent.="&& git commit -m 'v$version,$desc'";
            $somecontent.="&& git push -u origin $copyType-$copyName-$copyWorkEnv:$type-$name-$workEnv";
            echo '<br>执行命令'.$somecontent;
            $result=exec($somecontent,$output);
            echo "正在打开目录 $dir //copy提交后再比较修改，再提交";
            exec("start explorer $dir");
            var_dump($result);
            dump($output);
        }

    }

//打开配置目录

    public function openDir(Request $request)
    {
        //获取传入参数
$data=$request->param();
$dir=$data['dir'];
        $dir=str_replace('@','\\',$dir);
        exec("start explorer $dir",$out,$result);
        if ($result==0) {
            return ['data'=>true];
        }
    }
}
