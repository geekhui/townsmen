<?php
namespace Home\Event;
use Think\Controller;

class CartEvent extends Controller{
    protected $cart_model;
    
    public function __construct()
    {
        $cart_model = D('user_cart');
        $this->cart_model = $cart_model;
    }

    // 购物车商品信息列表
    public function cartGoodsList($user_id) {
        $where['uc.uid'] = $user_id;
        return $this->cart_model->getCartGoods($where);
    }
    
    // 删除单个购物车商品
    public function deleteCartGoods($user_id, $cart_id) {
        $where['uid'] = $user_id;
        $where['cart_id'] = $cart_id;
        return M("user_cart")->where($where)->setField("status", 0);
    }
    
    // （批量）删除选中购物车商品
    public function batchDeleteCartGoods($user_id, $cart_ids) {
        $where['uid'] = $user_id;
        $where['cart_id'] = ["in", implode(',', $cart_ids)];
        M()->startTrans();
        $num = M("user_cart")->where($where)->setField("status", 0);
        if($num == count($cart_ids)){
            M()->commit();
            return 1;
        }else{
            M()->rollback();
            return 0;
        } 
    }
    
    // 购物车商品数量+1
    public function increaseGoodsNum($user_id, $cart_id) {
        $where['uid'] = $user_id;
        $where['cart_id'] = $cart_id;
        $where['status'] = 1;
        return M("user_cart")->where($where)->setInc("quantity");
    }
    
    // 购物车商品数量-1
    public function decreaseGoodsNum($user_id, $cart_id) {
        $where['uid'] = $user_id;
        $where['cart_id'] = $cart_id;
        $where['status'] = 1;
        return M("user_cart")->where($where)->setDec("quantity");
    }
    
    // 更新购物车商品数量
    public function updateGoodsNum($user_id, $cart_id, $number) {
        $where['uid'] = $user_id;
        $where['cart_id'] = $cart_id;
        $where['status'] = 1;
        return M("user_cart")->where($where)->setField("quantity", $number);
    }
    
    // 清空购物车商品
    public function cleatCartGoods($user_id) {
        $where['uid'] = $user_id;
        $where['status'] = ["eq", 1];
        return M("user_cart")->where($where)->setField("status", 0);
    }
    
    // 生成临时订单
    public function creatTempOrder($user_id, $cart_ids) {
        $where['uc.uid'] = $user_id;
        $where['uc.cart_id'] = ["in", implode(',', $cart_ids)];
        return $this->cart_model->getCartGoods($where); 
    }
    
    // 生成订单
    public function creatOrder($user_id, $cart_ids, $address) {
        $where['uc.uid'] = $user_id;
        $where['uc.cart_id'] = ["in", implode(',', $cart_ids)];
        $order_goods = $this->cart_model->getCartGoods($where);
        
        M()->startTrans();
        $data_goods = [];
        $score = 0;$rmb = 0;
        foreach ($order_goods as $key=>$goods){
            $data_goods[$key]['goods_id'] = $goods['goods_id'];
            $data_goods[$key]['spec_id'] = $goods['spec_id'];
            $data_goods[$key]['quantity'] = $goods['quantity'];
            $data_goods[$key]['unit_score'] = $goods['vip_score'];
            $data_goods[$key]['unit_rmb'] = $goods['vip_rmb'];
            
            $score += $goods['vip_score'];
            $rmb += $goods['vip_rmb'];
        }
        // 新增订单数据
        $data_order['score'] = $score;
        $data_order['rmb'] = $rmb;
        $data_order['buyer_uid'] = $user_id;
        $data_order['receiver_name'] = $address['recipient'];
        $data_order['receiver_mobile'] = $address['telephone'];
        $data_order['receiver_address'] = $address['area'].$address['address'];
        $data_order['order_time'] = time();
        $data_order['order_status'] = 1;
        $new_order_id = M("user_order")->add($data_order);
        if(!$new_order_id){
            M()->rollback();
            return 0;
        }
        // 定义并更新商品订单号
        $order_number = '100'.unique_number().str_pad($new_order_id,4,"0");
        if(M("user_order")->where(['id'=>$new_order_id])->setField("order_number",$order_number)){
            // 新增订单商品数据
            foreach ($data_goods as $val){
                $val['order_num'] = $order_number;
                $new_order_goods = M("user_order")->add($val);
                if($new_order_goods != 1){
                    M()->rollback();
                    return 0;
                }
            }
        }else {
            M()->rollback();
            return 0;
        }
        // 购物车选中数据清除
        if(! $this->batchDeleteCartGoods($user_id, $cart_ids)){
            M()->rollback();
            return 0;
        }
        
        M()->commit();
        return 0;
    }
    
    
}