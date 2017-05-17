function scroll(){
    var top=$("#demo2").css("top")
    top= parseInt(top);
    if(top<-700){
        top=0;
    }else{
        top-=1;
    }
    $("#demo2").css("top",top+"px");
}
var timeID=setInterval(scroll,44);
$(".service").hover(function () {
    clearInterval(timeID);
},function(){
    timeID=setInterval(scroll,44);
});
var topNum = parseInt($(".service").css("top"));
$(window).scroll(function(){
        var scrollTopNum = $(window).scrollTop();//获取网页被卷去的高
        $(".service").stop(true,true).delay(0).animate({top:scrollTopNum+topNum},"slow");
});
$("#owl-demo").owlCarousel({
      autoPlay: 3000, //Set AutoPlay to 3 seconds
      items : 2,
      itemsDesktop : [1199,3],
      itemsDesktopSmall : [979,3],
      stopOnHover : true, 
  });
