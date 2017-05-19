<?php
/*
* Author: [ Copy Lian ]
* Date: [ 2015.05.08 ]
* Description [ 会员分组 ]
*/
namespace Zzcfadmin\Model;
use Think\Model\RelationModel;

class MembergroupModel extends RelationModel
{
	protected $_validate = array(
	    array('name','require','{%_NAME_MUST_}'),
	    array('name','','{%_NAME_EXISTS_}',0,'unique',3),
	    array('ltscore','number',"{%_JIFEN_MUST_NUMBER_}")
    );

    protected $_link = array(
		'Member' => array(
			'mapping_type' => self::HAS_MANY,
			'mapping_class' => 'Member',
			'foreign_key' => 'gid',
			'mapping_name' => 'members',
			'mapping_fields' => 'id,username'
		)
	);
}
?>