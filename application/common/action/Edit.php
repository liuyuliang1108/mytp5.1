/*{$name}编辑模板*/
public function {$littleController}Edit(Request $request)
{
/*模板赋值*/
$this->view->assign('title', '{$name}编辑');
$this->view->assign('keywords', '{$name}编辑关键字');
$this->view->assign('description', '{$name}编辑描述');
//获取传入参数
$attr = $request->param();
$id = $attr['id'];
//使用模型get方法,返回model对象
$data = {$model}::get(['id' => $id]);
//模板赋值
$this->view->assign('data', $data);
/*渲染模板*/
return $this->fetch();
}
