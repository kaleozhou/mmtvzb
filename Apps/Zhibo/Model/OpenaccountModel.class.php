<?php
/*
* Author : [ Copy Lian ]
* Date : [ 2015.05.13 ]
* Description : [ 在线预约 ]
*/
namespace Zhibo\Model;
use Think\Model;

class OpenaccountModel extends Model
{
	//自动验证
	protected $_validate = array(
	 	array('name','require','{%_NAME_MUST_}'),
	 	array('tel','require','{%_TEL_MUST_}'),
	 	array('email','require','{%_EMAIL_MUST_}'),
	 	array('email','email','{%_EMAIL_ERROR_}'),
	 	array('qq','require','{%_QQ_MUST_}'),
	);
}
?>