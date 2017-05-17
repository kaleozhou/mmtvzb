/*
* Author: [ Copy Lian ]
* Date: [ 2015.05.14 ]
* Description [ iframe基础js ]
*/
$(function(){
    //设置选择框
    $(".select_ids,.selectAll_ids,.selectAll_ids_list").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green"});

    //系统配置全选
    $('.selectAll_ids').on('ifChanged', function(event){
    	if($(this).is(':checked')){
    		$(this).parents('.tab-content').find('.select_ids').iCheck('uncheck');
    		$(this).parents('.panel-body').find('.select_ids').iCheck('check');
    	} else {
    		$(this).parents('.panel-body').find('.select_ids').iCheck('uncheck');
    	}
	});

    //公共列表全选
    $('.selectAll_ids_list').on('ifChanged', function(event){
        if($(this).is(':checked')){
            $(this).parents('.tab-content').find('.select_ids').iCheck('uncheck');
            $(this).parents('.tab-content').find('.select_ids').iCheck('check');
        } else {
            $(this).parents('.tab-content').find('.select_ids').iCheck('uncheck');
        }
    });

    //信息提示
    $('[data-toggle="tooltip"]').tooltip();

    //静态弹出框
    $('[data-toggle="popover"]').popover();
});

/**
 * [refresh 刷新]
 * @return {[type]} [description]
 */
function refresh(){
    location.reload();
}