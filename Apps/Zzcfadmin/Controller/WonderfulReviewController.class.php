<?php
/*
* Author: [ Copy Lian ]
* Date: [ 2015.05.08 ]
* Description [ 精彩回顾视频 ]
*/
namespace Zzcfadmin\Controller;

class WonderfulReviewController extends CommonController
{
	
	/**
	 * [index 列表]
	 */
	public function index()
	{
		$this->catid = I('get.id');
		//获取主播分类信息
		$cat_where['id'] = array('eq',$this->catid);
		if(!$this->cate = M('Reviewcate')->where($cat_where)->find()){
			$this->error(L('_ACCESS_ERROR_'));
		}

		$WonderfulReview = D('WonderfulReview');
		$where['catid'] = array('eq',$this->catid);
		$count = $WonderfulReview->where($where)->field('id')->count();
		//获取分页
		$page = getPage($count,C('SYSTEM_PAGENUM'),'','',"/",1,"<span aria-hidden='true'>&laquo;</span>","<span aria-hidden='true'>&raquo;</span>",'');
		$this->pagelist = $page->show();
		$data = $WonderfulReview->where($where)->order('create_time DESC')->limit($page->firstRow,$page->listRows)->select();

		//处理数组
		if(!empty($data)){
			foreach ($data as $key => $value) {
				$data[$key]['thumb'] = unserialize($value['thumb']);
			}
		}
		$this->data = $data;

		//获取参数
		$this->linkcate_parameter = I('get.linkcate_parameter');

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
			$WonderfulReview = D('WonderfulReview');
			if($data = $WonderfulReview->create()){
				//key重新索引解决冲突
				$WonderfulReview->thumb = array_values($WonderfulReview->thumb);
				$WonderfulReview->thumb = serialize($WonderfulReview->thumb);

				//创建时间
				if(!empty($WonderfulReview->create_time)){
					$WonderfulReview->create_time = strtotime($WonderfulReview->create_time);
				} else {
					$WonderfulReview->create_time = time();
				}

				if($insertid = $WonderfulReview->add()){
					//新增排序
					$WonderfulReview->where("id = {$insertid}")->setField('sort',$insertid);
					$this->success(L('_ADD_SUCCESS_'),U('index',decode(I('post.parameter'))));
				} else {
					$this->error(L('_ADD_ERROR_'));
				}
			} else {
				$this->error($WonderfulReview->getError());
			}
		} else {
			$this->catid = I('get.catid');
			if(!is_numeric($this->catid) ){
				$this->error(L('_ACCESS_ERROR_'));
			}
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
			$WonderfulReview = D('WonderfulReview');
			if($data = $WonderfulReview->create()){
				//key重新索引解决冲突
				$WonderfulReview->thumb = array_values($WonderfulReview->thumb);
				$WonderfulReview->thumb = serialize($WonderfulReview->thumb);

				//创建时间
				if(!empty($WonderfulReview->create_time)){
					$WonderfulReview->create_time = strtotime($WonderfulReview->create_time);
				}

				//保存数据
				if($WonderfulReview->save()){
					$this->success(L('_SAVE_SUCCESS_'),U('index',decode(I('post.parameter'))));
				} else {
					$this->error(L('_SAVE_ERROR_'));
				}
			} else {
				$this->error($WonderfulReview->getError());
			}
		} else {
			//验证数据
			$id = I('get.id');
			$this->catid = I('get.catid');
			if(!is_numeric($id) || !is_numeric($this->catid)){
				$this->error(L('_ACCESS_ERROR_'));
			}

			$this->catid = I('get.catid');

			//获取参数
			$this->parameter = I('get.parameter');

			//获取数据
			$WonderfulReview = M('WonderfulReview');
			$where['id'] = array('eq',$id);
			$data = $WonderfulReview->where($where)->find();
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
			$WonderfulReview = D('WonderfulReview');
			foreach ($ids as $key => $value) {
				$data = $WonderfulReview->find($value);
				if(!$data){
					$this->error(L('_NODATA_'));
					break;
				}

				//设置附件与图像
				$pictures_files_arr[] = unserialize($data['thumb']);
			}

			//删除数据
			if($WonderfulReview->delete($id)){
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

			$WonderfulReview = M('WonderfulReview');
			//更新数据
			foreach ($sortarr as $key => $value) {
				list($data['id'],$data['sort']) = explode("|", $value);
				$data['sort'] = intval($data['sort']);
				$WonderfulReview->save($data);
			}
			$this->success(L('_SORT_SUCCESS_'));
		} else {
			$this->error(L('_ACCESS_ERROR_'));
		}
	}

	/**
	 * [cate 分类]
	 */
	public function reviewcate()
	{
		$reviewcate = D('Reviewcate');

		$count = $reviewcate->field('id')->count();
		//获取分页
		$page = getPage($count,C('SYSTEM_PAGENUM'),'','',"/",1,"<span aria-hidden='true'>&laquo;</span>","<span aria-hidden='true'>&raquo;</span>",'');

		$this->pagelist = $page->show();
		$this->data = $reviewcate->relation(array('wonderfulreview'))->order('sort ASC,id DESC')->limit($page->firstRow,$page->listRows)->select();

		//获取参数
		$this->parameter = I('get.parameter');

		//设置分页
		$this->linkcate_parameter =  getParameter(I('get.'),$page);

		$this->display();
	}


	/**
	 * [add 分类新增]
	 */
	public function reviewcateadd()
	{
		//数据提交
		if(IS_POST){
			$reviewcate = D('Reviewcate');
			if($data = $reviewcate->create()){
				//key重新索引解决冲突
				$reviewcate->thumb = array_values($reviewcate->thumb);
				$reviewcate->thumb = serialize($reviewcate->thumb);

				$reviewcate->create_time = time();
				if($insertid = $reviewcate->add()){
					//新增排序
					$reviewcate->where("id = {$insertid}")->setField('sort',$insertid);
					$this->success(L('_ADD_SUCCESS_'),U('reviewcate',decode(I('post.linkcate_parameter'))));
				} else {
					$this->error(L('_ADD_ERROR_'));
				}
			} else {
				$this->error($reviewcate->getError());
			}
		} else {
			//获取参数
			$this->linkcate_parameter = I('get.linkcate_parameter');

			$this->display();
		}
	}

	/**
	 * [edit 分类编辑]
	*/
	public function reviewcateedit()
	{
		//提交数据处理
		if(IS_POST){
			$reviewcate = D('Reviewcate');
			if($data = $reviewcate->create()){
				//key重新索引解决冲突
				$reviewcate->thumb = array_values($reviewcate->thumb);
				$reviewcate->thumb = serialize($reviewcate->thumb);
	
				//保存数据
				if($reviewcate->save()){
					$this->success(L('_SAVE_SUCCESS_'),U('reviewcate',decode(I('post.linkcate_parameter'))));
				} else {
					$this->error(L('_SAVE_ERROR_'));
				}
			} else {
				$this->error($reviewcate->getError());
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
			$reviewcate = M('Reviewcate');
			$where['id'] = array('eq',$id);
			$data = $reviewcate->where($where)->find();
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
	public function reviewcatedel()
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
			$reviewcate = M('Reviewcate');
			foreach ($ids as $key => $value) {
				$data = $reviewcate->field('id')->find($value);
				if(!$data){
					$this->error(L('_NODATA_'));
					break;
				}
			}

			//验证分类下是否有数据
			$wonderfulreview = M('WonderfulReview');
			$wonderfulreview_where['catid'] = array('in',$ids);
			if($wonderfulreview->where($wonderfulreview_where)->field('id')->find()){
				$this->error(L('_EXISIST_CATE_ERROR_'));
			}

			//删除数据
			if($reviewcate->delete($id)){
				$this->success(L('_DEL_SUCCESS_'));
			} else {
				$this->error(L('_DEL_ERROR_'));
			}
		} else {
			$this->error(L('_ACCESS_ERROR_'));
		}
	}

	/**
	 * [sort 分类排序]
	 * @return [type] [description]
	 */
	public function reviewcatesort()
	{
		if(IS_POST){
			$sort = I('post.sort');
			$sortarr = explode(",", $sort);

			//验证数据
			if(empty($sortarr)){
				$this->error(L('_ACCESS_ERROR_'));
			}

			$reviewcate = M('Reviewcate');
			//更新数据
			foreach ($sortarr as $key => $value) {
				list($data['id'],$data['sort']) = explode("|", $value);
				$data['sort'] = intval($data['sort']);
				$reviewcate->save($data);
			}
			$this->success(L('_SORT_SUCCESS_'));
		} else {
			$this->error(L('_ACCESS_ERROR_'));
		}
	}
}
?>