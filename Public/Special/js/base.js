/*
* Author: [ Copy Lian ]
* Date: [ 2015.05.14 ]
* Description [ 基础js ]
*/

/**
 * [delall 组装删除数据的id：批量删除]
 * @param  {[string]} url [处理数据的url地址]
 * @return {[json]}       [返回json格式]
 */
function delall(url,otherData){
    var ids = [];
    $(".select_ids:checked").each(function(index, el) {
        ids.push($(this).val());
    });

    if(ids.length == 0){
        parent.layer.open({
            content:'请选择要删除的数据！',
            yes:function(index){
                parent.layer.close(index);
            },
            icon:2
        });
        return false;
    }

    //变成字符串
    ids = ids.join(',');

    //发送数据
    del(url,ids,otherData);
}

/**
 * [del 删除函数]
 * @param  {[string]} url [处理数据的url地址]
 * @param  {[string]} ids [数据的id字符串]
 * @return {[json]}       [返回json格式]
 */
function del(url,ids,otherData){
    parent.layer.confirm('确定要删除数据？',{icon:3},function(index){
        $.post(url,{id:ids,other:otherData}, function(data, textStatus, xhr) {
            //返回消息
            if(data.status){
                parent.layer.msg(data.info,{icon:1,time:2000,shade: [0.3,'#000']},function(){
                    location.reload();
                });
            } else {
                parent.layer.open({
                    content:data.info,
                    yes:function(index){
                        parent.layer.close(index);
                    },
                    icon:2
                });
                //layer.msg(data.info,{icon:2});
            }

        });
    });
}

/**
 * [myrand 范围随机数]
 * @param  {[type]} begin [开始]
 * @param  {[type]} end   [结尾]
 * @return {[type]}       [返回随机数]
 */
function myrand(begin,end){
     return Math.random()*(end-begin)+begin;
    //return Math.floor(Math.random()*(end-begin))+begin;
}

/**
 * [dialogContent 内容弹窗]
 * @param  {[DOM]} obj  [DOM节点]
 * @return {[type]}     [description]
 */
function dialogContent(obj,title,width,height,maxmin){
    if(typeof(width) == "undefined"){
        var width = "80%";
    }
    if(typeof(height) == "undefined"){
        var height = "auto";
    }
    var dialogContent = layer.open({
        title:title,
        type:1,
        content:$(obj),
        maxWidth:'auto',
        area:[width,height],
        maxmin:maxmin
    })
}

/**
 * [checkBaoming 在线报名]
 * @param  {[type]} url     [description]
 * @param  {[type]} formDom [description]
 * @return {[type]}         [description]
 */
function checkBaoming(url,formDom){
    //验证数据合法性
    //验证姓名
    if(!checkName($("#name").val())){
        layer.open({
            content:'姓名格式不正确！',
            yes:function(index){
                layer.close(index);
                $("#name").focus();
            },
            icon:2
        });
        return false;
    }

    //验证联系电话
    if(!checkTel($("#tel").val())){
        layer.open({
            content:'手机格式不正确！',
            yes:function(index){
                layer.close(index);
                $("#tel").focus();
            },
            icon:2
        });
        return false;
    }

    //验证QQ号码
    if($("#myqq").val().length > 0){
        if(!checkQQ($("#myqq").val())){
            layer.open({
                content:'QQ号码格式不正确！',
                yes:function(index){
                    layer.close(index);
                    $("#myqq").focus();
                },
                icon:2
            });
            return false;
        }
    }

    //执行写入
    submitForm(url,formDom);
}


/**
 * [openwindow 打开新窗口]
 * @param  {[type]} url [description]
 * @return {[type]}     [description]
 */
function openwindow(url,width,height){
    if(isNaN(width)){
        var width = 800;
    }

    if(isNaN(height)){
        var height = 600;
    }
    var openUrl = url;
    var iWidth = width;
    var iHeight = height;
    var iTop = (window.screen.availHeight-30-iHeight)/2;
    var iLeft = (window.screen.availWidth-10-iWidth)/2;
    window.open(openUrl,"","height="+iHeight+", width="+iWidth+", top="+iTop+", left="+iLeft); 
}

/**
 * [submitForm 表单提交ajx数据处理]
 * @param  {[url]} str    [数据发送的url地址]
 * @param  {[object]} obj [对象的DOM节点：#myFrom]
 * @return {[json]}     [返回json]
 */
function submitForm (url,obj) {
   $.post(url, $(obj).serialize(), function(data, textStatus, xhr) {
        //弹出消息
        if(data.status){
            parent.layer.msg(data.info,{icon:1,time:2000,shade: [0.3,'#000']},function(){
                if(data.url){
                    location.href = data.url;
                } else {
                    location.reload();
                }
            });
        } else {
            parent.layer.open({
                content:data.info,
                yes:function(index){
                    if(data.url){
                        location.href = data.url;
                    } else {
                        parent.layer.close(index);
                    }
                },
                icon:2
            });
            //layer.msg(data.info,{icon:2});
        }
    });
}


/**
 * [submitForm_Redirect 表单提交ajx数据处理：成功返回数据直接跳转]
 * @param  {[url]} str    [数据发送的url地址]
 * @param  {[object]} obj [对象的DOM节点：#myFrom]
 * @return {[json]}     [返回json]
 */
function submitForm_Redirect (url,obj) {
   $.post(url, $(obj).serialize(), function(data, textStatus, xhr) {
        //弹出消息
        if(data.status){
            if(data.url){
                location.href = data.url;
            } else {
                location.reload();
            }
        } else {
            layer.open({
                content:data.info,
                yes:function(index){
                    if(data.url){
                        location.href = data.url;
                    } else {
                        layer.close(index);
                    }
                },
                icon:2
            });
            //layer.msg(data.info,{icon:2});
        }
    });
}

/**
 * [loginout 退出登陆]
 * @param  {[string]} url [url地址]
 */
function loginout(url){
    $.post(url, {}, function(data, textStatus, xhr) {
        //弹出消息
        if(data.status){
            layer.msg(data.info,{icon:1,time:2000,shade: [0.3,'#000']},function(){
                if(data.url){
                    location.href = data.url;
                } else {
                    //location.reload();
                }
            });
        } else {
            layer.open({
                content:data.info,
                yes:function(index){
                    if(data.url){
                        location.href = data.url;
                    } else {
                        layer.close(index);
                    }
                },
                icon:2
            });
            //layer.msg(data.info,{icon:2});
        }
    });
}

/**
 * [AddFavorite 收菜本站]
 * @param {[type]} title [description]
 * @param {[type]} url   [description]
 */
function AddFavorite(title, url) {
    try {
        window.external.addFavorite(url, title);
    } catch (e) {
        try {
            window.sidebar.addPanel(title, url, "");
        }
        catch (e) {
            layer.alert("抱歉，您所使用的浏览器无法完成此操作。\n\n加入收藏失败，请使用Ctrl+D进行添加");
        }
    }
}

/**
 * [SetHome 设置主页]
 * @param {[type]} obj [description]
*/
function SetHome(obj){
    url = document.URL;
    try{
        obj.style.behavior='url(#default#homepage)';
        obj.setHomePage(url);
    }catch(e) {
        if(window.netscape){
            try{
                netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
            }catch(e){
               layer.alert("抱歉，此操作被浏览器拒绝！\n\n请在浏览器地址栏输入“about:config”并回车然后将[signed.applets.codebase_principal_support]设置为'true'");     
            }
        }else{
            layer.alert("抱歉，您所使用的浏览器无法完成此操作。\n\n您需要手动将【"+url+"】设置为首页。");
        }
    }
}

/**
 * [checkQQ 验证QQ号码5-11位]
 * @param  {[type]} qq [description]
 * @return {[type]}    [description]
*/
function checkQQ(qq) {
    var filter = /^\s*[.0-9]{5,12}\s*$/;
    if (!filter.test(qq)) {
        return false;
    } else {
        return true;
    }
}

/**
 * [checkEmail 验证邮箱格式]
 * @param  {[type]} str [description]
 * @return {[type]}     [description]
*/
function checkEmail(str) {
    if (str.charAt(0) == "." || str.charAt(0) == "@" || str.indexOf('@', 0) == -1 ||
        str.indexOf('.', 0) == -1 || str.lastIndexOf("@") == str.length - 1 ||
        str.lastIndexOf(".") == str.length - 1 ||
        str.indexOf('@.') > -1)
        return false;
    else
        return true;
}

/**
 * [checkTel 校验手机号码]
 * @param  {[type]} s [description]
 * @return {[type]}   [description]
*/
function checkTel(s) {
    //var patrn = /^[+]{0,1}(\d){1,3}[ ]?([-]?((\d)|[ ]){1,12})+$/;
    var patrn = /^(13[0-9]{9})|(14[0-9])|(18[0-9])|(15[0-9][0-9]{8})$/;
    if (!patrn.exec(s)) return false
    return true
}

/**
 * [checkName 验证姓名]
 * @param  {[type]} name [description]
 * @return {[type]}      [description]
*/
function checkName(name){
    if(name.match(/^[\u4e00-\u9fa5]+$/)){
        return true;
    } else{
        return false;
    }
}


/**
 * [dialogIframe iframe弹窗]
 * @param  {[type]} url    [url地址]
 * @param  {[type]} title  [标题，可以为false]
 * @param  {[type]} width  [宽度：100%或者100px]
 * @param  {[type]} height [高度：100%或者100px]
 * @param  {[type]} maxmin [放大缩小：true或者false]
 * @return {[type]}        [description]
 */
function dialogIframe(url,title,width,height,maxmin){
    if(typeof(width) == "undefined"){
        var width = "80%";
    }
    if(typeof(height) == "undefined"){
        var height = "70%";
    }
    var url = url + "?iframe=" + window.name;
    parent.layer.open({
        title:title,
        type:2,
        area: [width,height],
        maxmin:maxmin,
        content:url
    });
}

/**
 * [closeIframe 关闭父级iframe]
 */
function closeIframe(){
    var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
    parent.layer.close(index);
}

/**
 * [postUrl post传递url处理函数]
 * @return {[json]}     [返回json]
 */
function postUrl(url,sendData){
    $.post(url, {data:sendData}, function(data){
        //弹出消息
        if(data.status){
            parent.layer.msg(data.info,{icon:1,time:2000,shade: [0.3,'#000']},function(){
                if(data.url){
                    location.href = data.url;
                } else {
                    location.reload();
                }
            });
        } else {
            parent.layer.open({
                content:data.info,
                yes:function(index){
                    if(data.url){
                        location.href = data.url;
                    } else {
                        parent.layer.close(index);
                    }
                },
                icon:2
            });
            //layer.msg(data.info,{icon:2});
        }
    });
}

/**
 * [chat_ajax 清除]
 * @return {[type]} [description]
 */
function chat_ajax()
{
    if($('#sc').val() == "1"){
      $("#zhiboscroll").mCustomScrollbar("scrollTo","bottom");
    }else{
      $("#zhiboscroll .content").mCustomScrollbar("disable");
    }
}

/**
 * [chat_clear 清屏函数]
 * @return {[type]} [description]
 */
function chat_clear()
{
    $("#zhiboscroll .content").html('');
    chat_ajax();
}

/**
 * [scrollNews 滚动新闻]
 * @return {[type]} [description]
 */
function scrollNews() {
    var $news = $('div.scrollNews>ul');
    var $lineHeight = $news.find('li:first').height();
    $news.animate({ 'marginTop': -$lineHeight + 'px' }, 400, function () {
        $news.css({ margin: 0 }).find('li:first').appendTo($news);
    });
}

/**
 * [send_msg 发送信息]
 * @return {[type]} [description]
 */
function send_msg(){
    var mob = $("#mob").val();
    var msg = $('#msg_content').html();

    //判断内容是否为空
    var emptymsg = msg.replace(/&nbsp;/g,'');
    emptymsg = emptymsg.replace(/<div><br><\/div>/g,'');
    emptymsg = emptymsg.replace(/<br>/g,'');
    emptymsg = $.trim(emptymsg);
    if(emptymsg == ''){
        return false;
    }

    if(msg == '' || msg == '观望一天，不如咨询一遍'){
        return false;
    }

    if(mob == '1'){
        msg = msg+" [手机用户]";
    }
    $("#fxchat").val(msg);
    //发送ajax
    $.ajax({
        type: "POST",
        dataType:'json',
        url:massageUrl,
        data:$('#formMessage').serialize(),
        success: function(data){
            //发送成功之后
            if(data.mystatus){
                //关闭ajax方式
                //ajaxmess(data);
            } else {
                layer.msg(data.info,{icon: 0});
            }
        }
    });

    $("#fxchat").val("");
    $('#msg_content').focus().html('');
    //return false;
}


/**
 * [tyupfile 上传图片]
 * @param  {[type]} objid [file对象id名称]
 * @param  {[type]} content [添加到的内容id]
 * @param  {[type]} name [组件的名称]
 * @param  {[type]} type [上传类型：图片image 视频video 文件file]
 * @param  {[type]} url [上传处理url]
 * @return {[type]}   [description]
 */
function tyupfile(objid,content,name,url) { //d  图片image 视频video 文件file
    if(arguments.length == 0){
        objid = "fileupload";
        content = "pic1";
        name = "上传图片";
    }

    //目标存放的容器
    var descontent = $("#"+content);
    $("#"+objid).wrap("<div class='tyupbtn'><form id='tyup"+objid+"' action='"+url+"' method='post' enctype='multipart/form-data'><span>"+name+"</span></form></div>");
    $("#"+objid).change(function(){
        $("#tyup"+objid).ajaxSubmit({
            dataType:'json',
            type:"POST",
            success: function(data) {
                if(data.status){
                    var thumb = data.thumb;
                    var photo = data.photo;
                    var dialogId = "dialogimg_"+data.uniqid;
                    descontent.html(descontent.html()+'<div class="picpart"><div class="imgdiv"><a href="javascript:;" onclick="dialogContent('+dialogId+',false,\'auto\')"><img src="'+photo+'" align="bottom" title="点击查看大图"/></a><div style="display:none;" id="'+dialogId+'"><img src="'+photo+'" /></div></div></div>');
                } else {
                    layer.msg(data.info,{icon: 0});
                }
            }
        });
    });
};

/**
 * [ajaxmess 聊天信息返回数据处理]
 * @param  {[type]} data [数据]
 * @return {[type]}      [string]
 */
function ajaxmess(data){
    if(data.data.fromuid != data.data.touid || data.data.fromusertype != data.data.tousertype){
        showdata  = '<div class="chat" id="'+data.dataid+data.data.create_time+'">';
            showdata += '<table border="0" cellspacing="3" cellpadding="0"><tr>';
            
            //判断图标
            if(data.data.fromusertype == 0){
                showdata += '<td valign="top"><div class="jbpic"><img src="/Public/images/member/user1.gif" title="游客" /></div></td>';
            } else if(data.data.fromusertype == 1){
                showdata += '<td valign="top"><div class="jbpic"><img src="'+data.userdata.membergroup.icon+'" title="'+ data.userdata.membergroup.name +'" /></div></td>';
            } else if(data.data.fromusertype == 2){
                showdata += '<td valign="top"><div class="jbpic"><img src="/Public/Zhibo/zhibo/Images/xc01.png" title="'+ data.userdata.authrole +'" /></div></td>';
            }

            showdata += '<td><div class="chatmain"><div class="top">';

            //判断管理员角色
            if(data.data.fromusertype == 2){
                showdata += '<div class="cname">'+data.userdata.authrole+'</div>';
            }

            //不能给自己发信息
            if(data.userdata.id == data.data.fromuid){
                showdata += '<div class="cname"><a href="javascript:void(0)">'+data.userdata.username+'</a></div>';
            } else {
                showdata += '<div class="cname"><a href="javascript:void(0)" onclick="talk('+data.data.fromuid+',\''+data.userdata.username+'\','+data.data.fromusertype+')" title="对ＴＡ说">'+data.userdata.username+'</a></div>';
            }
            
            if(data.data.tousertype != -1){
               showdata += '<div class="dui"></div>';
               //当别人对当前用户说时，禁止对自己说，有bug
               showdata += '<div class="cname"><a href="javascript:void(0)" onclick="talk('+data.data.touid+',\''+data.data.tousername+'\','+data.data.tousertype+')" title="对ＴＡ说">'+data.data.tousername+'</a></div>';

            }
            showdata += '<div class="time">['+data.data.create_time+']</div>';
            showdata += '</div>';
            showdata += '<table border="0" cellspacing="0" cellpadding="0"><tr><td valign="top"><img src="/Public/Zhibo/zhibo/Picture/chatleft.png"></td><td><div class="txt">';
            showdata += '<div class="cont'+data.data.fromusertype+'">'+data.data.content+'</div>';
            showdata += '</div></td></tr></table></div></td></tr></table>';
        showdata += '</div>';
        if(data.data.content != ""){$("#zhiboscroll .content").append(showdata);}
        chat_ajax();
    }
}

/**
 * [talk 对谁说]
 * @param  {[type]} touid  [description]
 * @param  {[type]} tonick [description]
 * @return {[type]}        [description]
 */
function talk(touid,tonick,tousertype){
    //如果是手机端则禁止
    if($("#mob").val() == 1){
        return false;
    }
    //这里做一个不能给自己发消息的禁止
    //发送ajax
    $.ajax({
        type: "POST",
        dataType:'json',
        url:checkselfmsg,
        data:{touid:touid,tousertype:tousertype},
        success: function(data){
            //发送成功之后
            if(data.status){
                $("#touid").val(touid);
                //$("#tonick").val(tonick);
                $("#tousertype").val(tousertype);

                var nicklist = $("#nicklist");
                var optionlen = nicklist.find('option').length;
                var flag = 0;
                for(var i = 0; i < optionlen; i++){
                   if(nicklist.find('option:eq('+i+')').html() == tonick){
                      nicklist.find('option:eq('+i+')').prop({selected:true});
                      flag = 1;
                      break;
                   }
                }

                if(flag == 0){
                   nicklist.append('<option value="'+touid+'" tousertype="'+tousertype+'">'+tonick+'</option>')
                   nicklist.find('option:eq('+(nicklist.find('option').length-1)+')').prop({selected:true});
                }
            } else {
                layer.msg(data.info,{icon: 0});
            }
        }
    });
}

/**
 * [changetalk 改变用户列表]
 * @return {[type]} [description]
 */
function changetalk(){
    var nicklist = $('#nicklist');
    var touid = nicklist.val();

    var optindex = $('option:selected', '#nicklist').index();
    //var tonick = nicklist.find('option:eq('+optindex+')').html();
    var tousertype = nicklist.find('option:eq('+optindex+')').attr('tousertype');
    $("#touid").val(touid);
    //$("#tonick").val(tonick);
    $("#tousertype").val(tousertype);
}