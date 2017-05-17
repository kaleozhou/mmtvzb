<?php
/*
* Author: [ Copy Lian ]
* Date: [ 2017.02.21 ]
* Description [ 路由配置文件 ]
*/

return array(
	//开启全局路由
	'URL_ROUTER_ON' => true,

	//全局路由规则：全局路由是针对所有分组项目
	'URL_ROUTE_RULES' => array(

		//单页
		'/^about\/(\d+)$/' => 'Home/About/index?cid=:1',

		//内容
		'/^arc\/(\d+)$/' => 'Home/Articles/index?cid=:1',
		'/^arc\/(\d+)\_(\d+)$/' => 'Home/Articles/index?cid=:1&p=:2',
		'/^arc\/(\d+)\/(\d+)$/' => 'Home/Articles/details?cid=:1&id=:2',

		//专题
		'/^sp\/teacherli$/' => 'Special/Teacherli/index',
		'/^sp\/teacherwu$/' => 'Special/Teacherwu/index',
		'/^sp\/teacherwang$/' => 'Special/Teacherwang/index',
		'/^sp\/teacherjiang$/' => 'Special/Teacherjiang/index',
		'/^sp\/openuser$/' => 'Special/Openuser/index',
		'/^sp\/queryplartform$/' => 'Special/Queryplartform/index',
		'/^vrecord$/' => 'Special/Videorecord/index',
		'/^vrecord\_v(\d+)$/' => 'Special/Videorecord/index?id=:1',
		'/^vreview$/' => 'Special/Videoreview/index',
		'/^vreview\_v(\d+)$/' => 'Special/Videoreview/details?id=:1',
		'/^vreview\_v(\d+)\_(\d+)$/' => 'Special/Videoreview/details?id=:1&p=:2',
		'/^sp\/teacherzhou$/' => 'Special/Teacherzhou/index',
		'/^sp\/teachershen$/' => 'Special/Teachershen/index',
		'/^sp\/host$/' => 'Special/Host/index',
		'/^sp\/host1$/' => 'Special/Host/host1',
		'/^sp\/host2$/' => 'Special/Host/host2',
		'/^sp\/host3$/' => 'Special/Host/host3',
		'/^sp\/host4$/' => 'Special/Host/host4',
		'/^sp\/host5$/' => 'Special/Host/host5',
		'/^sp\/host6$/' => 'Special/Host/host6',
		'/^sp\/host7$/' => 'Special/Host/host7',
		'/^sp\/host8$/' => 'Special/Host/host8',
	),
);
?>