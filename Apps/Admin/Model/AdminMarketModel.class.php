<?php
namespace Admin\Model;
use Think\Model;

class AdminMarketModel extends Model
{
    protected $tableName = 'goods';
    
    /**
     * @name    商品列表
     * @author  wanghui 20180525
     */
    public function getGoodsList($where, $order="creat_time desc", $limit = 20){
        $where['status'] = 1;
        foreach ($where as $key=>$val){
            $where_join['gd.'.$key] = $val;
        }
        $count = $this->where($where)->count();
        $page = new \Think\Page($count,$limit);
        $show = $page->show();
        
        $order = "gd." . $order;
        $list = $this
            ->field("gd.gid,gd.name,gd.unique_code,gd.description,gd.icon,gt.name")
            ->join("gd join ct_goods_type gt on gt.tid = gd.type_id and gt.status = 1")
            ->where($where_join)
            ->order($order)
            ->limit($page->firstRow.','.$page->listRows)
            ->select();
    
        return array('page' => $show , 'list' => $list);
    }
    
    /**
     * @name    商品规格列表
     * @author  wanghui 20180525
     */
    public function getSpecList($where, $order="creat_time desc", $limit = 20){
        $where['status'] = 1;
        foreach ($where as $key=>$val){
            $where_join['gs.'.$key] = $val;
        }
        $count = $this->where($where)->count();
        $page = new \Think\Page($count,$limit);
        $show = $page->show();
        
        $order = "gs." . $order;
        $list = $this
            ->field("gd.name,gd.unique_code,gs.id,gs.spec,gs.score,gs.rmb,gs.vip_score,gs.vip_rmb")
            ->join("join ct_goods_spec gs on gs.goods_id = gd.id and gs.status = 1")
            ->where($where_join)
            ->order($order)
            ->limit($page->firstRow.','.$page->listRows)
            ->select();
    
        return array('page' => $show , 'list' => $list);
    }
    
    
}