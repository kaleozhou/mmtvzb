<?php
/*
* Author : [ Copy Lian ]
* Date : [ 2015.05.13 ]
* Description : [ 用户注册模型 ]
*/
namespace Zhibo\Model;
use Think\Model\RelationModel;

class MemberModel extends RelationModel
{
	//验证用户注册数据
	protected $_validate = array(
	 	array('username','require','用户名必须填写！'),
	 	array('username','','用户名已经存在！',1,'unique',3),
	 	array('password','require','密码必须填写！'),
	 	array('password','6,20','密码长度必须是6到20位！',0,'length',3),
	 	array('mobile','require','手机必须填写！'),
	 	array('mobile','11','手机长度必须是11位！',0,'length',3),
	 	array('mobile','','手机已经存在！',0,'unique',3)
	);

	protected $_link = array(
		'Membergroup' => array(
			'mapping_type' => self::BELONGS_TO,
			'mapping_class' => 'Membergroup',
			'foreign_key' => 'gid',
			'mapping_name' => 'membergroup',
			'mapping_fields' => 'id,name,icon'
		)
	);
}
?>