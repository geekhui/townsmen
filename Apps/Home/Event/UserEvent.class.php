<?php
namespace Home\Event;
use Think\Controller;
class UserEvent extends Controller{
    
    /****************************** 用户登录 ***********************************/
    
    // 验证新用户注册信息
    public function checkRegisterUser($passport, $password, $mobile, $nickname)
    {
        if(M("user_login")->where(['username'=>$passport])->find()){
            $this->ajaxReturn(['code'=>401, 'msg'=> "该通行证账号已存在"]);
        }

        if(M("user_login")->where(['mobile'=>$mobile])->find()){
            $this->ajaxReturn(['code'=>401, 'msg'=> "该手机号已存在"]);
        }
        
        if(M("user_login")->where(['nickname'=>$nickname])->find()) {
            $this->ajaxReturn(['code'=>401, 'msg'=> "该昵称已存在"]);
        }
        // 通行证验证
        $reg_pass = '/^[a-zA-Z][a-zA-Z0-9_]{5,11}$/';  //数字、字母、下划线的组合，以字母开头，长度6-12
        if(!preg_match($reg_pass, $passport)){
            $this->ajaxReturn(['code'=>500, 'msg'=>"通行证格式不正确"]);
        }
        
        // 密码验证
        $reg_pwd = '/(?!^\\d+$)(?!^[a-zA-Z]+$)(?!^[_#@]+$).{6,12}/';  //数字、字母、特殊字符的任意两种以上组合，不得单一，长度6-12
        if(!preg_match($reg_pwd, $password)){
            $this->ajaxReturn(['code'=>500, 'msg'=>"密码格式不正确"]);
        }
        
        // 手机号验证
        $phone_reg = '/^((13[0-9])|(14[5,7,9])|(15[^4])|(18[0-9])|(17[0,1,3,5,6,7,8]))\\d{8}$/';
        if(!preg_match($phone_reg, $mobile)){
            $this->ajaxReturn(['code'=>500, 'msg'=>"手机号格式不正确"]);
        }
        
        // 昵称验证
        $reg_name = "/[ '.,:;*?~`!@#$%^&+=)(<>{}]|\]|\[|\/|\\\|\"|\|/"; //禁止出现特殊字符
        if(preg_match($reg_name, $nickname)){
            $this->ajaxReturn(['code'=>500, 'msg'=>"禁止特殊符号"]);
        }
        return 200;
    }
    
    // 验证玩家用户注册信息
    public function checkRegisterPlayer($password, $mobile, $nickname)
    {
        // 密码验证
        $reg_pwd = '/(?!^\\d+$)(?!^[a-zA-Z]+$)(?!^[_#@]+$).{6,12}/';  //数字、字母、特殊字符的任意两种以上组合，不得单一，长度6-12
        if(!preg_match($reg_pwd, $password)){
            $this->ajaxReturn(['code'=>500, 'msg'=>"密码格式不正确"]);
        }
    
        // 手机号验证
        $phone_reg = '/13[123569]{1}\d{8}|15[1235689]\d{8}|188\d{8}/';
        if(!preg_match($phone_reg, $mobile)){
            $this->ajaxReturn(['code'=>500, 'msg'=>"手机号格式不正确"]);
        }
    
        // 昵称验证
        $reg_name = "/[ '.,:;*?~`!@#$%^&+=)(<>{}]|\]|\[|\/|\\\|\"|\|/"; //禁止出现特殊字符
        if(preg_match($reg_name, $nickname)){
            $this->ajaxReturn(['code'=>500, 'msg'=>"禁止特殊符号"]);
        }
    
        $this->ajaxReturn(['code'=>200]);
    }
    
    // 用户登录字段
    public function getLoginField($user) 
    {
        // 验证是否手机号登录
        $phone_reg = '/13[123569]{1}\d{8}|15[1235689]\d{8}|188\d{8}/';
        if(preg_match($phone_reg, $user)){
            $login_field = "mobile";
        }
        // 验证是否邮箱登录
        $emaill_reg = '/^[a-z]([a-z0-9]*[-_\.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[\.][a-z]{2,3}([\.][a-z]{2})?$/i';
        if(preg_match($emaill_reg, $user)){
            $login_field = "emaill";
        }
        $login_field = isset($login_field) ? $login_field : 'username'; //用户名登录
        return $login_field;
    }
    
    
    
    
    
    
    
    // 用户基本信息
    public function getUserInfo($user_id, $field="*", $status=1){
        $where['uid'] = $user_id;
        if(isset($status)) $where['status'] = $status;
        $user_info = M("user_login")->where($where)->field($field)->find(true);
        return $user_info;
    }
    
    // 用户统计数据
    public function countUserData($user_id, $count_field){
        $where['uid'] = $user_id;
        $count_data = M("user_info")->where(['uid'=>$user_id])->field($count_field)->find(true);
        return $count_data;
    }
    
    // 用户好友数据
    public function getUserFriends($user_id){
        $where['uid'] = $user_id;
        $user_friends = M("user_friend")->where($where)->count();
        return $user_friends;
    }
    
    // 用户小说数据
    public function getUserNovels($user_id, $field="*"){
        $where['author'] = $user_id;
        $user_novel = M("user_novel")->where($where)->field($field)->select();
        return $user_novel;
    }
    
    // 获取用户发帖数据
    public function getUserPosts($user_id, $field="*", $status=1){
        $where['uid'] = $user_id;
        if(isset($status)) $where['status'] = $status;
        $user_posts = M("forum_post")->where($where)->field($field)->select();
        return $user_posts;
    }
    
    // 获取用户回帖数据
    public function getUserReplies($user_id, $field="*", $status=1){
        $where['uid'] = $user_id;
        $where['rpid'] = 0;
        if(isset($status)) $where['status'] = $status;
        $user_replies = M("forum_post")->where($where)->field($field)->select();
        return $user_replies;
    }
    
}