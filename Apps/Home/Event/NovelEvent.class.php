<?php
namespace Home\Event;
use Think\Controller;

class NovelEvent extends Controller{
    protected $novel_model;
    public function __construct()
    {
        $novel_model = D('Novel');
        $this->novel_model = $novel_model;
    }

    // 获取小说页面头部广告部分信息
    public function getHeadNovels(){
        $where['type_num'] = 2;
        $where['status'] = 1;
        return M("adpositionid")->where($where)->order('sort')->select();
    }

    // 获取小说列表
    public function getNovelList($type_num){
        $novels = [];
        if(in_array($type_num, [1,2])){     //同人、原创小说
            // 精品推荐小说
            $where['fn.type'] = $type_num;
            $novels['fine'] = $this->novel_model->getNovelList($where, 2);
            
            // 其他小说
            $fine_ids = array_column($novels['fine'], 'nid');
            $map['type'] = $type_num;
            $map['nid'] = ['not in',$fine_ids];
            $novels['list'] = M("user_novel")->where($map)->field('nid,bookname,author,cover,intro,words,isend')->select();
        }else{
            switch ($type_num){
                case 3:         //完结
                    // 精品推荐小说
                    $where['fn.type'] = $type_num;
                    $where['un.isend'] = 1;
                    $novels['fine'] = $this->novel_model->getNovelList($where, 2);
                    
                    // 其他小说
                    $fine_ids = array_column($novels['fine'], 'nid');
                    $map['nid'] = ['not in',$fine_ids];
                    $map['isend'] = 1;
                    $novels['list'] = M("user_novel")->where($map)->field('nid,bookname,author,cover,intro,words,isend')->select();
                    break;
                case 4:
                    // 精品推荐小说
                    $where['fn.type'] = $type_num;
                    $novels['fine'] = $this->novel_model->getNovelList($where, 2);
                    
                    // 其他小说
                    $fine_ids = array_column($novels['fine'], 'nid');
                    $map['nid'] = ['not in',$fine_ids];
                    $novels['list'] = M("user_novel")->where($map)->field('nid,bookname,author,cover,intro,words,isend')->order('sort')->select();
                    break;
            }
        }
        return $novels;
    }
    
    
    
}