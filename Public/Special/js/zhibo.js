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
		var html = '<img src="arclist/9.gif" title="鲜花">'
		$('.send_input_text').append(html);
	}
})
$(function(){
	$('.face01_imgs img, .face02_imgs img').click(function(){
		var html = '<img src="'+$(this).attr('src')+'" title="'+$(this).attr('title')+'">'
		//var html = '[em_'+$(this).attr('src').replace('.gif','').replace('arclist/','')+']';
		if($('.send_input_text').html()=='观望一天，不如咨询一遍'){$('.send_input_text').html('');}
		$('.send_input_text').append(html);
		$('.send_input_text').scrollTop($('.send_input_text')[0].scrollHeight - $('.send_input_text').height());
	})
	
	
})
$(document).ready(function () {
	$("a.fancyiframe").fancybox({'width':900,'height':500,'transitionIn':'elastic','transitionOut':'elastic','overlayOpacity':0,'autoScale':false,'type':'iframe'});
	$("a.fancyiframe2").fancybox({'width':900,'height':600,'transitionIn':'elastic','transitionOut':'elastic','overlayOpacity':0,'autoScale':false,'type':'iframe'});
	$("a.fancyiframe3").fancybox({'width':1030,'height':600,'transitionIn':'elastic','transitionOut':'elastic','overlayOpacity':0,'autoScale':false,'type':'iframe'});
	$("a.fancyiframe4").fancybox({'width':900,'height':600,'transitionIn':'elastic','transitionOut':'elastic','padding':'0','overlayOpacity':0,'autoScale':false,'type':'iframe'});
	$("a.userloginiframe").fancybox({'width':420,'height':450,'transitionIn':'elastic','transitionOut':'elastic','overlayOpacity':0,'autoScale':false,'type':'iframe'});
	$("a.fancybox").fancybox({'transitionIn':'none','transitionOut':'none','autoScale':false});
	$("a.inline_img").fancybox({'hideOnContentClick': true,'padding':0,'overlayShow':true,'overlayOpacity':0});
	$("a.danliaofancy").fancybox({'hideOnContentClick': true,'padding':0,'overlayShow':false,'type':'inline'});
	$("a.cornet").fancybox({'onComplete':$('#fancybox-outer, #fancybox-content').corner('10px')});
	$("a.vipfw").fancybox({'hideOnContentClick': true,'padding':0,'overlayShow':true,'overlayOpacity':0});
	$('#danliaobox .dtop').mousedown(function (event){  
		var isMove = true;  
		var abs_x = event.pageX - $('#danliaobox').offset().left;  
		var abs_y = event.pageY - $('#danliaobox').offset().top;  
		$(document).mousemove(function (event){  
			if(isMove){var obj = $('#danliaobox');obj.css({'left':event.pageX - abs_x, 'top':event.pageY - abs_y});}  
		}).mouseup(function (){isMove = false;});  
	}); 
	 
});
	
(function($){
	$(window).load(function(){
		$("#zhiboscroll").mCustomScrollbar({theme:"3d",scrollInertia:0});
		$(".zhibodata .tab-pane").mCustomScrollbar({theme:"3d"});
		$(".slideGroup .slideBox2 li").mCustomScrollbar({theme:"3d"});
		$("#gbqtxtmain").mCustomScrollbar({theme:"3d"});
		$(".dlist").mCustomScrollbar({theme:"3d"});
		$(".danliao").mCustomScrollbar({theme:"3d"});
	});
})(jQuery);

document.onkeydown = function (e){
	var e = e || window.event;
	var keyCode = e.keyCode || e.which || e.charCode;
	if( $(element).attr('id') == 'msg_content'){
		var element = e.srcElement||e.target;
		if( $(element).attr('id') == 'msg_danliao'){
			danliao_msg();
			send_msg();
		}
	}
};
// ----------------窗口函数----------------
function Dimensions(){
	var screen_width = $(window).width();
	var screen_height = $(window).height();
	var zbbox_width = screen_width;
	var zbbox_height = screen_height;

	var header_height = $(".header").outerHeight(); //40
	var footer_height =4;//25

	//if(zbbox_width>1440){zbbox_width=1440;}
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
	var chat_sendbox_height = $("#sendbox").outerHeight();
	$("#zhibochat").outerWidth(zhibochat_width);
	$("#zhibochat").outerHeight(screen_height-header_height-footer_height-15);
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

// ----------------群聊函数----------------
var dataid=0;
function messajax(zjuid){
	$.ajax({
        type:"get",
        dataType:"html",
        url:"zhiboajax.php?id="+dataid,
        success:function(data){
		 if(data!=window.messdata && data!="" && data !=null){
			window.messdata=data;
			var datajson=JSON.parse(data);
			for(var o in datajson){
				if(datajson[o]){
				if($("#"+datajson[o].id+datajson[o].zbid).length==0 && datajson[o].ispass != 2 && datajson[o].ispass != 12){
			  	 ajaxmess(0,zjuid,datajson[o].id,datajson[o].time,datajson[o].jbpic,datajson[o].jbname,datajson[o].ispass,datajson[o].glname,datajson[o].nick,datajson[o].cont,datajson[o].fromuid,datajson[o].touid,datajson[o].tonick,datajson[o].tc,datajson[o].zbid);
			  	 dataid=datajson[o].id;
				}
				}
			 }
		}
        }
  });
}
$(function(){
	$('#menuLeft li.spec0').mouseenter(function(event) {
		$("#navShow").show();
	}).mouseleave(function(event) {
		$("#navShow").hide();
	});
});


function ajaxmess(flag,zjuid,dataid,datatime,datajbpic,datajbname,dataispass,dataname,datanick,datacont,fromuid,touid,tonick,datatc,datazbid){
	if(zjuid!=fromuid || flag==1){
		showdata  = '<div class="chat" id="'+dataid+datazbid+'">';
		if(dataispass==1 || dataispass==3 || dataname!=""){
			showdata += '<table border="0" cellspacing="3" cellpadding="0"><tr>';
			showdata += '<td valign="top"><div class="jbpic"><img src="'+datajbpic+'" title="'+ datajbname +'" /></div></td>';
			showdata += '<td><div class="chatmain"><div class="top">';
			if( (event.ctrlKey && (event.keyCode == 13)) ||  event.keyCode == 13 ){
			}
			if(dataname!=""){showdata += '<div class="cname">'+dataname+'</div>';}
	   showdata += '<div class="cname"><a href="javascript:void(0)" onclick="talk(\''+fromuid+'\',\''+datanick+'\')" title="对ＴＡ说">'+datanick+'</a></div>';
	   if(touid!="" && tonick!=""){
		   showdata += '<div class="dui"></div>';
		   showdata += '<div class="cname"><a href="javascript:void(0)" onclick="talk(\''+touid+'\',\''+tonick+'\')" title="对ＴＡ说">'+tonick+'</a></div>';
	   }
	   showdata += '<div class="time">['+datatime+']</div>';
	   //if(dataispass>9){showdata += '	<div class="pass"><a href="javascript:void(0);" onclick="javascript:tyisdel('+dataid+','+datazbid+');"><img src="images/del.png" /></a></div>';}
	  // if(dataispass=="10"){showdata += '	<div class="pass"><a href="javascript:void(0);" onclick="javascript:tyispass('+dataid+','+datazbid+');"><img src="images/shenghe.png" /></a></div>';}
	   showdata += '</div>';//top_end
	   showdata += '<table border="0" cellspacing="0" cellpadding="0"><tr><td valign="top"><img src="images/chatleft.png"></td><td><div class="txt">';
	   showdata += '<div class="cont'+datatc+'">'+datacont+'</div>';
	   showdata += '</div></td></tr></table></div></td></tr></table>';
   }
   showdata += '</div>';
   if(datacont!=""){$("#zhiboscroll .content").append(showdata);}
   chat_ajax();
}
   return false;
}

function tyispass(dataid,datazbid){
  $.ajax({
        type:"get",
        dataType:"html",
        url:"tyispassajax.php?id="+dataid,
        success:function(data){
			$("#"+dataid+datazbid+" .pass").html("");
		}
  });
}

function tyisdel(dataid,datazbid){
  $.ajax({
        type:"get",
        dataType:"html",
        url:"tyisdelajax.php?id="+dataid,
        success:function(data){
			$("#"+dataid+datazbid+"").html("");
		}
  });
}


var datazid=0;
function passdelajax(){
  $.ajax({
        type:"get",
        dataType:"html",
        url:"passdelajax.php?zid="+datazid,
        success:function(data){
		 if(data!=window.passdeldata && data!="" && data !=null){
			window.passdeldata=data;
			var datajson=JSON.parse(data);
			for(var o in datajson){
				if(datajson[o]){
				if($("#"+datajson[o].id+datajson[o].zbid).length==1){
			  	 ajaxpassdel(datajson[o].id,datajson[o].time,datajson[o].jbpic,datajson[o].jbname,datajson[o].ispass,datajson[o].glname,datajson[o].nick,datajson[o].cont,datajson[o].fromuid,datajson[o].touid,datajson[o].tonick,datajson[o].tc,datajson[o].zbid);
			  	 datazid=datajson[o].zbid;
				}
				}
			 }
		}
        }
  });
}

function ajaxpassdel(dataid,datatime,datajbpic,datajbname,dataispass,dataname,datanick,datacont,fromuid,touid,tonick,datatc,datazbid){
	if(dataispass=="2"){
		showdata = '';
		if(datacont!=""){$("#"+dataid+datazbid).html(showdata);}
	}
	if(dataispass=="3"){
	   showdata = '<table border="0" cellspacing="3" cellpadding="0"><tr>';
	   showdata += '<td valign="top"><div class="jbpic"><img src="'+datajbpic+'" title="'+ datajbname +'" /></div></td>';
	   showdata += '<td><div class="chatmain"><div class="top">';
	   if(dataname!=""){showdata += '<div class="cname">'+dataname+'</div>';}
	   showdata += '<div class="cname"><a href="javascript:void(0)" onclick="talk(\''+fromuid+'\',\''+datanick+'\')" title="对ＴＡ说">'+datanick+'</a></div>';
	   if(touid!="" && tonick!=""){
		   showdata += '<div class="dui"></div>';
		   showdata += '<div class="cname"><a href="javascript:void(0)" onclick="talk(\''+touid+'\',\''+tonick+'\')" title="对ＴＡ说">'+tonick+'</a></div>';
	   }
	   showdata += '<div class="time">['+datatime+']</div>';
	   showdata += '</div>';//top_end
	   showdata += '<table border="0" cellspacing="0" cellpadding="0"><tr><td valign="top"><img src="images/chatleft.png"></td><td><div class="txt">';
	   showdata += '<div class="cont'+datatc+'">'+datacont+'</div>';
	   showdata += '</div></td></tr></table></div></td></tr></table>';
	   if(datacont!=""){$("#"+dataid+datazbid).html(showdata);}
	}

   chat_ajax();
   return false;
}

function send_msg(zjuid){
	var mob = $("#mob").val();
	var msg = $('#msg_content').html();
	if(msg == '' || msg == '观望一天，不如咨询一遍'){return;}
	if(mob == '1'){msg = msg+" [手机用户]";}
	$("#fxchat").val(msg);

	$.ajax({
		type: "POST",
		url:"msgsave.php",
		data:$('#form1').serialize(),
		async: false,
		success: function(data){
		 if(data!="" && data !=null){
			var datajson=JSON.parse(data);
			for(var o in datajson){
				if(datajson[o]){
			  	 ajaxmess(1,zjuid,datajson[o].id,datajson[o].time,datajson[o].jbpic,datajson[o].jbname,datajson[o].ispass,datajson[o].glname,datajson[o].nick,datajson[o].cont,datajson[o].fromuid,datajson[o].touid,datajson[o].tonick,datajson[o].tc,datajson[o].zbid);
			  	}
			 }
		 }
		}
	});

    $("#fxchat").val("");
    $('#msg_content').focus().html('');

   return false;

}

function chat_ajax()
{
	if(document.getElementById("sc").value=="1"){
	  $("#zhiboscroll").mCustomScrollbar("scrollTo","bottom");
	}else{
	  $("#zhiboscroll .content").mCustomScrollbar("disable");
	}

}

function novideoajax()
{
  $.ajax({
        type:"get",
        dataType:"html",
        url:"videoajax.php?v=1",
		success:function(data){
		 	var datajson=JSON.parse(data);
		 	if(datajson.v=="0"){
				videodiv_0();
			}

        }
  });

}


// ----------------单聊函数----------------
window.datadanid=0;
function danliaoajax(){
  var duid=$("#duid").val();
  var dgid=$("#dgid").val();
  var drole=$("#drole").val();

 if(drole!="2" ){
  $.ajax({
	type:"get",
	dataType:"html",
	url:"dopenajax.php?role="+drole+"&uid="+duid+"&gid="+dgid,
	success:function(data){
		 var datajson=JSON.parse(data);
			for(var o in datajson){
				if(datajson[o]){
					if(datajson[o].flag=="1" ){
						//$("#dk"+duid+dgid).click();
						$("#danliaobox").css("display","");
					}
				}
			 }

	}
 });
}
  
if(document.getElementById("danliaobox").style.display==""){  
  $.ajax({     
        type:"get",     
        dataType:"html",     
        url:"danliaoajax.php?uid="+duid+"&gid="+dgid+"&id="+window.datadanid,
        success:function(data){
		 if(data!=window.danmessdata && data!="" && data !=null){ 
			window.danmessdata=data;
			var datajson=JSON.parse(data); 
			for(var o in datajson){
				if(datajson[o]){
				if($("#"+datajson[o].id).length==0){		
			  	 ajaxdanmess(datajson[o].id,datajson[o].time,datajson[o].nick,datajson[o].cont);
			  	 window.datadanid=datajson[o].id;	
				}
				}
			 }
		}		 
        } 
  });
}
}

function ajaxdanmess(dataid,datatime,datanick,datacont){
 if(dataid=="-101"){
	 showdata  = '<div class="noline" id="'+dataid+'">'+datacont+'</div>';
	 $(".danliao .content").append(showdata);
 }else{
	showdata  = '<div class="chat" id="'+dataid+'">';
	showdata += '<div class="chattop">';
	showdata += '<div class="name">'+datanick+'</div><div class="time">'+datatime+'</div>';
	showdata += '</div><div class="clear_none"></div>';
	showdata += '<div class="cont">'+datacont+'</div>';
	showdata += '</div>';
	if(datacont!=""){$(".danliao .content").append(showdata);}
 }
	$(".danliao").mCustomScrollbar("scrollTo","bottom");
	return false;	
}

function uidclick(id,uid,nick){
	$(".dlist #dlist"+id).siblings().removeClass("on");
	$(".dlist #dlist"+id).addClass("on");
	$("#duid").val(uid);
	$("#dnick").val(nick);
	$(".danliao .content").html('');
	window.datadanid=0;
	
}

function dlistajax(role,uid,gid,nick){
if(role=='2'){	
	$("#duid").val(uid);
	$("#dnick").val(nick);
  $.ajax({     
	type:"POST",     
	dataType:"html",     
	url:"dlistajax.php?role="+role+"&uid="+uid+"&gid="+gid,
	success:function(data){ 
		 var datajson=JSON.parse(data); 
		 showdata = '';
			for(var o in datajson){
				if(datajson[o]){
					showdata += '<li id="dlist'+datajson[o].id+'"';	
					if(datajson[o].uid==datajson[o].duid){showdata += ' class="on"';}
					showdata += '>';	
					showdata += '<a href="javascript:void(0)" ';	
					showdata += 'onclick="javascript:uidclick(\''+datajson[o].id+'\',\''+datajson[o].uid+'\',\''+datajson[o].dnick+'\')">';	
					showdata += datajson[o].dnick+'</a>';	
					showdata += '</li>';
				}
			 }
			 $(".dlist ul").html(showdata);	
			 window.datadanid=0;
			 $(".danliao .content").html('');
			 danliaoajax();
	} 
 });
}
 	$("#danliaobox").css("display","");
	$(".danliao").mCustomScrollbar("scrollTo","bottom");
}

function danliao_msg(){
	var msg = $('#msg_danliao').html();
	if(msg == '') return;
	$("#dlchat").val(msg);
	$.post("danliaosave.php",$("#form2").serialize()); 
    $("#dlchat").val("");
    $('#msg_danliao').focus().html('');
   return false;
	
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


function onlineajax(role){
  $.ajax({
		type:"POST",
		dataType:"html",
		url:"onlineajax.php?role="+role,
		success:function(data){
			 $("#userlist").html(data);
		}
	});
}
function onlinenum(){
  $.ajax({
		type:"POST",
		dataType:"html",
		url:"onlinenumajax.php",
		success:function(data){
			 $("#onlinenum").html(data);
		}
	});
}

function kehulistajax(){
	$.ajax({
		type:"POST",
		dataType:"html",
		url:"kehulistajax.php",
		success:function(data){
			if(data!=""){
				$("#kehulist").html(data);
			}
		}
	});
}


function talk(touid,tonick){
	$("#touid").val(touid);
	$("#tonick").val(tonick);
	var obj=document.getElementById('nicklist');
	var flag=0;
	for(var i=0; i<obj.options.length; i++){
       if(obj.options[i].innerHTML == tonick){
          obj.options[i].selected = true;
		  flag=1;
          break;
       }
    }


	if(flag==0){
	   obj.options.add(new Option(tonick,touid));
	   obj.options[obj.options.length-1].selected = true;
	}
}
function changetalk(){
	var obj=document.getElementById('nicklist');
	var index = obj.selectedIndex;
	var touid=obj.options[index].value;
	var tonick=obj.options[index].text;
	if(tonick=="对所有人说"){tonick="";}
	$("#touid").val(touid);
	$("#tonick").val(tonick);
}
function changejqr(){
	var obj=document.getElementById('jqr');
	var index = obj.selectedIndex;
	var nick=obj.options[index].text;
	var userjb=obj.options[index].value;
	$("#nick").val(nick);
	$("#userjb").val(userjb);
	if(userjb=='911' || userjb=='966' || userjb=='999'){$("#role").val("2");}else{$("#role").val("912");}
}


function nickClick(str){
	var obj=document.getElementById('jqr');
	var index = obj.selectedIndex;
	var nick=str;
	var userjb="";
	for(var i=0; i<obj.options.length; i++){
       if(obj.options[i].innerHTML == str){
          obj.options[i].selected = true;
		  userjb= obj.options[i].value;
          break;
       }
    }
	$("#nick").val(nick);
	$("#userjb").val(userjb);
	if(userjb=='911' || userjb=='966'){$("#role").val("2");}else{$("#role").val("912");}
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
			  	   $("#fpmarquee").html('<marquee loop=1 scrollamount="2" scrolldelay="10" style="width:100%;" class="fptext">'+datajson[o].fptext+'</marquee>'); 
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

//-----------------抽奖与红包----------------------------------------------------------------

function cjrotate(tel){
    $.ajax({ 
        type: 'POST', 
        url: 'cjajax.php', 
		data:"tel="+tel,
        dataType: 'json', 
        cache: false, 
        error: function(){ 
            return false; 
        }, 
        success:function(json){ 
            $("#cjstartbtn").unbind('click').css("cursor","default"); 
            var a = json.angle; //角度 
            var p = json.prize; //奖项 
            $("#cjzhuanpan").rotate({ 
                duration:8000, //转动时间
                angle: 0, 
                animateTo:3600+360-a, //转动角度 
                easing: $.easing.easeOutSine, 
                callback: function(){
					$("#dialog_price").html('恭喜你，中得'+p+'！');
					openDialog();
                } 
            }); 
        } 
    }); 
}
function cjshow(){
	$(".cjdesk").css("display","");
}
function cjclose(){
	$(".cjdesk").css("display","none");
}

function cjcheck(){
    // $.ajax({
    //     type: 'POST',
    //     url: 'cjcheck.php',
    //     dataType: 'json',
    //     cache: false,
    //     error: function(){
    //         return false;
    //     },
    //     success:function(json){
    //         var a = json.flag; //标记
    //         var p = json.msg; //信息
		// 	if(a=="0"){cjrotate();}
    //         //if(a=="1"){alert(p);location.href='user/login.php';}
		// 	if(a=="1"){$("#cjtel").css("display","");}
    //         if(a=="2"){alert(p);cjclose();}
		// 	if(a=="3"){cjrotate();}
    //     }
    // });
	$("#cjtel").css("display","");
}
function telsendyz(a){
	if(a == "" || a == '请输入您的手机号码'){
		alert("请先输入手机号码！");
	}else{
		$("#Checkyz").html("<div class='btxt3'>正在发送</div>");
		$.ajax({  
        type:"get",     
        dataType:"html",     
        url:"user/sendyz.php?t="+a+"&y=dir&cj=cj",
        success:function(data){ 
		   $("#Checkyz").html("<div class='btxt3'>"+data+"</div>");
        }
  		});
	}
}
function telregcheck(){
var telephone = $("#cjphone").val();
var validate = $("#cjyan").val();

if(telephone=='请输入您的手机号码'){telephone='';}
if(validate=='验证码'){validate='';}

var isTelephone=/1[345678]{1}\d{9}$/.test(telephone);
if(!isTelephone){
	alert('手机号不正确');
	$("#cjphone").val("");
	return false;	
}else{
	$.post(
		"cjcheck.php",
		 {tel:telephone},
		 function(data){
		 if(data==1){
			 alert("该手机已经抽奖过！");
		 }else{
			 if(!validate){
				 alert("验证码不能为空");
				 $("#cjyan").val("");
			 }else {
				 $.ajax({
					 type: "get",
					 url:"cjtelsave.php?tel="+telephone+"&yan="+validate,
					 error: function(data){
						 alert("error:"+data.responseText);
					 },
					 success: function(data){
						 if(data.trim()=='success'){
							 $("#cjphone").val("");
							 $("#cjyan").val("");
							 $("#cjtel").css("display","none");
							 cjrotate(telephone);
						 }else if(data.trim()=='invalidate_validate'){
							 $("#cjyan").val("");
							 alert("验证码不正确");
						 }else{
							 alert("提交失败");
						 }
					 }
				 });
			 }
		 }
	})
}
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

//录播视频
function tyflv(tsrc){
var w=$("#player").outerWidth();
var h=$("#player").outerHeight();
var timg="";
var tplay=true;
tytxt="";
tvars="";
tvars += "&CuPlayerFile=" + tsrc + "";
tvars += "&CuPlayerImage=" + timg + "";
tvars += "&CuPlayerWidth=" + w + "";
tvars += "&CuPlayerHeight=" + h + "";
tvars += "&CuPlayerAutoPlay=" + tplay + "";
tvars += "&CuPlayerLogo=flv/flvlogo.png";
tvars += "&CuPlayerAutoRepeat=false";
tvars += "&CuPlayerGetNext=false";
tvars += "&CuPlayerShowImage=true";
tvars += "&CuPlayerShowControl=true";
tvars += "&CuPlayerAutoHideControl=false";
tvars += "&CuPlayerAutoHideTime=3";
//tvars += "&CuPlayerVolume=80";

if(tsrc.substr(tsrc.length-3,3)=="flv" || tsrc.substr(tsrc.length-3,3)=="mp4" || tsrc.substr(tsrc.length-3,3)=="f4v"){
tytxt += '<div style="text-align:center;margin:0px">';
tytxt += '<embed src="flv/tyflv.swf" flashvars="' + tvars + '' ;
tytxt += ' quality="high" bgcolor="#000000" allowfullscreen="true" wmode="opaque" ';
tytxt += ' pluginspage="http://www.macromedia.com/go/getflashplayer" ';
tytxt += ' type="application/x-shockwave-flash" align="middle"';
tytxt += ' width="'+ w +'" height="'+ h +'"></embed>';
tytxt += '</div>';
}
	$("#player").html(tytxt);
}


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

//-----------------消息框----------------------------------------------------------------
(function () {
  $.MsgBox = {
    Alert: function (title, msg) {
      GenerateHtml("alert", title, msg);
      btnOk(); //alert只是弹出消息，因此没必要用到回调函数callback
      btnNo();
    },
    Confirm: function (title, msg, callback) {
      GenerateHtml("confirm", title, msg);
      btnOk(callback);
      btnNo();
    }
  }
 
  //生成Html
  var GenerateHtml = function (type, title, msg) {
 
    var _html = "";
 
    _html += '<div id="mb_box"></div><div id="mb_con"><span id="mb_tit">' + title + '</span>';
    _html += '<a id="mb_ico">x</a><div id="mb_msg">' + msg + '</div><div id="mb_btnbox">';
 
    if (type == "alert") {
      _html += '<input id="mb_btn_ok" type="button" value="确定" />';
    }
    if (type == "confirm") {
      _html += '<input id="mb_btn_ok" type="button" value="确定" />';
      _html += '<input id="mb_btn_no" type="button" value="取消" />';
    }
    _html += '</div></div>';
 
    //必须先将_html添加到body，再设置Css样式
    $("body").append(_html); GenerateCss();
  }
 
  //生成Css
  var GenerateCss = function () {
 
    $("#mb_box").css({ width: '100%', height: '100%', zIndex: '99999', position: 'fixed',
      filter: 'Alpha(opacity=60)', backgroundColor: 'black', top: '0', left: '0', opacity: '0.6'
    });
 
    $("#mb_con").css({ zIndex: '999999', width: '400px', position: 'fixed',
      backgroundColor: 'White', borderRadius: '15px'
    });
 
    $("#mb_tit").css({ display: 'block', fontSize: '14px', color: '#444', padding: '10px 15px',
      backgroundColor: '#DDD', borderRadius: '15px 15px 0 0',
      borderBottom: '3px solid #009BFE', fontWeight: 'bold'
    });
 
    $("#mb_msg").css({ padding: '20px', lineHeight: '20px',
      borderBottom: '1px dashed #DDD', fontSize: '13px'
    });
 
    $("#mb_ico").css({ display: 'block', position: 'absolute', right: '10px', top: '9px',
      border: '1px solid Gray', width: '18px', height: '18px', textAlign: 'center',
      lineHeight: '16px', cursor: 'pointer', borderRadius: '12px', fontFamily: '微软雅黑'
    });
 
    $("#mb_btnbox").css({ margin: '15px 0 10px 0', textAlign: 'center' });
    $("#mb_btn_ok,#mb_btn_no").css({ width: '85px', height: '30px', color: 'white', border: 'none' });
    $("#mb_btn_ok").css({ backgroundColor: '#168bbb' });
    $("#mb_btn_no").css({ backgroundColor: 'gray', marginLeft: '20px' });
 
 
    //右上角关闭按钮hover样式
    $("#mb_ico").hover(function () {
      $(this).css({ backgroundColor: 'Red', color: 'White' });
    }, function () {
      $(this).css({ backgroundColor: '#DDD', color: 'black' });
    });
 
    var _widht = document.documentElement.clientWidth; //屏幕宽
    var _height = document.documentElement.clientHeight; //屏幕高
 
    var boxWidth = $("#mb_con").width();
    var boxHeight = $("#mb_con").height();
 
    //让提示框居中
    $("#mb_con").css({ top: (_height - boxHeight) / 2 + "px", left: (_widht - boxWidth) / 2 + "px" });
  }
 
 
  //确定按钮事件
  var btnOk = function (callback) {
    $("#mb_btn_ok").click(function () {
      $("#mb_box,#mb_con").remove();
      if (typeof (callback) == 'function') {
        callback();
      }
    });
  }
 
  //取消按钮事件
  var btnNo = function () {
    $("#mb_btn_no,#mb_ico").click(function () {
      $("#mb_box,#mb_con").remove();
    });
  }
})();
//-----------------消息框　end----------------------------------------------------------------
$(function(){
	$("#dialog_close").click(function () {
		$("#dialog-bg").fadeOut("slow",function(){
			$("#dialog").animate({"top":"0px"},0)
			$("#dialog p").animate({"top":"0px"},0)
			$("#dialog_close").animate({"top":"50px"},0)
		})
	})
});
function openDialog(){
	$("#dialog-bg").show(0,function(){
		$("#dialog").animate({"top":"50%"},"4s");
		$("#dialog").animate({"margin-top":"-250px"},"4s");
		$("#dialog p").animate({"top":"200px"},"15s");
		$("#dialog_close").animate({"top":"365px"},"5s")
	})
}

//今日数据滚动函数
$(function () {
	var scrollTimer = null;
	var delay = 2000;
//1.鼠标对新闻触发mouseout事件后每2s调用scrollNews()
//2.鼠标对新闻触发mouseover事件后停止调用scrollNews()
//3.初次加载页面触发鼠标对新闻的mouseout事件
	$('div.scrollNews').hover(function () {
		clearInterval(scrollTimer);
	}, function () {
		scrollTimer = setInterval(function () {
			scrollNews();
		}, delay);
	}).triggerHandler('mouseout');
});
//滚动新闻
function scrollNews() {
	var $news = $('div.scrollNews>ul');
	var $lineHeight = $news.find('li:first').height();
	$news.animate({ 'marginTop': -$lineHeight + 'px' }, 400, function () {
		$news.css({ margin: 0 }).find('li:first').appendTo($news);
	});
}

//左边菜单栏 老师战队
$(function () {
	$(".zhibolist .tb1>ul>li").hover(function () {
		var index=$(this).index();
		if(index===0){
			$(this).find("img").attr("src","images/leftMenuOut"+index+".gif");
		}else{
			$(this).find("img").attr("src","images/leftMenuOut"+index+".jpg");
		}

	},function () {
		var index=$(this).index();
		if(index===0){
			$(this).find("img").attr("src","images/leftMenu"+index+".gif");
		}else{
			$(this).find("img").attr("src","images/leftMenu"+index+".jpg");
		}

	})
});
//财经日历
$(function () {
	$("#showIframe>a").click(function () {
		$("#showIframe").fadeOut();
	});
	$("#rltckkk").click(function () {
		$("#showIframe").fadeIn();
	})
});

//登陆和注册
$(function(){
	$("#toLogin").hover(function(){
		$(this).find("img").attr("src","images/new/44.png")
	},function () {
		$(this).find("img").attr("src","images/new/4.png")
	})
});
$(function(){
	$("#toReg").hover(function(){
		$(this).find("img").attr("src","images/new/55.png")
	},function () {
		$(this).find("img").attr("src","images/new/5.png")
	})
});
//左侧领取马甲
$(function(){
	$(".register").click(function () {
		$("#wholeReg").fadeIn();
	});
	$("#closeReg").click(function () {
		$("#wholeReg").fadeOut();
	})
});

/**
 * [userreg 登录框立即注册]
 * @return {[type]} [description]
 */
function userreg(){
	$(".register").trigger('click');
	$('#wholeLogin').fadeOut();
}

function getCookie(name)
{
	var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
	if(arr=document.cookie.match(reg))
		return unescape(arr[2]);
	else
		return null;
}

/*$(function () {
	$("#closeMakeName").click(function () {
		$("#makeName").fadeOut();
		clearInterval(interval_1);
	})
});*/
$(function () {
	//打开登陆界面
	$("#toLogin").click(function(){
		 $("#wholeLogin").fadeIn();
	});
   //关闭登陆界面
	$(".loginNew_close").click(function(){
		$("#wholeLogin").fadeOut();
	});
	//登陆进行验证
	$("#loginBtn").click(function () {
		$.post(
			"user/userlogin.php",
			$("#submitLogin").serialize()+"&act=login",
			function (data) {
		        var res=data.replace(/(^\s*)/g,"");
				if(res==1){
					alert("你试的次数太多了！歇会吧！")
				}else if(res==2){
					location.href="../index.php"
				}else if(res==3){
					alert("账号或密码错误，请重新登陆！")
				}else{
					alert("验证码错误！请重新输入！")
				}
		    }
		)
	});

$(function(){
	var makeNameTag=0;
	var interval_1 = setInterval(qmztc,60000);
	var interval_2 = setInterval(qmztc_2,900000);

	function qmztc(){
		$.ajax({
			type:"post",
			dataType:"json",
			url:"islogin.php",
			success:function(data){
				if(data.status == 1){
					//已经登录
					 //makeNameTag=1;
				} else {
					//未登录
					 //makeNameTag=0;
					 $("#makeName").fadeIn();
					 clearInterval(interval_1);
				}
			}
		});
	}

	function qmztc_2(){
		$.ajax({
			type:"post",
			dataType:"json",
			url:"islogin.php",
			success:function(data){
				if(data.status == 1){
					//已经登录
					 //makeNameTag=1;
				} else {
					//未登录
					 //makeNameTag=0;
					 $("#makeName").fadeIn();
				}
			}
		});
	}

	$("#closeMakeName").click(function () {
		$("#makeName").fadeOut();
		clearInterval(interval_1);
	});


	/*var setInt_2 = setInterval(function(){
		$.ajax({
			type:"post",
			dataType:"json",
			url:"islogin.php",
			success:function(data){
				if(data.status == 1){
					//已经登录
					 //makeNameTag=1;
				} else {
					//未登录
					 //makeNameTag=0;
					 $("#makeName").fadeIn();
					 clearinterval('setInt_2');
				}
			}
		});
	},900000);*/

	//console.log(makeNameTag);

	/*if(!makeNameTag){
		$("#makeName").fadeIn();
	}*/
	$("#makeNameBtn").click(function(){
		var nickName=$("#nickName").val();
		if(nickName){
		    var name=$("#topNickName").html();
			$.post(
				"makeName.php",
				{'nickName':nickName,
				'name':name
				},
				function(data){
					var res=data.replace(/(^\s*)/g,"");
					if(res){
						$("#topNickName").html(nickName);
						//设置表单
						$('#nick').val(nickName);
						makeNameTag=1;
						$("#makeName").fadeOut();
					}else{
						alert("操作失败！")
					}
				}
			);
		}else{
			alert("请输入要取的名字！")
		}
	})
})
});
/*客服*/
$(function(){
	$('.yicefin_float_right').hover(function() {
		$('.yicefin_float_right3').show();
	}, function() {
		$('.yicefin_float_right3').hide();
	});

	//设置客服下滑
	setTimeout(function(){
		$(".yicefin_float_right").animate({top:225});
	},1200);
});
/*end 客服*/
