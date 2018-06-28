<?php
namespace Home\Model;
use Think\Model;

class UserModel extends Model{
    protected $tableName = 'user_info';
    
    // 获取用户信息
    public function getUserData($uid){
        $where['ul.status'] = 1;
        $where['ul.uid'] = $uid;
        $userInfo = $this
            ->field("ui.uid,ui.score,ul.username,ul.mobile,ul.email,ul.passport,ul.nickname")
            ->join(" ui left join ct_user_login ul on ul.uid = ui.uid")
            ->where($where)
            ->select();
        return $userInfo;
    }
    

}