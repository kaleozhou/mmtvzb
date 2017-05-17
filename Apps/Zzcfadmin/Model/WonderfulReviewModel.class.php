<?php
/**
* Author : [ Copy Lian ]
* Date : [ 2015.05.13 ]
* Description : [ 精彩回顾模型 ]
*/
namespace Zzcfadmin\Model;
use Think\Model\RelationModel;

class WonderfulReviewModel extends RelationModel
{

	//自动验证
	protected $_validate = array(
	    array('title','require','{%_TITLE_MUST_}'),
	    array('title','','{%_TITLE_EXISTS_}',0,'unique',3),
	    array('url','require','视频地址必须填写！')
    );
}
?>