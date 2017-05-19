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
	});
})