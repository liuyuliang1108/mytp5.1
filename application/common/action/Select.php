{$littleController}小驼峰控制器名
{$name}控制器中文名
{$model}模型名
//{$name}标题关键字列表展示
public function {$littleController}Select(Request $request)
{
$cId = 1111;
$result = $request->param();
if (array_key_exists('cId', $result)) {
$cId = $result['cId'];
}
//构造查询条件
$map = ['child_id' => $cId];

$model = new {$model};
//查询总文章数量
$count = $model->where($map)->count();
//如果该分类暂无文章，返回空
if (empty($count)) {
$data = '';
return $data;
} else {

//设置获取每页的文章条数
$limits = 2;

//获取当前页码，默认为第1页
$Nowpage = array_key_exists('page', $result) ? $result['page'] : 1;

//计算本页起始文章数
$start = ($Nowpage - 1) * $limits;

//获取本页所需文章所有数据
$data = $model->where($map)->limit($start, $limits)->all();


//循环输出原始数据
foreach ($data as $key => $value) {
$data[$key] = $data[$key]->getdata();
}

$this->assign('limits', $limits); //每页字符数
$this->assign('count', $count); //总字符

if (array_key_exists('page', $result)) {

return json($data);
}
}


$this->assign('child_id', $cId); //本类id
return $this->fetch('', ['data' => $data]);
}