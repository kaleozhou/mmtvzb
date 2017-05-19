<?php
/*
* Author: [ Copy Bingo ]
* Date: [ 2016.11.21 ]
* Description [ 课程安排]
*/
namespace Zzcfadmin\Controller;

class CourseController extends CommonController
{
	/**
	 * [index 课程安排列表]
	 */
	public function index()
	{
		$adminuser = D('CourseArrange');
		//不显示超级管理员
		$count = $adminuser->field('id')->count();
		//获取分页
		$page = getPage($count,C('SYSTEM_PAGENUM'),'','',"/",1,"<span aria-hidden='true'>&laquo;</span>","<span aria-hidden='true'>&raquo;</span>",'');
		$this->pagelist = $page->show();
		$this->data = $adminuser->order('sort ASC,id DESC')->limit($page->firstRow,$page->listRows)->select();

		/*获取url参数*/
		$this->parameter = getParameter(I('get.'),$page);
		/* end 获取url参数*/
		$this->display();
	}
	/**
	 * [add 添加]
	 */
	public function add()
	{
		//数据提交
		if(IS_POST){
			$adminuser = D('CourseArrange');
			if($data = $adminuser->create()){
				$adminuser->update_time=time();
				if($insertid = $adminuser->add()){
					//新增排序
					$adminuser->where("id = {$insertid}")->setField('sort',$insertid);
					$this->success(L('_ADD_SUCCESS_'),U('index',decode(I('post.parameter'))));
				} else {
					$this->error(L('_ADD_ERROR_'));
				}
			} else {
				$this->error($adminuser->getError());
			}
		} else {
			//获取参数
			$this->parameter = I('get.parameter');
			$this->display();
		}
	}

	/**
	 * [edit 编辑]
	 */
	public function edit()
	{
		//提交数据处理
		if(IS_POST){
			$adminuser = D('CourseArrange');
			if($data = $adminuser->create()){
				//设置更新时间
				$adminuser->update_time = time();
				//保存数据
				if($adminuser->save()){
					$this->success(L('_SAVE_SUCCESS_'),U('index',decode(I('post.parameter'))));
				} else {
					$this->error(L('_SAVE_ERROR_'));
				}
			} else {
				$this->error($adminuser->getError());
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
			$adminuser = M('CourseArrange');
			$where['id'] = array('eq',$id);
			$this->data = $adminuser->where($where)->find();
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
			$adminuser = D('CourseArrange');
			foreach ($ids as $key => $value) {
				$data = $adminuser->field('id')->find($value);
				if(!$data){
					$this->error(L('_NODATA_'));
					break;
				}
			}
			//删除数据
			if($adminuser->delete($id)){
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
			$adminuser = M('CourseArrange');
			//更新数据
			foreach ($sortarr as $key => $value) {
				list($data['id'],$data['sort']) = explode("|", $value);
				$data['sort'] = intval($data['sort']);
				$adminuser->save($data);
			}
			$this->success(L('_SORT_SUCCESS_'));
		} else {
			$this->error(L('_ACCESS_ERROR_'));
		}
	}
}
?>