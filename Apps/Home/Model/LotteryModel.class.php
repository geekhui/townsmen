<?php
namespace Home\Model;
use Think\Model;

class LotteryModel extends Model{
    
   //protected $tableName = 'awards';

    /**
     * @desc  获取有效奖项奖品数据
     * @author  wanghui 20180530
     */
    public function getPrizeData(){
        $where['aw.status'] = 1;
        $where['pz.status'] = 1;
        $where['ap.status'] = 1;
        $prize_data = M("awards")
            ->field("aw.aw_id,aw.name,aw.percentage as scale,pz.pz_id,pz.prize,pz.percentage,pz.type,pz.amount,pz.remainder")
            ->join("aw left join ct_awards_prize ap on ap.aw_id = aw.aw_id")
            ->join("left join ct_prize pz on pz.pz_id = ap.pz_id")
            ->where($where)
            ->order('pz.create_time desc')
            ->select();
        return $prize_data;
    }
    
    /**
     * @desc  获取奖品相关信息
     * @author  wanghui 20180604
     */
    public function getPrizeInfo($prize_id){
        $where['aw.status'] = 1;
        $where['pz.status'] = 1;
        $where['pz.pz_id'] = $prize_id;
        $prize_info = M("prize")
            ->field("aw.aw_id,pz.pz_id,pz.prize,pz.type,pz.remainder,pz.indate")
            ->join("pz left join ct_awards aw on aw.aw_id = pz.aw_id")
            ->where($where)
            ->order('pz.create_time desc')
            ->select();
        return $prize_info;
    }
}