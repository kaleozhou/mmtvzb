<?php
/*
* Author: [ Copy Lian ]
* Date: [ 2015.05.08 ]
* Description [ Ö÷²¥·ÖÀà ]
*/
namespace Zzcfadmin\Model;
use Think\Model\RelationModel;

class ReviewcateModel extends RelationModel
{
	protected $_validate = array(
	    array('name','require','{%_NAME_MUST_}'),
	    //array('name','','{$_NAME_EXISTS_}',0,'unique',3)
    );

    protected $_link = array(
		'WonderfulReview' => array(
			'mapping_type' => self::HAS_MANY,
			'mapping_class' => 'WonderfulReview',
			'foreign_key' => 'catid',
			'mapping_name' => 'wonderfulreview'
		)
	);
}
?>