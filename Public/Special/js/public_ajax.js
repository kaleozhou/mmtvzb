/**
 * Created by Administrator on 2016/8/31 0031.
 */
$(function(){
    /*绑定发送短信事件
     *参数1是发送短信按钮的jq对象，参数2是手机号码输入框的jq对象*/
    bind_send_sms_button($("#get-code"),$("#phone"));
    /*绑定注册事件
     *参数1：注册提交按钮的jq对象。
     * 参数2：手机号码输入框的jq对象
     * 参数3：用户昵称输入框的jq对象
     * 参数4：验证码输入框的jq对象
     * */
    bind_reg_items($("#real-accout"),$("#phone"),$("#initials"),$("#inp-code"));
    $('#mock-account').click(function(){
        $("#real-accout").click();
    });
    bind_send_sms_button($("#get-code2"),$("#phone2"));
    bind_reg_items($("#bottom_submit_btn"),$("#phone2"),$("#initials2"),$("#inp-code2"));
// end
});