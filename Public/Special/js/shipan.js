// ----------------函数----------------
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
	if($(srcObj).hasClass('showbgs')){
		$('#bgs').css('display','');
	}else{
		$('#bgs').css('display','none');
	}
	if($(srcObj).hasClass('xuanhua')){
		var html = '<img src="arclist/10.gif" title="鲜花">'
		$('.send_input_text').append(html);
	}
})

$(function(){
	$('.face01_imgs img, .face02_imgs img').click(function(){
		var html = '<img src="'+$(this).attr('src')+'" title="'+$(this).attr('title')+'">'
		//var html = '[em_'+$(this).attr('src').replace('.gif','').replace('arclist/','')+']';
		$('.send_input_text').append(html);
	})

})



$(document).ready(function () {
	$("a.fancyiframe").fancybox({'width':900,'height':500,'transitionIn':'elastic','transitionOut':'elastic','overlayOpacity':0,'autoScale':false,'type':'iframe'});
	$("a.fancyiframe2").fancybox({'width':900,'height':600,'transitionIn':'elastic','transitionOut':'elastic','overlayOpacity':0,'autoScale':false,'type':'iframe'});
	$("a.fancyiframe3").fancybox({'width':1030,'height':600,'transitionIn':'elastic','transitionOut':'elastic','overlayOpacity':0,'autoScale':false,'type':'iframe'});
	$("a.userloginiframe").fancybox({'width':420,'height':450,'transitionIn':'elastic','transitionOut':'elastic','overlayOpacity':0,'autoScale':false,'type':'iframe'});
	$("a.fancybox").fancybox({'transitionIn':'none','transitionOut':'none','autoScale':false});
	$("a.inline_img").fancybox({'hideOnContentClick': true,'padding':0,'overlayShow':true,'overlayOpacity':0});
	$("a.danliaofancy").fancybox({'hideOnContentClick': true,'padding':0,'overlayShow':false,'type':'inline'});
	$("a.cornet").fancybox({'onComplete':$('#fancybox-outer, #fancybox-content').corner('10px')});
 
});
	
(function($){
	$(window).load(function(){
		$("#zhiboscroll").mCustomScrollbar({theme:"3d"});
		$(".zbshipanmain").mCustomScrollbar({theme:"3d"});
		$(".zhibodata .tab-pane").mCustomScrollbar({theme:"light-thin"});
		$(".slideGroup .slideBox2 li").mCustomScrollbar({theme:"light-thin"});
	});
})(jQuery);

document.onkeydown = function (e){
	var e = e || window.event;
	var keyCode = e.keyCode || e.which || e.charCode;
	if( (e.ctrlKey && (e.keyCode == 13)) ||  e.keyCode == 13 ){
		var element = e.srcElement||e.target;
		if( $(element).attr('id') == 'msg_content'){
			send_msg();
		}
	}
};

// ----------------窗口函数----------------
function Dimensions(){
	var screen_width = $(window).width();
	var screen_height = $(window).height();
	
	var header_height = $(".header").outerHeight(); //40
	var footer_height = $(".footer").outerHeight(); //40
	
	var zhibomenu_width = $(".zhibomenu").outerWidth(); //60
	var zhibolist_width = $(".zhibolist").outerWidth(); //240 + 10
	var list_lefttop_height = $(".zhibobox .lefttop").outerHeight();
	$(".zhibobox").outerHeight(screen_height-header_height-footer_height-10);
	$(".zhibodata #userlist").height(screen_height-list_lefttop_height-header_height-footer_height-20);
	
	//视频 
	var room_width = 600;
	var room_height = 500;
	$('.zhiboright').outerWidth(room_width);
	$('.zbshipanmain').outerHeight(room_height);
	
	//聊天区	
	var zhibochat_width=screen_width-zhibomenu_width-zhibolist_width-room_width-40;
	if(room_width <= 500){zhibochat_width=screen_width-room_width-30;}
	if(zhibochat_width<300){zhibochat_width=300;}
	var chat_sendbox_height = $("#sendbox").outerHeight();
	$("#zhibochat").outerWidth(zhibochat_width);	
	$("#zhiboscroll").outerHeight(screen_height-chat_sendbox_height-header_height-footer_height-55);
	$("#ggmarquee").css("width",($("#zhibochat").width()-90)+"px");
	$("#send_input").outerWidth(zhibochat_width-20);
	$("#msg_content").outerWidth(zhibochat_width-180);
	
	var sT2bd_height=screen_height-$('.zbshipanmain').outerHeight()-header_height-footer_height-65;
	if(sT2bd_height<50){sT2bd_height=50;}
	var parHd_height=$('.slideGroup .parHd').outerHeight();
	var pp_height=sT2bd_height-parHd_height;
	$(".zhibonews").outerHeight(sT2bd_height);
	$(".slideGroup").outerHeight(sT2bd_height);
	$(".slideGroup").outerWidth(room_width);
	$(".slideGroup .slideBox").outerWidth(room_width);
	$(".slideGroup .slideBox").outerHeight(pp_height);
	$(".slideGroup .slideBox2 li").outerHeight(sT2bd_height-50);
	$(".slideGroup .slideBox img").innerWidth(room_width);
	$(".slideGroup .slideBox img").outerHeight(pp_height);
	
}

// ----------------群聊函数----------------


function send_msg(){
	var msg = $('#msg_content').html();
	if(msg == '' || msg == '输入您的问题'){return;}
	$("#fxchat").val(msg);
	$.post("spsave.php",$("#form1").serialize()); 
    $("#fxchat").val("");
    $('#msg_content').focus().html('');
	
   return false;
	
}
function chat_ajax(){
	if(document.getElementById("sc").value=="1"){
	  $("#zhiboscroll").mCustomScrollbar("scrollTo","bottom");
	}else{
	  $("#zhiboscroll .content").mCustomScrollbar("disable");
	}
}
// ----------------聊天区函数----------------

function bgchange(str){
	$("#imgbg").attr("src",str);
	$("#bgs").css("display","none");
}


function tyscpic(){
	aa=document.getElementById("scpic");
	bb=document.getElementById("sc");
	if(bb.value=="0"){
		aa.src="images/lt_sc1.png";
		bb.value="1";
		chat_ajax()
	}else{
		aa.src="images/lt_sc0.png";
		bb.value="0";
	}
}

function chat_clear()
{//清屏
  $.ajax({     
        type:"POST",     
        dataType:"html",     
        url:"zhibocls.php"
  });
	$("#zhiboscroll .content").html('');
	chat_ajax();
}


function playsound(){
	$('embed').remove();  
	$('body').prepend('<embed src="notice.wav" autostart="true" hidden="true" loop="false"></embed>'); 
}



function hy01(){
	$("#hy02").removeClass("on");
	$("#hy01").addClass("on");
	$("#hylist02").css("display","none");
	$("#hylist01").css("display","");	 
}
function hy02(){
	$("#hy01").removeClass("on");
	$("#hy02").addClass("on");
	$("#hylist01").css("display","none");
	$("#hylist02").css("display","");
}


function toupiaoClick(str){
		$.ajax({     
			type:"POST",     
			dataType:"html",     
			url:"toupiaoajax.php?t="+str,
			success: function(msg){
			  v=msg.split("|");
			  if(v[1]=="ssss"){				   
			  }else{
				if(str=='1' || str=='2' || str=='3'){
						vv0=v[1];
						vv1=parseInt(v[2]*100/vv0);
						vv3=parseInt(v[4]*100/vv0);
						vv2=100-vv1-vv3;
						$('#tp1').html(vv1+'%');
						$('#tp2').html(vv2+'%');
						$('#tp3').html(vv3+'%');
				}
				if(str=='4' || str=='5' || str=='6'){
						vv0=v[5];
						vv1=parseInt(v[6]*100/vv0);
						vv3=parseInt(v[8]*100/vv0);
						vv2=100-vv1-vv3;
						$('#tp4').html(vv1+'%');
						$('#tp5').html(vv2+'%');
						$('#tp6').html(vv3+'%');
				}
				if(str=='7' || str=='8' || str=='9'){
						vv0=v[9];
						vv1=parseInt(v[10]*100/vv0);
						vv3=parseInt(v[12]*100/vv0);
						vv2=100-vv1-vv3;
						$('#tp7').html(vv1+'%');
						$('#tp8').html(vv2+'%');
						$('#tp9').html(vv3+'%');
				}
			  }
			   alert(v[0]);
			   }
		});
}


// ----------------弹窗函数----------------
window.fptime="";
function fptext_ajax(){ //飞屏滚动
  $.ajax({     
        type:"get",     
        dataType:"html",     
        url:"fpajax.php?t="+window.fptime,
        success:function(data){ 
		 	var datajson=JSON.parse(data); 
		 	for(var o in datajson){
				window.fptime=datajson[o].fptime;
				if(datajson[o].fptext !== null && datajson[o].fptext != "" ){
			  	   $("#fpmarquee").html('<marquee loop=1 scrolldelay="100" style="width:100%;" class="fptext">'+datajson[o].fptext+'</marquee>'); 
				}
			}
		 
        } 
  });

}
window.tchdtime="";
function tchdtxt_ajax(){ //喊单弹窗
	$.ajax({     
        type:"get",     
        dataType:"html",     
        url:"tchdajax.php?t="+window.tchdtime,
        success:function(data){ 
		 	var datajson=JSON.parse(data); 
		 	for(var o in datajson){
				window.tchdtime=datajson[o].tchdtime;
				if(datajson[o].tchdtxt !== null && datajson[o].tchdtxt != ""){
			  	   $("#tchdtxt").html(datajson[o].tchdtxt); 
				   $("#tchdopen").click();
				}
			}
		 
        } 
	});
}


window.welcometime="";
function welcome_ajax(){ //弹窗欢迎
	$.ajax({     
        type:"get",     
        dataType:"html",     
        url:"welcomeajax.php?t="+window.welcometime,
        success:function(data){ 
		 	var datajson=JSON.parse(data); 
		 	for(var o in datajson){
				window.welcometime=datajson[o].welcometime;
				if(datajson[o].welcometxt !== null && datajson[o].welcometxt != ""){
					$("#welcome img").attr("src",datajson[o].welcometxt); 
				   $("#welcomeopen").click();
				}
			}
		 
        } 
	});
}
//---------------------------------------------------------------------------------

function tyfav(sURL, sTitle) {  
if(arguments.length==0){sURL=document.URL;sTitle=document.title;}  
	try {   
		window.external.addFavorite(sURL, sTitle);   
	} catch (e) {   
		try {   
			window.sidebar.addPanel(sTitle, sURL, "");   
		} catch (e) {   
			alert("加入收藏失败，请使用Ctrl+D进行添加");   
		}   
	}   
}



// 自动判断手机端
function uaredirect(f){
try{if(document.getElementById("bdmark") != null) {return;}var b = false;
	if (arguments[1]){var e = window.location.host;var a = window.location.href;
		if (isSubdomain(arguments[1], e) == 1){f = f + "/#m/" + a;b = true;
		}else{if(isSubdomain(arguments[1], e) == 2){f = f + "/#m/" + a;b = true;}else{f = a;b = false;}}}else{b = true;}
	if(b){var c = window.location.hash;if (!c.match("fromapp")){if ((navigator.userAgent.match(/(iPhone|iPod|Android|ios)/i))) {location.replace(f);}}}
}catch (d){}}


//图片自动调整的模式，1为按比例调整 ，2 按大小调整。
// onload="DrawImage(this,640,480)"
function DrawImage(ImgD,w,h){ 
  var image=new Image(); 
  image.src=ImgD.src; 
  if(image.width>0 && image.height>0){ 
    if(image.width/image.height>= w/h){ 
       if(image.width>w){ 
          ImgD.width=w; 
          ImgD.height=(image.height*w)/image.width; 
       }else{ 
          ImgD.width=image.width; 
          ImgD.height=image.height; 
       } 
       //ImgD.alt=image.width+"x"+image.height; 
     }else{ 
       if(image.height>h){ 
          ImgD.height=h; 
          ImgD.width=(image.width*h)/image.height; 
       }else{ 
          ImgD.width=image.width; 
          ImgD.height=image.height; 
       } 
       //ImgD.alt=image.width+"x"+image.height; 
    } 
  } 
} 
