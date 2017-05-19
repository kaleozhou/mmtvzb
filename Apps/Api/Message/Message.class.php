<?php
/*
* Author: [ Copy Lian ]
* Date: [ 2015.05.08 ]
* Description [ 短信接口类 ]
*/
namespace Api\Message;
class Message
{
	private $target;
	private $codelength = 6; //验证码长度
	private $mobile; //手机号
	private $password; //密码
	private $account; //账号
	private $content; //模板内容：您的验证码是：{$code}。请不要把验证码泄露给其他人。
	private $code; //验证码
	private $type; //随机字符串类型

	/**
	 * [__construct 初始化]
	 */
	public function __construct($mobile = '',$config = array()){
		//获取当前手机
		$this->mobile = $mobile;

		//账号
		$this->account = $config['MESSAGE_ACCOUT'];

		//密码
		$this->password = $config['MESSAGE_PASSWORD'];

		//请求地址
		$this->target = $config['MESSAGE_TARGET'];

		//验证码长度
		if(is_numeric($config['MESSAGE_CODELENTH']) && $config['MESSAGE_CODELENTH'] > 0){
			$this->codelength = $config['MESSAGE_CODELENTH'];
		}

		//类型
		$this->type = $config['MESSAGE_TYPE'];

		//模板
		$this->content = $config['MESSAGE_CONTENT'];

		//产生随机验证码
		$this->code = $this->random($this->codelength,$this->type);

		//解析短信模板
		$this->content = str_replace('{$code}', $this->code, $this->content);
	}

	/**
	 * [send 发送]
	 * @return [type] [description]
	 */
	public function send()
	{

		$post_data = array();
		$post_data['account'] = $this->account;
		$post_data['pswd'] = $this->password;
		$post_data['mobile'] = $this->mobile;
		$post_data['msg'] = $this->content;

		$post_data_srt = '';
		foreach ($post_data as $k=>$v)
		{
		    $post_data_srt .= "$k=".urlencode($v).'&';
		}
		$tydata = substr($post_data_srt,0,-1);
		$result = $this->Post($this->target,$tydata);
		//返回数据
		//第一行：resptime,respstatus，respstatus为0时表示成功
		//第二行：msgid
		$returnData = explode("\n", $result);
		$resdata = explode(",", $returnData[0]);
		$msgid = $returnData[1];
		if($resdata[1] == 0){
			//成功
			$data['status'] = 1;
			$data['info'] = '发送成功！';
			$data['msgid'] = $msgid;
		} else {
			//失败
			$data['status'] = 0;
			$data['info'] = '发送失败！';
		}

		$data['code'] = $resdata[1];
		$data['mobile'] = $this->mobile;
		$data['content'] = $this->content;
		$data['mobile_code'] = $this->code;
		return $data;
	}

	/**
	 * [Post 发送数据]
	 * @param [type] $url  [description]
	 * @param [type] $data [description]
	 */
	private function Post($url,$data){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果需要将结果直接返回到变量里，那加上这句。
		$response = curl_exec($ch);
		return $response;
	}

	/**
	 * [random 随机函数]
	 * @param  integer $length  [验证码长度]
	 * @param  integer $numeric [数字|字符串]
	 * @return [string]         [返回hash]
	 */
	private function random($length = 6 , $numeric = 0) {
		if($numeric) {
			$hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
		} else {
			$hash = '';
			$chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789abcdefghjkmnpqrstuvwxyz';
			$max = strlen($chars) - 1;
			for($i = 0; $i < $length; $i++) {
				$hash .= $chars[mt_rand(0, $max)];
			}
		}
		return $hash;
	}
}
?>