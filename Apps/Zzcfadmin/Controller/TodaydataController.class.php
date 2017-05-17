<?php
/*
* Author: [ Copy Lian ]
* Date: [ 2015.05.08 ]
* Description [ 今日数据 ]
*/
namespace Zzcfadmin\Controller;

class TodaydataController extends CommonController
{
	/**
	 * [index 今日数据列表]
	 */
	public function index()
	{
		$Todaydata = M('Todaydata');
		$count = $Todaydata->field('id')->where($where)->count();
		//获取分页
		$page = getPage($count,C('SYSTEM_PAGENUM'),'','',"/",1,"<span aria-hidden='true'>&laquo;</span>","<span aria-hidden='true'>&raquo;</span>",'');
		$this->pagelist = $page->show();
		$this->data = $Todaydata->where($where)->order('create_time DESC,id DESC')->limit($page->firstRow,$page->listRows)->select();

	/*获取url参数*/
		$this->parameter = getParameter(I('get.'),$page);
	/* end 获取url参数*/
		$this->display();
	}

	/**
	 * [add 新增今日数据]
	 */
	public function add()
	{
		//数据提交
		if(IS_POST){
			$Todaydata = D('Todaydata');
			if($data = $Todaydata->create()){
				//处理时间
				if(!empty($Todaydata->create_time)){
					$Todaydata->create_time = strtotime($Todaydata->create_time);
				} else {
					$Todaydata->create_time = time();
				}

				if($insertid = $Todaydata->add()){
					//新增排序
					$Todaydata->where("id = {$insertid}")->setField('sort',$insertid);
					$this->success(L('_ADD_SUCCESS_'),U('index',decode(I('post.parameter'))));
				} else {
					$this->error(L('_ADD_ERROR_'));
				}
			} else {
				$this->error($Todaydata->getError());
			}
		} else {
			//获取参数
			$this->parameter = I('get.parameter');
			$this->display();
		}
	}

	/**
	 * [edit 编辑今日数据]
	*/
	public function edit()
	{
		//提交数据处理
		if(IS_POST){
			$Todaydata = D('Todaydata');
			if($data = $Todaydata->create()){

				//处理时间
				if(!empty($Todaydata->create_time)){
					$Todaydata->create_time = strtotime($Todaydata->create_time);
				} else {
					$Todaydata->create_time = time();
				}

				//保存数据
				if($Todaydata->save()){
					$this->success(L('_SAVE_SUCCESS_'),U('index',decode(I('post.parameter'))));
				} else {
					$this->error(L('_SAVE_ERROR_'));
				}
			} else {
				$this->error($Todaydata->getError());
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
			$Todaydata = M('Todaydata');
			$where['id'] = array('eq',$id);
			$this->data = $Todaydata->where($where)->find();
			if(!$this->data){
				$this->error(L('_NODATA_'));
			}

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
			$Todaydata = D('Todaydata');
			foreach ($ids as $key => $value) {
				$data = $Todaydata->field('id')->find($value);
				if(!$data){
					$this->error(L('_NODATA_'));
					break;
				}
			}

			//删除数据
			if($Todaydata->delete($id)){
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

			$Todaydata = M('Todaydata');
			//更新数据
			foreach ($sortarr as $key => $value) {
				list($data['id'],$data['sort']) = explode("|", $value);
				$data['sort'] = intval($data['sort']);
				$Todaydata->save($data);
			}
			$this->success(L('_SORT_SUCCESS_'));
		} else {
			$this->error(L('_ACCESS_ERROR_'));
		}
	}
}
?>