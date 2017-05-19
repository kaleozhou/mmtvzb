<?php
/**
* Author : [ Copy Lian ]
* Date : [ 2015.05.13 ]
* Description : [ Members ]
*/
namespace Zzcfadmin\Model;
use Think\Model\RelationModel;

class MemberModel extends RelationModel
{

	//自动验证
	protected $_validate = array(
	    array('username','require','{%_USERNAME_MUST_}'),
	    array('username','2,20','{%_UERNAMELENGTH_ERROR_}',0,'length',3),
	    array('username','','{%_USERNAME_EXISTS_}',0,'unique',3),
	    array('password','require','{%_PASSWORD_MUST_}',0,'regex',1), //密码插入时验证
	    array('password','6,20','{%_PASSWORDLENTH_ERROR_}',0,'length',1),//密码插入时验证
	    array('ckpassword','password','{%_CHECKPASSWORD_ERROR_}',0,'confirm',1), //密码插入时验证
	    array('email','email','{%_EMAIL_ERROR_}',2,'regex',3), //邮件
	    array('mobile','11','{%_MOBILE_LENGTH_}',2,'length',3), //手机
	    array('mobile','','{%_MOBILE_UNIQUE_}',2,'unique',3) //手机验证唯一性
    );

    protected $_link = array(
		'Membergroup' => array(
			'mapping_type' => self::BELONGS_TO,
			'mapping_class' => 'Membergroup',
			'foreign_key' => 'gid',
			'mapping_name' => 'membergroup',
		)
	);
}
?>