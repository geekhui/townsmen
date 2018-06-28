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
            $this->ajaxReturn(['code'=>401, 'msg'=>"已收藏"]);
        }else {
            $data = $where;
            $data['create_time'] = time();
            if(M("user_collection")->add($data)){
				$this->ajaxReturn(['code'=>200, 'msg'=>"收藏成功"]);
            }else {
				$this->ajaxReturn(['code'=>400, 'msg'=>"收藏失败"]);
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
				$this->ajaxReturn(['code'=>200, 'msg'=>"取消成功"]);
            }else {
				$this->ajaxReturn(['code'=>400, 'msg'=>"取消失败"]);
            }
        }else {
			$this->ajaxReturn(['code'=>401, 'msg'=>"数据异常"]);
        }
    }
    

    
    
}