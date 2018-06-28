<?php
namespace Home\Controller;

/**
 * @name    积分商城控制器
 * @author  wanghui
 */
class MallController extends CommonController {
    
    public function _initialize() {
        
    }
    
    // 商城首页
    public function index() {
        // 商品类别
        $goods_type = M("goods_type")->where(['status'=>1])->field("tid,name")->select();
		$result_data["types"] = $goods_type;
        
        // 类别商品列表
        $type_id = intval($_REQUEST['type_id']);
        $goods_list = A("Mall", "Event")->getTypeGoods($type_id);
        $result_data["list"] = $goods_list;
        
        $this->ajaxReturn(['code'=>100, 'data'=>$result_data]);
    }
    
    // 商品详情
    public function goodsInfo() {
        $goods_id = intval($_REQUEST['gid']);
        $spec_id = isset($_REQUEST['spec_id']) ? intval($_REQUEST['spec_id']) : null;
        
        $goods_info = A("Mall", "Event")->getGoodsInfo($goods_id,$spec_id);

        //$this->assign("goods_pic", $goods_info['imgs']);
        //$this->assign("goods_base", $goods_info['basic']);
        //$this->assign("goods_spec", $goods_info['spec']);
        //$this->assign("goods_detail", $goods_info['detail']);
        
        $this->ajaxReturn(['code'=>100, 'data'=>$goods_info]);
    }   
    
    // 加入收藏
    public function putincollect() {
        $goods_id = intval($_REQUEST['goods_id']);
        $user = session("zd_login_info.user");
        if(A("Mall", "Event")->collectGoods($user['uid'], $goods_id)){
            $this->ajaxReturn(['code'=>200, 'msg'=>'收藏成功']);
        }else{
            $this->ajaxReturn(['code'=>400, 'msg'=>'收藏失败']);
        }
    }
    
    // 加入购物车
    public function putinCart() {
        $goods_id = intval($_REQUEST['goods_id']);
        $spec_id = intval($_REQUEST['spec_id']);
        $quantity = intval($_REQUEST['quantity']);
        $user = session("zd_login_info.user");
        if(A("Mall", "Event")->addcartGoods($user['uid'], $goods_id, $spec_id, $quantity)){
			$this->ajaxReturn(['code'=>200, 'msg'=>'加入购物车成功']);
        }else{
			$this->ajaxReturn(['code'=>400, 'msg'=>'加入购物车失败']);
        }
    }
    
    
    
    
    
    
    
}