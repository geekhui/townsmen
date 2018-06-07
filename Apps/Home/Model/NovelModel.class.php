<?php
namespace Home\Model;
use Think\Model;

class NovelModel extends Model{
    
   protected $tableName = 'fine_novel';

    /**
     * @desc    获取小说信息列表
     * @author  wanghui 20180606
     */
    public function getNovelList($where, $limit){
        $where['un.status'] = 1;
        $where['fn.status'] = 1;
        $novel_list = $this
            ->field('un.nid,un.bookname,un.author,un.cover,un.intro')
            ->join("fn left join ct_user_novel un on un.nid = fn.nid")
            ->where($where)
            ->limit($limit)
            ->select();
        return $novel_list;
    }
    

}