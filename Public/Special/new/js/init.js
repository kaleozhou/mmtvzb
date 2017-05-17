// JavaScript Document

$(function(){
	//首页关于银天下切换
	var mySwiper5 = new Swiper('.m-a-l-container',{
		pagination: '.pagination',
		loop:true,
		grabCursor: true,
		paginationClickable: true,
		nextButton: '.list-arwright',
        prevButton: '.list-arwleft',
	});

	var swiper = new Swiper('#zhanji', {
        pagination: '.swiper-pagination',
        paginationClickable: true
    });
});