<?php
/*
* Author: [ Copy Lian ]
* Date: [ 2015.05.13 ]
* Description [ 首页 ]
*/
namespace Special\Controller;
class VideoreviewController extends CommonController {

    public function index()
    {   
        $Review = D('WonderfulReview');
        
        //获取推荐视频
        $where['status'] = array('eq',1);
        $where['command'] = array('eq',1);
        $command = $Review->where($where)->order('create_time DESC')->limit(5)->select();
        if(!empty($command)){
            foreach ($command as $key => $value) {
                $command[$key]['thumb'] = unserialize($value['thumb']);
            }
        }
        $this->command = $command;

        //获取不同主播的最新的视频
        $reviewcate = D('Reviewcate');
        $review_where['status'] = array('eq',1);
        $reviewcate = $reviewcate->where($review_where)->relation(array('wonderfulreview'))->order('sort ASC')->select();
        
        //处理数据
        if(!empty($reviewcate)){
            foreach ($reviewcate as $key => $value) {
                //处理图片
                $reviewcate[$key]['thumb'] = unserialize($value['thumb']);
                if(!empty($value['wonderfulreview'])){
                    foreach ($value['wonderfulreview'] as $key_1 => $value_1) {
                        //获取第一个视频id
                        if($key_1 == 0){
                            $reviewcate[$key]['firstid'] = $value_1['id'];
                        }

                        //处理图片
                        $reviewcate[$key]['wonderfulreview'][$key_1]['thumb'] = unserialize($value_1['thumb']);
                    }
                }
            }
        }

        $this->reviewcate = $reviewcate;
        $this->display();
    }

    /**
     * 详细页
    */
    public function details(){;
        $vid = I('get.id',0);
        if(!is_numeric($vid)){
            $this->_404();
        }

        $WonderfulReview = D('WonderfulReview');
        $where['status'] = array('eq',1);
        $where['id'] = array('eq',$vid);

        //更新播放量
        $WonderfulReview->where($where)->setInc('count',mt_rand(10,50));

    	//获取视频
    	$data = $WonderfulReview->where($where)->relation(array('reviewcate'))->find();

        //查询老师视频数量
        $whereCount['status'] = array('eq',1);
        $whereCount['catid'] = array('eq',$data['reviewcate']['id']);
        $this->vcount = $WonderfulReview->where($whereCount)->count();

    	//处理数据
    	if(!empty($data)){
			//预览图
			$data['thumb'] = unserialize($data['thumb']);
    	}
    	$this->data = $data;

        //获取所有数据，不包括当前
        $whereAll['status'] = array('eq',1);
        $whereAll['id'] = array('neq',$vid);
        $whereAll['catid'] = array('eq',$data['reviewcate']['id']); //当前主播的所有视频
        $count = $WonderfulReview->where($whereAll)->count();
        $page = getPage($count,C('BASE_PAGENUM'),'','vreview_v'.$vid.'/',"_",1,"<span aria-hidden='true'>&laquo;</span>","<span aria-hidden='true'>&raquo;</span>",'',5);
        $this->showpage = $page->show();
        $this->totalPages = $page->totalPages;

        //获取数据
        $list = $WonderfulReview->where($whereAll)->order('create_time DESC,sort ASC')->limit($page->firstRow,$page->listRows)->select();

        //处理数据
        if(!empty($list)){
            foreach ($list as $key => $value) {
                $list[$key]['thumb'] = unserialize($value['thumb']);
            }
        }

        $this->list = $list;
    	$this->display();
    }
}