;(function($){

  $.fn.extend({
     'mytab':function(settings){
          var opts = $.extend({},$.fn.mytab.defaults,settings);
          var $this = $(this);
          
          //清空所有
          $(opts.tabId).find('li').find('a').removeClass(opts.selectedClass);
          $(opts.tabListId).find('ul').css('display','none');
          //默认第一个选中
          $(opts.tabId).find('li:eq(0)').find('a').addClass(opts.selectedClass);
          $(opts.tabId).find('li:eq(0)').find('a').after(opts.tabAddString);
          $(opts.tabListId).find('ul:eq(0)').css('display','block');
          // $(opts.tabId).find('li').opts.tabEvent(function(event) {
                
          // });
          $(opts.tabId).find('li').on(opts.tabEvent, '', function(event) {
            //切换标题样式
                $(this).parent(opts.tabId).find('li a').removeClass(opts.selectedClass);
                $(this).parent(opts.tabId).find('li a').siblings('span.icon-b').remove();
                $(this).find('a').addClass(opts.selectedClass);
                $(this).find('a').after(opts.tabAddString);
                //显示某个内容
                $(opts.tabListId).find('ul').css('display','none');
                $(opts.tabListId).find('ul:eq('+$(this).index()+')').css('display','block');
          });

     }
  });

$.fn.mytab.defaults = {
    tabId:'#rec_tab_h2',//tab标题项
    tabListId:'#tab_list',//tab内容id
    selectedClass:'tab_s_selected',//tab内容id
    tabEvent:'mouseover', //触发事件
    tabAddString:'<span class="icon-b"><img src="'+img_path+'/icon-b.png" alt="" /></span>' //添加字符串
};


/*
//html代码
<div id="rec_tab" class='width'>
    <div class="rec_tab_h2">
      <ul id='rec_tab_h2'>
        <li class='tab_a'><a href="#" class='tab_s_selected'>英语</a></li>
        <li class='tab_a'><a href="#">小语种</a></li>
        <li class='tab_a'><a href="#">小学辅导</a></li>
      </ul>
    </div>
    <div id="tab_list">
      <ul class="tab_list">
        <li><a href="#"><img src="images/demo.jpg" alt="" /></a></li>
        <li><a href="#"><img src="images/demo.jpg" alt="" /></a></li>
        <li style='margin-right: 0'><a href="#"><img src="images/demo.jpg" alt="" /></a></li>
        <li><a href="#"><img src="images/demo.jpg" alt="" /></a></li>
        <li><a href="#"><img src="images/demo.jpg" alt="" /></a></li>
        <li style='margin-right: 0'><a href="#"><img src="images/demo.jpg" alt="" /></a></li>
        <div class="clear"></div>
      </ul>
      <ul class="tab_list" style='display:none'>
        <li><a href="#"><img src="images/demo.jpg" alt="" /></a></li>
        <li><a href="#"><img src="images/demo.jpg" alt="" /></a></li>
        <li style='margin-right: 0'><a href="#"><img src="images/demo.jpg" alt="" /></a></li>
        <li><a href="#"><img src="images/demo.jpg" alt="" /></a></li>
        <li><a href="#"><img src="images/demo.jpg" alt="" /></a></li>
        
        <div class="clear"></div>
      </ul>
      <ul class="tab_list" style='display:none'>
        <li><a href="#"><img src="images/demo.jpg" alt="" /></a></li>
        <li><a href="#"><img src="images/demo.jpg" alt="" /></a></li>
        <li style='margin-right: 0'><a href="#"><img src="images/demo.jpg" alt="" /></a></li>
        <li><a href="#"><img src="images/demo.jpg" alt="" /></a></li>
    
        <div class="clear"></div>
      </ul>
    </div>
  </div>

//css代码
#rec_tab .rec_tab_h2 ul#rec_tab_h2 .tab_a a.tab_s_selected{
  background: url(../images/tab.png) no-repeat center 40px #F3BA23;
  position: relative;
} 
*/

})(jQuery)
