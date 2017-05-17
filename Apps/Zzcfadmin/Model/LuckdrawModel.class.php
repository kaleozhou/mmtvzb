<?php
/**
* Author : [ Copy Lian ]
* Date : [ 2015.05.13 ]
* Description : [ 喊单模型 ]
*/
namespace Zzcfadmin\Model;
use Think\Model\RelationModel;

class LuckdrawModel extends RelationModel
{

    protected $_link = array(
		//获取奖品
		'Luckproducts' => array(
			'mapping_type' => self::BELONGS_TO,
			'class_name' => 'Luckproducts',
			'foreign_key' => 'lid',
			'mapping_name' => 'luckproducts'
		)
	);
}
?>