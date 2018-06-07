<?php
namespace Home\Model;
use Think\Model;

class UserCartModel extends Model{
    protected $tableName = 'user_cart';
    
    // 获取用户购物车商品
    public function getCartGoods($where){
        $where['uc.status'] = 1;
        $where['gd.status'] = 1;
        $where['gs.status'] = 1;
        $goodsList = $this
            ->field("uc.*,gd.name,gd.unique_code,gd.icon,gs.spec,gs.score,gs.rmb,gs.vip_score,gs.vip_rmb")
            ->join(" uc left join ct_goods gd on gd.gid = uc.goods_id")
            ->join(" left join ct_goods_spec gs on gs.sid = uc.spec_id")
            ->where($where)
            ->order("uc.cart_id desc")
            ->select();
        return $goodsList;
    }
    

}