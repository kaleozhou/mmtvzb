// JavaScript Document

$(function(){
   $(".foot1 span#timebox").html(show());	
//随机数字
    number1();
    setInterval("number1()", 4000);
    number2();
    setInterval("number2()", 6000);
    number3();
    setInterval("number3()", 8000);
	
	});
		
	var cout1 = 7794;
	
function number1() {
		var spannumber = document.getElementById("number1");
		var date = new Date();
		var second = date.getSeconds();
		var minutes = date.getMinutes();
		var hours = date.getHours();
		var number = cout1 + 24 * 60 * hours + 60 * minutes + second + parseInt(Math.random() * 3);
	
		spannumber.innerHTML = number;
		var html = "";
		for (var i = 0; i < String(number).length; i++) {
			var temp = String(number).substr(i, 1);
			html += "<b>" + temp + "</b>";
		}
		$(".num_show1").html(html);}		
	
	var cout2 = 75895;
	
function number2() {
		var spannumber = document.getElementById("number2");
		var date = new Date();
		var second = date.getSeconds();
		var minutes = date.getMinutes();
		var hours = date.getHours();
		var number = cout2 + 24 * 60 * hours + 60 * minutes + second + parseInt(Math.random() * 3);
	
		spannumber.innerHTML = number;
		var html = "";
		for (var i = 0; i < String(number).length; i++) {
			var temp = String(number).substr(i, 1);
			html += "<b>" + temp + "</b>";
		}
		$(".num_show2").html(html);
		}		

	var cout3 = 61476;
	
function number3() {
		var spannumber = document.getElementById("number3");
		var date = new Date();
		var second = date.getSeconds();
		var minutes = date.getMinutes();
		var hours = date.getHours();
		var number = cout3 + 24 * 60 * hours + 60 * minutes + second + parseInt(Math.random() * 3);
	
		spannumber.innerHTML = number;
		var html = "";
		for (var i = 0; i < String(number).length; i++) {
			var temp = String(number).substr(i, 1);
			html += "<b>" + temp + "</b>";
		}
		$(".num_show3").html(html);
}		

  function show(){
   var mydate = new Date();
   var str = "" + mydate.getFullYear() + "-";
   str += (mydate.getMonth()+1) + "-";
   str += mydate.getDate();
   return str;   
  }




















	