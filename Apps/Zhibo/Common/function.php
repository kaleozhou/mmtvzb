<?php
/**
 * [getPage 分页方法]
 * @param  [int] $count   [总记录条数]
 * @param  [int] $pagenum [显示条数]
 * @return [object]       [分页对象]
 */
function getPage($count,$pagenum,$parameter = array(), $url = '',$routepage_depr = '',$is_bootstrap = 0,$prev = '上一页',$next = '下一页',$header = '<span class="rows">共 %TOTAL_ROW% 条记录</span>'){

	//判断分页数是否存在，如果不存在且不是数字的话就去配置的值，如果配置没有值就默认15
	if(!isset($pagenum) || !is_numeric($pagenum)){
		$page_c = C('BASE_PAGENUM');
		if(isset($page_c)){
			$pagenum = C('BASE_PAGENUM');
		} else {
			$pagenum = 15;
		}
	}

	//开始分页设置
	$page = new \Think\Page($count,$pagenum,$parameter,$url,$routepage_depr,$is_bootstrap);
	$page->setConfig('header',$header);
	$page->setConfig('prev',$prev);
	$page->setConfig('next',$next);
	$page->setConfig('first','1...');
	$page->setConfig('last','...%TOTAL_PAGE%');
	$page->setConfig('theme','%UP_PAGE% %FIRST% %LINK_PAGE% %END% %DOWN_PAGE% %HEADER%');
	$page->lastSuffix = false;
	return $page;
}

/**
 * [seo 获取seo数据]
 * @param  [type] $cate  [分类]
 * @param  [type] $danye [单页]
 * @return [type]        [返回seo数组]
 */
function seo($cate = array(),$danye = array())
{
    /*单页有则取单页的值否则取栏目的值*/
    $seoData = array();
    $seoData['title'] = !empty($danye['title']) ?  $danye['title'] :  $cate['title'];
    $seoData['keywords'] = !empty($danye['keywords']) ?  $danye['keywords'] :  $cate['keywords'];
    $seoData['description'] = !empty($danye['description']) ?  $danye['description'] :  $cate['description'];

    /*如果都没有的话取栏目名称*/
    $seoData['title'] = !empty($seoData['title']) ? $seoData['title'] : $cate['name'];
    $seoData['keywords'] = !empty($seoData['keywords']) ? $seoData['keywords'] : $cate['name'];
    $seoData['description'] = !empty($seoData['description']) ?  $seoData['description'] : $cate['name'];
    return $seoData;
}

/**
 * [getvideo 获取视频]
 * @return [type]        [description]
 */
function getvideo(){
	//类型：1-YY直播，2-乐视云直播，3-奥点云直播，4-GenSee直播	
	$type = C('VIDEO_TYPE');
	$roomurl = C('VIDEO_ROOMURL');
	$thumb_pic = C('VIDEO_THUMB_PIC');
	$photo_pic = C('VIDEO_PHOTO_PIC');
	$status = C('VIDEO_STATUS');
	if($type == 1){
		$roomurl = str_replace("ID","",$roomurl);
		$roomurl = str_replace(" ","",$roomurl);
		$videotext = 'var videodivtxt = \'';
		if(ismobile()){
			$videotext .= '<video controls autoplay width="100%" height="100%">';
			$videotext .= '<source src="http://hls.duowan.com/newlive/74453847_74453847.m3u8?org=yyweb&appid=0&uuid=b2c5b4f1f63c4a05bb69d9d5d4688edd&t=1494985305&tk=5cc79958e471fecf320e58bf50bd9233&uid=0&ex_audio=0&ex_coderate=700&ex_spkuid=0">';
			$videotext .= '</video>';
		} else {
			//$videotext .= '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="100%" height="100%"><param name="movie" value="http://yy.com/s/'.$roomurl.'/'.$roomurl.'/yyscene.swf" />';
			//$videotext .= '<param name="quality" value="high" /><param name="allowFullScreen" value="true" />';
			$videotext .= '<embed id="videolive" src="http://yy.com/s/'.$roomurl.'/'.$roomurl.'/yyscene.swf"';
			$videotext .= ' quality="high" width="100%" height="100%" align="middle" allowscriptaccess="always" allowfullscreen="true" ';
			$videotext .= ' wmode="opaque" type="application/x-shockwave-flash"></embed>';
			//$videotext .= '</object>';
		}
		$videotext .= '\';';
		$videotext .= '$("#player").html(videodivtxt);';
	} elseif($type == 2){
		$videotext = '$("#player").html("");';
		$videotext .= 'var player = new CloudLivePlayer(); player.init({activityId:"'.$roomurl.'"},"player");';
	} elseif($type == 3){

		if(strstr($roomurl,"token")){
			//r=1114&token=d0a7FEPjGSV-O-m6eDrX-42Y-4Kch0uLZTbovAPe21O7&id=dyyplayer
			$roomurl = str_replace("&id=dyyplayer","",$roomurl);
			$roomurl = str_replace("&id=player","",$roomurl);
	        $videotext = '$("body").append("<scr"+"ipt src=http://cdn.aodianyun.com/dms/rop_client.js></scr"+"ipt>");';
	        $videotext .= '$("body").append("<scr"+"ipt src=http://finance.aodianyun.com/helper/room_player.js?'.$roomurl.'&id=player></scr"+"ipt>");';
		} else {
			//23308.lssplay.aodianyun.com/cnzzcf/stream
			$roomurl = str_replace("rtmp://","",$roomurl);
			$roomurl = str_replace("http://","",$roomurl);
			$videotext = '$("#player").html("");';
			$videotext .= 'var objectPlayer=new aodianPlayer({container:"player", rtmpUrl:"rtmp://'.$roomurl.'",';
			$videotext .= ' hlsUrl: "http://'.$roomurl.'.m3u8", width: "100%",height: "100%",autostart: true,';
			$videotext .= ' bufferlength: "1", maxbufferlength: "2", controlbardisplay:"enable", stretching: "2",adveDeAddr: "'.$photo_pic.'"});';
			 //stretching 设置全屏模式,1代表按比例撑满至全屏,2代表铺满全屏,3代表视频原始大小
		}

	} elseif($type == 4){
		//$roomurl = "http://zrby.gensee.com/webcast/site/entry/join-550b28e8d8b04b0aaff47dab3962db42";
		$aa = explode("live-",$roomurl);
		$cc = $aa[1];
		if(empty($cc)){
			$bb = explode("join-",$roomurl);
			$cc = $bb[1];
		} 
		$dd = explode(".",$roomurl);
		$ee = str_replace("http://","",$dd[0]);
		$videotext = '$("#player").html("");';
		$videotext .= 'var videodivtxt = \'';
		$videotext .= '<gs:video-live site="'.$ee.'.gensee.com" ctx="webcast" ownerid="'.$cc.'" bar="true" uname="'.$ee.'" lang="zh_CN" visible="true" />';
		$videotext .= '\';';
		$videotext .= ' $("#player").html(videodivtxt);';
	}
	return $videotext;	
}

/**
 * [downloadShortcuts 保持到桌面]
 * @param  [type] $url      [地址]
 * @param  [type] $filename [文件名称]
 * @param  [type] $ico      [ico图标]
 * @return [type]           [description]
 */
function downloadShortcuts($url,$filename,$ico){
	//首先判断浏览器类型
	$user_agent=$_SERVER['HTTP_USER_AGENT'];
	
    if(preg_match("/MSIE/", $user_agent)){
        header('content-disposition:attachment; filename="'.rawurlencode($filename).'"');
    }else if(preg_match("/Firefox/", $user_agent)){
        header("content-disposition:attachment; filename*=\"utf8''".urldecode($filename).'"');
    }else{
        header('content-disposition:attachment; filename="'.urlencode($filename).'"');
    }
    
	//生成快捷方式并下载 
	$Shortcuts='[InternetShortcut]
	URL='.$url.'
	IDList=
	[{000214A0-0000-0000-C000-000000000046}]
	IconFile='.$ico.'
	IconIndex=1
	HotKey=0
	Prop3=19,2';
	header('Content-type: application/octet-stream');
	header('Content-Disposition: attachment; filename='.$filename.'.url;');
	echo $Shortcuts;
}
?>
