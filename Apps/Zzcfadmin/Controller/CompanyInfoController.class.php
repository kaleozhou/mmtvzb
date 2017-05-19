<?php
/*
* Author: [ Copy Lian ]
* Date: [ 2015.05.08 ]
* Description [ 实时广播 ]
*/
namespace Zzcfadmin\Controller;

class CompanyInfoController extends CommonController
{

	/**
	 * [index 视频设置]
	 */
	function index(){
		if(IS_POST){
	        $Broadcast = D('CompanyInfo');
	        if($data = $Broadcast->create()){
	        	$Broadcast->create_time = time();
	            if($Broadcast->save()){
			        $this->success(L('_SAVE_SUCCESS_'));
	            } else {
	                  $this->error(L('_SAVE_ERROR_'));
	            }
	        } else {
	            $this->error($Broadcast->getError());
	        }
	    } else {
			
	        //获取数据
	        $Broadcast = M('CompanyInfo');
	        $this->data = $Broadcast->find();
	        $this->display();
	    }
	}
}
?>