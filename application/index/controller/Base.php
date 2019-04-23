<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/27
 * Time: 15:25
 */

namespace app\index\controller;

use think\Controller;
use think\facade\Session;

class Base extends Controller
{
    //定义一些公共方法和系统常量
   public function initialize(){
       parent::initialize();
       define('USER_ID',Session::get('user_id'));
    }

    //判断用户是否登录，用在入口位置，category/index
    protected function isLogin(){
        if (empty(USER_ID)) {
            $this->error('用户未登录，无权访问',url('index/login'));
        }
    }

    //防止用户重复登录
    protected function alreadyLogin(){
        if (!empty(USER_ID)) {
            $this->error('用户已登录，请勿重复登录',url('index/index'));
        }
    }
    /**
     * 创建方法
     * @access protected
     * @param  string $module 模块名
     * @param  string $controller 控制器名
     * @param  string $model 模型名
     * @param  string $action 方法名
     * @param  string $name 中文名称
     * @param  bool   $suffix 类库后缀
     * @return void
     */
    protected function buildAction($module, $controller, $model,$action,$name, $suffix = false)
    {
        if ($controller==$model) {
            //如果模型与控制器名相同，则给模型设置一个别名
            $model=$model.'Model';
        }
        $actionName=$action;
        //控制器名大驼峰转小驼峰
        $littleController=lcfirst($controller);
        //控制器名大驼峰转下划线
        $underlineController=humpToLine($controller);
        //判断是否有对应基础方法
        $filename=APP_PATH. 'common' . '/'. 'action' . '/' . $action. '.php';
        if (!is_file($filename)){
            $action='Base';
        }
        $filename = APP_PATH . ($module ? $module . '/' : '') . 'controller' . '/' . $controller . ($suffix ? 'Controller' : '') . '.php';

        $content = file_get_contents( APP_PATH. 'common' . '/'. 'action' . '/' . $action. '.php');
        $content = str_replace(['{$littleController}', '{$model}', '{$name}','{$actionName}'], [$littleController,$model,$name,$actionName], $content);
        //判断写入文件目录是否存在
        if(!is_dir(APP_PATH . ($module ? $module . '/' : '') . 'view' . '/' . $underlineController . ($suffix ? 'Model' : ''))){
            mkdir((APP_PATH . ($module ? $module . '/' : '') . 'view' . '/' . $underlineController . ($suffix ? 'Model' : '')));
        }
        file_put_contents($filename, $content.PHP_EOL, FILE_APPEND);

    }

    /**
     * @name buildTpl
     * @decs
     * @abstract 申明变量/类/方法
     * @access protected
     * 关联数据库：
     * @param $module
     * @param $controller
     * @param $action
     * @param $name
     * @param $attr
     * @param bool $suffix
     * User: yliang_liu
     * Created on: 2019/4/19 10:33
     */
    protected function buildTpl($module, $controller,$action,$name,$attr, $suffix = false)
    {
        //控制器名大驼峰转小驼峰
        $littleController=lcfirst($controller);
        //控制器名大驼峰转下划线
        $underlineController=humpToLine($controller);
        $tplName=humpToLine($controller.$action);
        //判断是否有对应基础模板
        $filename=APP_PATH. 'common' . '/'. 'tpl' . '/' . $action. '.html';
        if (!is_file($filename)){
            $action='Base';
        }
        //判断是否需控制器名前缀
        if ($attr) {
            $filename = APP_PATH . ($module ? $module . '/' : '') . 'view' . '/' . $underlineController . ($suffix ? 'Model' : '') .'/' .$tplName. '.html';
        }else{
            $filename = APP_PATH . ($module ? $module . '/' : '') . 'view' . '/' .$tplName. '.html';
        }

        $content = file_get_contents( APP_PATH. 'common' . '/'. 'tpl' . '/' . $action. '.html');
        $content = str_replace(['{$littleController}','{$underlineController}','{$name}'], [$littleController,$underlineController,$name], $content);
        //判断写入文件目录是否存在
        if(!is_dir(APP_PATH . ($module ? $module . '/' : '') . 'view' . '/' . $underlineController . ($suffix ? 'Model' : ''))){
            mkdir((APP_PATH . ($module ? $module . '/' : '') . 'view' . '/' . $underlineController . ($suffix ? 'Model' : '')));
        }
        file_put_contents($filename, $content.PHP_EOL, FILE_APPEND);

    }
    /**
     * 创建默认控制器
     * @access protected
     * @param  string $module 模块名
     * @param  string $controller 控制器名
     * @param  string $model 模型名
     * @param  string $name 中文名称
     * @param  bool   $suffix 类库后缀
     * @return void
     */
    protected function buildController($module, $controller,$model, $suffix = false)
    {
        if ($controller==$model) {
            //如果模型与控制器名相同，则给模型设置一个别名
            $model=$model.'Model';
        }
        $filename = APP_PATH . ($module ? $module . '/' : '') . 'controller' . '/' . $controller . ($suffix ? 'Controller' : '') . '.php';
        if (!is_file($filename)) {//如果已存在该文件则不创建
            $content = file_get_contents( APP_PATH. 'common' . '/'. 'controller' . '/' . 'Base' . '.php');
            $content = str_replace(['{$module}','{$model}' , '{$controller}'], [$module,$model,$controller], $content);
            file_put_contents($filename, $content);
        }
    }
    /**
     * 创建默认模型model
     * @access protected
     * @param  string $module 模块名
     * @param  string $model 模型名
     * @return void
     */
    protected function buildModel($module, $model)
    {
        $filename = APP_PATH . ($module ? $module . '/' : '') . 'model' . '/' .$model . '.php';
        if (!is_file($filename)) {//如果已存在该文件则不创建
            $content = file_get_contents( APP_PATH. 'common' . '/'. 'model' . '/' . 'Base' . '.php');
            $content = str_replace(['{$module}','{$model}'], [$module,$model], $content);
            file_put_contents($filename, $content);
        }
    }
    /**
     * 创建公共基础方法
     * @access protected
     * @param  string $baseName 基础方法名
     * @return void
     */
    protected function buildBaseAction($baseName)
    {
        $filename = APP_PATH  . 'common' . '/' .'action' . '/' . $baseName . '.php';
        if (!is_file($filename)) {//如果已存在该文件则不创建
            $content = '{$littleController}小驼峰控制器名'."\n".'{$name}控制器中文名'."\n".'{$model}模型名';
            file_put_contents($filename, $content);
        }
    }
    /**
     * 创建公共基础模板
     * @access protected
     * @param  string $baseName 基础模板名
     * @return void
     */
    protected function buildBaseTpl($baseName)
    {
        $filename = APP_PATH  . 'common' . '/' .'tpl' . '/' . $baseName . '.html';
        if (!is_file($filename)) {//如果已存在该文件则不创建
            $content = '{$littleController}小驼峰控制器名'."\n".'{$underlineController}下划线控制器名'."\n".'{$name}控制器中文名';
            file_put_contents($filename, $content);
        }
    }
}
