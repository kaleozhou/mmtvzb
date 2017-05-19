<?php
/**
* Author : [ Copy Lian ]
* Date : [ 2015.05.13 ]
* Description : [ 喊单模型 ]
*/
namespace Zzcfadmin\Model;
use Think\Model\RelationModel;

class HandanModel extends RelationModel
{

	//自动验证
	protected $_validate = array(
		array('pt_cid','require','{%_HANDAN_PT_MUST_}'),
		array('pz_cid','require','{%_HANDAN_PZ_MUST_}'),
	    array('jnum','require','{%_HANDAN_JNUM_MUST_}'),
	    array('jnum','number','{%_HANDAN_JNUM_MUSTNUM_}',2,'regex',3),
	    array('zsnum','require','{%_HANDAN_ZSNUM_MUST_}'),
	    array('zsnum','number','{%_HANDAN_ZSNUM_MUSTNUM_}',2,'regex',3),
	    array('zynum','require','{%_HANDAN_ZYNUM_MUST_}'),
	    array('zynum','number','{%_HANDAN_ZYNUM_MUSTNUM_}',2,'regex',3),
	    array('pnum','require','{%_HANDAN_PNUM_MUST_}'),
	    array('pnum','number','{%_HANDAN_PNUM_MUSTNUM_}',2,'regex',3),
    );

    protected $_link = array(
		//获取平台
		'Catept' => array(
			'mapping_type' => self::BELONGS_TO,
			'class_name' => 'Handancate as pt',
			'foreign_key' => 'pt_cid',
			'mapping_name' => 'catept'
		),

		//获取品种
		'Catepz' => array(
			'mapping_type' => self::BELONGS_TO,
			'class_name' => 'Handancate as pz',
			'foreign_key' => 'pz_cid',
			'mapping_name' => 'catepz'
		),

		//获取用户
		'Adminuser' => array(
			'mapping_type' => self::BELONGS_TO,
			'class_name' => 'Adminuser',
			'foreign_key' => 'uid',
			'mapping_name' => 'adminuser'
		),
	);
}
?>