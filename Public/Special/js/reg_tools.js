/**
 * Created by Administrator on 2016/8/31.
 */
$(function(){
    var url = "http://www.yiqutz.com";
//    var url = "http://139.196.212.129:5500";
//绑定发送短信的按钮 $btn_obj是发送短信按钮的jq对象。$phone是输入手机号码的jq对象，
    bind_send_sms_button = function($btn_obj,$phone){
        $btn_obj.click(function(){
            var phone = $.trim($phone.val());
            console.log("phone is "+phone);
            var myreg = /^(((1[3-9][0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
            if(myreg.test(phone)){
                // $.getJSON(url+"/send_check_code_public",
                //     {"phone":phone},
                //     function(data){
                //         if(data["message"]=="success"){
                //             alert("验证码发送成功，十五分钟内有效。");
                //             count_down($btn_obj);
                //         }
                //         else{
                //             alert(data["message"]);
                //             return false;
                //         }
                //     });
                $.ajax({
                    type:"get",
                    dataType:"html",
                    url:"user/sendyz.php?t="+phone+"&y=dir&cj=cj",
                    success:function(data){
                        alert("验证码发送成功，1分钟内有效。");
                        count_down($btn_obj);
                    }
                });
            }else{
                alert("手机号码不正确");
                return false;
            }
        });
    };

//    成功后的倒计时函数
    count_down = function ($obj){
        var prev_value = $obj.text()==""?$obj.val():$obj.text();
        var prev_color = $obj.css("background-color");
        a_count = 0;
        $obj.css({"background-color":"gray","border-color":"gray"});
        var a_interval = setInterval(function(){
            $obj.text((60-a_count)+"秒");
            $obj.val((60-a_count)+"秒");
            a_count += 1;
            if(a_count<60){
                //nothing
            }else{
                $obj.text(prev_value);
                $obj.val(prev_value);
                $obj.css("background-color",prev_color);
                clearInterval(a_interval);
            }
        },1000);
    };
    //提交注册
    //绑定提交注册事件。
    // $submit_button_obj是提交注册按钮的jq对象，
    // $phone_obj是提交手机的输入框jq对象
    // $real_name_obj提交的用户昵称的输入框jq对象
    // $check_code_obj 是提交的验证码的输入框jq对象
    bind_reg_items = function($submit_button_obj,$phone_obj,$real_name_obj,$check_code_obj){
        $submit_button_obj.click(function(){
            var phone = $.trim($phone_obj.val());
            var real_name = $.trim($real_name_obj.val());
            var check_code = $.trim($check_code_obj.val());
            var myreg = /^(((1[3-9][0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
            if(!myreg.test(phone)){
                alert("手机号码不正确");
                return false;
            }
            else if(real_name==''){
                alert("姓名不能为空");
                return false;
            }
            else if(check_code==''){
                alert("验证码不能为空");
                return false;
            }
            else if(check_code.length!=6){
                alert("验证码长度不对");
                return false;
            }
            else{
                var adata = {"phone":phone,"real_name":real_name,"check_code":check_code,'source':"开户查询平台"};
                $.post("openUser.php",adata,function (data) {
                    if(data.trim()=='success'){
                        alert("请及时注意手机！电话回访中");
                        $check_code_obj.val("");
                        $phone_obj.val("");
                        $real_name_obj.val("");
                        // 清除计时器

                    }else if(data.trim()=='invalidate_validate'){
                        alert("验证码不正确");
                    }else{
                        alert("提交失败");
                    }
                    a_count = 60;
                });
            }
        });
    };

    // end!
});
