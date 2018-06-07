<?php
namespace Home\Model;
use Think\Model;

class MallModel extends Model{
    
   protected $tableName = 'goods';

    /**
     * @desc  获取线上商品列表
     * @author  wanghui 20180605
     */
    public function getGoodsList($where){
        $where['gd.status'] = 1;
        $where['gs.status'] = 1;
        $where['gt.status'] = 1;
        $goods_data = $this
            ->field("gd.*,gs.*")
            ->join("gd left join ct_goods_type gt on gt.tid = gd.type_id")
            ->join("left join ct_goods_spec gs on gs.goods_id = gd.gid")
            ->where($where)
            ->order('gd.create_time desc')
            ->select();
        return $goods_data;
    }
    
    /**
     * @desc  获取商品相关信息
     * @author  wanghui 20180605
     */
    public function getGoodsInfo($where){
        $where['gd.status'] = 1;
        $where['gs.status'] = 1;
        $where['gt.status'] = 1;
        $goods_info = $this
            ->field("gd.*,gs.*")
            ->join("gd left join ct_goods_type gt on gt.tid = gd.type_id")
            ->join("left join ct_goods_spec gs on gs.goods_id = gd.gid")
            ->where($where)
            ->order('gd.create_time desc')
            ->select();
        return $goods_info;
    }
}