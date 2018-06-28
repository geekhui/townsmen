<?php
namespace Home\Controller;
use Think\Controller;

class CommonController extends Controller {
    // 初始化
    public function _initialize() {
        header("Content-Type:application/json;charset=utf8");
        header('Access-Control-Allow-Origin:*');
        $login_info = [
            'user' => [
                'uid' => 1,
                'username' => 'test帐号',
                'niackname'=> 'XXX522',
                'mobile' => '18257193431',
                'email' => '334103879@qq.com',
                'login_time' => 'test帐号',
                'score' => '45'
            ]
        ];
        session("zd_login_info",$login_info);
    }
    
    // 登录用户信息
    public function loginUserInfo(){
        $user = session("zd_login_info.user");
        
    }
    
    
    
    
    
    
    

    // 空操作
    public function _empty() {
        A("Index")->index();
    }
}