<?php
/**
* Author : [ Copy Lian ]
* Date : [ 2015.05.13 ]
* Description : [ 飞屏模型 ]
*/
namespace Zzcfadmin\Model;
use Think\Model\RelationModel;

class FlyscreenModel extends RelationModel
{

	//自动验证
	protected $_validate = array(
	    array('content','require','{%_CONTENT_MUST_}'),
	    array('content','','{%_CONTENT_EXISTS_}',0,'unique',3),
    );
}
?>