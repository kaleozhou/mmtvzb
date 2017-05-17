<?php
/*
* Author: [ Copy Lian ]
* Date: [ 2015.05.08 ]
* Description [ 抽奖管理 ]
*/
namespace Zzcfadmin\Controller;

class LuckdrawController extends CommonController
{
	/**
	 * [index 喊单列表]
	 */
	public function index()
	{
		$Luckdraw = D('Luckdraw');
		$count = $Luckdraw->field('id')->count();
		//获取分页
		$page = getPage($count,C('SYSTEM_PAGENUM'),'','',"/",1,"<span aria-hidden='true'>&laquo;</span>","<span aria-hidden='true'>&raquo;</span>",'');
		$this->pagelist = $page->show();
		$data = $Luckdraw->relation(array('luckproducts'))->order('create_time DESC,id DESC')->limit($page->firstRow,$page->listRows)->select();

		//处理数组
		if(!empty($data)){
			foreach ($data as $key => $value) {
				//解析地区
				$data[$key]['area'] = unserialize($value['area']);
			}
		}

		$this->data = $data;

	/*获取url参数*/
		$this->parameter = getParameter(I('get.'),$page);
	/* end 获取url参数*/
		$this->display();
	}

	/**
	 * [edit 编辑喊单]
	*/
	public function edit()
	{
		//提交数据处理
		if(IS_POST){
			$Luckdraw = D('Luckdraw');
			if($data = $Luckdraw->create()){

				//保存数据
				if($Luckdraw->save()){
					$this->success(L('_SAVE_SUCCESS_'),U('index',decode(I('post.parameter'))));
				} else {
					$this->error(L('_SAVE_ERROR_'));
				}
			} else {
				$this->error($Luckdraw->getError());
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
			$Luckdraw = D('Luckdraw');
			$where['id'] = array('eq',$id);
			$data = $Luckdraw->where($where)->relation(array('luckproducts'))->find();
			if(!$data){
				$this->error(L('_NODATA_'));
			}

			//处理数组
			if(!empty($data)){
				//解析地区
				$data['area'] = unserialize($data['area']);
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
			$Luckdraw = D('Luckdraw');
			foreach ($ids as $key => $value) {
				$data = $Luckdraw->field('id')->find($value);
				if(!$data){
					$this->error(L('_NODATA_'));
					break;
				}
			}

			//删除数据
			if($Luckdraw->delete($id)){
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

			$Luckdraw = M('Luckdraw');
			//更新数据
			foreach ($sortarr as $key => $value) {
				list($data['id'],$data['sort']) = explode("|", $value);
				$data['sort'] = intval($data['sort']);
				$Luckdraw->save($data);
			}
			$this->success(L('_SORT_SUCCESS_'));
		} else {
			$this->error(L('_ACCESS_ERROR_'));
		}
	}

	/**
	 * [indexcate 奖品列表]
	 */
	public function indexcate()
	{

		$Luckproducts = M('Luckproducts');
		$count = $Luckproducts->field('id')->count();
		//获取分页
		$page = getPage($count,C('SYSTEM_PAGENUM'),'','',"/",1,"<span aria-hidden='true'>&laquo;</span>","<span aria-hidden='true'>&raquo;</span>",'');
		$this->pagelist = $page->show();
		$data = $Luckproducts->order('create_time ASC')->limit($page->firstRow,$page->listRows)->select();

		//获取参数
		$this->parameter = I('get.parameter');

		//设置分页
		$this->linkcate_parameter =  getParameter(I('get.'),$page);

		$this->data = $data;
		$this->display();
	}

	/**
	 * [addcate 新增奖品]
	 */
	public function addcate()
	{
		//数据提交
		if(IS_POST){
			$Luckproducts = D('Luckproducts');
			if($data = $Luckproducts->create()){
				$Luckproducts->create_time = time();
				if($insertid = $Luckproducts->add()){
					//新增排序
					$Luckproducts->where("id = {$insertid}")->setField('sort',$insertid);
					$this->success(L('_ADD_SUCCESS_'),U('indexcate',decode(I('post.linkcate_parameter'))));
				} else {
					$this->error(L('_ADD_ERROR_'));
				}
			} else {
				$this->error($Luckproducts->getError());
			}
		} else {
			//获取参数
			$this->linkcate_parameter = I('get.linkcate_parameter');
			$this->display();
		}
	}

	/**
	 * [editcate 编辑奖品]
	 */
	public function editcate()
	{
		//提交数据处理
		if(IS_POST){
			$Luckproducts = D('Luckproducts');
			if($data = $Luckproducts->create()){
				//保存数据
				if($Luckproducts->save()){
					$this->success(L('_SAVE_SUCCESS_'),U('indexcate',decode(I('post.linkcate_parameter'))));
				} else {
					$this->error(L('_SAVE_ERROR_'));
				}
			} else {
				$this->error($Luckproducts->getError());
			}
		} else {
			//验证数据
			$id = I('get.id');
			if(!is_numeric($id)){
				$this->error(L('_ACCESS_ERROR_'));
			}

			//获取参数
			$this->linkcate_parameter = I('get.linkcate_parameter');

			//获取数据
			$Luckproducts = M('Luckproducts');
			$Luckproducts_where['id'] = array('eq',$id);
			$data = $Luckproducts->where($Luckproducts_where)->find();
			if(!$data){
				$this->error(L('_NODATA_'));
			}
			$this->data = $data;

			$this->display();
		}
	}

	/**
	 * [delcate 删除奖品]
	 */
	public function delcate()
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
			$Luckproducts = M('Luckproducts');
			foreach ($ids as $key => $value) {
				$data = $Luckproducts->field('id')->find($value);
				if(!$data){
					$this->error(L('_NODATA_'));
					break;
				}
			}

			//删除数据
			if($Luckproducts->delete($id)){
				$this->success(L('_DEL_SUCCESS_'));
			} else {
				$this->error(L('_DEL_ERROR_'));
			}
		} else {
			$this->error(L('_ACCESS_ERROR_'));
		}
	}

	/**
	 * [sortcate 奖品排序]
	 */
	public function sortcate()
	{
		if(IS_POST){
			$sort = I('post.sort');
			$sortarr = explode(",", $sort);

			//验证数据
			if(empty($sortarr)){
				$this->error(L('_ACCESS_ERROR_'));
			}

			$Luckproducts = M('Luckproducts');
			//更新数据
			foreach ($sortarr as $key => $value) {
				list($data['id'],$data['sort']) = explode("|", $value);
				$data['sort'] = intval($data['sort']);
				$Luckproducts->save($data);
			}
			$this->success(L('_SORT_SUCCESS_'));
		} else {
			$this->error(L('_ACCESS_ERROR_'));
		}
	}
}
?>