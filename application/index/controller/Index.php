<?php

namespace app\index\controller;


use think\Request;
use think\facade\Session;
use app\index\model\User as UserModel;
use app\admin\model\CategoryList;
use app\common\func\PhpTree;
use \app\index\controller\Base;

class Index extends Base
{
    /**
     * @name index
     * @decs 首页
     * @access public
     * 关联model：
     * @return mixed
     * User: Administrator
     * Created on: 2019/4/20 9:39
     */
    public function index()
    {
      //  $this->isLogin();//判断用户是否登录

        return $this->fetch('');
    }
    public function getTree(){
        $model= new CategoryList;
        $result=$model->field(['id','child_id','parent_id','name','url'])->all();

        //将分类信息遍历成数组
        foreach ($result as $key=>$value){
            $data[]=$value->toArray();
        }
        //将数据进行
        $data = PhpTree::makeTree($data,array(
            'expanded' => true
        ));
        return json_encode($data);
    }

    /**
     * @name main
     * @decs 主页
     * @access public
     * 关联model：
     * @return mixed
     * User: Administrator
     * Created on: 2019/4/20 9:40
     */
    public function main()
    {

        return $this->fetch();
    }

    /**
     * @name login
     * @decs 登录页面
     * @access public
     * 关联model：
     * @return mixed
     * User: Administrator
     * Created on: 2019/4/20 9:41
     */
    public function login()
    {
        $this->alreadyLogin();//防止用户重复登录
        return $this->fetch();
    }

    /**
     * @name checkLogin
     * @decs 校验登录
     * @access public
     * 关联model：app\index\model\User as UserModel
     * @param Request $request
     * @return array
     * User: Administrator
     * Created on: 2019/4/20 9:41
     */
    public function checkLogin(Request $request)
    {
        //初始返回参数
        $status = 0;
        $result = '';
        $data = $request->param();

        //设置验证规则
        $rule = [
            'name' => 'require',
            'password' => 'require',
            'verify|验证码' => 'require|captcha',//必填
        ];

        //自定义验证提示信息
        $msq = [
            'name' => ['require' => '姓名不能为空'],
            'password' => ['require' => '密码不能为空'],
            'verify' => ['require' => '验证码不能为空', 'captcha' => '验证码输入错误，请检查！'],
        ];

        //进行验证
        $result = $this->validate($data, $rule, $msq);

        //如果验证成功，返回为true,则提交到数据库中进行处理
        if ($result === true) {
            //构造查询条件
            $map = [
                'name' => $data['name'],
                'password' => $data['password'],
            ];
            //查询用户信息
            $user = UserModel::get($map);
            if ($user == null) {
                $result = '没有找到该用户';
            } else {
                $status = 1;
                $result = '验证通过，点击[确定]后进入';

                //用session设置用户登录信息
                Session::set('user_id', $user->id);//用户id
                Session::set('user_info', $user->getdata());//用户所有信息
            }
        }
        return ['status' => $status, 'message' => $result, 'data' => $data];

    }

    /*退出登录*/
    /**
     * @name logout
     * @decs 退出
     * 关联model：
     * User: Administrator
     * Created on: 2019/4/20 13:48
     */
    public function logout()
    {
        //注销session
        Session::delete('user_id');
        Session::delete('user_info');
        $this->success('已注销登录，正在返回', 'index/login');

    }

}
