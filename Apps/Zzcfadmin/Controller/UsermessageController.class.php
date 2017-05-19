<?php
/*
* Author: [ Copy Lian ]
* Date: [ 2015.05.08 ]
* Description [ 聊天消息 ]
*/
namespace Zzcfadmin\Controller;

class UsermessageController extends CommonController
{
	/**
	 * [index 消息列表]
	 */
	public function index()
	{
		/*搜索条件*/
		$searchData = I('get.');
		$condition = array();
		if(!empty($searchData)){
			//fromusertype
			isset($searchData['fromusertype']) ? $condition['fromusertype'] = array('eq',$searchData['fromusertype']) : '';

			//tousertype
			isset($searchData['tousertype']) ? $condition['tousertype'] = array('eq',$searchData['tousertype']) : '';

			//ip
			isset($searchData['ip']) ? $condition['ip'] = array('eq',$searchData['ip']) : '';

			//status
			isset($searchData['status']) ? $condition['status'] = array('eq',$searchData['status']) : '';

			//create_time
			isset($searchData['starttime']) ? $starttime = array('egt',$searchData['starttime']) : $starttime = '';
			isset($searchData['endtime']) ? $endtime = array('elt',$searchData['endtime']) : $endtime = '';
			if(!empty($starttime) || !empty($endtime)){
				$create_time = array();
				if(!empty($starttime)){
					$create_time[] = $starttime;
				}
				if(!empty($endtime)){
					$create_time[] = $endtime;
				}
				$condition['create_time'] = $create_time;
			}
		}
		//设置where
		if(!empty($condition)){
			$where = $condition;
		} else {
			$where = array();
		}
	/*end 搜索条件*/

		$Usermessage = D('Usermessage');
		$count = $Usermessage->field('id')->where($where)->count();
		//获取分页
		$page = getPage($count,C('SYSTEM_PAGENUM'),'','',"/",1,"<span aria-hidden='true'>&laquo;</span>","<span aria-hidden='true'>&raquo;</span>",'');
		$this->pagelist = $page->show();
		$data = $Usermessage->where($where)->order('create_time DESC,sort ASC')->limit($page->firstRow,$page->listRows)->select();
		if(!empty($data)){
			foreach ($data as $key => $value) {
				//地区
				$data[$key]['area'] = unserialize($value['area']);

				//判断来源用户信息
                if($value['fromusertype'] == 0){
                    //游客
                    $data[$key]['userdata']['username'] = '游客' . $value['fromuid'];
                    $data[$key]['userdata']['id'] = $value['fromuid'];
                } elseif($value['fromusertype'] == 1){
                    //会员
                    $data[$key]['userdata'] = D('Member')->field('id,gid,username,thumb,robots')->relation(array('membergroup'))->find($value['fromuid']);
                } elseif($value['fromusertype'] == 2){
                    //管理员
                    $adminuser_where['id'] = array('eq',$value['fromuid']);
                    $adminuserinfo = D('Adminuser')->field('id,username,head,email,mobile')->where($adminuser_where)->relation('authgroupaccess')->find();
                    $userdata = $adminuserinfo;

                    /**
                     * 1、如果 authgroupaccess 值但是为超级管理员
                     * 2、如果 authgroupaccess 值不为空则设置相应的角色
                     * 3、如果 authgroupaccess 值为空且不是超级管理员则是游客
                     */
                    if(in_array($value['fromuid'], C('SUPERADMIN'))){
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

                //目标用户信息
                if($value['tousertype'] == 0){
                    //游客
                    $data[$key]['touserdata']['username'] = '游客' . $value['touid'];
                    $data[$key]['touserdata']['id'] = $value['touid'];
                } elseif($value['tousertype'] == 1){
                    //会员
                    $data[$key]['touserdata'] = D('Member')->field('id,gid,username,thumb')->relation(array('membergroup'))->find($value['touid']);
                } elseif($value['tousertype'] == 2){
                    //管理员
                    $adminuser_where['id'] = array('eq',$value['touid']);
                    $adminuserinfo = D('Adminuser')->field('id,username,head,email,mobile')->where($adminuser_where)->relation('authgroupaccess')->find();
                    $userdata = $adminuserinfo;

                    /**
                     * 1、如果 authgroupaccess 值但是为超级管理员
                     * 2、如果 authgroupaccess 值不为空则设置相应的角色
                     * 3、如果 authgroupaccess 值为空且不是超级管理员则是游客
                     */
                    if(in_array($value['touid'], C('SUPERADMIN'))){
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
                    $data[$key]['touserdata'] = $userdata;
                } else {
                    //全部人
                    $data[$key]['touserdata'] = array();
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
	 * [setStatus 审核]
	 */
	public function setStatus()
	{
		if(IS_POST){
			$id = I('post.data');
			if(!is_numeric($id)){
				$this->error(L('_ACCESS_ERROR_'));
			}

			$Usermessage = M('Usermessage');
			//设置状态为1或者0
			$data = $Usermessage->find($id);
			$where['id'] = array('eq',$id);
			if($data['status'] == 1){
				//设置为0
				$Usermessage->where($where)->setField('status',0);
			} else {
				//设置为1
				$Usermessage->where($where)->setField('status',1);
			}
			$this->success(L('_SHENHE_OK_'));
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
			$Usermessage = D('Usermessage');
			foreach ($ids as $key => $value) {
				$data = $Usermessage->field('id')->find($value);
				if(!$data){
					$this->error(L('_NODATA_'));
					break;
				}
			}

			//删除数据
			if($Usermessage->delete($id)){
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

			$Usermessage = M('Usermessage');
			//更新数据
			foreach ($sortarr as $key => $value) {
				list($data['id'],$data['sort']) = explode("|", $value);
				$data['sort'] = intval($data['sort']);
				$Usermessage->save($data);
			}
			$this->success(L('_SORT_SUCCESS_'));
		} else {
			$this->error(L('_ACCESS_ERROR_'));
		}
	}
}
?>