<?php
/*
* Author: [ Copy Lian ]
* Date: [ 2015.05.13 ]
* Description [ 首页 ]
*/
namespace Special\Controller;
class VideorecordController extends CommonController {

    /**
     * 首页
    */
    public function index(){
    	$vid = I('get.id',0);
    	$this->vid = $vid;
    	$oneData = array(); //详细数据
    	$firstData = array(); //第一条数据

    	//获取视频
    	$Videorecord = M('Videorecord');
    	$where['status'] = array('eq',1);
    	$data = $Videorecord->where($where)->order("create_time DESC")->select();

    	//处理数据
    	if(!empty($data)){
    		foreach ($data as $key => $value) {
    			//预览图
    			$data[$key]['thumb'] = unserialize($value['thumb']);

    			//日期
    			$data[$key]['date'] = date("Y.m.d",$value['create_time']);

    			//获取单个数据
    			if($vid == $value['id']){
    				$oneData = $data[$key];
    			}
    		}

    		//第一条数据
    		$firstData = $data[0];
    	}

    	//如果没有vid则是最新一条数据
   		if(!empty($vid)){
   			$this->oneData = $oneData;

   			//获取上一篇
	        $prev_where['status'] = array('eq',1);
	        $prev_where['id'] = array('lt',$vid);
	        $previd = $Videorecord->where($prev_where)->order('id ASC')->max('id');
	        $this->prev = $Videorecord->where($prev_where)->find($previd);

	        //获取下一篇
	        $next_where['status'] = array('eq',1);
	        $next_where['id'] = array('gt',$vid);
	        $nextid = $Videorecord->where($next_where)->order('id ASC')->min('id');
	        $this->next = $Videorecord->where($next_where)->find($nextid);

   		} else {
   			$this->oneData = $firstData;
   		}


    	//获取新数据
    	$newData = array();
    	if(!empty($data)){
    		foreach ($data as $key => $value) {
    			foreach ($data as $key_1 => $value_1) {
	    			if($value['date'] == $value_1['date']){
	    				$newData[$value['date']]['child'][] = $value_1;
	    				unset($data[$key_1]);
	    			}
	    		}
    		}
    	}

    	//处理新数据
    	$i = 1;
    	if(!empty($newData)){
    		foreach ($newData as $key => $value) {
    			$newData[$key]['datei'] = $i;
    			++$i;
    		}
    	}

    	$this->data = $newData;
    	$this->display();
    }
}