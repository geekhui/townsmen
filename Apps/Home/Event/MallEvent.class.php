<?php
namespace Home\Event;
use Think\Controller;

class MallEvent extends Controller{
    protected $mall_model;
    
    public function __construct()
    {
        $mall_model = D('Mall');
        $this->mall_model = $mall_model;
    }

    // 获取类别商品列表
    public function getTypeGoods($type_id) {
        $where['gt.tid'] = $type_id;
        $goods_list = $this->mall_model->getGoodsList($where);

        return $goods_list;
    }
    
    // 获取商品详细信息
    public function getGoodsInfo($goods_id,$spec_id) {
        $where['gd.gid'] = $goods_id;
        if($spec_id) $where['gd.spec_id'] = $spec_id;
        $goods_info = $this->mall_model->getGoodsInfo($where);
        
        return $goods_info;
    }
    
    // 收藏商品
    public function collectGoods($user_id, $goods_id) {
        
        return A("Collection", "Event")->addCollection($user_id, $goods_id, 1);
    }
    
    // 商品加入购物车
    public function addcartGoods($user_id, $goods_id, $spec_id, $quantity) {
        $where['uid'] = $user_id;
        $where['goods_id'] = $goods_id;
        $where['spec_id'] = $spec_id;
        $where['status'] = 1;
        $cart_id = M("user_collection")->where($where)->getField("cart_id");
        
        if($cart_id){
            if(M("user_cart")->where(['cart_id'=>$cart_id])->setInc("quantity", $quantity)){
                return 1;   //加入购物车成功
            }else{
                return 0;   //加入购物车失败
            }
        }else{
            $data['uid'] = $user_id;
            $data['goods_id'] = $goods_id;
            $data['spec_id'] = $spec_id;
            $data['quantity'] = $quantity;
            $data['put_time'] = time();
            $data['status'] = 1;
            if(M("user_cart")->add($data)){
                return 1;   //加入购物车成功
            }else{
                return 0;   //加入购物车失败
            }
        }
        
    }
}