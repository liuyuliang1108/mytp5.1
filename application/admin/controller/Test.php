<?php
namespace app\admin\controller;
use \app\admin\controller\Base;
use think\facade\Cache;
use think\Request;
use think\Db;
class Test extends Base
{
//测试输出专用
    public function output()
    {
        // 获取`think_user`表所有信息
        $result=Db::table('grade');
        $keys= $result->getTableFields();

        $object=$this->arrayToObject($keys);
        foreach ($keys as $attr){
            $sub=substr($attr,0,strlen($attr)-3);
            if (substr($attr,-3)=='_id') {
                if ($attr=='child_id'||$attr=='parent_id') {
                    $data["$attr"]=$object->$attr;
                }else{
                    $data["$sub"]= 'isset($value->'.$sub.'->name) ? $value->'.$sub."->name : "."'".'<span style="color:red;">未分配</span>';
                }
            }else{
                if ($attr=='create_time'||$attr=='update_time'||$attr=='delete_time'||$attr=='is_delete') {

                }else{
                    $data["$attr"]=$object->$attr;
                }
            }
        }

        dump($keys);
       var_export($data);

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
    public function redis(){

        dump(Cache::store('redis')->set('sfdsf','yingying11111',1000000));
    }
}

/** 打开windows的计算器 */
//exec('start C:WindowsSystem32calc.exe');