<?php
namespace Home\Model;
use Think\Model;

class CollectionModel extends Model{
    
   protected $tableName = 'user_collection';

   /**
    * @desc    收藏的商品
    * @author  wanghui 20180606
    */
   public function getCollectionGoods($where) {
       $where['uc.c_type'] = 1;
       $where['uc.status'] = 1;
       $collectgoods = $this
           ->field("gt.name,gd.*,gs.spec,gs.vip_score,gs.vip_rmb")
           ->join("uc left join ct_goods gd on uc.c_id = gd.gid and gd.status = 1")
           ->join("left join ct_goods_spec gs on gs.goods_id = uc.c_id and gs.status = 1")
           ->join("left join ct_goods_type gt on gt.tid = gd.type_id and gt.status = 1")
           ->where($where)
           ->select();
       return $collectgoods;
   }
   
    /**
     * @desc    收藏的小说
     * @author  wanghui 20180606
     */
    public function getCollectionNovels($where) {
        $where['uc.c_type'] = 2;
        $where['uc.status'] = 1;
        $collectnovals = $this
            ->field("un.id,un.bookname,un.author,un.category,nc.catename")
            ->join("uc left join ct_user_novel un on uc.c_id = un.nid and un.status = 1")
            ->join("left join ct_novel_category nc on un.category = nc.id and nc.status = 1")
            ->where($where)
            ->select();
        return $collectnovals;
    }
    
    /**
     * @desc    收藏的发帖
     * @author  wanghui 20180606
     */
    public function getCollectionPosts($where){
        $where['uc.c_type'] = 3;
        $where['uc.status'] = 1;
        $collectposts = $this
            ->field("up.pid,up.uid,up.title,up.tid,up.post_time,ut.theme")
            ->join("uc left join ct_post up on uc.c_id = up.pid and up.status = 1")
            ->join("left join ct_theme ut on up.tid = ut.id and ut.status = 1")
            ->where($where)
            ->select();
        return $collectposts;
    }
}