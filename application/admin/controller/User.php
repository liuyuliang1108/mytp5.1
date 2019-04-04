<?php

namespace app\admin\controller;

use app\admin\controller\Base;
use think\captcha\CaptchaController;
use app\admin\model\User as UserModel;
use think\Request;

/*继承基类控制器*/

class User extends Base
{
    /*登录界面*/
    public function login()
    {

        /*渲染模板*/
        return $this->fetch();
    }

    /**
     * $this->validate()
     * @param $data $rule $msq
     */
    /*验证登录*/
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
            $user=UserModel::get($map);
            if ($user==null) {
                $result='没有找到该用户';
            }else{
                $status=1;
                $result='验证通过，点击[确定]后进入';
            }
        }
        return ['status' => $status, 'message' => $result, 'data' => $data];

    }

    /*退出登录*/
    public function logout()
    {


    }

    //验证码
    public function verify()
    {
        //使用扩展内置的方法生成验证码
        $captcha = new CaptchaController();
        $result = $captcha->index();
        return $result;
    }
}