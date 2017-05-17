/*
* Author: [ Copy Lian ]
* Date: [ 2015.05.14 ]
* Description [ 基础js ]
*/
$(function(){
	$(document).bind('click', function (e){
		e = e || window.event;
		srcObj = e.srcElement ? e.srcElement : e.target;
		if($(srcObj).hasClass('face') || $(srcObj).hasClass('clap')){
			$('.face01_imgs').css('display','block');
		}else{
			$('.face01_imgs').css('display','none');
		}
		if($(srcObj).hasClass('color_bar') || $(srcObj).hasClass('clap1')){
			$('.face02_imgs').css('display','block');
		}else{
			$('.face02_imgs').css('display','none');
		}
		if($(srcObj).hasClass('gift_bar')){
			$('.face03_imgs').css('display','block');
		}else{
			if($(srcObj).parents('.face03_imgs').attr('id') != 'face03_imgs'){
				$('.face03_imgs').css('display','none');
			}
		}
		if($(srcObj).hasClass('showbgs')){
			$('#bgs').css('display','');
		}else{
			$('#bgs').css('display','none');
		}
		if($(srcObj).hasClass('xuanhua')){
			var html = '<img src="arclist/9.gif" title="鲜花">'
			$('.send_input_text').append(html);
		}
	});

	$('.face01_imgs img, .face02_imgs img').click(function(){
		var html = '<img src="'+$(this).attr('src')+'" title="'+$(this).attr('title')+'">'
		if($('.send_input_text').html()=='观望一天，不如咨询一遍'){$('.send_input_text').html('');}
		$('.send_input_text').append(html);
		//console.log($('.send_input_text')[0].scrollHeight);
		$('.send_input_text').scrollTop($('.send_input_text')[0].scrollHeight - $('.send_input_text').height());
	});

	//窗口函数
	Dimensions();
	$(window).resize(function(event) {
		Dimensions();
	});

	//设置回车消息
	$('#msg_content').keydown(function(e){
		//console.log(e.keyCode);
		if(e.ctrlKey && e.keyCode == 13){
			$(this).append('<div><br/></div>')
		} else if(e.keyCode == 13){
		   $(".send_button").trigger('click');
		}
	}).keyup(function(e){
		if(e.ctrlKey && e.keyCode == 13){
			set_focus($(this));
		} else if(e.keyCode == 13){
		   $(this).empty();
		}
	});

	//设置滚动
	$("#zhiboscroll").mCustomScrollbar({theme:"default",scrollInertia:0});
	
	$("#erweimanewdiv1").mCustomScrollbar({theme:"default",scrollInertia:0});
	
	
	$("[data-toggle='popover']").popover();
	$("#erweima").hover(function(){
		$(this).trigger('click');
	},function(){
		$(this).trigger('click');
	});
	

	//下拉菜单
	$('#menuLeft li.spec0').mouseenter(function(event) {
		$("#navShow").show();
	}).mouseleave(function(event) {
		$("#navShow").hide();
	});

	//今日数据滚动函数
	var scrollTimer = null;
	var delay = 2000;
	$('div.scrollNews').hover(function () {
		clearInterval(scrollTimer);
	}, function () {
		scrollTimer = setInterval(function () {
			scrollNews();
		}, delay);
	}).triggerHandler('mouseout');

	//设置抽奖居中
    if($(window).height() > $("#cjdeskId").outerHeight()){
        $("#cjdeskId").animate({"top": ($(window).height() - $("#cjdeskId").outerHeight())/2},500);
    }
    if($(window).width() > $("#cjdeskId").outerWidth()){
        $("#cjdeskId").animate({"left": ($(window).width() - $("#cjdeskId").outerWidth())/2},500);
    }
    $(window).resize(function(){
        if($(window).height() > $("#cjdeskId").outerHeight()){
            $("#cjdeskId").animate({"top": ($(window).height() - $("#cjdeskId").outerHeight())/2},500);
        }
        if($(window).width() > $("#cjdeskId").outerWidth()){
	        $("#cjdeskId").animate({"left": ($(window).width() - $("#cjdeskId").outerWidth())/2},500);
	    }
    });

    /* 客服 */
    $('.yicefin_float_right').hover(function() {
		$('.yicefin_float_right3').show();
	}, function() {
		$('.yicefin_float_right3').hide();
	});

	//设置客服下滑
	setTimeout(function(){
		$(".yicefin_float_right").animate({top:225});
	},1200);
	/* end 客服 */
});

/**
 * [set_focus 光标移到最后]
 * @param {[type]} el [description]
 */
function set_focus(el){  
	el=el[0];  // jquery 对象转dom对象  
	//el.focus();  
	if(/msie/.test(navigator.userAgent.toLowerCase())){  
		var rng;  
		el.focus();  
		rng = document.selection.createRange();  
		rng.moveStart('character', -el.innerText.length);  
		var text = rng.text;  
		for (var i = 0; i < el.innerText.length; i++) {  
			if (el.innerText.substring(0, i + 1) == text.substring(text.length - i - 1, text.length)) {  
				result = i + 1;  
			}  
		}  
	}  else  {  
		var range = document.createRange();  
		range.selectNodeContents(el);  
		range.collapse(false);  
		var sel = window.getSelection();  
		sel.removeAllRanges();  
		sel.addRange(range);  
	}  
}

/**
 * [Dimensions 窗口控制函数]
 */
function Dimensions(){
	var screen_width = $(window).width();
	var screen_height = $(window).height();
	var zbbox_width = screen_width;
	var zbbox_height = screen_height;

	var header_height = $(".header").outerHeight(); //40
	var footer_height =4;//25

	$(".zhibobox").width(zbbox_width);
	$(".zhibobox").height(zbbox_height);

	var zhibomenu_width = $(".zhibomenu").outerWidth(); //60
	var zhibolist_width = $(".zhibolist").outerWidth(); //240 + 10
	var list_lefttop_height = $(".zhibobox .lefttop").outerHeight();
	var list_leftfoot_height = $(".zhibobox .leftfoot").outerHeight();;
	$(".zhibolist").outerHeight(screen_height-header_height-footer_height);
	$(".zhibodata .scrollNews").height($(".zhibolist").outerHeight()-list_lefttop_height-list_leftfoot_height-52);
	$(".zhibodata #newslist").height($(".zhibolist").outerHeight()-list_lefttop_height-list_leftfoot_height-5);

	//聊天区
	var zhibochat_width=parseInt(((zbbox_width-zhibomenu_width-zhibolist_width) *0.36)/50)*45;
	//zhibochat_width = 400; //固定聊天区宽度
	if(zhibochat_width<300){zhibochat_width=300;}
	$("#zhibochat").outerWidth(zhibochat_width);
	$("#zhibochat").outerHeight(screen_height-header_height-footer_height-15);
	var chat_sendbox_height = $("#sendbox").outerHeight();
	$("#zhiboscroll").outerHeight($("#zhibochat").outerHeight()-chat_sendbox_height-40);
	$("#kx").outerHeight($("#zhibochat").outerHeight-45);
	$("#gbqchat").css("width",(zhibochat_width-100)+"px");
	$("#send_input").outerWidth(zhibochat_width-20);
	$("#send_input2").outerWidth(zhibochat_width-20);
	$("#msg_content").outerWidth(zhibochat_width-110);
	$("#gbqtxt").outerWidth(zhibochat_width-100);
	$("#ggmarquee4").outerWidth(zhibochat_width-100);

	//视频
	var room_width = zbbox_width-zhibomenu_width-zhibolist_width-zhibochat_width-40;
	var sT2bd_height=parseInt(((screen_height-header_height-footer_height)*0.28)/10)*9;
	var room_height = screen_height-sT2bd_height-header_height-footer_height-40;
	if(room_width < 500){
		room_width = 500;
		zhibochat_width=screen_width-room_width-30;
		$(".zhibolist").css("display","none");$(".zhibomenu").css("display","none");
	}else{
		$(".zhibolist").css("display","");$(".zhibomenu").css("display","");
	}
	$('.zhiboright').outerWidth(room_width);
	$('.zhibovideomain').outerHeight(room_height+14);
	$('#hqv_1b').outerWidth(room_width-10);
	$("#ggmarquee").css("width",(room_width-100)+"px");
	var parHd_height=$('.slideGroup .parHd').outerHeight();
	var pp_height=sT2bd_height-parHd_height;
	$(".zhibonews").outerHeight(sT2bd_height);
	$(".slideGroup").outerHeight(sT2bd_height);
	$(".slideGroup").outerWidth(room_width);
	$(".slideGroup .slideBox").outerWidth(room_width);
	$(".slideGroup .slideBox").outerHeight(pp_height);
	$(".slideGroup .slideBox2 li").outerWidth(room_width);
	$(".slideGroup .slideBox2 li").outerHeight(sT2bd_height-50);
	$(".slideGroup .slideBox img").innerWidth(room_width);
	$(".slideGroup .slideBox img").outerHeight(pp_height);

	//聊天区不固定
	if($("#zhibochat").outerWidth()<350){$(".hidden5").css("display","none");}else{$(".hidden5").css("display","");}
	if($("#zhibochat").outerWidth()<470){$(".hidden4").css("display","none");}else{$(".hidden4").css("display","");}
	if($("#zhibochat").outerWidth()<520){$(".hidden3").css("display","none");}else{$(".hidden3").css("display","");}
	if($("#zhibochat").outerWidth()<550){$(".hidden2").css("display","none");}else{$(".hidden2").css("display","");}
	if($("#zhibochat").outerWidth()<620){$(".hidden").css("display","none");}else{$(".hidden").css("display","");}
}

//送礼物
$(function(){
	$(".gift-tab>li").click(function () {
		$(".gift-tab>li").removeClass("on");
		$(this).addClass("on");
		var index=$(this).index();
		$(".gift-group>ul").hide();
		$(".gift-group>ul").eq(index).show();
	})

	$(".gift-group .lastLift").css("left","-131px")
	$(".gift-group li").hover(function () {
		$(this).css("backgroundColor","rgba(0,0,0,0.6)");
		$(this).children("div").show();
	},function () {
		$(this).css("backgroundColor","rgba(0,0,0,0)");
		$(this).children("div").hide();
	})
	$(".gift-group li").click(function () {
		$(".gift-group li").css("border","1px solid #fff").removeClass('selected');
		$(this).css("border","1px solid #ccc").addClass('selected');
	});

	//老师
	$('.teacher_list').find('a').click(function(event) {
		$('.teacher_list').find('a').removeClass('teachercur');
		$(this).addClass('teachercur');
	});
});