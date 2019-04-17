/*{$name}列表模板*/
public function {$controllerName}List()
{
/*模板赋值*/
$this->view->assign('title', '{$name}列表');
$this->view->assign('keywords', '{$name}列表关键字');
$this->view->assign('description', '{$name}列表描述');

/*获取所有{$name}列表数据 返回对象数组*/
${$controllerName} = {$model}::all();

/*获取记录总数*/
$count = {$model}::count();

/*遍历{$controllerName}表*/
foreach (${$controllerName} as $value) {
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

/*每次关联查询结果保存到数组${$controllerName}List中*/
${$controllerName}List[] = $data;
}