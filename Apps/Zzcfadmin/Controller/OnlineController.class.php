<?php
/*
* Author: [ Copy Lian ]
* Date: [ 2015.05.08 ]
* Description [ 在线用户 ]
*/
namespace Zzcfadmin\Controller;

class OnlineController extends CommonController
{
	/**
	 * [index 在线用户列表]
	 */
	public function index()
	{
		/*搜索条件*/
		$searchData = I('get.');
		$condition = array();
		if(!empty($searchData)){
			//username
			isset($searchData['username']) ? $condition['username'] = array('like',"%".$searchData['username']."%") : '';

			//usertype
			isset($searchData['usertype']) ? $condition['usertype'] = array('eq',$searchData['usertype']) : '';

			//ip
			isset($searchData['ip']) ? $condition['ip'] = array('eq',$searchData['ip']) : '';

			//watch_time
			isset($searchData['watch_time']) ? $condition['watch_time'] = array('egt',$searchData['watch_time']) : '';

			//status
			isset($searchData['status']) ? $condition['status'] = array('eq',$searchData['status']) : '';

			//fip
			isset($searchData['fip']) ? $condition['fip'] = array('eq',$searchData['fip']) : '';

			//loginout_time
			isset($searchData['starttime']) ? $starttime = array('egt',$searchData['starttime']) : $starttime = '';
			isset($searchData['endtime']) ? $endtime = array('elt',$searchData['endtime']) : $endtime = '';
			if(!empty($starttime) || !empty($endtime)){
				$loginout_time = array();
				if(!empty($starttime)){
					$loginout_time[] = $starttime;
				}
				if(!empty($endtime)){
					$loginout_time[] = $endtime;
				}
				$condition['loginout_time'] = $loginout_time;
			}
		}
		//设置where
		if(!empty($condition)){
			$where = $condition;
		} else {
			$where = array();
		}
	/*end 搜索条件*/

		$Online = D('Online');
		$count = $Online->field('id')->where($where)->count();
		//p($Online->getLastSql());
		//获取分页
		$page = getPage($count,C('SYSTEM_PAGENUM'),'','',"/",1,"<span aria-hidden='true'>&laquo;</span>","<span aria-hidden='true'>&raquo;</span>",'');
		$this->pagelist = $page->show();
		$data = $Online->where($where)->order('create_time DESC,sort ASC')->limit($page->firstRow,$page->listRows)->select();
		if(!empty($data)){
			foreach ($data as $key => $value) {
				//地区
				$data[$key]['area'] = unserialize($value['area']);

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

				//判断来源用户信息
                if($value['usertype'] == 0){
                    //游客
                    $data[$key]['userdata']['username'] = '游客' . $value['uid'];
                    $data[$key]['userdata']['id'] = $value['uid'];
                } elseif($value['usertype'] == 1){
                    //会员
                    $data[$key]['userdata'] = D('Member')->field('id,gid,username,thumb,robots')->relation(array('membergroup'))->find($value['uid']);
                } elseif($value['usertype'] == 2){
                    //管理员
                    $adminuser_where['id'] = array('eq',$value['uid']);
                    $adminuserinfo = D('Adminuser')->field('id,username,head,email,mobile')->where($adminuser_where)->relation('authgroupaccess')->find();
                    $userdata = $adminuserinfo;

                    /**
                     * 1、如果 authgroupaccess 值但是为超级管理员
                     * 2、如果 authgroupaccess 值不为空则设置相应的角色
                     * 3、如果 authgroupaccess 值为空且不是超级管理员则是游客
                     */
                    if(in_array($value['uid'], C('SUPERADMIN'))){
                        $userdata['authrole'] = '超级管理员';
                    } elseif(!empty($adminuserinfo['authgroupaccess'])){
                        //获取角色组
                        $roleids = array();
                        foreach ($adminuserinfo['authgroupaccess'] as $key_1 => $value_1) {
                            $roleids[] = $value_1['group_id'];
                        }
                        $authgroup = M('AuthGroup');
                        $whereRole['id'] = array('in',$roleids);
                        $whereRole['status'] = array('eq',1);
                        $authroleData = $authgroup->field('id,title')->where($whereRole)->order('sort ASC,id DESC')->select();
                        shuffle($authroleData);
                        $userdata['authrole'] = $authroleData[0]['title'];
                    } else {
                        $userdata['authrole'] = '游客';
                    }
                    $data[$key]['userdata'] = $userdata;
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
	 * [setStatus 禁言]
	 */
	public function setStatus()
	{
		if(IS_POST){
			$id = I('post.data');
			if(!is_numeric($id)){
				$this->error(L('_ACCESS_ERROR_'));
			}

			$Online = M('Online');
			//设置状态为1或者0
			$data = $Online->find($id);
			$where['id'] = array('eq',$id);
			if($data['status'] == 1){
				//设置为0
				$Online->where($where)->setField('status',0);
			} else {
				//设置为1
				$Online->where($where)->setField('status',1);
			}
			$this->success(L('_SHENHE_OK_'));
		}
	}

	/**
	 * [setStatus 封ip]
	 */
	public function setIp()
	{
		if(IS_POST){
			$id = I('post.data');
			if(!is_numeric($id)){
				$this->error(L('_ACCESS_ERROR_'));
			}

			$Online = M('Online');
			//设置状态为1或者0
			$data = $Online->find($id);
			$where['id'] = array('eq',$id);
			if($data['fip'] == 1){
				//设置为0
				$Online->where($where)->setField('fip',0);
			} else {
				//设置为1
				$Online->where($where)->setField('fip',1);
			}
			$this->success(L('_FIP_OK_'));
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
			$Online = D('Online');
			foreach ($ids as $key => $value) {
				$data = $Online->field('id')->find($value);
				if(!$data){
					$this->error(L('_NODATA_'));
					break;
				}
			}

			//删除数据
			if($Online->delete($id)){
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

			$Online = M('Online');
			//更新数据
			foreach ($sortarr as $key => $value) {
				list($data['id'],$data['sort']) = explode("|", $value);
				$data['sort'] = intval($data['sort']);
				$Online->save($data);
			}
			$this->success(L('_SORT_SUCCESS_'));
		} else {
			$this->error(L('_ACCESS_ERROR_'));
		}
	}
}
?>