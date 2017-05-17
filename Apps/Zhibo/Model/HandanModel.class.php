<?php
/**
* Author : [ Copy Lian ]
* Date : [ 2015.05.13 ]
* Description : [ 喊单模型 ]
*/
namespace Zhibo\Model;
use Think\Model\RelationModel;

class HandanModel extends RelationModel
{
    protected $_link = array(
		//获取平台
		'Catept' => array(
			'mapping_type' => self::BELONGS_TO,
			'class_name' => 'Handancate as pt',
			'foreign_key' => 'pt_cid',
			'mapping_name' => 'catept'
		),

		//获取品种
		'Catepz' => array(
			'mapping_type' => self::BELONGS_TO,
			'class_name' => 'Handancate as pz',
			'foreign_key' => 'pz_cid',
			'mapping_name' => 'catepz'
		),

		//获取用户
		'Adminuser' => array(
			'mapping_type' => self::BELONGS_TO,
			'class_name' => 'Adminuser',
			'foreign_key' => 'uid',
			'mapping_name' => 'adminuser'
		),
	);
}
?>