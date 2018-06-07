<?php
namespace Home\Event;
use Think\Controller;
class UserEvent extends Controller{

    // 用户登录字段
    public function getLoginField($user) {
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
        $where['id'] = $user_id;
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