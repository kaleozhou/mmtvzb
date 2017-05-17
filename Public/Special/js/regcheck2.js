
function isMob(String){
  if(!/^(1[3-8][0-9])\d{8}$/i.test(String)){
	  return false;
  }
  return true;
}
function isTel(String) 
{   
  var Letters = "1234567890-()"; //可以自己增加可输入值 
  var i; 
  var c; 
  if(String.charAt( 0 )=='-') 
     return false; 
  if( String.charAt( String.length - 1 ) == '-' ) 
     return false; 
  for( i = 0; i < String.length -1; i ++ ) 
  {   
    c = String.charAt( i ); 
    if (Letters.indexOf( c ) < 0) 
       return false; 
    } 
   return true; 
} 

function ShowCheckResult(ObjectID, Message, ImageName) {
	obj = document.getElementById(ObjectID);
	obj.style.display = '';
	obj.innerHTML = '<img src="images/check_'+ImageName+'.gif" align=absmiddle>&nbsp;' + Message;
}
function CheckUserName(UserName) {
	if(UserName.length < 1 || UserName.length > 15) {
		ShowCheckResult("CheckUserName","用户名长度为 1-15 ","error");
		return;
	}
	ShowCheckResult("CheckUserName", "","right");
}

function CheckUserPass(UserPass) {
	if (UserPass.length < 6){
		ShowCheckResult("CheckUserPass", "密码至少包含 6 个字符","error");
		return;
	}

	ShowCheckResult("CheckUserPass", "","right");

}
function CheckRetypePassword(RetypePassword) {
	if (RetypePassword != $("#regPwd").val()){
		ShowCheckResult("CheckRetypePassword", "2 次输入的密码不相同","error");
		return;
	}
	if (RetypePassword != ''){
		ShowCheckResult("CheckRetypePassword", "","right");
	}
}
function CheckTel(Tel) {
	if(!isMob(Tel) || Tel=="") {
		ShowCheckResult("CheckTel", "手机号码输入有误","error");
		return;
	}
	
	if (Tel != ''){
		ShowCheckResult("CheckTel", "","right");
		var but=$("<input type='button' value='发送验证码' class='tybu' id='regCode'>");
		but.click(function () {
			sendyz($("#regTel").val(),"regWin")
		});
		$("#Checkyz2").html(but);
	}
}
function regcheck(){
var regtext="";
var regflag=false;
if ($("#regUser").val().length < 1 || $("#regUser").val().length  > 15) {
   regtext+="您输入的用户名长度应该在 1-15 范围内！\n";
   regflag=true;
}
if ($("#regPwd").val().length < 6) {
   regtext+="密码必须至少包含 6 个字符！\n";
   regflag=true;
}
if ($("#regPwd").val()!=$("#regPwd2").val()) {
   regtext+="您 2 次输入的密码不相同！\n";
   regflag=true;
}
if (!isTel($("#regTel").val()) || $("#regTel").val()=="") {
   regtext+="您没有输入电话号码或输入有误！\n";
   regflag=true;
}
if(regflag){
	alert(regtext);
	return false;
}else{
	$.post(
		"regCheck.php",
		$("#regForm").serialize(),
		function(data){
			var data=data.replace(/(^\s*)|(\s*$)/g, "");
			alert(data);
			if(data=="注册成功!"){
				$("#wholeReg").fadeOut();
				$('#login_reg_hd').fadeOut();
			}
		});
}
}
function sendyz(a,b){
	if(a == ""){
		alert("请先输入手机号码！");
	}else{
			$("#regyz").attr("readonly",false);
			$("#Checkyz2").html("<div class='btxt3'>正在发送</div>");
			$.ajax({
				type:"get",
				dataType:"html",
				url:"user/sendyz.php?t="+a+"&y=dir&reg=b",
				success:function(data){
					$("#Checkyz2").html("<div class='btxt3'>"+data+"</div>");
				}
			});
	}
}
$(function () {
	$("#regCode").click(function () {
		sendyz($("#regTel").val(),"regWin")
	})
});

