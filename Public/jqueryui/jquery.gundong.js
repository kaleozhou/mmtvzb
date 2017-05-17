;(function($){

  $.fn.extend({
     'gundong':function(settings){
        var opts = $.extend({},$.fn.gundong.defaults,settings);
        var $this = $(this);

        $this.width($this.find('li').outerWidth(true)*$this.find('li').length);
        $(opts.txtId).eq(0).css('display', 'block');
        //获取容器能够容下的个数
        var number = Math.ceil($this.parent().width()/$this.find('li').outerWidth(true));
          //如果大于当前容器宽度
          if($this.parent().width() < $this.width()){
              //点击向前
              $this.parent().next().click(function(){
                  if(Math.abs($this.position().left) < $this.width()-$this.find('li').outerWidth(true)*number){
                      $this.animate({left:"-="+$this.find('li').outerWidth(true)+"px"},opts.speed).clearQueue();
                      //文字效果
                      if(opts.txtAnimate){
                          var number_id = Math.abs($this.position().left)/$this.find('li').outerWidth(true);
                          $(opts.txtId).eq(number_id).hide();
                          $(opts.txtId).eq(number_id+1).show();
                      }
                  } else {
                      //文字效果
                      if(opts.txtAnimate){
                          var number_id = Math.abs($this.position().left)/$this.find('li').outerWidth(true);
                          $(opts.txtId).eq(number_id).hide();
                          $(opts.txtId).eq(0).show();
                      }
                      $this.animate({left:0},opts.speed).clearQueue();
                  }
              });

              //点击向后
              $this.parent().prev().click(function(){
                  if($this.position().left < 0){

                      $this.animate({left:"+="+$this.find('li').outerWidth(true)+"px"},opts.speed).clearQueue();
                      //文字效果
                      if(opts.txtAnimate){
                          var number_id = Math.abs($this.position().left)/$this.find('li').outerWidth(true);
                          $(opts.txtId).eq(number_id).hide();
                          $(opts.txtId).eq(number_id-1).show();
                      }
                  } else {
                      $this.animate({left:"-"+($this.width()-$this.find('li').outerWidth(true)*number+"px")},opts.speed).clearQueue();
                      //文字效果
                      if(opts.txtAnimate){
                          $(opts.txtId).eq(0).hide();
                          $(opts.txtId).eq($this.find('li').length-1).show();
                      }
                  }
              });

               /*自动滚动*/
              if(opts.isAuto){
                  var intval = function(){
                      return setInterval(function(){
                        if(opts.direction == 'left'){
                          $this.parent().prev().trigger('click');
                        } else if(opts.direction == 'right') {
                           $this.parent().next().trigger('click');
                        }
                      },opts.time);
                  }
                  var intval_id = intval();
                  /*取消滚动*/
                  $this.add($this.parent().next()).add($this.parent().prev()).hover(function() {
                      clearInterval(intval_id);
                  }, function() {
                     intval_id = intval();
                  });
              }
          }

     }
  });

$.fn.gundong.defaults = {
    time:3000, //轮播时间
    direction:'left', //默认的方向
    speed:'normal', //速度
    isAuto:true, //是否自动滚动
    txtAnimate:false, //默认不开启文字显示效果
    txtId:'.gundong_info' //文本元素，在开启txtAnimate时有效
};

/*
*说明
*在外部直接调用：$("#xxx").gundong({time:2000,direction:'right'})
*#xxx这个元素的position必须是absolute
*父级元素position必须是relative而且是overflow:hidden
*html结构：
<div class="jtxszgs_div">
  <div class="prev"></div>
  <div class="zgs_img">
    <ul id='zgs_ul'>
      <li><a href="#"><img src="images/bsd_logo.png" /></a></li>
      <li><a href="#"><img src="images/bsd_logo.png" /></a></li>
      <li><a href="#"><img src="images/bsd_logo.png" /></a></li>
      <li><a href="#"><img src="images/bsd_logo.png" /></a></li>
      <li><a href="#"><img src="images/bsd_logo.png" /></a></li>
      <li><a href="#"><img src="images/bsd_logo.png" /></a></li>
      <div class="clear"></div>
    </ul>
  </div>
  <div class="next"></div>
  <div class="clear"></div>
</div>

<div class="top_pzjx_r">
  <div class="gundong_info">
    <div class='title'>贴心周到的服务！1</div>
  </div>
  <div class="gundong_info">
    <div class='title'>贴心周到的服务！1</div>
  </div>
  <div class="gundong_info">
    <div class='title'>贴心周到的服务！1</div>
  </div>
  <div class="gundong_info">
    <div class='title'>贴心周到的服务！1</div>
  </div>
</div>
*css样式：

.jtxszgs_div .prev{
  width: 58px;
  height: 120px;
  float: left;
  background: url(../images/zgs_prev.png) no-repeat center center;
  cursor: pointer;
}

.jtxszgs_div .next{
  width: 58px;
  height: 120px;
  float: right;
  background: url(../images/zgs_next.png) no-repeat center center;
  cursor: pointer;
}

.jtxszgs_div .zgs_img{
  float: left;
  width: 660px;
  height: 120px;
  position: relative;
  overflow: hidden;
}

.jtxszgs_div .zgs_img ul{
  position: absolute;
  top:30px;
  left: 0;
  height: 70px;
  vertical-align: middle;
}

.jtxszgs_div .zgs_img ul li{
  height: 70px;
  float: left;
  width: 180px;
  display: inline;
  padding-right: 5px; 
}
*/
})(jQuery)
