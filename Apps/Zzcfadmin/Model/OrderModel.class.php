<?php
/**
* Author : [ Copy Lian ]
* Date : [ 2015.05.13 ]
* Description : [ 订单 ]
*/
namespace Zzcfadmin\Model;
use Think\Model\RelationModel;

class OrderModel extends RelationModel
{
	protected function _initialize(){
		$this->_link = array(
			//Member
			"Member" => array(
				'mapping_type' => self::BELONGS_TO,
				'class_name' => "Member",
				'mapping_name' => 'member',
				'foreign_key' => 'uid'
			)
		);
	}
}
?>