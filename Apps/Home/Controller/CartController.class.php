<?php
namespace Home\Controller;

/**
 * @name    购物车控制器
 * @author  wanghui 20180524
 */
class CartController extends CommonController {
    
    public function _initialize() {
        
    }
    
    // 购物车商品列表
    public function index() {
        $user = session("zd_login_info.user");
        $goods_list = A("Cart", "Event")->getCartGoods($user['uid']);
        
		$this->ajaxReturn(['code'=>100, 'data'=>$goods_list]);
    }
    
    // 删除单个购物车商品
    public function delGoods() {
        $cart_id = intval($_REQUEST['cart_id']);
        $user = session("zd_login_info.user");
        if(A("Cart", "Event")->deleteCartGoods($user['uid'], $cart_id)){
			$this->ajaxReturn(['code'=>200, 'msg'=>'删除成功']);
        }else{
			$this->ajaxReturn(['code'=>400, 'msg'=>'删除失败']);
        }
    }
    
    // （批量）选中删除购物车商品
    public function batchDelGoods() {
        $cart_ids = $_REQUEST['cart_ids'];
        $user = session("zd_login_info.user");
        if(A("Cart", "Event")->batchDeleteCartGoods($user['uid'], $cart_ids)){
            $this->ajaxReturn(['code'=>200, 'msg'=>'删除成功']);
        }else{
            $this->ajaxReturn(['code'=>400, 'msg'=>'删除失败']);
        }
    }
    
    // 购物车商品数量+1
    public function increaseGoods() {
        $cart_id = intval($_REQUEST['cart_id']);
        $user = session("zd_login_info.user");
        if(A("Cart", "Event")->increaseGoodsNum($user['uid'], $cart_id)){
			$this->ajaxReturn(['code'=>200, 'msg'=>'更新成功']);
        }else{
            $this->ajaxReturn(['code'=>400, 'msg'=>'更新失败']);
        }
    }
    
    // 购物车商品数量-1
    public function decreaseGoods() {
        $cart_id = intval($_REQUEST['cart_id']);
        $user = session("zd_login_info.user");
        if(A("Cart", "Event")->decreaseGoodsNum($user['uid'], $cart_id)){
            $this->ajaxReturn(['code'=>200, 'msg'=>'更新成功']);
        }else{
            $this->ajaxReturn(['code'=>400, 'msg'=>'更新失败']);
        }
    }
    
    // 更新购物车商品数量
    public function updateGoods() {
        $number = intval($_REQUEST['number']);
        $cart_id = intval($_REQUEST['cart_id']);
        $user = session("zd_login_info.user");
        if(A("Cart", "Event")->updateGoodsNum($user['uid'], $cart_id, $number)){
            $this->ajaxReturn(['code'=>200, 'msg'=>'更新成功']);
        }else{
            $this->ajaxReturn(['code'=>400, 'msg'=>'更新失败']);
        }
    }
    
    // 清空购物车商品
    public function clearGoods() {
        $user = session("zd_login_info.user");
        if(A("Cart", "Event")->cleatCartGoods($user['uid'])){
            $this->ajaxReturn(['code'=>200, 'msg'=>'更新成功']);
        }else{
            $this->ajaxReturn(['code'=>400, 'msg'=>'更新失败']);
        }
    }
    
    // 购物车(选中)商品生成临时订单
    public function tempOrderGoods() {
        $cart_ids = $_REQUEST['cart_ids'];
        $user = session("zd_login_info.user");
        if(count($cart_ids) == 0){
			$this->ajaxReturn(['code'=>401, 'msg'=>'请先选中商品']);
        }else{
            // 临时订单商品列表
            $temp_goods = A("Cart", "Event")->creatTempOrder($user['uid'], $cart_ids);
			$result_data['temp_goods'] = $temp_goods;
            
            // 获取用户收货地址信息
            $receive_address = M("receive_address")->where(['uid'=>$user['uid'],'status'=>1])->select();
			$result_data['address'] = $receive_address;
            
            $this->ajaxReturn(['code'=>100, 'data'=>$result_data]);
        }
    }
    
    // 生成订单
    public function createOrder() {
        $user = session("zd_login_info.user");
        $cart_ids = $_REQUEST['cart_ids'];
        // 收货人信息
        $address['recipient'] = $_REQUEST['recipient'];
        $address['telephone'] = $_REQUEST['telephone'];
        $address['area'] = $_REQUEST['area'];
        $address['address'] = $_REQUEST['address'];
        
        if(A("Cart", "Event")->creatOrder($user['uid'], $cart_ids, $address)){
            $this->ajaxReturn(['code'=>200, 'msg'=>'下单成功']);
        }else{
            $this->ajaxReturn(['code'=>400, 'msg'=>'下单失败']);
        }
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}