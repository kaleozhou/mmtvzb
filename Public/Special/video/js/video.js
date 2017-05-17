$(function(){
	$("#j-hideRightBtn").click(function(event) {
		if(parseInt($("#course-toolbar-box").css('left')) < 0){
			$("#course-toolbar-box").animate({left: "0"});
			$("#j-coursebox").animate({left: "320"});
			$(this).css({backgroundPosition: "left center"}).attr({title:"隐藏目录"});

			//设置视频
			$(".learn-total").animate({top: "63px",maxWidth:"70%"},1000);
		} else {
			$("#course-toolbar-box").animate({left: "-320px"});
			$("#j-coursebox").animate({left: "0"});
			$(this).css({backgroundPosition: "right center"}).attr({title:"显示目录"});

			//设置视频
			$(".learn-total").animate({top: "15px",maxWidth:"65%"},1000);
		}
	});

	//下滑效果
	$(".learn-total").animate({top: "63px"},1000);

	//设置滚动条
    var scrollInertiaNum;
    if(/firefox/.test(navigator.userAgent.toLowerCase())){
        scrollInertiaNum = 200;
    } else {
        scrollInertiaNum = 200;
    }
    $("#tb-chapterlist-box").mCustomScrollbar({
        theme:'dark',
        scrollInertia:scrollInertiaNum,
        horizontalScroll:false,
        axis:"y",
    });

    //静态弹出框
    $('[data-toggle="popover"]').popover();

});
