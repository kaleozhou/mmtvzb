<?php
/*
* Author: [ Copy Lian ]
* Date: [ 2015.05.08 ]
* Description [ Ucenter ]
*/
namespace Zzcfadmin\Controller;

class MemberController extends CommonController
{
	/**
	 * [index 列表]
	 */
	public function index()
	{
	/*搜索条件*/
		$searchData = I('get.');
		$condition = array();
		if(!empty($searchData)){
			//username
			isset($searchData['username']) ? $condition['_string'] = 'username LIKE "%'.$searchData['username'].'%"': '';
			//email
			isset($searchData['email']) ? $condition['email'] = array('like',"%".$searchData['email']."%") : '';
			//mobile
			isset($searchData['mobile']) ? $condition['mobile'] = array('like',"%".$searchData['mobile']."%") : '';
			//gid
			isset($searchData['gid']) ? $condition['gid'] = array('eq',$searchData['gid']) : '';
			//lock
			isset($searchData['lock']) ? $condition['lock'] = array('eq',$searchData['lock']) : '';
			isset($searchData['robots']) ? $condition['robots'] = array('eq',$searchData['robots']) : '';
			//status
			isset($searchData['status']) ? $condition['status'] = array('eq',$searchData['status']) : '';
			//regdate
			isset($searchData['starttime']) ? $starttime = array('egt',$searchData['starttime']) : $starttime = '';
			isset($searchData['endtime']) ? $endtime = array('elt',$searchData['endtime']) : $endtime = '';
			if(!empty($starttime) || !empty($endtime)){
				$regdate = array();
				if(!empty($starttime)){
					$regdate[] = $starttime;
				}
				if(!empty($endtime)){
					$regdate[] = $endtime;
				}
				$condition['regdate'] = $regdate;
			}
		}
		//设置where
		if(!empty($condition)){
			$where = $condition;
		} else {
			$where = array();
		}
	/*end 搜索条件*/
		$Member = D('Member');
		$count = $Member->field('id')->where($where)->count();
		//获取分页
		$page = getPage($count,C('SYSTEM_PAGENUM'),'','',"/",1,"<span aria-hidden='true'>&laquo;</span>","<span aria-hidden='true'>&raquo;</span>",'');
		$this->pagelist = $page->show();
		$data = $Member->where($where)->relation(array('membergroup'))->order('regdate DESC,sort ASC')->limit($page->firstRow,$page->listRows)->select();

		//处理数组
		if(!empty($data)){
			foreach ($data as $key => $value) {
				//解析地区
				$data[$key]['regarea'] = unserialize($value['regarea']);

				//解析地区
				$data[$key]['last_login_area'] = unserialize($value['last_login_area']);

				//设置头像
				if(!empty($value['thumb'])){
					$data[$key]['thumb'] = unserialize($value['thumb']);
				}

				//设置时长
				$data[$key]['watch_time'] = changeTimeType($value['watch_time']);

				//设置意向
				$watch_time = (int)$value['watch_time'];
				$ah = (int)($watch_time/3600);
				$am = (int)(($watch_time - $ah*3600)/60);
				$as = $watch_time - $ah*3600 - $am*60;
				if($ah >= 10){
					$data[$key]['watch_time_info'] = '<span style="color:#F00">意向程度非常大</span>';
				}else if($ah >= 5){
					$data[$key]['watch_time_info'] = '<span style="color:#FF8040">意向程度很大</span>';
				}else if($ah >= 1){
					$data[$key]['watch_time_info'] = '<span style="color:#0080C0">意向程度大</span>';
				}
			}
		}

		$this->data = $data;

		//获取用户组
		$Membergroup = M('Membergroup');
		$Membergroup_where['status'] = array('eq',1);
		$this->group = $Membergroup->where($Membergroup_where)->select();

	/*获取url参数*/
		$this->parameter = getParameter(I('get.'),$page);
	/* end 获取url参数*/

		$this->display();
	}

	/**
	 * [search 搜索]
	 */
	public function search()
	{
		if(IS_POST){
			$data = I('post.');
			//处理时间
			if(!empty($data['starttime'])){
				$data['starttime'] = strtotime($data['starttime']);
			}
			if(!empty($data['endtime'])){
				$data['endtime'] = strtotime($data['endtime']);
			}
			//加载自定义函数库
			load('myfunction',APP_PATH.'Common/Common');
			$data = clearEmptyData($data);

			//直接跳转
			$this->success(L('_SEARCHING_'),U("index",$data));
		} else {
			$this->error(L('_ACCESS_ERROR_'));
		}
	}

	/**
	 * [add 新增]
	*/
	public function add()
	{
		//数据提交
		if(IS_POST){
			$Member = D('Member');
			if($data = $Member->create()){

				//key重新索引解决冲突
				$Member->thumb = array_values($Member->thumb);
				$Member->thumb = serialize($Member->thumb);

				//设置密码
				$Member->password =  md5($Member->password);
				$Member->regip = get_client_ip();
				$Member->regdate = time();

				//生日
				if(!empty($Member->birthday)){
					$Member->birthday = strtotime($Member->birthday);
				}

				//注册地区
                $ip = new \Org\Net\IpLocation('UTFWry.dat');
                $area = $ip->getlocation($Member->regip);
                $Member->regarea = serialize($area);

				if($insertid = $Member->add()){
					//新增排序
					$Member->where("id = {$insertid}")->setField('sort',$insertid);
					$this->success(L('_ADD_SUCCESS_'),U('index',decode(I('post.parameter'))));
				} else {
					$this->error(L('_ADD_ERROR_'));
				}
			} else {
				$this->error($Member->getError());
			}
		} else {
			//获取用户组
			$Membergroup = M('Membergroup');
			$where['status'] = array('eq',1);
			$this->group = $Membergroup->where($where)->select();

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
			$Member = D('Member');
			if($data = $Member->create()){

				//key重新索引解决冲突
				$Member->thumb = array_values($Member->thumb); 
				$Member->thumb = serialize($Member->thumb);

				//密码不填时不进行验证
				if(!empty($data['password'])){
					$rules = array(
						array('password','require',L('_PASSWORD_MUST_'),0,'regex',2), //密码插入时验证
		    			array('password','6,20',L('_PASSWORDLENTH_ERROR_'),0,'length',2),//密码插入时验证
		    			array('ckpassword','password',L('_CHECKPASSWORD_ERROR_'),0,'confirm',2) //密码插入时验证
					);

					//验证
					if(!$Member->validate($rules)->create()){
						$this->error($Member->getError());
					}

					//设置密码
					$Member->password =  md5($Member->password);
				} else {
					//删除对象里的密码
					unset($Member->password);
				}

				//生日
				if(!empty($Member->birthday)){
					$Member->birthday = strtotime($Member->birthday);
				}

				//保存数据
				if($Member->save()){
					$this->success(L('_SAVE_SUCCESS_'),U('index',decode(I('post.parameter'))));
				} else {
					$this->error(L('_SAVE_ERROR_'));
				}
			} else {
				$this->error($Member->getError());
			}
		} else {
			//验证数据
			$id = I('get.id');
			if(!is_numeric($id)){
				$this->error(L('_ACCESS_ERROR_'));
			}

			//获取数据
			$Member = D('Member');
			$data = $Member->relation(array('membergroup'))->find($id);
			if(!$data){
				$this->error(L('_NODATA_'));
			}

			//处理图片解析
			if(!empty($data['thumb'])){
				$data['thumb'] = unserialize($data['thumb']);
			}

			$this->data = $data;

			//获取参数
			$this->parameter = I('get.parameter');

			//获取用户组
			$Membergroup = M('Membergroup');
			$where['status'] = array('eq',1);
			$this->group = $Membergroup->where($where)->select();

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

			$pictures_files_arr = array(); //附件、图片数组
			//验证数据
			$Member = D('Member');
			foreach ($ids as $key => $value) {
				$data = $Member->find($value);
				if(!$data){
					$this->error(L('_NODATA_'));
					break;
				}

				//设置附件与图像
				$pictures_files_arr[] = unserialize($data['thumb']);
			}

			//删除数据
			if($Member->delete($id)){
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

			$Member = D('Member');
			//更新数据
			foreach ($sortarr as $key => $value) {
				list($data['id'],$data['sort']) = explode("|", $value);
				$data['sort'] = intval($data['sort']);
				$Member->save($data);
			}
			$this->success(L('_SORT_SUCCESS_'));
		} else {
			$this->error(L('_ACCESS_ERROR_'));
		}
	}

	/**
	 * [membergroup 会员组列表]
	 */
	public function membergroup()
	{
		$pid = I('get.pid',0);
		$membergroup = D('Membergroup');
		$membergroup_where['pid'] = array('eq',$pid);
		$count = $membergroup->field('id')->where($membergroup_where)->count();

		//获取分页
		$page = getPage($count,C('SYSTEM_PAGENUM'),'','',"/",1,"<span aria-hidden='true'>&laquo;</span>","<span aria-hidden='true'>&raquo;</span>",'');
		$this->pagelist = $page->show();
		$data = $membergroup->where($membergroup_where)->relation(array('members'))->order('sort ASC')->limit($page->firstRow,$page->listRows)->select();

		//获取参数
		$this->parameter = I('get.parameter');

		//设置分页
		$this->linkcate_parameter =  getParameter(I('get.'),$page);

		$this->data = $data;
		$this->display();
	}

	/**
	 * [membergroupadd 新增会员组]
	 */
	public function membergroupadd()
	{
		//数据提交
		if(IS_POST){
			$Membergroup = D('Membergroup');
			if($data = $Membergroup->create()){
				$Membergroup->create_time = time();
				if($insertid = $Membergroup->add()){
					//新增排序
					$Membergroup->where("id = {$insertid}")->setField('sort',$insertid);
					$this->success(L('_ADD_SUCCESS_'),U('membergroup',decode(I('post.linkcate_parameter'))));
				} else {
					$this->error(L('_ADD_ERROR_'));
				}
			} else {
				$this->error($Membergroup->getError());
			}
		} else {
			//获取参数
			$this->linkcate_parameter = I('get.linkcate_parameter');
			$this->display();
		}
	}

	/**
	 * [membergroupedit 编辑会员组]
	 */
	public function membergroupedit()
	{
		//提交数据处理
		if(IS_POST){
			$Membergroup = D('Membergroup');
			if($data = $Membergroup->create()){
				//保存数据
				if($Membergroup->save()){
					$this->success(L('_SAVE_SUCCESS_'),U('membergroup',decode(I('post.linkcate_parameter'))));
				} else {
					$this->error(L('_SAVE_ERROR_'));
				}
			} else {
				$this->error($Membergroup->getError());
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
			$Membergroup = M('Membergroup');

			$membergroup_where['id'] = array('eq',$id);
			$data = $Membergroup->where($membergroup_where)->find();
			if(!$data){
				$this->error(L('_NODATA_'));
			}
			$this->data = $data;
			$this->display();
		}
	}

	/**
	 * [membergroupdel 删除会员组]
	 */
	public function membergroupdel()
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
			$Membergroup = M('Membergroup');
			foreach ($ids as $key => $value) {
				$data = $Membergroup->field('id')->find($value);
				if(!$data){
					$this->error(L('_NODATA_'));
					break;
				}
			}

			//删除数据
			if($Membergroup->delete($id)){
				$this->success(L('_DEL_SUCCESS_'));
			} else {
				$this->error(L('_DEL_ERROR_'));
			}
		} else {
			$this->error(L('_ACCESS_ERROR_'));
		}
	}

	/**
	 * [membergroupsort 会员组排序]
	 */
	public function membergroupsort()
	{
		if(IS_POST){
			$sort = I('post.sort');
			$sortarr = explode(",", $sort);

			//验证数据
			if(empty($sortarr)){
				$this->error(L('_ACCESS_ERROR_'));
			}

			$Membergroup = M('Membergroup');
			//更新数据
			foreach ($sortarr as $key => $value) {
				list($data['id'],$data['sort']) = explode("|", $value);
				$data['sort'] = intval($data['sort']);
				$Membergroup->save($data);
			}
			$this->success(L('_SORT_SUCCESS_'));
		} else {
			$this->error(L('_ACCESS_ERROR_'));
		}
	}

}
?>