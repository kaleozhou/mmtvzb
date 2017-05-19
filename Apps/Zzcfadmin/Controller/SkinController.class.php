<?php
/*
* Author: [ Copy Lian ]
* Date: [ 2015.05.08 ]
* Description [ 皮肤设置 ]
*/
namespace Zzcfadmin\Controller;

class SkinController extends CommonController
{

	/**
	 * [index 皮肤设置]
	 */
	public function index(){
		if(IS_POST){
	        $Skin = D('Skin');
	        if($data = $Skin->create()){
	        	//key重新索引解决冲突
				$Skin->thumb = array_values($Skin->thumb);
				$Skin->thumb = serialize($Skin->thumb);

	        	$Skin->create_time = time();
	            if($Skin->add()){
			        $this->success(L('_ADD_SUCCESS_'));
	            } else {
	                $this->error(L('_ADD_ERROR_'));
	            }
	        } else {
	            $this->error($Skin->getError());
	        }
	    } else {

	        //获取数据
	        $Skin = M('Skin');
	        $data = $Skin->order('create_time ASC')->select();
	        //处理图片解析
			if(!empty($data)){
				foreach ($data as $key => $value) {
					if(!empty($value['thumb'])){
						$data[$key]['thumb'] = unserialize($value['thumb']);
					}
				}
			}
			$this->data = $data;
	        $this->display();
	    }
	}

	/**
	 * [setStatus 选择背景]
	 */
	public function setStatus()
	{
		if(IS_POST){
			$id = I('post.data');
			if(!is_numeric($id)){
				$this->error(L('_ACCESS_ERROR_'));
			}

			$Skin = M('Skin');
			//设置状态为1或者0
			$data = $Skin->find($id);
			$where['id'] = array('eq',$id);
			if($data['status'] == 1){
				//设置为0
				$Skin->where($where)->setField('status',0);
			} else {
				//设置为1
				$where_status['id'] = array('neq',0);
				$Skin->where($where_status)->setField('status',0);
				$Skin->where($where)->setField('status',1);
			}
			$this->success(L('_CHANGE_BG_SUCCESS_'));
		}
	}

	/**
	 * [del 删除]
	 */
	public function del()
	{
		if(IS_POST){
			//验证数据
			$id = I('post.id');
			$ids = explode(",", $id);

			//没有数据
			if(empty($ids)){
				$this->error(L('_ACCESS_ERROR_'));
			}

			$pictures_files_arr = array(); //附件、图片数组
			//验证数据
			$photo = M('Skin');
			foreach ($ids as $key => $value) {
				$data = $photo->find($value);
				if(!$data){
					$this->error(L('_NODATA_'));
					break;
				}

				//设置附件与图像
				$pictures_files_arr[] = unserialize($data['thumb']);
			}

			//删除数据
			if($photo->delete($id)){
				//删除图片
				delfilefun($pictures_files_arr);

				$this->success(L('_DEL_SUCCESS_'));
			} else {
				$this->error(L('_DEL_ERROR_'));
			}
		} else {
			$this->error(L('_ACCESS_ERROR_'));
		}
	}

}
?>