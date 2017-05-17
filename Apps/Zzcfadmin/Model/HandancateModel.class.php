<?php
/**
* Author : [ Copy Lian ]
* Date : [ 2015.05.13 ]
* Description : [ 喊单分类模型 ]
*/
namespace Zzcfadmin\Model;
use Think\Model\RelationModel;

class HandancateModel extends RelationModel
{

	//自动验证
	protected $_validate = array(
	    array('name','require','{%_NAME_MUST_}'),
	    array('name','','{%_NAME_EXISTS_}',0,'unique',3),
    );

    protected $_link = array(
		//获取父类
		'Catepid' => array(
			'mapping_type' => self::BELONGS_TO,
			'class_name' => 'Handancate as parent',
			'foreign_key' => 'pid',
			'mapping_name' => 'catepid',
			'mapping_fields' => 'id,name',
			'as_fields' => 'name:pidname'
		),

		//获取子类
		'Cateson' => array(
			'mapping_type' => self::HAS_MANY,
			'class_name' => 'Handancate as son',
			'foreign_key' => 'pid',
			'mapping_name' => 'cateson',
			'mapping_fields' => 'id,name'
		),
	);
}
?>