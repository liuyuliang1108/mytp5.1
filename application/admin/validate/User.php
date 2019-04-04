<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/28
 * Time: 18:46
 */

namespace app\index\validate;

use think\Validate;

class User extends Validate
{
    protected $rule = [
        'captcha|验证码'=>'require|captcha',
    ];

}