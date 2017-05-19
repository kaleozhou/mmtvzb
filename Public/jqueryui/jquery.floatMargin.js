;(function($){

  $.fn.extend({
     'floatMagin':function(settings){
        var opts = $.extend({},$.fn.floatMagin.defaults,settings);
        var $this = $(this);

        $this.each(function(i){
           if((i+1)%opts.number == 0 ){ 
              if(opts.float == 'right'){
                 $this.eq(i).css({marginLeft:0});
              } else {
                 $this.eq(i).css({marginRight:0});
              }
           }
        })

     }
  });

$.fn.floatMagin.defaults = {
   number:3, //默认的个数是3个
   float:'left', //默认left浮动
};


/*
*说明
*外部调用：$("#xxx").floatMargin({number:4,float:'right'})
*/
})(jQuery)
