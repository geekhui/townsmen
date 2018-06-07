<?php
namespace Home\Event;
use Think\Controller;

class LotteryEvent extends Controller{
    protected $lottery_model;
    
    public function __construct()
    {
        $lottery_model = D('Lottery');
        $this->lottery_model = $lottery_model;
    }

    // 奖项数据
    public function allPrizeData() {
        $prize_data = $this->lottery_model->getPrizeData();
        $awards_data = array_unique(array_column($prize_data, 'aw_id'));
        sort($awards_data);

        $awards = [];
        $prizes = [];
        if(!empty($awards_data)){
            foreach ($awards_data as $key=>$value){
                foreach($prize_data as $pzval){
                    if($pzval['aw_id']==$value && ($pzval['amount']==0 || ($pzval['amount']!=0 && $pzval['remainder']==0))){
                        $awards[$key]['id'] = $value;
                        $awards[$key]['title'] = $pzval['name'];
                        $awards[$key]['scale'] = $pzval['scale'];
            
                        $middle['id'] = $pzval['pz_id'];
                        $middle['title'] = $pzval['prize'];
                        $middle['scale'] = $pzval['percentage'];
                        $middle['type'] = $pzval['type'];
                        $prizes[$value][] = $middle;
                    }
                }
            }
        }
        $result_data = ['awards'=>$awards,'prize'=>$prizes];
        return $result_data;
    }
    
    
}