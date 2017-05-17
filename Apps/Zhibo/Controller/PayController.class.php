<?php
/*
* Author: [ Copy Lian ]
* Date: [ 2015.05.13 ]
* Description [ 支付接口 ]
*/
namespace Zhibo\Controller;
class PayController extends CommonController {

    /**
     * [pay description]
     * @return [type] [description]
     */
    public function pay()
    {
        if(IS_POST){
            $postData = I('post.');

            //充值商品id
            $proid = $postData['rechargeid'];

            //查询商品
            $Rechargetype = M('Rechargetype');
            $Rechargetype_where['status'] = array('eq',1);
            if(!$RechargeData = $Rechargetype->where($Rechargetype_where)->find($proid)){
                $this->error('充值的商品不存在！');
            }

            //支付类型
            $type = $postData['paytype'];
            if(empty($type)){
                $this->error('没有配置支付接口！');
            }

            //订单号
            $sn = $postData['sn'];

            //商品单价
            $price = $postData['price'];;

            //商品数量
            $quantity = $postData['quantity'];

            //支付金额总金额
            $money = $price * $quantity;

            //兑换的总钻石
            $dhmoney = $postData['dhmoney'] * $quantity;

            $Member = M('Member');
            if(!$userdata = $Member->field('id,username')->find(session('memberid'))){
                $this->error('请先登录账号！');
            }

            //订单名称
            $name ="购买单价". $price ."元，价值". $RechargeData['dhmoney'] ."钻石的商品共". $quantity ."份，总共支付金额为". $money ."元";

            //说明
            $description = "会员：". $userdata['username'] ."，购买了单价". $price ."元，价值". $RechargeData['dhmoney'] ."钻石的商品共". $quantity ."份，总共支付金额为". $money ."元";;

        /* 在进行支付行为之前，先生成订单数据 */
            //购买用户地区信息
            $ip = new \Org\Net\IpLocation('UTFWry.dat');
            $orderip = get_client_ip();
            $area = $ip->getlocation($orderip);
            $area = serialize($area);

            $order = M('Order');
            $order_where['sn'] = array('eq',$sn);
            $order_where['uid'] = array('eq',session('memberid'));
            if(!$userorder = $order->where($order_where)->find()){
                $order_data = array(
                    'uniqid'       => aikehou_uniqid(),
                    'paytype'      => $type,
                    'uid'          => session('memberid'),
                    'proid'        => $proid, 
                    'sn'           => $sn, //订单号，必须保证订单编号是唯一性的
                    'name'         => $name, //订单名称
                    'price'        => $price, //商品单价
                    'quantity'     => $quantity, //商品数量
                    'money'        => $money, //商品总金额
                    'dhmoney'      => $dhmoney, //商品兑换的总钻石
                    'description'  => $description, //商品说明
                    'ip'           => $orderip, //购买用户ip信息
                    'area'         => $area, //购买用户ip信息
                    'create_time'  => time(), //订单创建时间
                );
                if($insertid = $order->data($order_data)->add()){
                    //设置排序
                    $order->where("id = {$insertid}")->setField('sort',$insertid);
                } 
            }
        /* end 在进行支付行为之前，先生成订单数据 */    

            //选择支付接口
            if(strtolower($type) == 'alipay'){
                //加载alipay配置文件
                $alipay_config = $this->getAlipayConfig(strtolower($type));

                //加载AlipaySubmit类
                import('Api.Pay.Alipay.create.lib.alipay_submit',APP_PATH,'.class.php');

                //构造要请求的参数数组，无需改动
                $parameter = array(
                    "service"            => $alipay_config['service'],
                    "partner"            => $alipay_config['partner'],
                    "seller_id"          => $alipay_config['seller_id'],
                    "payment_type"       => $alipay_config['payment_type'],
                    "notify_url"         => $alipay_config['notify_url'],
                    "return_url"         => $alipay_config['return_url'],
                    
                    "anti_phishing_key"  => $alipay_config['anti_phishing_key'],
                    "exter_invoke_ip"    => $alipay_config['exter_invoke_ip'],
                    "out_trade_no"       => $sn, //订单号
                    "subject"            => $name, //订单名称
                    "total_fee"          => $money, //付款金额
                    "price"              => $price, //商品单价
                    "quantity"           => $quantity, //商品数量
                    "body"               => $description, //说明
                    "show_url"           => U("index@$this->myurl",array('type'=>'buy','proid'=>$proid),false,true), //商品展示地址
                    "_input_charset"     => trim(strtolower($alipay_config['input_charset']))
                    //其他业务参数根据在线开发文档，添加参数
                    //如"参数名"=>"参数值" 
                );

                //建立请求
                $alipaySubmit = new \AlipaySubmit($alipay_config);
                $html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
                echo $html_text;

            } elseif(strtolower($type) == 'wxpay') {

            }
        } else {
            $this->error(L('_ACCESS_ERROR_'));
        }
    }

/* 支付宝 */
    /**
     * [getAlipayConfig 获取参数]
     * @param  [type] $type [类型]
     * @return [type]       [description]
     */
    private function getAlipayConfig($type)
    {
        //支付配置
        $payconfig = C('PAY_INTERFACE_'.strtoupper($type));

        //$alipay_config = require_once(APP_PATH . 'Api/Pay/Alipay/create/alipay.config.php');
        $alipay_config = array(
            'partner' => $payconfig['PARTERID'], //合作身份者ID
            'seller_id' => $payconfig['ACCOUT'], //收款支付宝账号
            'key'    =>    $payconfig['KEY'], //MD5密钥
            'notify_url' => U("notify_url@$this->myurl",'',false,true), //服务器异步通知页面路径
            'return_url' => U("return_url@$this->myurl",'',false,true), //页面跳转同步通知页面路径
            'sign_type' => strtoupper('MD5'), //签名方式
            'input_charset' => strtolower('utf-8'), //字符编码格式
            'cacert' => getcwd() . '/Apps/Api/Pay/Alipay/create/cacert.pem', //ca证书路径地址，用于curl中ssl校验
            'transport' => 'http', //访问模式
            'payment_type' => '1', //服务器异步通知页面路径
            'service' => 'create_direct_pay_by_user', //产品类型
            'anti_phishing_key' => '', //防钓鱼时间戳
            'exter_invoke_ip' => get_client_ip(), //客户端的IP地址
        );
        return $alipay_config;
    }

    /**
     * [notify_url 服务器异步通知页面路径]
     * @return [type]        [description]
     */
    public function notify_url()
    {
        if(IS_POST){

            //加载alipay配置文件
            $alipay_config = $this->getAlipayConfig('alipay');

            //加载AlipaySubmit类
            import('Api.Pay.Alipay.create.lib.alipay_notify',APP_PATH,'.class.php');

            //计算得出通知验证结果
            $alipayNotify = new \AlipayNotify($alipay_config);
            $verify_result = $alipayNotify->verifyNotify();

            //验证成功
            if($verify_result) {

                //商户订单号
                //$out_trade_no = $_POST['out_trade_no'];

                //支付宝交易号
                //$trade_no = $_POST['trade_no'];

                //交易状态
                //$trade_status = $_POST['trade_status'];

                //验证成功
                //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
                $alipay_return = I('post.');

                if($alipay_return['trade_status'] == 'TRADE_FINISHED') {
                    //
                }  else if ($alipay_return['trade_status'] == 'TRADE_SUCCESS') {
                    
                    if(!$this->checkorderstatus($alipay_return['out_trade_no'])){
                        $this->orderhandle($alipay_return); //进行订单处理，并传送从支付宝返回的参数；
                    }
                }

                echo "success";     //请不要修改或删除
                
            } else {
                //验证失败
                echo "fail";

                //调试用，写文本函数记录程序运行情况是否正常
                //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
            }
        }
    }

    /**
     * [return_url 页面跳转同步通知页面路径]
     * @return [type]        [description]
     */
    public function return_url()
    {

        //加载alipay配置文件
        $alipay_config = $this->getAlipayConfig('alipay');

        //加载AlipaySubmit类
        import('Api.Pay.Alipay.create.lib.alipay_notify',APP_PATH,'.class.php');

        //计算得出通知验证结果
        $alipayNotify = new \AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyReturn();

        //验证成功
        if($verify_result) {
            //验证成功
            //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
            $alipay_return = I('get.');
            
            if($alipay_return['trade_status'] == 'TRADE_FINISHED' || $alipay_return['trade_status'] == 'TRADE_SUCCESS') {
                
                if(!$this->checkorderstatus($alipay_return['out_trade_no'])){
                    $this->orderhandle($alipay_return); //进行订单处理，并传送从支付宝返回的参数；
                }
                redirect(U("index@$this->myurl",array('type'=>'finish','sn'=>$alipay_return['out_trade_no'])));
            } else {
                echo "trade_status=".$alipay_return['trade_status'];
                redirect(U("index@$this->myurl",array('type'=>'finish','sn'=>$alipay_return['out_trade_no'])));
            }

            echo "验证成功<br />";
        } else {
            //验证失败
            //如要调试，请看alipay_notify.php页面的verifyReturn函数
            echo "验证失败";
        }
    }

    //在线交易订单支付处理函数
    //函数功能：根据支付接口传回的数据判断该订单是否已经支付成功；
    //返回值：如果订单已经成功支付，返回true，否则返回false；
    private function checkorderstatus($ordid){
        $order = M('Order');
        $order_where['sn'] = array('eq',$ordid);
        $ordstatus = $order->where($order_where)->getField('status'); 
        if($ordstatus == 1){
            return true;
        }else{
            return false;
        }
    }

    //处理订单函数
    //更新订单状态，写入订单支付后返回的数据
    private function orderhandle($parameter){
        $data['returninfo'] = serialize($parameter);
        $data['status']     = 1;
        $data['payoktime']  = time();

        $model = M();
        $model->startTrans();
        $order_where['sn'] = array('eq',$parameter['out_trade_no']);
        if($order_info = $model->table('__ORDER__')->where($order_where)->find()){
            
            //更新订单状态信息
            $ordersave = $model->table('__ORDER__')->where($order_where)->save($data);

            //更新用户账户钻石
            $member_where['id'] = array('eq',session('memberid'));
            $membersave = $model->table('__MEMBER__')->where($member_where)->setInc('money',$order_info['dhmoney']);
            if($ordersave && $membersave){
                $model->commit();
                //记录日志
                action_log(2,session('memberid'),'member_recharge','Member',session('memberid'),C('BASE_LOG'),C("SITEID"));
            } else {
                $model->rollback();
            }
        }
    } 
/* end 支付宝 */
}