<?php
/*
* Author: [ Copy Lian ]
* Date: [ 2015.05.08 ]
* Description [ 管理员 ]
*/
namespace Zhibo\Model;
use Think\Model\RelationModel;

class AdminuserModel extends RelationModel
{
    protected $_link = array(
    	'AuthGroupAccess' => array(
    		'mapping_type' => self::HAS_MANY,
    		'class_name' => 'AuthGroupAccess',
    		'foreign_key'   => 'uid',
		    'mapping_name'  => 'authgroupaccess',
		    'mapping_fields'  => 'group_id'
    	),

    	'AuthGroup' => array(
		    'mapping_type'      =>  self::MANY_TO_MANY,
		    'class_name'        =>  'AuthGroup',
		    'mapping_name'      =>  'authgroup',
		    'foreign_key'       =>  'uid',
		    'relation_foreign_key'  =>  'group_id',
		    'relation_table'    =>  '__AUTH_GROUP_ACCESS__' //此处应显式定义中间表名称，且不能使用C函数读取表前缀
		)
    );
}
?>