/*{$name}模板*/
public function {$littleController}List()
{
/*模板赋值*/
$this->view->assign('title', '{$name}');
$this->view->assign('keywords', '{$name}关键字');
$this->view->assign('description', '{$name}描述');

/*获取所有{$name}数据 返回对象数组*/
${$littleController} = {$model}::all();

/*获取记录总数*/
$count = {$model}::count();

/*遍历{$littleController}表*/
foreach (${$littleController} as $value) {
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

/*每次关联查询结果保存到数组$list中*/
$list[] = $data;
}
/*模板赋值*/
$this->view->assign('list', $list);
$this->view->assign('count', $count);

/*渲染模板*/
return $this->fetch();
}