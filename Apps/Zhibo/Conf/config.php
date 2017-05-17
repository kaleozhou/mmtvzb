<?php
return array(
	//主题配置
	'DEFAULT_THEME' => 'default',

	//模板替换
	'TMPL_PARSE_STRING' => array(
		'__JS__' => __ROOT__ . '/Public/' . MODULE_NAME . '/js',
		'__CSS__' => __ROOT__ . '/Public/' . MODULE_NAME . '/css',
		'__IMG__' => __ROOT__ . '/Public/' .MODULE_NAME . '/images',
		'__JQUERY__' => __ROOT__ . '/Public/jqueryui',
		'__ZHIBO__' => __ROOT__ . '/Public/' .MODULE_NAME . '/zhibo',
	),

	//404跳转页面
	//'URL_404_REDIRECT' => __ROOT__ . '/Public/ErrorTpl/404.html',

	//异常页面的模板文件
	//'TMPL_EXCEPTION_FILE'   =>  './Public/ErrorTpl/think_exception.html',

	//设置url为兼容模式
	'URL_MODEL' => 2,

	//站点id
	'SITEID' => 0,

	//参数分隔符
	'URL_PATHINFO_DEPR'=>'/',

	//设置后台超级管理员id
    'SUPERADMIN' => array(1),

	//设置多语言
    'LANG_SWITCH_ON' => true,
    'LANG_AUTO_DETECT' => true, // 自动侦测语言 开启多语言功能后有效
    'LANG_LIST'        => 'zh-cn', // 允许切换的语言列表 用逗号分隔
);