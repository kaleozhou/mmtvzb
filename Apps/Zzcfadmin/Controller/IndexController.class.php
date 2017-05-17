<?php
/*
* Author: [ Copy Lian ]
* Date: [ 2015.05.08 ]
* Description [ 后台首页 ]
*/
namespace Zzcfadmin\Controller;

class IndexController extends CommonController{

	/**
	 * [index 后台首页]
	 * @return [type] [description]
	 */
    public function index(){
        //获取用户信息
        $adminuser = D('Adminuser');
        $where['id'] = array('eq',session('uid'));
        $adminuserinfo = $adminuser->field('id,username,head,email,mobile')->where($where)->relation('authgroupaccess')->find();
        $this->adminuserinfo = $adminuserinfo;
        if(!$this->adminuserinfo){
            $this->error(L('_NODATA_'));
        }
        /**
         * 1、如果 authgroupaccess 值但是为超级管理员
         * 2、如果 authgroupaccess 值不为空则设置相应的角色
         * 3、如果 authgroupaccess 值为空且不是超级管理员则是游客
         */
        if(in_array(session('uid'), C('SUPERADMIN'))){
            $this->authrole = '超级管理员';
        } elseif(!empty($adminuserinfo['authgroupaccess'])){
            //获取角色组
            $roleids = array();
            foreach ($adminuserinfo['authgroupaccess'] as $key => $value) {
                $roleids[] = $value['group_id'];
            }
            $authgroup = M('AuthGroup');
            $whereRole['siteid'] = array('eq',session('siteid'));
            $whereRole['id'] = array('in',$roleids);
            $whereRole['status'] = array('eq',1);
            $authroleData = $authgroup->field('id,title')->where($whereRole)->order('sort ASC,id DESC')->select();
            shuffle($authroleData);
            $this->authrole = $authroleData[0]['title'];
        } else {
            $this->authrole = '游客';
        }

        //获取常用菜单
        $this->getRecommendMenu();

    	$this->display();
    }

    /**
     * [getRecommendMenu 获取后台常用菜单]
     */
    public function getRecommendMenu(){

            //获取开发身份
            $adminuser_where['id'] = session("uid");
            $adminuser_one = M('Adminuser')->field('id,dev')->where($adminuser_where)->find();
            //如果不是开发者那么隐藏开发者选项的菜单，如果是开发者则忽略
            if(!$adminuser_one['dev']){
                $dev_where['dev'] = array('eq',0);

                //设置不同的条件
                $all_where['dev'] = $dev_where['dev'];
            }

        /*获取当前站点的条件*/
            $all_where['siteid'] = array('in',array(0,session("siteid")));
        /*end 获取当前站点的条件*/

            //获取后台菜单
            $adminmenud = D('Adminmenu');
            $all_where['status'] = array('eq',1);
            $all_where['recommend'] = array('eq',1);
            $all_where['pid'] = array('neq',0);
            $menu_data_list = $adminmenud->where($all_where)->relation('rulemodle')->order('sort ASC')->select();

            //处理数据
            if(!empty($menu_data_list)){
                foreach ($menu_data_list as $key => $value) {
                    if(!empty($value['parameter'])){
                        $menu_data_list[$key]['parameter'] = htmlspecialchars_decode($value['parameter']);
                    }
                }
            }

            $menu['child'] = array();
            //通过父类的id获取子类的菜单
            $child = $menu_data_list;
            if(!empty($child)){
                foreach ($child as $key => $value) {
                    //验证权限
                    if(!authCheck(MODULE_NAME . "/" . $value['url'], session("uid"))){
                        unset($child[$key]);
                    }
                }
            }
            $this->recommendMenu = $child;
    }

    /**
     * [welcome 欢迎页]
     * @return [type] [description]
     */
    public function welcome()
    {
        //计算在线人数
        $Online = M('Online');
        $this->all_online_count = $Online->field('id')->count();

        //计算在线会员
        $online_member['usertype'] = array('eq',1);
        $this->member_online_count = $Online->field('id')->where($online_member)->count();

        //计算在线游客
        $online_guest['usertype'] = array('eq',0);
        $this->guest_online_count = $Online->field('id')->where($online_guest)->count();

        //计算注册会员
        //开始时间
        $startime = strtotime(date('Y-m-d'));
        //结束时间
        $etime = $startime + 86399;
        $Member = M('Member');
        $member_where['regdate'] = array('between',array($startime,$etime));
        $this->member_count = $Member->field('id')->where($member_where)->count();

        //总会员
        $this->all_member_count = $Member->field('id')->count();

        $this->display();
    }
}