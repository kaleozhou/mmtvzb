<?php
/*
* Author: [ Copy Lian ]
* Date: [ 2015.05.08 ]
* Description [ 礼物订单 ]
*/
namespace Zzcfadmin\Model;
use Think\Model\RelationModel;

class GiftsorderModel extends RelationModel
{
	protected $_link = array(
		//礼物
    	'Gifts' => array(
    		'mapping_type' => self::BELONGS_TO,
    		'class_name' => 'Gifts',
    		'foreign_key'   => 'giftsid',
		    'mapping_name'  => 'gifts'
    	),
    	//老师
    	'Teacher' => array(
    		'mapping_type' => self::BELONGS_TO,
    		'class_name' => 'Teacher',
    		'foreign_key'   => 'touid',
		    'mapping_name'  => 'teacher'
    	),
    	//会员
    	'Member' => array(
    		'mapping_type' => self::BELONGS_TO,
    		'class_name' => 'Member',
    		'foreign_key'   => 'fromid',
		    'mapping_name'  => 'member'
    	),
    );
}
?>