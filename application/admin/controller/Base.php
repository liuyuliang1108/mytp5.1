<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/27
 * Time: 15:25
 */

namespace app\admin\controller;

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
            $this->error('用户未登录，无权访问',url('admin/user/login'));
        }
    }

    //防止用户重复登录
    protected function alreadyLogin(){
        if (!empty(USER_ID)) {
            $this->error('用户已登录，请勿重复登录',url('admin/category/index'));
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
        $controllerName=strtolower($controller);
        $filename = APP_PATH . ($module ? $module . '/' : '') . 'controller' . '/' . $controller . ($suffix ? 'Controller' : '') . '.php';
        if (is_file($filename)) {
            $content = file_get_contents( APP_PATH. 'common' . '/'. 'action' . '/' . $action. '.php');
            $content = str_replace(['{$controllerName}', '{$model}', '{$name}'], [$controllerName,$model,$name], $content);
            file_put_contents($filename, $content.PHP_EOL, FILE_APPEND);
        }
    }
    /**
     * 创建模型
     * @access protected
     * @param  string $module 模块名
     * @param  string $controller 控制器名
     * @param  string $model 模型名
     * @param  string $action 方法名
     * @param  string $name 中文名称
     * @param  bool   $suffix 类库后缀
     * @return void
     */
    protected function buildTpl($module, $controller,$action,$name, $suffix = false)
    {
        $controllerName=strtolower($controller);
        $tplName=humpToLine($controller.$action);
        $filename = APP_PATH . ($module ? $module . '/' : '') . 'view' . '/' . $controller . ($suffix ? 'Model' : '') .'/' .$tplName. '.html';
        if (is_file($filename)) {
            $content = file_get_contents( APP_PATH. 'common' . '/'. 'tpl' . '/' . $action. '.html');
            $content = str_replace(['{$controllerName}',  '{$name}'], [$controllerName,$name], $content);
            file_put_contents($filename, $content.PHP_EOL, FILE_APPEND);
        }
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
}
