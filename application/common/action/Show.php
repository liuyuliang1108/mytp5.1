{$model}模型名
{$littleController}小驼峰控制器名
{$name}控制器中文名
//{$name}文章内容展示
public function {$littleController}Show(Request $request)
{
//初始返回参数
$status = 0;
$result = '';

//获取文章编号，查询出内容
$data = $request->param();
$child = $data['child_id'];
$id = $data['order_id'];

//构造查询条件
$map = [
'child_id' => $child,
'order_id' => $id,
];

$model = new {$model}();
//查询此文章数据
$result = $model->where($map)->find();
if (empty($result)) {
$status = 0;
$result = '暂无查询记录';
return ['status' => $status, 'result' => $result];
} else {
$status = 1;
$value = $result->getData();
//将文章内容以分页符为标记，分隔为数组
$string = $value['content'];
$result = explode("|page|", $string);

//计算总页数
$count = count($result);

//获取当前页码
$Nowpage = array_key_exists('page', $data) ? $data['page'] : 1;

//输出当前页数据
$result = $result[$Nowpage - 1];

$this->assign('limits', 1); //每页显示一个分页符内容
$this->assign('count', $count); //总页数

if (array_key_exists('page', $data)) {

return json(['status' => $status, 'result' => $result]);
}
return $this->fetch('', ['data' => $value]);


}
}