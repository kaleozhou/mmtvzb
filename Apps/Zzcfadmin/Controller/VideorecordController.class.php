<?php
/*
* Author: [ Copy Lian ]
* Date: [ 2015.05.08 ]
* Description [ 录播视频 ]
*/
namespace Zzcfadmin\Controller;

class VideorecordController extends CommonController
{
	/**
	 * [index 列表]
	 */
	public function index()
	{
		$Videorecord = D('Videorecord');
		$count = $Videorecord->field('id')->count();
		//获取分页
		$page = getPage($count,C('SYSTEM_PAGENUM'),'','',"/",1,"<span aria-hidden='true'>&laquo;</span>","<span aria-hidden='true'>&raquo;</span>",'');
		$this->pagelist = $page->show();
		$data = $Videorecord->order('create_time DESC')->limit($page->firstRow,$page->listRows)->select();

		//处理数组
		if(!empty($data)){
			foreach ($data as $key => $value) {
				$data[$key]['thumb'] = unserialize($value['thumb']);
			}
		}
		$this->data = $data;

	/*获取url参数*/
		$this->parameter = getParameter(I('get.'),$page);
	/* end 获取url参数*/
		$this->display();
	}
	/**
	 * [add 新增]
	 */
	public function add()
	{
		//数据提交
		if(IS_POST){
			$Videorecord = D('Videorecord');
			if($data = $Videorecord->create()){
				//key重新索引解决冲突
				$Videorecord->thumb = array_values($Videorecord->thumb);
				$Videorecord->thumb = serialize($Videorecord->thumb);

				//创建时间
				if(!empty($Videorecord->create_time)){
					$Videorecord->create_time = strtotime($Videorecord->create_time);
				} else {
					$Videorecord->create_time = time();
				}

				if($insertid = $Videorecord->add()){
					//新增排序
					$Videorecord->where("id = {$insertid}")->setField('sort',$insertid);
					$this->success(L('_ADD_SUCCESS_'),U('index',decode(I('post.parameter'))));
				} else {
					$this->error(L('_ADD_ERROR_'));
				}
			} else {
				$this->error($Videorecord->getError());
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
			$Videorecord = D('Videorecord');
			if($data = $Videorecord->create()){
				//key重新索引解决冲突
				$Videorecord->thumb = array_values($Videorecord->thumb);
				$Videorecord->thumb = serialize($Videorecord->thumb);

				//创建时间
				if(!empty($Videorecord->create_time)){
					$Videorecord->create_time = strtotime($Videorecord->create_time);
				}

				//保存数据
				if($Videorecord->save()){
					$this->success(L('_SAVE_SUCCESS_'),U('index',decode(I('post.parameter'))));
				} else {
					$this->error(L('_SAVE_ERROR_'));
				}
			} else {
				$this->error($Videorecord->getError());
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
			$Videorecord = M('Videorecord');
			$where['id'] = array('eq',$id);
			$data = $Videorecord->where($where)->find();
			if(!$data){
				$this->error(L('_NODATA_'));
			}

			//处理图片解析
			if(!empty($data['thumb'])){
				$data['thumb'] = unserialize($data['thumb']);
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
			$Videorecord = D('Videorecord');
			foreach ($ids as $key => $value) {
				$data = $Videorecord->find($value);
				if(!$data){
					$this->error(L('_NODATA_'));
					break;
				}

				//设置附件与图像
				$pictures_files_arr[] = unserialize($data['thumb']);
			}

			//删除数据
			if($Videorecord->delete($id)){
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

			$Videorecord = M('Videorecord');
			//更新数据
			foreach ($sortarr as $key => $value) {
				list($data['id'],$data['sort']) = explode("|", $value);
				$data['sort'] = intval($data['sort']);
				$Videorecord->save($data);
			}
			$this->success(L('_SORT_SUCCESS_'));
		} else {
			$this->error(L('_ACCESS_ERROR_'));
		}
	}
}
?>