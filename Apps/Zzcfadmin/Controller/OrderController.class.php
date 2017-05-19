<?php
/*
* Author: [ Copy Lian ]
* Date: [ 2015.05.08 ]
* Description [ 订单管理 ]
*/
namespace Zzcfadmin\Controller;

class OrderController extends CommonController
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
			//sn
			isset($searchData['sn']) ? $condition['sn'] = array('eq',$searchData['sn']) : '';

			//name
			isset($searchData['name']) ? $condition['name'] = array('like',"%".$searchData['name']."%") : '';

			//status
			isset($searchData['status']) ? $condition['status'] = array('eq',$searchData['status']) : '';

			//create_time
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
				$condition['create_time'] = $regdate;
			}
		}
		//设置where
		if(!empty($condition)){
			$where = $condition;
		} else {
			$where = array();
		}
	/*end 搜索条件*/
		$Order = D('Order');
		$where['isdel'] = array('eq',0);
		$count = $Order->where($where)->count();

		//获取分页
		$page = getPage($count,C('SYSTEM_PAGENUM'),'','',"/",1,"<span aria-hidden='true'>&laquo;</span>","<span aria-hidden='true'>&raquo;</span>",'');
		$this->pagelist = $page->show();
		$data = $Order->where($where)->relation('member')->order('create_time DESC')->limit($page->firstRow,$page->listRows)->select();
		if(!empty($data)){
			foreach ($data as $key => $value) {
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
	 * [details 订单详情]
	 * @return [type] [description]
	*/
	public function details()
	{
		//验证数据
		$id = I('get.id');
		if(!is_numeric($id)){
			$this->error(L('_ACCESS_ERROR_'));
		}

		//获取参数
		$this->parameter = I('get.parameter');

		//获取数据
		$order = D('Order');
		$data = $order->relation('member')->find($id);
		if(!$data){
			$this->error(L('_NODATA_'));
		}

		$data['area'] = unserialize($data['area']);
		$this->data = $data;

		$this->display();
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
			$Order = M('Order');
			foreach ($ids as $key => $value) {
				$data = $Order->field('id')->find($value);
				if(!$data){
					$this->error(L('_NODATA_'));
					break;
				}
			}

			//设置isdel为1
			$del_where['id'] = array('in',$ids);
			if($Order->where($del_where)->setField("isdel",1)){
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

			$Order = M('Order');
			//更新数据
			foreach ($sortarr as $key => $value) {
				list($data['id'],$data['sort']) = explode("|", $value);
				$data['sort'] = intval($data['sort']);
				$Order->save($data);
			}
			$this->success(L('_SORT_SUCCESS_'));
		} else {
			$this->error(L('_ACCESS_ERROR_'));
		}
	}

	/**
	 * [export 导出数据]
	 * @return [type] [description]
	 */
	public function export()
	{
		$data = I('post.');
		//处理时间
		if(!empty($data['starttime_1'])){
			$data['starttime_1'] = strtotime($data['starttime_1']);
		}
		if(!empty($data['endtime_1'])){
			$data['endtime_1'] = strtotime($data['endtime_1']);
		}

		//加载自定义函数库
		load('myfunction',APP_PATH.'Common/Common');
		$data = clearEmptyData($data);

		//设置条件
		$condition = array();

		//status
		isset($data['status']) ? $condition['status'] = array('eq',$data['status']) : '';

		//create_time
		isset($data['starttime_1']) ? $starttime = array('egt',$data['starttime_1']) : $starttime = '';
		isset($data['endtime_1']) ? $endtime = array('elt',$data['endtime_1']) : $endtime = '';
		if(!empty($starttime) || !empty($endtime)){
			$regdate = array();
			if(!empty($starttime)){
				$regdate[] = $starttime;
			}
			if(!empty($endtime)){
				$regdate[] = $endtime;
			}
			$condition['create_time'] = $regdate;
		}

		//设置where
		if(!empty($condition)){
			$where = $condition;
		} else {
			$where = array();
		}

		$Order = D('Order');
		$where['isdel'] = array('eq',0);
		$order_data = $Order->field('id,sn,name,paytype,uid,price,quantity,money,description,ip,area,status,payoktime,create_time')->where($where)->relation('member')->select();
		if(empty($order_data)){
			$this->error(L('_NODATA_'));
		}

		foreach ($order_data as $key => $value) {

			//订单编号
			$order_data[$key]['sn'] = "'".$value['sn'];

			//物流编号
			$order_data[$key]['expressnum'] = "'".$value['expressnum'];

			//购买客户
			$order_data[$key]['uid'] = !empty($value['member']['username']) ? $value['member']['username'] : $value['member']['name'];

			//单价
			$order_data[$key]['price'] = $value['price'];

			//总价格
			$order_data[$key]['money'] = $value['money'];


			//下单时间
			$order_data[$key]['create_time'] = date("Y-m-d H:i:s",$value['create_time']);

			//来源地区
			$area = unserialize($value['area']);
			$order_data[$key]['area'] = $area['area'] . " " . $area['country'];

			//支付状态
			$order_data[$key]['status'] = ($value['status'] == 0) ? '未支付' : '已支付';

			//支付时间
			if(!empty($value['payoktime'])){
				$order_data[$key]['payoktime'] = date("Y-m-d H:i:s",$value['payoktime']);
			}
			
			unset($order_data[$key]['member']);
		}

		//导出数据
		exportData('Order',array('ID','订单号','订单名称','支付类型','购买用户','单价','数量','总金额','说明','ip','地区','支付状态','支付时间','下单时间'),$order_data);
	}


	/**
	 * [recycle 回收站列表]
	 */
	public function recycle()
	{
	/*搜索条件*/
		$searchData = I('get.');
		$condition = array();
		if(!empty($searchData)){
			//sn
			isset($searchData['sn']) ? $condition['sn'] = array('eq',$searchData['sn']) : '';

			//name
			isset($searchData['name']) ? $condition['name'] = array('like',"%".$searchData['name']."%") : '';

			//status
			isset($searchData['status']) ? $condition['status'] = array('eq',$searchData['status']) : '';

			//create_time
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
				$condition['create_time'] = $regdate;
			}
		}
		//设置where
		if(!empty($condition)){
			$where = $condition;
		} else {
			$where = array();
		}
	/*end 搜索条件*/
		$Order = D('Order');
		$where['siteid'] = array('eq',session('siteid'));
		$where['isdel'] = array('eq',1);
		$count = $Order->where($where)->count();

		//获取分页
		$page = getPage($count,C('SYSTEM_PAGENUM'),'','',"/",1,"<span aria-hidden='true'>&laquo;</span>","<span aria-hidden='true'>&raquo;</span>",'');
		$this->pagelist = $page->show();
		$data = $Order->where($where)->relation('member')->order('create_time DESC')->limit($page->firstRow,$page->listRows)->select();
		if(!empty($data)){
			foreach ($data as $key => $value) {
				$data[$key]['area'] = unserialize($value['area']);
			}
		}
		$this->data = $data;

		//获取参数
		$this->parameter = I('get.parameter');

	/*获取url参数*/
		//$this->parameter = getParameter(I('get.'),$page);
	/* end 获取url参数*/
		$this->display();
	}

	/**
	 * [recyclesearch 回收站搜索]
	 */
	public function recyclesearch()
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
			$this->success(L('_SEARCHING_'),U("recycle",$data));
		} else {
			$this->error(L('_ACCESS_ERROR_'));
		}
	}

	/**
	 * [recycledata 还原订单]
	 */
	public function recycledata()
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
			$Order = M('Order');
			foreach ($ids as $key => $value) {
				$data = $Order->field('id')->find($value);
				if(!$data){
					$this->error(L('_NODATA_'));
					break;
				}
			}

			//设置isdel为1
			$recycle_where['id'] = array('in',$ids);
			if($Order->where($recycle_where)->setField("isdel",0)){
				$this->success(L('_RECYCLE_SUCCESS_'));
			} else {
				$this->error(L('_RECYCLE_ERROR_'));
			}
		} else {
			$this->error(L('_ACCESS_ERROR_'));
		}
	}


	/**
	 * [recycledel 删除]
	 */
	public function recycledel()
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
			$Order = M('Order');
			foreach ($ids as $key => $value) {
				$data = $Order->field('id')->find($value);
				if(!$data){
					$this->error(L('_NODATA_'));
					break;
				}
			}

			//删除数据
			if($Order->delete($id)){
				$this->success(L('_DEL_SUCCESS_'));
			} else {
				$this->error(L('_DEL_ERROR_'));
			}
		} else {
			$this->error(L('_ACCESS_ERROR_'));
		}
	}

}
?>