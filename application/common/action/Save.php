{$littleController}小驼峰控制器名
{$name}控制器中文名
{$model}模型名
//保存{$name}
public function {$littleController}Save(Request $request)
{
$data = $request->param();
//数据处理
$data['title'] = trim($data['title']);
$data['child_id'] = trim($data['child_id']);
$data['content'] = $data['content'];
//将字符串转为索引数组
$data['keywords'] = explode(',', $data['keywords']);

//当新增以及所在分类关系改变时，自动生成order
if (array_key_exists('id', $data)) { //存在id键为更新操作
$result = {$model}::get($data['id']);
$cId = $result->child_id;
if (!$cId == $data['child_id']) {   //判断更新操作是否更新了分类关系
//自动获取order_id
$model = new {$model};
$orderId = $model->where(['child_id' => $data['child_id']])->max('order_id');
$data['order_id'] = $orderId ? $orderId + 1 : 1;//三元表达式，如果此类中不存在，则序号为1
} else {
$data['order_id'] = $result->order_id;
}
} else {
//自动获取order_id
$model = new {$model};
$orderId = $model->where(['child_id' => $data['child_id']])->max('order_id');
$data['order_id'] = $orderId ? $orderId + 1 : 1;//三元表达式，如果此类中不存在，则序号为1
}

$data['url'] = '?child_id=' . $data['child_id'] . '&order_id=' . $data['order_id'];
$data['status'] = trim($data['status']);


if (array_key_exists('id', $data)) {//调用本模型中的更新方法

$model = new {$model};
$result = $model->isUpdate()->save($data);

$data = $result ? ['flag' => 1] : ['flag' => -1];

} else {                                     //调用本模型中的新增方法
$model = new {$model};
$result = $model->save($data);
$data = $result ? ['flag' => 1] : ['flag' => -1];
}
return json($data);//以json格式输出;

}