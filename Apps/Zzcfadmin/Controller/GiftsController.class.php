<?php
/*
* Author: [ Copy Lian ]
* Date: [ 2015.05.08 ]
* Description [ 礼物 ]
*/
namespace Zzcfadmin\Controller;

class GiftsController extends CommonController
{
	/**
	 * [index 礼物列表]
	 */
	public function index()
	{
		$Gifts = D('Gifts');
		$count = $Gifts->field('id')->count();
		//获取分页
		$page = getPage($count,C('SYSTEM_PAGENUM'),'','',"/",1,"<span aria-hidden='true'>&laquo;</span>","<span aria-hidden='true'>&raquo;</span>",'');
		$this->pagelist = $page->show();
		$data = $Gifts->order('sort ASC,id DESC')->limit($page->firstRow,$page->listRows)->select();
		if(!empty($data)){
			foreach ($data as $key => $value) {
				$data[$key]['thumb'] = unserialize($value['thumb']);
				$data[$key]['gifthumb'] = unserialize($value['gifthumb']);
			}
		}
		$this->data = $data;

	/*获取url参数*/
		$this->parameter = getParameter(I('get.'),$page);
	/* end 获取url参数*/
		$this->display();
	}
	/**
	 * [add 新增礼物]
	 */
	public function add()
	{
		//数据提交
		if(IS_POST){
			$Gifts = D('Gifts');
			if($data = $Gifts->create()){
				//key重新索引解决冲突
				$Gifts->thumb = array_values($Gifts->thumb);
				$Gifts->thumb = serialize($Gifts->thumb);

				$Gifts->gifthumb = array_values($Gifts->gifthumb);
				$Gifts->gifthumb = serialize($Gifts->gifthumb);
				if($insertid = $Gifts->add()){
					//新增排序
					$Gifts->where("id = {$insertid}")->setField('sort',$insertid);
					$this->success(L('_ADD_SUCCESS_'),U('index',decode(I('post.parameter'))));
				} else {
					$this->error(L('_ADD_ERROR_'));
				}
			} else {
				$this->error($Gifts->getError());
			}
		} else {
			//获取参数
			$this->parameter = I('get.parameter');
			$this->display();
		}
	}

	/**
	 * [edit 编辑礼物]
	*/
	public function edit()
	{
		//提交数据处理
		if(IS_POST){
			$Gifts = D('Gifts');
			if($data = $Gifts->create()){
				//key重新索引解决冲突
				$Gifts->thumb = array_values($Gifts->thumb);
				$Gifts->thumb = serialize($Gifts->thumb);

				$Gifts->gifthumb = array_values($Gifts->gifthumb);
				$Gifts->gifthumb = serialize($Gifts->gifthumb);

				//保存数据
				if($Gifts->save()){
					$this->success(L('_SAVE_SUCCESS_'),U('index',decode(I('post.parameter'))));
				} else {
					$this->error(L('_SAVE_ERROR_'));
				}
			} else {
				$this->error($Gifts->getError());
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
			$Gifts = M('Gifts');
			$where['id'] = array('eq',$id);
			$data = $Gifts->where($where)->find();
			if(!$data){
				$this->error(L('_NODATA_'));
			}

			//处理图片解析
			if(!empty($data['thumb'])){
				$data['thumb'] = unserialize($data['thumb']);
				$data['gifthumb'] = unserialize($data['gifthumb']);
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
			$Gifts = D('Gifts');
			foreach ($ids as $key => $value) {
				$data = $Gifts->field('id')->find($value);
				if(!$data){
					$this->error(L('_NODATA_'));
					break;
				}

				//设置附件与图像
				$pictures_files_arr[] = unserialize($data['thumb']);
				//设置附件与图像
				$pictures_files_arr_1[] = unserialize($data['gifthumb']);
			}

			//删除数据
			if($Gifts->delete($id)){
				//删除图片
				delfilefun($pictures_files_arr);
				delfilefun($pictures_files_arr_1);

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

			$Gifts = M('Gifts');
			//更新数据
			foreach ($sortarr as $key => $value) {
				list($data['id'],$data['sort']) = explode("|", $value);
				$data['sort'] = intval($data['sort']);
				$Gifts->save($data);
			}
			$this->success(L('_SORT_SUCCESS_'));
		} else {
			$this->error(L('_ACCESS_ERROR_'));
		}
	}
}
?>