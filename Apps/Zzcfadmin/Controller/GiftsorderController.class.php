<?php
/*
* Author: [ Copy Lian ]
* Date: [ 2015.05.08 ]
* Description [ 礼物订单 ]
*/
namespace Zzcfadmin\Controller;

class GiftsorderController extends CommonController
{
	/**
	 * [index 礼物订单列表]
	 */
	public function index()
	{
		$Giftsorder = D('Giftsorder');
		$count = $Giftsorder->field('id')->count();
		//获取分页
		$page = getPage($count,C('SYSTEM_PAGENUM'),'','',"/",1,"<span aria-hidden='true'>&laquo;</span>","<span aria-hidden='true'>&raquo;</span>",'');
		$this->pagelist = $page->show();
		$data = $Giftsorder->relation(array('gifts','teacher','member'))->order('create_time DESC,id DESC')->limit($page->firstRow,$page->listRows)->select();
		if(!empty($data)){
			foreach ($data as $key => $value) {
				$data[$key]['area'] = unserialize($value['area']);
				$data[$key]['gifts']['thumb'] = unserialize($value['gifts']['thumb']);
			}
		}
		$this->data = $data;

	/*获取url参数*/
		$this->parameter = getParameter(I('get.'),$page);
	/* end 获取url参数*/
		$this->display();
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

			//验证数据
			$Giftsorder = D('Giftsorder');
			foreach ($ids as $key => $value) {
				$data = $Giftsorder->field('id')->find($value);
				if(!$data){
					$this->error(L('_NODATA_'));
					break;
				}
			}

			//删除数据
			if($Giftsorder->delete($id)){

				$this->success(L('_DEL_SUCCESS_'));
			} else {
				$this->error(L('_DEL_ERROR_'));
			}
		} else {
			$this->error(L('_ACCESS_ERROR_'));
		}
	}

	/**
	 * [sort 排序]
	 * @return [type] [description]
	 */
	public function sort()
	{
		if(IS_POST){
			$sort = I('post.sort');
			$sortarr = explode(",", $sort);

			//验证数据
			if(empty($sortarr)){
				$this->error(L('_ACCESS_ERROR_'));
			}

			$Giftsorder = M('Giftsorder');
			//更新数据
			foreach ($sortarr as $key => $value) {
				list($data['id'],$data['sort']) = explode("|", $value);
				$data['sort'] = intval($data['sort']);
				$Giftsorder->save($data);
			}
			$this->success(L('_SORT_SUCCESS_'));
		} else {
			$this->error(L('_ACCESS_ERROR_'));
		}
	}
}
?>