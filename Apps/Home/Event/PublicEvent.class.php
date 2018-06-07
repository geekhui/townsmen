<?php
namespace Home\Event;
use Think\Controller;
class PublicEvent extends Controller{

    // 加入收藏
    public function addCollection($user_id, $collect_id, $collect_type) {
        $where['uid'] = $user_id;
        $where['c_type'] = $collect_type;
        $where['c_id'] = $collect_id;
        $collectstatus = M("user_collection")->where($where)->find();
        if($collectstatus){
            return ['state'=>'fail', 'msg'=>"已收藏"];
            $this->ajaxReturn(['code'=>0, 'state'=>'fail', 'msg'=>"已收藏"]);
        }else {
            $data = $where;
            $data['create_time'] = time();
            if(M("user_collection")->add($data)){
                return ['code'=>1, 'state'=>'ok', 'msg'=>"收藏成功"];
            }else {
                return ['code'=>0, 'state'=>'fail', 'msg'=>"收藏失败"];
            }
        }
    }
    
    // 取消收藏
    public function cancelCollection($user_id, $collect_id, $collect_type) {
        $where['uid'] = $user_id;
        $where['c_type'] = $collect_type;
        $where['c_id'] = $collect_id;
        $collectstatus = M("user_collection")->where($where)->find();
        if($collectstatus){
            if(M("user_collection")->delete($where)){
                return ['code'=>1, 'state'=>'ok', 'msg'=>"取消成功"];
            }else {
                return ['code'=>0, 'state'=>'fail', 'msg'=>"取消失败"];
            }
        }else {
            return ['code'=>0, 'state'=>'fail', 'msg'=>"数据异常"];
        }
    }
    

    
    
}