<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/28
 * Time: 11:47
 */

// +----------------------------------------------------------------------
// | 验证码设置
// +----------------------------------------------------------------------
return
[
            // 验证码字符集合
       'codeSet'  => '2345678abcdefhijkmnpqrstuvwxyzABCDEFGHJKLMNPQRTUVWXY',
        // 验证码字体大小(px)
        'fontSize' => 25,
        // 是否画混淆曲线
        'useCurve' => true,
         // 验证码图片高度
        'imageH'   => 40,
        // 验证码图片宽度
       'imageW'   => 150,
        // 验证码位数
       'length'   => 4,
        // 验证成功后是否重置
        'reset'    => true,
    'secure' => false,
];
