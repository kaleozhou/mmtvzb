<?php
/*
* Author: [ Copy Bingo ]
* Date: [ 2016.11.22]
* Description [ 老师战队]
*/
namespace Zzcfadmin\Controller;
class TeacherteamController extends CommonController
{
	/**
	 * [index 老师战队列表]
	 */
	public function index()
	{
		$adminuser = D('TeacherTeam');
		//不显示超级管理员
		$count = $adminuser->field('id')->count();
		//获取分页
		$page = getPage($count,C('SYSTEM_PAGENUM'),'','',"/",1,"<span aria-hidden='true'>&laquo;</span>","<span aria-hidden='true'>&raquo;</span>",'');
		$this->pagelist = $page->show();
		$data = $adminuser->order('sort ASC,id DESC')->limit($page->firstRow,$page->listRows)->select();

		//处理图片解析
		if(!empty($data)){
			foreach ($data as $key => $value) {
				if(!empty($value['photo'])){
					$data[$key]['photo'] = unserialize($value['photo']);
				}
			}
		}

		$this->data = $data;
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
			$adminuser = D('TeacherTeam');
			if($data = $adminuser->create()){
				//key重新索引解决冲突
				$adminuser->photo = array_values($adminuser->photo);
				$adminuser->photo = serialize($adminuser->photo);
				$adminuser->add_time=time();
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
			$adminuser = D('TeacherTeam');
			if($data = $adminuser->create()){
				//key重新索引解决冲突
				$adminuser->photo = array_values($adminuser->photo);
				$adminuser->photo = serialize($adminuser->photo);
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
			$adminuser = M('TeacherTeam');
			$where['id'] = array('eq',$id);
			$data = $adminuser->where($where)->find();
			if(!$data){
				$this->error(L('_NODATA_'));
			}

			//处理图片解析
			if(!empty($data['photo'])){
				$data['photo'] = unserialize($data['photo']);
			}
			$this->data=$data;
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
			$adminuser = D('TeacherTeam');
			foreach ($ids as $key => $value) {
				$data = $adminuser->find($value);
				if(!$data){
					$this->error(L('_NODATA_'));
					break;
				}

				//设置附件与图像
				$pictures_files_arr[] = unserialize($data['photo']);
			}

			//删除数据
			if($adminuser->delete($id)){
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
			$adminuser = M('TeacherTeam');
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