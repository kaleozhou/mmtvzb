<?php
/*
* Author: [  Copy Lian ]
* Date: [ 2015.05.08 ]
* Description [ 用户行为 ]
*/
namespace Zzcfadmin\Model;
use Think\Model\RelationModel;

class ActionlogModel extends RelationModel
{
	protected $_link = array(
		//关联后台管理员
		'Adminuser' => array(
			'mapping_type' => self::BELONGS_TO,
			'class_name' => 'Adminuser',
			'mapping_name' => 'adminuser',
			'foreign_key' => 'user_id'
		),
		//管理member
		'Member' => array(
			'mapping_type' => self::BELONGS_TO,
			'class_name' => 'Member',
			'mapping_name' => 'member',
			'foreign_key' => 'user_id'
		),
		//行为
		'Action' => array(
			'mapping_type' => self::BELONGS_TO,
			'class_name' => 'Action',
			'mapping_name' => 'action',
			'foreign_key' => 'action_id',
			'mapping_fields' => 'id,title,name'
		),
	);
}
?>