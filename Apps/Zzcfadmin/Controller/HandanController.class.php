<?php
/*
* Author: [ Copy Lian ]
* Date: [ 2015.05.08 ]
* Description [ 喊单系统 ]
*/
namespace Zzcfadmin\Controller;

class HandanController extends CommonController
{
	/**
	 * [index 喊单列表]
	 */
	public function index()
	{
		$Handan = D('Handan');
		//如果是超级管理员可看全部内容
		if(!in_array(session('uid'),C('ADMINISTRATOR'))){
			$where['uid'] = array('eq',session('uid'));
		} else {
			$where = array();
		}

		$count = $Handan->field('id')->where($where)->count();
		//获取分页
		$page = getPage($count,C('SYSTEM_PAGENUM'),'','',"/",1,"<span aria-hidden='true'>&laquo;</span>","<span aria-hidden='true'>&raquo;</span>",'');
		$this->pagelist = $page->show();
		$data = $Handan->where($where)->relation(array('catept','catepz','adminuser'))->order('jtime DESC,id DESC')->limit($page->firstRow,$page->listRows)->select();

		//处理数据
		if(!empty($data)){
			foreach ($data as $key => $value) {
				//如果平仓数据为空则不做处理
				if(!empty($value['pnum'])){
					//如果duokong的值是1（多）则是：平仓 - 建仓
					//如果duokong的值是0（空）则是：建仓 - 平仓
					if($value['duokong'] == 0){
						$data[$key]['zhiyindian'] = $value['jnum'] - $value['pnum'];
					} else {
						$data[$key]['zhiyindian'] = $value['pnum'] - $value['jnum'];
					}
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
	 * [add 新增喊单]
	 */
	public function add()
	{
		//数据提交
		if(IS_POST){
			$Handan = D('Handan');
			if($data = $Handan->create()){
				$Handan->uid = session("uid");
				$Handan->create_time = time();
				$Handan->jtime = time();
				if($insertid = $Handan->add()){
					//新增排序
					$Handan->where("id = {$insertid}")->setField('sort',$insertid);
					$this->success(L('_ADD_SUCCESS_'),U('index',decode(I('post.parameter'))));
				} else {
					$this->error(L('_ADD_ERROR_'));
				}
			} else {
				$this->error($Handan->getError());
			}
		} else {

			$Handancate = M('Handancate');
			$where['status'] = array('eq',1);
			$menulist = $Handancate->field('id,name,pid')->where($where)->order('sort ASC,id DESC')->select();

			//平台
			$this->menulist_pt = getSelectedOption($menulist, 1, 0);

			//品种
			$this->menulist_pz = getSelectedOption($menulist, 2, 0);

			//获取参数
			$this->parameter = I('get.parameter');
			$this->display();
		}
	}

	/**
	 * [edit 编辑喊单]
	*/
	public function edit()
	{
		//提交数据处理
		if(IS_POST){
			$Handan = D('Handan');
			if($data = $Handan->create()){

				//保存数据
				if($Handan->save()){
					$this->success(L('_SAVE_SUCCESS_'),U('index',decode(I('post.parameter'))));
				} else {
					$this->error(L('_SAVE_ERROR_'));
				}
			} else {
				$this->error($Handan->getError());
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
			$Handan = M('Handan');
			$where['id'] = array('eq',$id);
			$data = $Handan->where($where)->find();
			if(!$data){
				$this->error(L('_NODATA_'));
			}
			$this->data = $data;

			//获取分类
			$Handancate = M('Handancate');
			$where_cate['status'] = array('eq',1);
			$menulist = $Handancate->field('id,name,pid')->where($where_cate)->order('sort ASC,id DESC')->select();

			//平台
			$this->menulist_pt = getSelectedOption($menulist, 1, $data['pt_cid']);

			//品种
			$this->menulist_pz = getSelectedOption($menulist, 2, $data['pz_cid']);

			$this->display();
		}
	}

	/**
	 * [edit 喊单平仓]
	*/
	public function pincang()
	{
		//提交数据处理
		if(IS_POST){
			$Handan = D('Handan');
			if($data = $Handan->create()){
				$Handan->ptime = time();
				//保存数据
				if($Handan->save()){
					$this->success(L('_SAVE_SUCCESS_'),U('index',decode(I('post.parameter'))));
				} else {
					$this->error(L('_SAVE_ERROR_'));
				}
			} else {
				$this->error($Handan->getError());
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
			$Handan = D('Handan');
			$where['id'] = array('eq',$id);
			$data = $Handan->where($where)->relation(array('catept','catepz'))->find();
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
			$Handan = D('Handan');
			foreach ($ids as $key => $value) {
				$data = $Handan->field('id')->find($value);
				if(!$data){
					$this->error(L('_NODATA_'));
					break;
				}
			}

			//删除数据
			if($Handan->delete($id)){
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

			$Handan = M('Handan');
			//更新数据
			foreach ($sortarr as $key => $value) {
				list($data['id'],$data['sort']) = explode("|", $value);
				$data['sort'] = intval($data['sort']);
				$Handan->save($data);
			}
			$this->success(L('_SORT_SUCCESS_'));
		} else {
			$this->error(L('_ACCESS_ERROR_'));
		}
	}

	/**
	 * [indexcate 喊单分类列表]
	 */
	public function indexcate()
	{
		$pid = I('get.pid',0);
		$where['pid'] = array('eq',$pid);
		$Handancate = D('Handancate');

		$count = $Handancate->field('id')->where($where)->count();
		//获取分页
		$page = getPage($count,C('SYSTEM_PAGENUM'),'','',"/",1,"<span aria-hidden='true'>&laquo;</span>","<span aria-hidden='true'>&raquo;</span>",'');
		$this->pagelist = $page->show();
		$data = $Handancate->where($where)->relation(array('catepid','cateson'))->order('sort ASC')->limit($page->firstRow,$page->listRows)->select();

		//获取参数
		$this->parameter = I('get.parameter');

		//设置分页
		$this->linkcate_parameter =  getParameter(I('get.'),$page);

		$this->data = $data;
		$this->display();
	}

	/**
	 * [addcate 新增分类]
	 */
	public function addcate()
	{
		//数据提交
		if(IS_POST){
			$Handancate = D('Handancate');
			if($data = $Handancate->create()){
				$Handancate->create_time = time();
				if($insertid = $Handancate->add()){
					//新增排序
					$Handancate->where("id = {$insertid}")->setField('sort',$insertid);
					$this->success(L('_ADD_SUCCESS_'),U('indexcate',decode(I('post.linkcate_parameter'))));
				} else {
					$this->error(L('_ADD_ERROR_'));
				}
			} else {
				$this->error($Handancate->getError());
			}
		} else {
			$Handancate = M('Handancate');
			$where['status'] = array('eq',1);
			$menulist = $Handancate->field('id,name,pid')->where($where)->order('sort ASC,id DESC')->select();
			//组装select下拉分类
			$this->menulist = getSelectedOption($menulist, 0, 0);

			//获取参数
			$this->linkcate_parameter = I('get.linkcate_parameter');
			$this->display();
		}
	}

	/**
	 * [editcate 编辑分类]
	 */
	public function editcate()
	{
		//提交数据处理
		if(IS_POST){
			$Handancate = D('Handancate');
			if($data = $Handancate->create()){
				//保存数据
				if($Handancate->save()){
					$this->success(L('_SAVE_SUCCESS_'),U('indexcate',decode(I('post.linkcate_parameter'))));
				} else {
					$this->error(L('_SAVE_ERROR_'));
				}
			} else {
				$this->error($Handancate->getError());
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
			$Handancate = M('Handancate');

			$Handancate_where['siteid'] = array('eq',session('siteid'));
			$Handancate_where['id'] = array('eq',$id);
			$data = $Handancate->where($Handancate_where)->find();
			if(!$data){
				$this->error(L('_NODATA_'));
			}
			$this->data = $data;

			//获取分类列表开始
			$where['status'] = array('eq',1);
			$menulist = $Handancate->field('id,name,pid')->where($where)->order('sort ASC,id DESC')->select();

			//编辑分类需要清除自身菜单以及子类
			$menulist_child = getChilds($menulist,$data['id']);
			//获取当前菜单以及所有子类id
			$idss = array();
			if(!empty($menulist_child)){
				foreach ($menulist_child as $key => $value) {
					$idss[] = $value['id'];
				}
			}
			//将父类id压入数组中
			array_push($idss,$data['id']);

			//删除数据中当前id包括子类id的数据
			if(!empty($menulist)){
				foreach ($menulist as $key => $value) {
					if(in_array($value['id'],$idss)){
						unset($menulist[$key]);
					}
				}
			}

			//组装select下拉菜单
			$this->menulist = getSelectedOption($menulist, 0, $data['pid']);

			$this->display();
		}
	}

	/**
	 * [delcate 删除分类]
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
			$Handancate = M('Handancate');
			foreach ($ids as $key => $value) {
				$data = $Handancate->field('id')->find($value);
				if(!$data){
					$this->error(L('_NODATA_'));
					break;
				}
			}

			//删除数据时需要删除子类
			$data = $Handancate->field('id,pid')->select();
			$arr = array();
			foreach ($ids as $key => $value) {
				//获取子类id数组
				$arr[] = getChilds($data,$value);
			}

			//取出id
			$idss = array();
			if(!empty($arr)){
				foreach ($arr as $key => $value) {
					foreach ($value as $key_1 => $value_1) {
						$idss[] = $value_1['id'];
					}
				}
			}

			//合并父级id与找到的子类id
			$ids = array_unique(array_merge($ids,$idss));
			$id = implode(',',$ids);

			//删除数据
			if($Handancate->delete($id)){
				$this->success(L('_DEL_SUCCESS_'));
			} else {
				$this->error(L('_DEL_ERROR_'));
			}
		} else {
			$this->error(L('_ACCESS_ERROR_'));
		}
	}

	/**
	 * [sortcate 分类排序]
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

			$Handancate = M('Handancate');
			//更新数据
			foreach ($sortarr as $key => $value) {
				list($data['id'],$data['sort']) = explode("|", $value);
				$data['sort'] = intval($data['sort']);
				$Handancate->save($data);
			}
			$this->success(L('_SORT_SUCCESS_'));
		} else {
			$this->error(L('_ACCESS_ERROR_'));
		}
	}
}
?>