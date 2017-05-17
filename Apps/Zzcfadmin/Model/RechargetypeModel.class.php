<?php
/*
* Author: [ Copy Lian ]
* Date: [ 2015.05.08 ]
* Description [ 充值商品 ]
*/
namespace Zzcfadmin\Model;
use Think\Model\RelationModel;

class RechargetypeModel extends RelationModel
{
	protected $_validate = array(
	    array('money','require','{%_CZMONEY_MUST_}'),
	    array('money','number','{%_CZMONEY_MUST_NUMBER_}'),
	    array('dhmoney','require','{%_DHMONEY_MUST_}'),
	    array('dhmoney','number','{%_DHMONEY_MUST_NUMBER_}'),
    );
}
?>