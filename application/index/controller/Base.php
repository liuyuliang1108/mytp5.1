<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/27
 * Time: 15:25
 */

namespace app\index\controller;
use think\Db;
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
            $modelName=$controller." as $model" ;
        }else{
            $modelName=$model;
        }
        $filename = APP_PATH . ($module ? $module . '/' : '') . 'controller' . '/' . $controller . ($suffix ? 'Controller' : '') . '.php';
        if (!is_file($filename)) {//如果已存在该文件则不创建
            $content = file_get_contents( APP_PATH. 'common' . '/'. 'controller' . '/' . 'Base' . '.php');
            $content = str_replace(['{$module}','{$model}' , '{$controller}','{$modelName}'], [$module,$model,$controller,$modelName], $content);
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
            $content ='{$model}模型名'."\n". '{$littleController}小驼峰控制器名'."\n".'{$name}控制器中文名';
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

    protected function replaceActionAttr($module, $controller, $model,$action,$name,$suffix = false){
        // 获取该表字段信息
        $result=Db::table($model);
        $keys= $result->getTableFields();

        //控制器名大驼峰转下划线
        $underlineController=humpToLine($controller);

        foreach ($keys as $attr){
            $sub=substr($attr,0,strlen($attr)-3);
            if (substr($attr,-3)=='_id') {
                if ($attr=='child_id'||$attr=='parent_id') {
                    $data["$attr"]='$value->'.$attr;
                }else{
                    $data["$sub"]= 'isset($value->'.$sub.'->name) ? $value->'.$sub."->name : "."'".'<span style="color:red;">未分配</span>';
                }
            }else{
                if ($attr=='create_time'||$attr=='update_time'||$attr=='delete_time'||$attr=='is_delete') {

                }else{
                    $data["$attr"]='$value->'.$attr;
                }
            }
        }


        $filename = APP_PATH . ($module ? $module . '/' : '') . 'controller' . '/' . $controller . ($suffix ? 'Controller' : '') . '.php';
        //判断写入文件目录是否存在,不存在创建目录
        if(!is_dir(APP_PATH . ($module ? $module . '/' : '') . 'view' . '/' . $underlineController . ($suffix ? 'Model' : ''))){
            mkdir((APP_PATH . ($module ? $module . '/' : '') . 'view' . '/' . $underlineController . ($suffix ? 'Model' : '')));
        }
        file_put_contents($filename, var_export($data,true).PHP_EOL, FILE_APPEND);

    }

    protected function replaceTplAttr($module, $controller,$model,$action,$name,$attr, $suffix = false){
        // 获取该表字段信息
        $result=Db::table($model);
        $keys= $result->getTableFields();
        //控制器名大驼峰转小驼峰
        $littleController=lcfirst($controller);
        //控制器名大驼峰转下划线
        $underlineController=humpToLine($controller);
        $tplName=humpToLine($controller.$action);
        $content='';
        $filename=APP_PATH. 'common' . '/'. 'tpl' . '/' . $action. '.html';

        foreach ($keys as $attr){

                if ($attr=='create_time'||$attr=='update_time'||$attr=='delete_time'||$attr=='is_delete') {

                }else{
                    $content.='<td>{$vo.'.$attr.'}</td>'."\n";
                }

        }
        //判断写入文件目录是否存在
        if(!is_dir(APP_PATH . ($module ? $module . '/' : '') . 'view' . '/' . $underlineController . ($suffix ? 'Model' : ''))){
            mkdir((APP_PATH . ($module ? $module . '/' : '') . 'view' . '/' . $underlineController . ($suffix ? 'Model' : '')));
        }
        file_put_contents($filename, $content.PHP_EOL, FILE_APPEND);
    }
    /**
     * 获取数据表信息
     * @access public
     * @param string $tableName 数据表名 留空自动获取
     * @param string $fetch 获取信息类型 包括 fields type bind pk
     * @return mixed
     */
    public function getTableInfo($tableName = '', $fetch = '')
    {
        static $_info = [];
        if (!$tableName) {
            $tableName = $this->getTable();
        }
        if (is_array($tableName)) {
            $tableName = key($tableName) ?: current($tableName);
        }
        if (strpos($tableName, ',')) {
            // 多表不获取字段信息
            return false;
        }
        $guid = md5($tableName);
        if (!isset($_info[$guid])) {
            $info   = $this->connection->getFields($tableName);
            $fields = array_keys($info);
            $bind   = $type   = [];
            foreach ($info as $key => $val) {
                // 记录字段类型
                $type[$key] = $val['type'];
                if (preg_match('/(int|double|float|decimal|real|numeric|serial)/is', $val['type'])) {
                    $bind[$key] = PDO::PARAM_INT;
                } elseif (preg_match('/bool/is', $val['type'])) {
                    $bind[$key] = PDO::PARAM_BOOL;
                } else {
                    $bind[$key] = PDO::PARAM_STR;
                }
                if (!empty($val['primary'])) {
                    $pk[] = $key;
                }
            }
            if (isset($pk)) {
                // 设置主键
                $pk = count($pk) > 1 ? $pk : $pk[0];
            } else {
                $pk = null;
            }
            $result       = ['fields' => $fields, 'type' => $type, 'bind' => $bind, 'pk' => $pk];
            $_info[$guid] = $result;
        }
        return $fetch ? $_info[$guid][$fetch] : $_info[$guid];
    }
   protected function arrayToObject($array) {
        if (is_array($array)) {
            $obj = new StdClass();
            foreach ($array as $key => $val){
                $obj->$key = $val;
            }
        }
        else { $obj = $array; }
        return $obj;
    }
}
