<?php
/*
* Author: [ Copy Lian ]
* Date: [ 2015.05.13 ]
* Description [ 会员中心 ]
*/
namespace Zhibo\Controller;
class MemberController extends CommonController {
    /**
     * [_initialize 初始化]
     * @return [type] [description]
     */
    public function _initialize()
    {
        parent::_initialize();
        if(!session('?memberid')){
            redirect(U("@".$this->myurl));
        }
        //验证数据
        $id = session("memberid");
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
    }
    /**
     * 首页
    */
    public function index(){
    	$this->display();
    }

    /**
     * [welcome 欢迎页]
     * @return [type] [description]
     */
    public function welcome()
    {
        $this->display();
    }

    /**
     * [userinfo 用户中心]
     * @return [type] [description]
     */
    public function userinfo()
    {
        //提交数据处理
        if(IS_POST){
            $Member = M('Member');
            $rules_1 = array(
                array('email','email','邮件格式不正确！',2,'regex',3), //邮件
            );
            if($data = $Member->validate($rules_1)->create()){

                //key重新索引解决冲突
                $Member->thumb = array_values($Member->thumb); 
                $Member->thumb = serialize($Member->thumb);

                //密码不填时不进行验证
                if(!empty($data['password'])){
                    $rules = array(
                        array('password','require','密码必须填写！',0,'regex',2), //密码插入时验证
                        array('password','6,20','密码必须是6到20位字符！',0,'length',2),//密码插入时验证
                        array('ckpassword','password','两次密码不一致！',0,'confirm',2), //密码插入时验证
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
                    $this->success('修改成功');
                } else {
                    $this->error('什么都没改！');
                }
            } else {
                $this->error($Member->getError());
            }
        } else {
            $this->display();
        }
    }

    /**
     * [order 充值订单]
     * @return [type] [description]
     */
    public function order()
    {
        $where['uid'] = array('eq',session('memberid'));
        $Order = M('Order');
        $where['isdel'] = array('eq',0);
        $count = $Order->where($where)->count();

        //获取分页
        $page = getPage($count,C('BASE_PAGENUM'),'','',"/",1,"<span aria-hidden='true'>&laquo;</span>","<span aria-hidden='true'>&raquo;</span>",'');
        $this->pagelist = $page->show();
        $data = $Order->where($where)->order('create_time DESC')->limit($page->firstRow,$page->listRows)->select();
        $this->data = $data;

        $this->display();
    }

    /**
     * [del 删除充值订单]
     */
    public function delorder()
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
     * [gifts 礼物订单]
     * @return [type] [description]
     */
    public function gifts()
    {
        $Giftsorder = D('Giftsorder');
        $where['fromid'] = array('eq',session('memberid'));
        $count = $Giftsorder->field('id')->where($where)->count();
        //获取分页
        $page = getPage($count,C('BASE_PAGENUM'),'','',"/",1,"<span aria-hidden='true'>&laquo;</span>","<span aria-hidden='true'>&raquo;</span>",'');
        $this->pagelist = $page->show();
        $data = $Giftsorder->where($where)->relation(array('gifts','teacher'))->order('create_time DESC,id DESC')->limit($page->firstRow,$page->listRows)->select();
        if(!empty($data)){
            foreach ($data as $key => $value) {
                $data[$key]['area'] = unserialize($value['area']);
                $data[$key]['gifts']['thumb'] = unserialize($value['gifts']['thumb']);
            }
        }
        $this->data = $data;
        $this->display('giftsorder');
    }

    /**
     * [delgifts 删除送礼物订单]
     */
    public function delgifts()
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
            $Giftsorder = D('Giftsorder');
            foreach ($ids as $key => $value) {
                $data = $Giftsorder->field('id')->find($value);
                if(!$data){
                    $this->error(L('_NODATA_'));
                    break;
                }
            }

            //删除数据
            if($Giftsorder->delete($id)){

                $this->success(L('_DEL_SUCCESS_'));
            } else {
                $this->error(L('_DEL_ERROR_'));
            }
        } else {
            $this->error(L('_ACCESS_ERROR_'));
        }
    }
}