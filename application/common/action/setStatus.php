/*设定状态*/
public function setStatus(Request $request)
{
//获取传入{$name}id
$id = $request->param('id');

//查询数据表
$result = {$model}::get($id);

//启用和禁用状态处理
if ($result->status == '已启用') {
{$model}::update(['status' => 0], ['id' => $id]);
} else {
{$model}::update(['status' => 1], ['id' => $id]);
}
}