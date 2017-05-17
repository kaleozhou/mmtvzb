<?php
/*
* Author: [ Copy Bingo ]
* Date: [ 2016.11.25 ]
* Description [ 手机端控制器 ]
*/
namespace Zhibo\Controller;
class MobileController extends CommonController {
    /**
     * [_initialize 初始化]
     * @return [type]        [description]
     */
    public function _initialize()
    {
        parent::_initialize();
        $nowsite = $this->nowsite;
        if(!empty($nowsite['url'])){
            //pc端条件下跳转到WAP端
            /*if(ismobile()){
                redirect($nowsite['wap_url']);
            }*/

            //WAP端条件跳转到pc端
            if(!ismobile()){
                redirect($nowsite['url']);
            }
        }
    }

    /**
     * 首页
     */
    public function index(){
    /* 获取公司信息 */
        $CompanyInfo = M("CompanyInfo");
        $CompanyInfo_where['status'] = array('eq',1);
        $this->CompanyInfoData = $CompanyInfo->where($CompanyInfo_where)->find();
    /* end 获取公司信息 */

    /*获取消息*/
        $Usermessage = D('Usermessage');
        $usermsg_where['status'] = array('eq',1);
        $usermesData = $Usermessage->where($usermsg_where)->order('create_time DESC')->limit(15)->select();
        krsort($usermesData);
        if(!empty($usermesData)){
            foreach ($usermesData as $key => $value) {
                //判断来源用户信息
                if($value['fromusertype'] == 0){
                    //游客
                    $usermesData[$key]['userdata']['username'] = '游客' . $value['fromuid'];
                    $usermesData[$key]['userdata']['id'] = $value['fromuid'];
                } elseif($value['fromusertype'] == 1){
                    //会员
                    $usermesData[$key]['userdata'] = D('Member')->field('id,gid,username,thumb')->relation(array('membergroup'))->find($value['fromuid']);
                } elseif($value['fromusertype'] == 2){
                    //管理员
                    $adminuser_where_from['id'] = array('eq',$value['fromuid']);
                    $adminuserinfo_from = D('Adminuser')->field('id,username,head,email,mobile')->where($adminuser_where_from)->relation('authgroupaccess')->find();
                    $userdata_from = $adminuserinfo_from;

                    /**
                     * 1、如果 authgroupaccess 值但是为超级管理员
                     * 2、如果 authgroupaccess 值不为空则设置相应的角色
                     * 3、如果 authgroupaccess 值为空且不是超级管理员则是游客
                     */
                    if(in_array($value['fromuid'], C('SUPERADMIN'))){
                        $userdata_from['authrole'] = '超级管理员';
                        $userdata_from['authicon'] = '/Public/images/authgroup/xc02.png';
                    } elseif(!empty($adminuserinfo_from['authgroupaccess'])){
                        //获取角色组
                        $roleids_from = array();
                        foreach ($adminuserinfo_from['authgroupaccess'] as $key_1 => $value_1) {
                            $roleids_from[] = $value_1['group_id'];
                        }
                        $authgroup = M('AuthGroup');
                        $whereRole_from['id'] = array('in',$roleids_from);
                        $whereRole_from['status'] = array('eq',1);
                        $authroleData_from = $authgroup->field('id,title,icon')->where($whereRole_from)->order('sort ASC,id DESC')->select();
                        shuffle($authroleData_from);
                        $userdata_from['authrole'] = $authroleData_from[0]['title'];
                        $userdata_from['authicon'] = $authroleData_from[0]['icon'];
                    } else {
                        $userdata_from['authrole'] = '游客';
                        $userdata_from['authicon'] = '/Public/images/authgroup/xc01.png';
                    }
                    $usermesData[$key]['userdata'] = $userdata_from;
                }

                //目标用户信息
                if($value['tousertype'] == 0){
                    //游客
                    $usermesData[$key]['touserdata']['username'] = '游客' . $value['touid'];
                    $usermesData[$key]['touserdata']['id'] = $value['touid'];
                } elseif($value['tousertype'] == 1){
                    //会员
                    $usermesData[$key]['touserdata'] = D('Member')->field('id,gid,username,thumb')->relation(array('membergroup'))->find($value['touid']);
                } elseif($value['tousertype'] == 2){
                    //管理员
                    $adminuser_where_to['id'] = array('eq',$value['touid']);
                    $adminuserinfo_to = D('Adminuser')->field('id,username,head,email,mobile')->where($adminuser_where_to)->relation('authgroupaccess')->find();
                    $userdata_to = $adminuserinfo_to;

                    /**
                     * 1、如果 authgroupaccess 值但是为超级管理员
                     * 2、如果 authgroupaccess 值不为空则设置相应的角色
                     * 3、如果 authgroupaccess 值为空且不是超级管理员则是游客
                     */
                    if(in_array($value['touid'], C('SUPERADMIN'))){
                        $userdata_to['authrole'] = '超级管理员';
                    } elseif(!empty($adminuserinfo_to['authgroupaccess'])){
                        //获取角色组
                        $roleids_to = array();
                        foreach ($adminuserinfo_to['authgroupaccess'] as $key_1 => $value_1) {
                            $roleids_to[] = $value['group_id'];
                        }
                        $authgroup = M('AuthGroup');
                        $whereRole_to['id'] = array('in',$roleids_to);
                        $whereRole_to['status'] = array('eq',1);
                        $authroleData_to = $authgroup->field('id,title,icon')->where($whereRole_to)->order('sort ASC,id DESC')->select();
                        shuffle($authroleData_to);
                        $userdata_to['authrole'] = $authroleData_to[0]['title'];
                        $userdata_to['authicon'] = $authroleData_to[0]['icon'];
                    } else {
                        $userdata_to['authrole'] = '游客';
                    }
                    $usermesData[$key]['touserdata'] = $userdata_to;
                } else {
                    //全部人
                    $usermesData[$key]['touserdata'] = array();
                }
            }
        }
        $this->usermesData = $usermesData;
        //p($this->usermesData);
    /*end 获取消息*/
        $this->display();
    }
}