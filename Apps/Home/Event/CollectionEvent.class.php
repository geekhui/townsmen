<?php
namespace Home\Event;
use Think\Controller;

class MallEvent extends Controller{
    protected $collection_model;
    
    public function __construct()
    {
        $collection_model = D('Collection');
        $this->collection_model = $collection_model;
    }
    
    // 获取用户收藏的商品列表
    public function collectionGoods($user_id) {
        $where['uc.uid'] = $user_id;
        $goods_list = $this->collection_model->getCollectionGoods($where);
    
        return $goods_list;
    }

    // 获取用户收藏的小说列表
    public function collectionNovels($user_id) {
        $where['uc.uid'] = $user_id;
        $noval_list = $this->collection_model->getCollectionNovels($where);

        return $noval_list;
    }
    
    // 获取用户收藏的小说列表
    public function collectionPosts($user_id) {
        $where['uc.uid'] = $user_id;
        $post_list = $this->collection_model->getCollectionPosts($where);
    
        return $post_list;
    }
    
    // 加入收藏
    public function addCollection($user_id, $c_id, $c_type) {
        $where['uid'] = $user_id;
        $where['c_id'] = $c_id;
        $where['c_type'] = $c_type;
        $status = M("user_collection")->where($where)->getField("status");
    
        if ($status){
            if($status == "1") return 2;       //已收藏
    
            $data['status'] = 1;
            if(M("user_collection")->where($where)->save($data)){
                return 1;   //收藏成功
            }else{
                return 0;   //收藏失败
            }
        }else{
            $data['uid'] = $user_id;
            $data['c_id'] = $c_id;
            $data['c_type'] = $c_type;
            $data['collect_time'] = time();
            $data['status'] = 1;
            if(M("user_collection")->add($data)){
                return 1;   //收藏成功
            }else{
                return 0;   //收藏失败
            }
        }
    }
    
    // 取消收藏
    public function cancelCollection($user_id, $c_id, $c_type) {
        $where['uid'] = $user_id;
        $where['c_id'] = $c_id;
        $where['c_type'] = $c_type;
        $status = M("user_collection")->where($where)->getField("status");
    
        if ($status){
            if($status == "0") return 2;       //已取消
    
            $data['status'] = 0;
            if(M("user_collection")->where($where)->save($data)){
                return 1;   //取消收藏成功
            }else{
                return 0;   //取消收藏失败
            }
        }else{
            return 3;       //未曾收藏
        }
    }
    
    
}