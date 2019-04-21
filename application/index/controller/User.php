<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/17
 * Time: 21:20
 */
namespace app\index\controller;

use \app\index\controller\Base;
use app\index\model\UserModel as UserModelModel;
use think\Request;

class User extends Base
{


}/*管理员管理模板*/
public function UserList()
{
/*模板赋值*/
$this->view->assign('title', '管理员管理');
$this->view->assign('keywords', '管理员管理关键字');
$this->view->assign('description', '管理员管理描述');

/*获取所有管理员管理数据 返回对象数组*/
$User = UserModel::all();

/*获取记录总数*/
$count = UserModel::count();

/*遍历User表*/
foreach ($User as $value) {
$data = [
'id' => $value->id,
'name' => $value->name,
'length' => $value->length,
'price' => $value->price,
'status' => $value->status,
'create_time' => $value->create_time,
/*用关联方法teacher属性方法访问teacher表中数据*/
'teacher' => isset($value->teacher->name) ? $value->teacher->name : '<span style="color:red;">未分配</span>'
];

/*每次关联查询结果保存到数组$UserList中*/
$UserList[] = $data;
}
/*模板赋值*/
$this->view->assign('UserList', $UserList);
$this->view->assign('count', $count);

/*渲染模板*/
return $this->fetch();
}
/*管理员管理模板*/
public function UserList()
{
/*模板赋值*/
$this->view->assign('title', '管理员管理');
$this->view->assign('keywords', '管理员管理关键字');
$this->view->assign('description', '管理员管理描述');

/*获取所有管理员管理数据 返回对象数组*/
$User = UserModel::all();

/*获取记录总数*/
$count = UserModel::count();

/*遍历User表*/
foreach ($User as $value) {
$data = [
'id' => $value->id,
'name' => $value->name,
'length' => $value->length,
'price' => $value->price,
'status' => $value->status,
'create_time' => $value->create_time,
/*用关联方法teacher属性方法访问teacher表中数据*/
'teacher' => isset($value->teacher->name) ? $value->teacher->name : '<span style="color:red;">未分配</span>'
];

/*每次关联查询结果保存到数组$UserList中*/
$UserList[] = $data;
}
/*模板赋值*/
$this->view->assign('UserList', $UserList);
$this->view->assign('count', $count);

/*渲染模板*/
return $this->fetch();
}
/*管理员管理模板*/
public function UserList()
{
/*模板赋值*/
$this->view->assign('title', '管理员管理');
$this->view->assign('keywords', '管理员管理关键字');
$this->view->assign('description', '管理员管理描述');

/*获取所有管理员管理数据 返回对象数组*/
$User = UserModel::all();

/*获取记录总数*/
$count = UserModel::count();

/*遍历User表*/
foreach ($User as $value) {
$data = [
'id' => $value->id,
'name' => $value->name,
'length' => $value->length,
'price' => $value->price,
'status' => $value->status,
'create_time' => $value->create_time,
/*用关联方法teacher属性方法访问teacher表中数据*/
'teacher' => isset($value->teacher->name) ? $value->teacher->name : '<span style="color:red;">未分配</span>'
];

/*每次关联查询结果保存到数组$UserList中*/
$UserList[] = $data;
}
/*模板赋值*/
$this->view->assign('UserList', $UserList);
$this->view->assign('count', $count);

/*渲染模板*/
return $this->fetch();
}
/*管理员管理模板*/
public function UserList()
{
/*模板赋值*/
$this->view->assign('title', '管理员管理');
$this->view->assign('keywords', '管理员管理关键字');
$this->view->assign('description', '管理员管理描述');

/*获取所有管理员管理数据 返回对象数组*/
$User = UserModel::all();

/*获取记录总数*/
$count = UserModel::count();

/*遍历User表*/
foreach ($User as $value) {
$data = [
'id' => $value->id,
'name' => $value->name,
'length' => $value->length,
'price' => $value->price,
'status' => $value->status,
'create_time' => $value->create_time,
/*用关联方法teacher属性方法访问teacher表中数据*/
'teacher' => isset($value->teacher->name) ? $value->teacher->name : '<span style="color:red;">未分配</span>'
];

/*每次关联查询结果保存到数组$UserList中*/
$UserList[] = $data;
}
/*模板赋值*/
$this->view->assign('UserList', $UserList);
$this->view->assign('count', $count);

/*渲染模板*/
return $this->fetch();
}
/*设定状态*/
public function setStatus(Request $request)
{
//获取传入管理员管理id
$id = $request->param('id');

//查询数据表
$result = UserModel::get($id);

//启用和禁用状态处理
if ($result->status == '已启用') {
UserModel::update(['status' => 0], ['id' => $id]);
} else {
UserModel::update(['status' => 1], ['id' => $id]);
}
}
/*管理员管理删除*/
public function UserDelete(Request $request)
{

//获取传入参数
$attr = $request->param();
$id = $attr['id'];
//使用模型update方法,返回model对象,软删除
$data= UserModel::destroy(['id' => $id]);
//使用删除事件同时将is_delete字段修改为1
return $data;
}

/*管理员管理添加模板*/
public function UserAdd()
{
/*模板赋值*/
$this->view->assign('title', '管理员管理');
$this->view->assign('keywords', '管理员管理关键字');
$this->view->assign('description', '管理员管理描述');
/*渲染模板*/
return $this->fetch();
}
/*管理员管理增加保存*/
public function UserAddSave(Request $request)
{
    //获取传入参数
$attr = $request->param();
    //将字符串转为索引数组
$attr['file_name'] = explode ( ',', $attr['file_name'] );
$attr['description'] = explode ( ';', $attr['description'] );
    //使用模型save方法,返回bool值
$object = new UserModel;
    //返回bool值
$data = $object->save($attr);
if ($data) {
    //json格式输出
return json_encode($object);
}else{
return null ;
}
}
/*管理员管理编辑模板*/
public function UserEdit(Request $request)
{
/*模板赋值*/
$this->view->assign('title', '管理员管理编辑');
$this->view->assign('keywords', '管理员管理编辑关键字');
$this->view->assign('description', '管理员管理编辑描述');
//获取传入参数
$attr = $request->param();
$id = $attr['id'];
//使用模型get方法,返回model对象
$data = UserModel::get(['id' => $id]);
//模板赋值
$this->view->assign('info', $data);
/*渲染模板*/
return $this->fetch();
}
/*管理员管理编辑保存*/
public function UserEditSave(Request $request)
{
//获取传入参数
$attr = $request->param();
//将字符串转为索引数组
$attr['file_name'] = explode ( ',', $attr['file_name'] );
$attr['description'] = explode ( '|', $attr['description'] );
$id = $attr['id'];
//使用模型save方法,返回bool值
$model = new UserModel;
$data = $model->isUpdate()->save($attr, ['id' => $id]);
return $data;
}
/*管理员管理模板*/
public function UserList()
{
/*模板赋值*/
$this->view->assign('title', '管理员管理');
$this->view->assign('keywords', '管理员管理关键字');
$this->view->assign('description', '管理员管理描述');

/*获取所有管理员管理数据 返回对象数组*/
$User = User::all();

/*获取记录总数*/
$count = User::count();

/*遍历User表*/
foreach ($User as $value) {
$data = [
'id' => $value->id,
'name' => $value->name,
'length' => $value->length,
'price' => $value->price,
'status' => $value->status,
'create_time' => $value->create_time,
/*用关联方法teacher属性方法访问teacher表中数据*/
'teacher' => isset($value->teacher->name) ? $value->teacher->name : '<span style="color:red;">未分配</span>'
];

/*每次关联查询结果保存到数组$UserList中*/
$UserList[] = $data;
}
/*模板赋值*/
$this->view->assign('UserList', $UserList);
$this->view->assign('count', $count);

/*渲染模板*/
return $this->fetch();
}
