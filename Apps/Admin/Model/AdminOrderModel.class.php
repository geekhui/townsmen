<?php
namespace Admin\Model;
use Think\Model;

class AdminOrderModel extends Model
{
    protected $tableName = 'user_order';
    
    /**
     * @name    订单列表
     * @author  wanghui 20180525
     */
    public function getOrderList($where, $order="order_time desc", $limit = 20){
        if(!empty($where)){
            foreach ($where as $key=>$val){
                $where_join['uo.'.$key] = $val;
            }
        }else{
            $where_join = [];
        }
        
        $count = $this->where($where)->count();
        $page = new \Think\Page($count,$limit);
        $show = $page->show();
        
        $order = "uo." . $order;
        $list = $this
            ->field("uo.*,ul.uid,ul.username")
            ->join("uo join ct_user_login ul on ul.uid = uo.buyer_uid")
            ->where($where_join)
            ->order($order)
            ->limit($page->firstRow.','.$page->listRows)
            ->select();
    
        return array('page' => $show , 'list' => $list);
    }
    
    /**
     * @name    订单商品列表
     * @author  wanghui 20180525
     */
    public function getOrderGoodsList($where, $order="creat_time desc", $limit = 20){
        if(!empty($where)){
            foreach ($where as $key=>$val){
                $where['uo.'.$key] = $val;
            }
        }else{
            $where_join = [];
        }
        
        $count = $this->where($where)->count();
        $page = new \Think\Page($count,$limit);
        $show = $page->show();
        
        $order = "uo." . $order;
        $list = $this
            ->field("uo.id,uo.order_number,uog.unit_score,uog.unit_rmb,gd.name,gs.spec,uog.quantity")
            ->join("uo join ct_user_order_goods uog on uog.order_num = uo.order_number")
            ->join("join ct_goods gd on gd.id = uog.goods_id")
            ->join("join ct_goods_spec gs on gs.id = uo.spec_id")
            ->where($where_join)
            ->order($order)
            ->limit($page->firstRow.','.$page->listRows)
            ->select();
    
        return array('page' => $show , 'list' => $list);
    }
    
    
}