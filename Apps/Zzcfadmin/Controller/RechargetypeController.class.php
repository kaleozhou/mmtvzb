<?php
/*
* Author: [ Copy Lian ]
* Date: [ 2015.05.08 ]
* Description [ 充值商品 ]
*/
namespace Zzcfadmin\Controller;

class RechargetypeController extends CommonController
{
	/**
	 * [index 充值商品列表]
	 */
	public function index()
	{
		$Rechargetype = D('Rechargetype');
		$count = $Rechargetype->field('id')->count();
		//获取分页
		$page = getPage($count,C('SYSTEM_PAGENUM'),'','',"/",1,"<span aria-hidden='true'>&laquo;</span>","<span aria-hidden='true'>&raquo;</span>",'');
		$this->pagelist = $page->show();
		$data = $Rechargetype->order('sort ASC,id DESC')->limit($page->firstRow,$page->listRows)->select();
		$this->data = $data;

	/*获取url参数*/
		$this->parameter = getParameter(I('get.'),$page);
	/* end 获取url参数*/
		$this->display();
	}
	/**
	 * [add 新增充值商品]
	 */
	public function add()
	{
		//数据提交
		if(IS_POST){
			$Rechargetype = D('Rechargetype');
			if($data = $Rechargetype->create()){
				if($insertid = $Rechargetype->add()){
					//新增排序
					$Rechargetype->where("id = {$insertid}")->setField('sort',$insertid);
					$this->success(L('_ADD_SUCCESS_'),U('index',decode(I('post.parameter'))));
				} else {
					$this->error(L('_ADD_ERROR_'));
				}
			} else {
				$this->error($Rechargetype->getError());
			}
		} else {
			//获取参数
			$this->parameter = I('get.parameter');
			$this->display();
		}
	}

	/**
	 * [edit 编辑充值商品]
	*/
	public function edit()
	{
		//提交数据处理
		if(IS_POST){
			$Rechargetype = D('Rechargetype');
			if($data = $Rechargetype->create()){

				//保存数据
				if($Rechargetype->save()){
					$this->success(L('_SAVE_SUCCESS_'),U('index',decode(I('post.parameter'))));
				} else {
					$this->error(L('_SAVE_ERROR_'));
				}
			} else {
				$this->error($Rechargetype->getError());
			}
		} else {
			//验证数据
			$id = I('get.id');
			if(!is_numeric($id)){
				$this->error(L('_ACCESS_ERROR_'));
			}

			//获取参数
			$this->parameter = I('get.parameter');

			//获取数据
			$Rechargetype = M('Rechargetype');
			$where['id'] = array('eq',$id);
			$data = $Rechargetype->where($where)->find();
			if(!$data){
				$this->error(L('_NODATA_'));
			}

			$this->data = $data;

			$this->display();
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

			//验证数据
			$Rechargetype = D('Rechargetype');
			foreach ($ids as $key => $value) {
				$data = $Rechargetype->field('id')->find($value);
				if(!$data){
					$this->error(L('_NODATA_'));
					break;
				}
			}

			//删除数据
			if($Rechargetype->delete($id)){

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

			$Rechargetype = M('Rechargetype');
			//更新数据
			foreach ($sortarr as $key => $value) {
				list($data['id'],$data['sort']) = explode("|", $value);
				$data['sort'] = intval($data['sort']);
				$Rechargetype->save($data);
			}
			$this->success(L('_SORT_SUCCESS_'));
		} else {
			$this->error(L('_ACCESS_ERROR_'));
		}
	}
}
?>