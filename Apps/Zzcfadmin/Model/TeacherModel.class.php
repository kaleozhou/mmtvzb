<?php
/*
* Author: [ Copy Lian ]
* Date: [ 2015.05.08 ]
* Description [ 老师 ]
*/
namespace Zzcfadmin\Model;
use Think\Model\RelationModel;

class TeacherModel extends RelationModel
{
	protected $_validate = array(
	    array('name','require','{%_NAME_MUST_}'),
	    array('name','','{%_NAME_EXISTS_}',0,'unique',3),
	    array('money','require','{%_MONEY_MUST_}'),
    );

    protected $_link = array(
    	'Adminuser' => array(
    		'mapping_type' => self::BELONGS_TO,
    		'class_name' => 'Adminuser',
    		'foreign_key'   => 'jsuid',
		    'mapping_name'  => 'adminuser'
    	)
    );
}
?>