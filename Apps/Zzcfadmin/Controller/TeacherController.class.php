<?php
/*
* Author: [ Copy Lian ]
* Date: [ 2015.05.08 ]
* Description [ 老师 ]
*/
namespace Zzcfadmin\Controller;

class TeacherController extends CommonController
{
	/**
	 * [index 老师列表]
	 */
	public function index()
	{
		$Teacher = D('Teacher');
		$count = $Teacher->field('id')->count();
		//获取分页
		$page = getPage($count,C('SYSTEM_PAGENUM'),'','',"/",1,"<span aria-hidden='true'>&laquo;</span>","<span aria-hidden='true'>&raquo;</span>",'');
		$this->pagelist = $page->show();
		$data = $Teacher->relation(array('adminuser'))->order('sort ASC,id DESC')->limit($page->firstRow,$page->listRows)->select();
		if(!empty($data)){
			foreach ($data as $key => $value) {
				$data[$key]['thumb'] = unserialize($value['thumb']);

				//结算地区
				$data[$key]['last_js_area'] = unserialize($value['last_js_area']);

				//提现余额按照80%提现
				$data[$key]['zhrmb'] = ($value['money']/100)*0.8;
			}
		}
		$this->data = $data;

	/*获取url参数*/
		$this->parameter = getParameter(I('get.'),$page);
	/* end 获取url参数*/
		$this->display();
	}
	/**
	 * [add 新增老师]
	 */
	public function add()
	{
		//数据提交
		if(IS_POST){
			$Teacher = D('Teacher');
			if($data = $Teacher->create()){
				//key重新索引解决冲突
				$Teacher->thumb = array_values($Teacher->thumb);
				$Teacher->thumb = serialize($Teacher->thumb);

				if($insertid = $Teacher->add()){
					//新增排序
					$Teacher->where("id = {$insertid}")->setField('sort',$insertid);
					$this->success(L('_ADD_SUCCESS_'),U('index',decode(I('post.parameter'))));
				} else {
					$this->error(L('_ADD_ERROR_'));
				}
			} else {
				$this->error($Teacher->getError());
			}
		} else {
			//获取参数
			$this->parameter = I('get.parameter');
			$this->display();
		}
	}

	/**
	 * [edit 编辑老师]
	*/
	public function edit()
	{
		//提交数据处理
		if(IS_POST){
			$Teacher = D('Teacher');
			if($data = $Teacher->create()){
				//key重新索引解决冲突
				$Teacher->thumb = array_values($Teacher->thumb);
				$Teacher->thumb = serialize($Teacher->thumb);

				//保存数据
				if($Teacher->save()){
					$this->success(L('_SAVE_SUCCESS_'),U('index',decode(I('post.parameter'))));
				} else {
					$this->error(L('_SAVE_ERROR_'));
				}
			} else {
				$this->error($Teacher->getError());
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
			$Teacher = M('Teacher');
			$where['id'] = array('eq',$id);
			$data = $Teacher->where($where)->find();
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
			$Teacher = D('Teacher');
			foreach ($ids as $key => $value) {
				$data = $Teacher->field('id')->find($value);
				if(!$data){
					$this->error(L('_NODATA_'));
					break;
				}

				//设置附件与图像
				$pictures_files_arr[] = unserialize($data['thumb']);
			}

			//删除数据
			if($Teacher->delete($id)){
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

			$Teacher = M('Teacher');
			//更新数据
			foreach ($sortarr as $key => $value) {
				list($data['id'],$data['sort']) = explode("|", $value);
				$data['sort'] = intval($data['sort']);
				$Teacher->save($data);
			}
			$this->success(L('_SORT_SUCCESS_'));
		} else {
			$this->error(L('_ACCESS_ERROR_'));
		}
	}

	/**
	 * [jiesuan 结算]
	 * @return [type] [description]
	 */
	public function jiesuan()
	{
		if(IS_POST){
			$postData = I('post.data');
			$id = $postData['id'];
			if(!is_numeric($id)){
				$this->error(L('_ACCESS_ERROR_'));
			}
			$Teacher = M('Teacher');
			$saveData['id'] = $id;
			$saveData['money'] = 0;
			$saveData['jsuid'] = session('uid');

			$saveData['last_js_time'] = time();
			$saveData['last_js_ip'] = get_client_ip();

			//地区
            $ip = new \Org\Net\IpLocation('UTFWry.dat');
            $area = $ip->getlocation($saveData['last_js_ip']);
			$saveData['last_js_area'] = serialize($area);

			//结算的人民币
			$saveData['last_js_money'] = $postData['zhrmb'];

			if($Teacher->save($saveData)){
				$this->success(L('完成结算！'));
			} else {
				$this->error(L('结算失败！'));
			}
		}
	}
}
?>