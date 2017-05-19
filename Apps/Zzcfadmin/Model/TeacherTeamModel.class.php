<?php
/*
* Author: [ Copy Lian ]
* Date: [ 2015.05.08 ]
* Description [ 管理员 ]
*/
namespace Zzcfadmin\Model;
use Think\Model\RelationModel;

class TeacherTeamModel extends RelationModel
{
	protected $_validate = array(
	    array('name','require','{%_TEACHER_NAME_MUST_}'),
	    array('motto','require','{%_TEACHER_MOTTO_MUST_}'),
	    array('profit','require','{%_TEACHER_PROFIT_MUST_}'),
	    array('profit','number','{%_TEACHER_PROFIT_MUST_NUM_}',2,'regex',3),
	    array('team_name','require','{%_TEAM_NAME_MUST_}'),
	    array('jump_url','require','{%_TEACHER_JUMPURL_MUST_}'),
    );
}
?>