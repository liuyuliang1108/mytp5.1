/*{$name}增加保存*/
public function {$littleController}AddSave(Request $request)
{
    //获取传入参数
$attr = $request->param();
    //将字符串转为索引数组
$attr['file_name'] = explode ( ',', $attr['file_name'] );
$attr['description'] = explode ( ';', $attr['description'] );
    //使用模型save方法,返回bool值
$object = new {$model};
    //返回bool值
$data = $object->save($attr);
$data = $result ? ['flag' => 1] : ['flag' => -1];
return json_encode($data);//以json格式输出
}