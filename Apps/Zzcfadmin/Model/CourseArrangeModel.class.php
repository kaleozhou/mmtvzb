<?php
/*
* Author: [ Copy Lian ]
* Date: [ 2015.05.08 ]
* Description [ 管理员 ]
*/
namespace Zzcfadmin\Model;
use Think\Model\RelationModel;

class CourseArrangeModel extends RelationModel
{
	protected $_validate = array(
	    array('ctime','require','{%_COURSE_TIME_MUST_}'),
    );
}
?>