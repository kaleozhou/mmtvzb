<?php
/**
* Author : [ Copy Lian ]
* Date : [ 2015.05.13 ]
* Description : [ 奖品模型 ]
*/
namespace Zzcfadmin\Model;
use Think\Model\RelationModel;

class LuckproductsModel extends RelationModel
{

	//自动验证
	protected $_validate = array(
	    array('name','require','{%_NAME_MUST_}'),
	    array('name','','{%_NAME_EXISTS_}',0,'unique',3),
    );
}
?>