<?php
/*
* Author: [ Copy Lian ]
* Date: [ 2015.05.13 ]
* Description [ 首页 ]
*/
namespace Zhibo\Controller;
class IndexController extends CommonController {
    /**
     * [_initialize 初始化]
     * @return [type]        [description]
     */
    public function _initialize()
    {
        parent::_initialize();
        $nowsite = $this->nowsite;
        if(!empty($nowsite['wap_url'])){
            //pc端条件下跳转到WAP端
            if(ismobile()){
                redirect($nowsite['wap_url']);
            }

            //WAP端条件跳转到pc端
            /*if(!ismobile()){
                $redirect_url = U("@".$this->myurl,"",false,true);
                redirect($redirect_url);
            }*/
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
    
    /* 获取抽奖*/
    	$Luckdraw = M('Luckdraw');
    	$this->luckdraws = $Luckdraw->order('create_time DESC')->limit(30)->select();
    /* end 获取抽奖*/

   	/* 获取今日数据 */
    	$Todaydata = M('Todaydata');
    	$Todaydata_where['status'] = array('eq',1);
    	$this->todaydata = $Todaydata->where($Todaydata_where)->order('create_time DESC')->limit(20)->select();
    /* end 获取今日数据 */

    /* 获取实时直播数据 */
        $Broadcast = M('Broadcast');
        $Broadcast_where['status'] = array('eq',1);
        $this->gbdata = $Broadcast->where($Broadcast_where)->find();
    /* end 获取实时直播数据 */
	
	
	/* 获取在线用户 */
        $online = D('Online');
        $online_where['status'] = array('eq',0);
        $this->online_user_list = $online->where($online_where)->order('id desc')->limit(100)->select();
    /* end 获取在线用户 */

    /* 图片轮播 */
        $this->banner_show = S('banner_show');
        if(empty($this->banner_show)){
        	/*获取分类信息的状态自行判断*/

            //图片模型
            $photo = M('Photo');

            //状态
            $photo_where['status'] = array('eq',1);

            //图片分类
            $photo_where['catid'] = array('eq',1);

            $banner_show = $photo->where($photo_where)->order('sort ASC,id DESC')->limit(10)->select();

            //处理thumb缩略图
            if(!empty($banner_show)){
                foreach ($banner_show as $key => $value) {
                    if(!empty($value['thumb'])){
                       $banner_show[$key]['thumb'] =  unserialize($value['thumb']);
                    }
                }
            }
            $this->banner_show = $banner_show;

            S('banner_show',$this->banner_show);
        }
    /* end 图片轮播 */

    /*获取消息*/
        $Usermessage = D('Usermessage');
        $usermsg_where['status'] = array('eq',1);
        $usermesData = $Usermessage->where($usermsg_where)->order('create_time DESC')->limit(30)->select();
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

        /*$post_data = array(
           "type" => "publish",
           "content" => "这个是推送的测试数据",
           "to" => $this->to_uid,
        );
        messocket($post_data);*/
    /* 获取礼物 */
        $Gifts = M('Gifts');
        $Gifts_where['status'] = array('eq',1);
        $GiftsData = $Gifts->where($Gifts_where)->order('sort ASC,id DESC')->select();
        if(!empty($GiftsData)){
            foreach ($GiftsData as $key => $value) {
                $GiftsData[$key]['thumb'] = unserialize($value['thumb']);
                $GiftsData[$key]['gifthumb'] = unserialize($value['gifthumb']);
            }
        }
        $this->GiftsData = $GiftsData;
    /* end 获取礼物 */
    /* 获取礼物 */
        /*$Teacher = M('Teacher');
        $Teacher_where['status'] = array('eq',1);
        $TeacherData = $Teacher->where($Teacher_where)->order('sort ASC,id DESC')->select();
        if(!empty($TeacherData)){
            foreach ($TeacherData as $key => $value) {
                $TeacherData[$key]['thumb'] = unserialize($value['thumb']);
            }
        }
        $this->TeacherData = $TeacherData;*/
        //获取课程表老师信息
        $onlineTeacher = array();
        $Course = M('CourseArrange');
        $where['status'] = array('eq',1);
        $Coursedata = $Course->where($where)->order('sort ASC')->select();
        if(!empty($Coursedata)){
            foreach ($Coursedata as $key => $value) {
                $datetime = explode('-',$value['ctime']);
                if(!empty($datetime)){
                    foreach ($datetime as $key_1 => $value_1) {
                        $Coursedata[$key]['datetime'][$key_1] = strtotime($value_1);
                        $datetime[$key_1] = strtotime($value_1);
                    }
                    sort($datetime);
                    $datetimecount = count($datetime);
                    if($datetime[0] <= time() && time() < $datetime[1]){
                        $onlineTeacher = $value;
                    }
                }
            }
        }

        $TeacherData = array();
        //获取送礼物的老师
        if(!empty($onlineTeacher)){
            //获取星期几的信息
            $weekarray = array("sun_day","mon_day","tues_day","wednes_day","thurs_day","fri_day","sat_day");
            $onlineT = preg_replace("/\s*/", '', $onlineTeacher[$weekarray[date("w")]]);
            $onlineT = explode("/",$onlineT);

            //获取老师信息
            $Teacher = M('Teacher');
            $Teacher_where['status'] = array('eq',1);
            if(!empty($onlineT)){
                $Teacher_where['name'] = array('in',$onlineT);
            }
            $TeacherData = $Teacher->where($Teacher_where)->order('sort ASC,id DESC')->select();
            if(!empty($TeacherData)){
                foreach ($TeacherData as $key => $value) {
                    $TeacherData[$key]['thumb'] = unserialize($value['thumb']);
                }
            }
        }

        //如果获取不到，则获取全部
        if(empty($TeacherData)){
            $Teacher = M('Teacher');
            $Teacher_where_all['status'] = array('eq',1);
            $TeacherData = $Teacher->where($Teacher_where_all)->order('sort ASC,id DESC')->select();
            if(!empty($TeacherData)){
                foreach ($TeacherData as $key => $value) {
                    $TeacherData[$key]['thumb'] = unserialize($value['thumb']);
                }
            }
        }
        $this->TeacherData = $TeacherData;
    /* end 获取礼物 */

    /* 获取充值类型 */
        $Rechargetype = M('Rechargetype');
        $Rechargetype_where['status'] = array('eq',1);
        $RechargetypeData = $Rechargetype->where($Rechargetype_where)->order('sort ASC,id DESC')->select();
        $this->RechargetypeData = $RechargetypeData;
    /* end 获取充值类型 */

    /*生成订单号*/
        $this->sn = date('YmdHis').mt_rand(100000,999999);
    /*end 生成订单号*/

    /* 获取支付接口 */
        $payconfig = require(CONF_PATH . 'pay.php');
        $this->payconfig = array_values($payconfig);
    /* end 获取支付接口 */

    /* 获取余额 */
        if(session("?memberid")){
            $memberData = M('Member')->find(session('memberid'));
            $this->leftmoney = $memberData['money'];
        }
    /* end 获取余额 */

    	$this->display();
    }

    /**
     * [FunctionName 喊单系统]
     * @param string $value [description]
     */
    public function handan()
    {
        $Handan = D('Handan');
        $where['status'] = array('eq',1);
        $data = $Handan->where($where)->relation(array('catept','catepz','adminuser'))->order('jtime DESC')->limit(10)->select();
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
        $this->handandata = $data;

        $this->display();
    }

    /**
     * [url 保存到桌面]
     * @return [type] [description]
     */
    public function url()
    {

    	$url = U("@".$this->myurl,'',true,true);
    	$filename = C('CONTENT_DESK_NAME');
    	downloadShortcuts($url,$filename,__ROOT__.'favicon.ico');
    }

    /**
     * [down 软件下载]
     * @return [type] [description]
     */
    public function down()
    {
        $this->display();
    }

    /**
     * [course 课程安排]
     * @return [type] [description]
     */
    public function course()
    {
        $Course = M('CourseArrange');
        $where['status'] = array('eq',1);
        $this->Coursedata = $Course->where($where)->order('sort ASC')->select();
        $this->display();
    }

    /**
     * [teacher 老师战队]
     * @return [type] [description]
     */
    public function teacher()
    {
        $TeacherTeam = M('TeacherTeam');
        $where['status'] = array('eq',1);
        $teacher = $TeacherTeam->where($where)->order('sort ASC')->select();
        //处理图片解析
        if(!empty($teacher)){
            foreach ($teacher as $key => $value) {
                if(!empty($value['photo'])){
                    $teacher[$key]['photo'] = unserialize($value['photo']);
                }
            }
        }
        $this->teacher = $teacher;
        $this->display();
    }

   	/**
   	 * [Luckcheck 抽奖验证]
   	 */
    public function Luckcheck()
    {
    	if(IS_POST){
    		$tel = I('post.tel');
    		$code = I('post.code');

    		if(empty($tel)){
    			$this->error('手机号码必须填写！');
    		}

    		if(empty($code)){
    			$this->error('验证码必须填写！');
    		}

    		if($code != session('mobile_code')){
    			$this->error('验证码不正确！');
    		}

    		$Luckdraw = M('Luckdraw');

    		//验证手机是否抽过奖
    		$where['tel'] = array('eq',$tel);
    		$datacheck = $Luckdraw->where($where)->find();
    		if($datacheck){
    			//清空session
	    		session('mobile_code',null);
    			$this->error('该手机已经抽奖过！');
    		}

    		//开始抽奖
    		$Luckproducts = M('Luckproducts');
    		$where_pro['status'] = array('eq',1);
    		$LuckproductsData = $Luckproducts->where($where_pro)->select();

    		//奖品
    		$prize_arr = array();
    		if(!empty($LuckproductsData)){
    			foreach ($LuckproductsData as $key => $value) {
    				$pjjd = 360/count($LuckproductsData);
    				$min = ($key-1)*$pjjd + ($pjjd/2) + 10;
    				$max = $key*$pjjd + ($pjjd/2) - 10;
    				$prize_arr[] = array('id'=>$value['id'],'min'=>$min,'max'=>$max,'num'=>$value['num'],'prize'=>$value['name'],'v'=>$value['probability']);
    			}
    		}

			//获取id对应的数组 array(1=>10,2=>15,3=>50,4=>25)
			foreach ($prize_arr as $key => $val) {
			    $arr[$key] = $val['v'];
			}
			$rid = get_rand($arr);

			//中奖项
			$res = $prize_arr[$rid];

        /* 如果中奖的是喵币，则将数据写入相对应的手机用户账户*/
            if(preg_match('/喵币/', $res['prize'])){
                $Member = M('Member');
                $member_where['mobile'] = array('eq',$tel);
                $member_where['status'] = array('eq',1);
                $member_where['lock'] = array('eq',0);
                if($memberdata = $Member->where($member_where)->find()){
                    //更新喵币
                    $money = intval($res['prize']);
                    $member_where['id'] = array('eq',$memberdata['id']);
                    $Member->where($member_where)->setInc('money',$money);
                }
            }
        /* end 如果中奖的是喵币，则将数据写入相对应的手机用户账户*/

			//随机生成一个角度
			$min = $res['min'];
			$max = $res['max'];
			$result['angle'] = mt_rand($min,$max);

			//验证商品库存是否还足够,如果不够就提示没有中奖
			$check_where['lid'] = array('eq',$res['id']);
			$count = $Luckdraw->where($check_where)->count();
			if($count >= $res['num']){
				//清空session
	    		session('mobile_code',null);
				$this->error('很遗憾，奖品已经被抽完啦！');
			}

			//清空session
	    	session('mobile_code',null);

			//写入数据
			$add_data['tel'] = $tel;
			$add_data['lid'] = $res['id'];
			$add_data['lname'] = $res['prize'];
			$add_data['ip'] = get_client_ip();
			$ip = new \Org\Net\IpLocation('UTFWry.dat');
            $add_data['area'] = serialize($ip->getlocation($add_data['ip']));
			$add_data['create_time'] = time();
			if($insertid = $Luckdraw->add($add_data)){
				//新增排序
				$Luckdraw->where("id = {$insertid}")->setField('sort',$insertid);

				$result['prize'] = $res['prize'];
				$result['info'] = '恭喜你，抽中了：' . $res['prize'];
	    		$result['status'] = 1;
			} else {
				$this->error('数据更新失败！');
			}

    		$this->ajaxReturn($result);
    	}
    }

    /**
     * [getFlyscreen 获取飞屏]
     * @return [type] [description]
     */
    public function getFlyscreen()
    {
    	if(IS_POST){
    		//获取ajax数据
    		$fpdata = I('post.fpdata');

    		//获取飞屏数据
    		$Flyscreen = M('Flyscreen');
	    	$where['status'] = array('eq',1);
	    	$data = $Flyscreen->where($where)->order('create_time DESC')->find();
    		if(empty($data) || empty($fpdata) || $data['create_time'] <= $fpdata){
                //不变则不返回数据
    			$returnData['status'] = 0;
    			$returnData['info'] = '没有新飞屏数据！';
    		} else {
    			//有更新则获取新数据
    			$returnData['status'] = 1;
    			$returnData['info'] = '有新飞屏数据！';
    		}

    		$returnData['fprdata'] = $data;
            $returnData['fpdata'] = $fpdata;
    		$this->ajaxReturn($returnData);

    	}
    }

    /**
     * [getBroadcast 获取广播]
     * @return [type] [description]
     */
    public function getBroadcast()
    {
    	if(IS_POST){
    		//获取ajax数据
    		$gbdata = I('post.gbdata');

    		//获取飞屏数据
    		$Broadcast = M('Broadcast');
	    	$where['status'] = array('eq',1);
	    	$data = $Broadcast->where($where)->find();
            if(empty($data) || empty($gbdata) || $data['create_time'] == $gbdata){
    			//不变则不返回数据
    			$returnData['status'] = 0;
    			$returnData['info'] = '没有新广播数据！';
    		} else {
    			//有更新则获取新数据
    			$returnData['status'] = 1;
    			$returnData['info'] = '有新广播数据！';
    		}

    		$returnData['gbrdata'] = $data;
    		$this->ajaxReturn($returnData);

    	}
    }

    /**
     * [newyear 新年视频]
     * @return [type] [description]
     */
    public function newyear()
    {
        $this->display();
    }
}