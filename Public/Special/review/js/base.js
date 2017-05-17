$(function(){
	
	//设置滚动条
    /*var scrollInertiaNum;
    if(/firefox/.test(navigator.userAgent.toLowerCase())){
        scrollInertiaNum = 200;
    } else {
        scrollInertiaNum = 200;
    }
    $(window).on("load",function(){
        $("body").mCustomScrollbar({
	        theme:'default',
	        scrollInertia:scrollInertiaNum,
	        horizontalScroll:false,
	        axis:"y",
	    });
    });*/

    //设置视频
    plyr.setup();
});
