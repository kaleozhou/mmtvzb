<?php
/*
* Author: [ Copy Lian ]
* Date: [ 2015.05.08 ]
* Description [ 精彩回顾模型 ]
*/
namespace Special\Model;
use Think\Model\RelationModel;

class WonderfulReviewModel extends RelationModel
{
	protected $_validate = array(
	    array('name','require','{%_NAME_MUST_}'),
	    //array('name','','{$_NAME_EXISTS_}',0,'unique',3)
    );

    protected $_link = array(
		'Reviewcate' => array(
			'mapping_type' => self::BELONGS_TO,
			'mapping_class' => 'Reviewcate',
			'foreign_key' => 'catid',
			'mapping_name' => 'reviewcate'
		)
	);
}
?>