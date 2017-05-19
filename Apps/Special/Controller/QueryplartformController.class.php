<?php
/*
* Author: [ Copy Lian ]
* Date: [ 2015.05.13 ]
* Description [ 首页 ]
*/
namespace Special\Controller;
class QueryplartformController extends CommonController {

    /**
     * 首页
    */
    public function index(){

    	$this->display();
    }

    /**
     * [Openaccount 信息提交]
     * @return [type] [description]
     */
    public function Openaccount()
    {
        if(IS_POST){
            //验证ip是否被限制
            $this->iplimit();

            //验证标题敏感词汇
            $this->sensitivewords(I('post.name'),L('_NAME_SENSITIVE_'));

            //验证码
            $code = I('post.code');
            $mobile = I('post.tel');
            /*if(isset($code) && !empty($code)){
                if(!check_verify($code)){
                    $this->error(L('_VERIFY_ERROR_'));
                }
            }*/

            /*if($code != session('mobile_code') || $mobile != session('mobile')){
                $this->error(L('_VERIFY_ERROR_'));
            }*/

            //创建数据
            $Openaccount = D('Openaccount');
            if($Openaccount->create()){
                //验证是否存在数据
                $where['name'] = array('eq',$Openaccount->name);
                $where['tel'] = array('eq',$Openaccount->tel);
                if($one = $Openaccount->where($where)->find()){
                    $this->error('您已经提交信息，我们很快会与您取得联系！');
                }

                //ip
                $Openaccount->ip = get_client_ip();

                //地区
                $ip = new \Org\Net\IpLocation('UTFWry.dat');
                $area = $ip->getlocation($Openaccount->ip);
                $Openaccount->area = serialize($area);

                //事件
                $Openaccount->create_time = time();
                $Openaccount->siteid = C('SITEID');

                if($inserid = $Openaccount->add()){
                    $Openaccount->where("id = ".$inserid."")->setField("sort",$inserid);
                    $this->success('信息提交成功！');
                } else {
                    $this->error('信息提交失败！');
                }
            } else {
                $this->error($Openaccount->getError());
            }
        } else {
            $this->error(L('_ACCESS_ERROR_'));
        }
    }
}