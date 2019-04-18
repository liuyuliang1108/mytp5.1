
/*{$name}添加模板*/
public function {$littleController}Add()
{
/*模板赋值*/
$this->view->assign('title', '{$name}');
$this->view->assign('keywords', '{$name}关键字');
$this->view->assign('description', '{$name}描述');
/*渲染模板*/
return $this->fetch();
}