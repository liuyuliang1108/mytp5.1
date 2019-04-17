/*设定状态*/
public function setStatus(Request $request)
{
//获取传入{$name}id
${$controllerName} = $request->param('id');

//查询数据表
$result = {$model}::get(${$controllerName}_id);

//启用和禁用状态处理
if ($result->getDate('status') == 1) {
{$model}::update(['status' => 0], ['id' => {$controllerName}_id]);
} else {
{$model}::update(['status' => 1], ['id' => {$controllerName}_id]);
}
}