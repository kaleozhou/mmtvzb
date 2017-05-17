<?php
/*
* Author: [ Copy Lian ]
* Date: [ 2015.05.08 ]
* Description [ 今日数据模型 ]
*/
namespace Zzcfadmin\Model;
use Think\Model\RelationModel;

class TodaydataModel extends RelationModel
{
	protected $_validate = array(
	    array('title','require','{%_TITLE_MUST_}'),
	    array('title','','{%_TITLE_EXISTS_}',0,'unique',3),
    );
}
?>