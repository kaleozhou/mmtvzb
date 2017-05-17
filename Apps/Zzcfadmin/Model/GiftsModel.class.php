<?php
/*
* Author: [ Copy Lian ]
* Date: [ 2015.05.08 ]
* Description [ 礼物 ]
*/
namespace Zzcfadmin\Model;
use Think\Model\RelationModel;

class GiftsModel extends RelationModel
{
	protected $_validate = array(
	    array('name','require','{%_NAME_MUST_}'),
	    array('money','require','{%_MONEY_MUST_}'),
    );
}
?>