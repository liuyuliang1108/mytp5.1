<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/17
 * Time: 21:20
 */

namespace app\index\controller;

use \app\index\controller\Base;
use app\index\model\Config as ConfigModel;
use think\Request;

class Config extends Base
{
    /*配置列表模板*/
    public function configList()
    {
        $this->view->assign('title', '配置列表');
        $this->view->assign('keywords', '配置列表关键字');
        $this->view->assign('description', '配置列表描述');

        $this->view->count = ConfigModel::count();
        $this->view->assign('count', $this->view->count);

        $list = ConfigModel::all();//获取所有配置项列表

        $this->view->assign('list', $list);
        return $this->fetch();
    }

    /*配置添加模板*/
    public function configAdd()
    {
        /*模板赋值*/
        $this->view->assign('title', '配置添加');
        $this->view->assign('keywords', '配置添加关键字');
        $this->view->assign('description', '配置添加描述');
        /*渲染模板*/
        return $this->fetch();
    }

    /*配置增加保存*/
    public function configAddSave(Request $request)
    {
        //获取传入参数
        $attr = $request->param();
        //数据处理

        //将字符串转为索引数组
        $attr['file_name'] = explode ( ',', $attr['file_name'] );
        $attr['description'] = explode ( ';', $attr['description'] );
        //使用模型save方法,返回bool值
        $object = new ConfigModel;
        //返回bool值
        $data = $object->save($attr);
        if ($data) {
            //json格式输出
            return json_encode($object);
        }else{
            return null ;
        }
    }

    /**
     * @name setStatus
     * @decs 设定状态
     * @abstract 申明变量/类/方法
     * @access public protected static
     * 关联数据库：
     * @param Request $request
     * User: yliang_liu
     * Created on: 2019/4/19 14:01
     */
    public function setStatus(Request $request)
    {
        //获取传入公共模板id
        $id = $request->param('id');

        //查询数据表
        $model=ConfigModel::get($id);
        //启用和禁用状态处理
        if ($model->status == '已启用') {
            ConfigModel::update(['status' => 0], ['id' => $id]);
        } else {
            ConfigModel::update(['status' => 1], ['id' => $id]);
        }
    }

    /*配置编辑模板*/
    public function configEdit(Request $request)
    {
        /*模板赋值*/
        $this->view->assign('title', '配置编辑');
        $this->view->assign('keywords', '配置编辑关键字');
        $this->view->assign('description', '配置编辑描述');
        //获取传入参数
        $attr = $request->param();
        $id = $attr['id'];
        //使用模型get方法,返回model对象
        $result = ConfigModel::get(['id' => $id]);
        //模板赋值
        $this->view->assign('data', $result);
        /*渲染模板*/
        return $this->fetch();
    }

    /*配置角色编辑*/
    public function configEditSave(Request $request)
    {
        //获取传入参数
        $attr = $request->param();
        //将字符串转为索引数组
        $attr['file_name'] = explode ( ',', $attr['file_name'] );
        $attr['description'] = explode ( '|', $attr['description'] );
        $id = $attr['id'];
        //使用模型save方法,返回bool值
        $object = new ConfigModel;
        $object->isUpdate()->save($attr, ['id' => $id]);
        return $object;
    }

    /*配置删除*/
    public function configDelete(Request $request)
    {

        //获取传入参数
        $attr = $request->param();
        $id = $attr['id'];

        //使用模型update方法,将is_delete字段修改为1
        ConfigModel::update(['is_delete' => 1], ['id' => $id]);

        //使用模型destroy方法,软删除,修改delete_time字段
        $result = ConfigModel::destroy(['id' => $id]);
        $data = $result ? ['flag' => 1] : ['flag' => -1];
        return json_encode($data);//以json格式输出;
    }

    /*配置删除恢复*/
    public function unDelete()
    {
        //软删除恢复
        $list = ConfigModel::withTrashed()->select(['is_delete' => 1]);
        $data=0;
        foreach ($list as $config){
            $config->restore();
            $config->is_delete=0;
            $data+=$config->save();
        }

        $data = $data ? ['flag' => 1] : ['flag' => -1];
        return json_encode($data);//以json格式输出;
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

        $list = ConfigModel::get($id);//获取传入配置项信息

        $this->view->assign('vo', $list);
        return $this->fetch();
    }

    public function initLoad($id)
    {

        //获取传入参数
        $list = ConfigModel::get($id);//获取传入配置项信息
        //第一步根据参数explorer打开目录
        //对啊 为啥要写.bat呢 直接运行把，不如
        $name=$list['name'];
        $dir=$list['dir'];
        $version=$list['version'];
        $description=$list['description'];
        $type=$list['type'];
        $workEnv=$list['work_env'];
        $fileName=$list['file_name'];

        //进入配置项目录
        $somecontent = "cd $dir";
        //克隆配置git管理项目至临时文件夹
        $somecontent.="&& git clone git@github.com:liuyuliang1108/myconfig.git tmpgit";
        if ($type=='win') {
            //将tmpgit内文件及文件夹移出，再删除目录
            $somecontent.="&& move tmpgit\*.* ./";
            //将.git隐藏文件夹 去除隐藏状态
            $somecontent.="&& attrib -h tmpgit\.git";
            $somecontent.="&& move tmpgit\.git ./";
            //再将.git文件夹加上隐藏状态
            $somecontent.="&& attrib +h .git";
            //删除临时文件夹
            $somecontent.="&& rd tmpgit";
            //更新git管理文件夹状态
            $somecontent.="&& git reset --hard HEAD";
            //创建本地分支
            $somecontent.="&& git branch $type-$name-$workEnv";
            //切换本地分支
            $somecontent.="&& git checkout $type-$name-$workEnv";
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
        }elseif($type=='linux'){
            //给tmpgit执行权限
            $somecontent .= "&& chmod +x tmpgit";
            //将tmpgit内文件及文件夹移出，再删除目录
            $somecontent .= "&& mv tmpgit/* .";
            $somecontent .= "&& mv tmpgit/.[^.]* .";
            //删除临时文件夹
            $somecontent .= "&& rmdir tmpgit";
            //更新git管理文件夹状态
            $somecontent .= "&& git reset --hard HEAD";
            //创建本地分支
            $somecontent.="&& git branch $type-$name-$workEnv";
            //切换本地分支
            $somecontent.="&& git checkout $type-$name-$workEnv";
            //修改.gitignore文件
            $somecontent .= "&& echo ''>>.gitignore";
            //遍历配置文件名,写入git管理目录
            foreach ($fileName as $value){
                $somecontent.="&& echo '!$value' >>.gitignore";
            }
            //修改README.md文件
            $somecontent.="&& echo ''>>README.md";
            $somecontent.="&& echo '----------------------------------------------------------'>>README.md";
            $somecontent.="&& echo '@config-title : $name'>>README.md";
            $somecontent.="&& echo '@config-work_env : $workEnv'>>README.md";
            $somecontent.="&& echo '@version : v$version'>>README.md";
            $somecontent.="&& echo '@version : v$version'>>README.md";
            $somecontent.="&& echo '@type : $type'>>README.md";
            $desc= implode(';',$description);
            $somecontent.="&& echo '@description : $desc'>>README.md";
        }

        $somecontent.="&& git add .";
        $somecontent.="&& git commit -m 'v$version,$desc'";
        $somecontent.="&& git push -u origin $type-$name-$workEnv:$type-$name-$workEnv";
        $data=['cmd'=>$somecontent];
        return $data;

    }
    public function execCmd($flag,$id,$attr){
        if ($flag==1) {
            $data=self::initLoad($id);//获取初始化命令
        }elseif($flag==0){
            $data=self::copyGit($attr,$id);//获取copy命令
        }elseif($flag==2){
            $data=self::updateCmd($id,0);
        }

        //执行命令
        exec($data['cmd'],$output,$flag);
        $data['flag'] = $flag ? 0 : 1;
        return $data;
    }

    /*config工作环境编辑*/
    public function copyToGit($id)
    {
        //使用模型get方法,返回model对象
        $model = ConfigModel::get(['id' => $id]);
        //模板赋值
        $this->view->assign('data', $model);
        /*渲染模板*/
        return $this->fetch();
    }
    public function cloneGit(Request $request){
        //获取传入参数
        $attr = $request->param();
        //数据处理

        //将字符串转为索引数组
        $attr['file_name'] = explode ( ',', $attr['file_name'] );
        $attr['description'] = explode ( ';', $attr['description'] );


    }

    /*config更新版本*/
    public function updateVersion($id,$flag)
    {
        //使用模型get方法,返回model对象
        $result = ConfigModel::get(['id' => $id]);
        //模板赋值
        $this->view->assign('data', $result);
        $this->view->assign('flag', $flag);
        /*渲染模板*/
        return $this->fetch();
    }

    public function copyGit($copyId,$id){

        //使用模型get方法,返回model对象
        $copy=ConfigModel::get(['id' => $copyId]);
        $list = ConfigModel::get(['id' => $id]);
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
        $somecontent = "cd $dir";
        $somecontent.="&& git clone -b $copyType-$copyName-$copyWorkEnv git@github.com:liuyuliang1108/myconfig.git tmpgit";
        if ( $type=='win') {
            //将tmpgit内文件及文件夹移出，再删除目录
            $somecontent.="&& move tmpgit\*.* ./";
            //将.git隐藏文件夹 去除隐藏状态
            $somecontent.="&& attrib -h tmpgit\.git";
            $somecontent.="&& move tmpgit\.git ./";
            //再将.git文件夹加上隐藏状态
            $somecontent.="&& attrib +h .git";
            //删除临时文件夹
            $somecontent.="&& rd tmpgit";
            //更新git管理文件夹状态
            $somecontent.="&& git reset --hard HEAD";
            //创建本地分支
            $somecontent.="&& git branch $type-$name-$workEnv";
            //切换本地分支
            $somecontent.="&& git checkout $type-$name-$workEnv";
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
        }elseif($type=='linux'){
            //给tmpgit执行权限
            $somecontent .= "&& chmod +x tmpgit";
            //将tmpgit内文件及文件夹移出，再删除目录
            $somecontent .= "&& mv tmpgit/* .";
            $somecontent .= "&& mv tmpgit/.[^.]* .";
            //删除临时文件夹
            $somecontent .= "&& rmdir tmpgit";
            //更新git管理文件夹状态
            $somecontent .= "&& git reset --hard HEAD";
            //创建本地分支
            $somecontent.="&& git branch $type-$name-$workEnv";
            //切换本地分支
            $somecontent.="&& git checkout $type-$name-$workEnv";
            //修改.gitignore文件
            $somecontent .= "&& echo ''>>.gitignore";
            //遍历配置文件名,写入git管理目录
            foreach ($fileName as $value){
                $somecontent.="&& echo '!$value' >>.gitignore";
            }
            //修改README.md文件
            $somecontent.="&& echo ''>>README.md";
            $somecontent.="&& echo '----------------------------------------------------------'>>README.md";
            $somecontent.="&& echo '@config-title : $name'>>README.md";
            $somecontent.="&& echo '@config-work_env : $workEnv'>>README.md";
            $somecontent.="&& echo '@version : v$version'>>README.md";
            $somecontent.="&& echo '@version : v$version'>>README.md";
            $somecontent.="&& echo '@type : $type'>>README.md";
            $desc= implode(';',$description);
            $somecontent.="&& echo '@description : $desc'>>README.md";
        }

        //copy提交后再比较修改，再提交
        $somecontent.="&& git add . ";
        $somecontent.="&& git commit -m 'v$version,$desc'";
        $somecontent.="&& git push -u origin $type-$name-$workEnv:$type-$name-$workEnv";
        $data=['cmd'=>$somecontent];
        return $data;
    }

//生成更新版本命令
    public function updateCmd($id,$attr,$flag){
        if (!$attr) {//修改版本启用状态信息
            //使用模型save方法,返回bool值
            $object = new ConfigModel;
            $object->isUpdate()->save(['status' => 0], ['id' => $attr]);
        }
        //使用模型get方法,返回model对象
        $list = ConfigModel::get(['id' => $id]);
        $name=$list['name'];
        $dir=$list['dir'];
        $version=$list['version'];
        $description=$list['description'];
        $type=$list['type'];
        $workEnv=$list['work_env'];
        $fileName=$list['file_name'];
        $somecontent = "cd $dir";
        if ($flag==0) {
            $somecontent.="&& git clone -b $type-$name-$workEnv git@github.com:liuyuliang1108/myconfig.git tmpgit";
            if ( $type=='win') {
                //将tmpgit内文件及文件夹移出，再删除目录
                $somecontent.="&& move tmpgit\*.* ./";
                //将.git隐藏文件夹 去除隐藏状态
                $somecontent.="&& attrib -h tmpgit\.git";
                $somecontent.="&& move tmpgit\.git ./";
                //再将.git文件夹加上隐藏状态
                $somecontent.="&& attrib +h .git";
                //删除临时文件夹，以及被git控制文件
                $somecontent.="&&rd /s/q tmpgit";
                //更新git管理文件夹状态
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
            }elseif($type=='linux'){
                //给tmpgit执行权限
                $somecontent .= "&& chmod +x tmpgit";
                //将tmpgit内文件及文件夹移出，再删除目录
                $somecontent .= "&& mv tmpgit/* .";
                $somecontent .= "&& mv tmpgit/.[^.]* .";
                //删除临时文件夹，以及被git控制文件
                $somecontent .= "&& rm -rf tmpgit";
                //更新git管理文件夹状态
                $somecontent .= "&& git reset --hard HEAD";

                //修改README.md文件
                $somecontent.="&& echo ''>>README.md";
                $somecontent.="&& echo '----------------------------------------------------------'>>README.md";
                $somecontent.="&& echo '@config-title : $name'>>README.md";
                $somecontent.="&& echo '@config-work_env : $workEnv'>>README.md";
                $somecontent.="&& echo '@version : v$version'>>README.md";
                $somecontent.="&& echo '@version : v$version'>>README.md";
                $somecontent.="&& echo '@type : $type'>>README.md";
                $desc= implode(';',$description);
                $somecontent.="&& echo '@description : $desc'>>README.md";
            }
        }elseif($flag==1){
            if ( $type=='win') {
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
            }elseif($type=='linux'){
                //修改README.md文件
                $somecontent.="&& echo ''>>README.md";
                $somecontent.="&& echo '----------------------------------------------------------'>>README.md";
                $somecontent.="&& echo '@config-title : $name'>>README.md";
                $somecontent.="&& echo '@config-work_env : $workEnv'>>README.md";
                $somecontent.="&& echo '@version : v$version'>>README.md";
                $somecontent.="&& echo '@version : v$version'>>README.md";
                $somecontent.="&& echo '@type : $type'>>README.md";
                $desc= implode(';',$description);
                $somecontent.="&& echo '@description : $desc'>>README.md";
            }

        }elseif($flag==2){
            if ( $type=='win') {
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
            }elseif($type=='linux'){
                //修改README.md文件
                $somecontent.="&& echo ''>>README.md";
                $somecontent.="&& echo '----------------------------------------------------------'>>README.md";
                $somecontent.="&& echo '@config-title : $name'>>README.md";
                $somecontent.="&& echo '@config-work_env : $workEnv'>>README.md";
                $somecontent.="&& echo '@version : v$version'>>README.md";
                $somecontent.="&& echo '@version : v$version'>>README.md";
                $somecontent.="&& echo '@type : $type'>>README.md";
                $desc= implode(';',$description);
                $somecontent.="&& echo '@description : $desc'>>README.md";
            }
        }

        //提交版本修改
        $somecontent.="&& git add . ";
        $somecontent.="&& git commit -m 'v$version,$desc'";
        $somecontent.="&& git push -u origin $type-$name-$workEnv:$type-$name-$workEnv";
        $data=['cmd'=>$somecontent];
        return $data;
    }
//生成初始更新版本命令
    public function initUpdateCmd($id,$attr){
        if (!$attr) {//修改版本启用状态信息
            //使用模型save方法,返回bool值
            $object = new ConfigModel;
            $object->isUpdate()->save(['status' => 0], ['id' => $attr]);
        }
        //使用模型get方法,返回model对象
        $list = ConfigModel::get(['id' => $id]);
        $name=$list['name'];
        $dir=$list['dir'];
        $version=$list['version'];
        $description=$list['description'];
        $type=$list['type'];
        $workEnv=$list['work_env'];
        $fileName=$list['file_name'];
        $somecontent = "cd $dir";
        $somecontent.="&& git clone -b $type-$name-$workEnv git@github.com:liuyuliang1108/myconfig.git tmpgit";
        if ( $type=='win') {
            //将tmpgit内文件及文件夹移出，再删除目录
            $somecontent.="&& move tmpgit\*.* ./";
            //将.git隐藏文件夹 去除隐藏状态
            $somecontent.="&& attrib -h tmpgit\.git";
            $somecontent.="&& move tmpgit\.git ./";
            //再将.git文件夹加上隐藏状态
            $somecontent.="&& attrib +h .git";
            //删除临时文件夹，以及被git控制文件
            $somecontent.="&&rd /s/q tmpgit";
            //更新git管理文件夹状态
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
        }elseif($type=='linux'){
            //给tmpgit执行权限
            $somecontent .= "&& chmod +x tmpgit";
            //将tmpgit内文件及文件夹移出，再删除目录
            $somecontent .= "&& mv tmpgit/* .";
            $somecontent .= "&& mv tmpgit/.[^.]* .";
            //删除临时文件夹，以及被git控制文件
            $somecontent .= "&& rm -rf tmpgit";
            //更新git管理文件夹状态
            $somecontent .= "&& git reset --hard HEAD";

            //修改README.md文件
            $somecontent.="&& echo ''>>README.md";
            $somecontent.="&& echo '----------------------------------------------------------'>>README.md";
            $somecontent.="&& echo '@config-title : $name'>>README.md";
            $somecontent.="&& echo '@config-work_env : $workEnv'>>README.md";
            $somecontent.="&& echo '@version : v$version'>>README.md";
            $somecontent.="&& echo '@version : v$version'>>README.md";
            $somecontent.="&& echo '@type : $type'>>README.md";
            $desc= implode(';',$description);
            $somecontent.="&& echo '@description : $desc'>>README.md";
        }
        $somecontent.="&& git add .";
        $somecontent.="&& git commit -m 'v$version,$desc'";
        $somecontent.="&& git push -u origin $type-$name-$workEnv:$type-$name-$workEnv";
        $data=['cmd'=>$somecontent];
        return $data;
    }
//打开配置目录

    public function openDir(Request $request)
    {
        //获取传入参数
        $attr = $request->param();
        $id = $attr['id'];

        $list = ConfigModel::get($id);//获取传入配置项信息
        $dir=$list['dir'];

        exec("start explorer $dir",$out,$result);
        if ($result==0) {
            return ['data'=>true];
        }
    }
}