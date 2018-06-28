"use strict";

//ajax统一拦截器
// !function(t){function r(i){if(n[i])return n[i].exports;var o=n[i]={exports:{},id:i,loaded:!1};return t[i].call(o.exports,o,o.exports,r),o.loaded=!0,o.exports}var n={};return r.m=t,r.c=n,r.p="",r(0)}([function(t,r,n){n(1)(window)},function(t,r){t.exports=function(t){t.hookAjax=function(t){function r(t){return function(){return this.hasOwnProperty(t+"_")?this[t+"_"]:this.xhr[t]}}function n(r){return function(n){var i=this.xhr,o=this;return 0!=r.indexOf("on")?void(this[r+"_"]=n):void(t[r]?i[r]=function(){t[r](o)||n.apply(i,arguments)}:i[r]=n)}}function i(r){return function(){var n=[].slice.call(arguments);if(!t[r]||!t[r].call(this,n,this.xhr))return this.xhr[r].apply(this.xhr,n)}}return window._ahrealxhr=window._ahrealxhr||XMLHttpRequest,XMLHttpRequest=function(){this.xhr=new window._ahrealxhr;for(var t in this.xhr){var o="";try{o=typeof this.xhr[t]}catch(t){}"function"===o?this[t]=i(t):Object.defineProperty(this,t,{get:r(t),set:n(t)})}},window._ahrealxhr},t.unHookAjax=function(){window._ahrealxhr&&(XMLHttpRequest=window._ahrealxhr),window._ahrealxhr=void 0},t.default=t}}]);
// 统一加信用凭证   headers: { 'x-requested-with': 'XMLHttpRequest' }

$.ajaxSetup({crossDomain: true, xhrFields: {'withCredentials': true}});


// 定义公共function等
var preLink = "http://221.123.178.232/smallgamesdk/Public/Uploads/";
var preHttp = 'http://community.73776.com/index.php/shop/WebShop/';

//获取url后的各种参数
function getUrlParams() {
	var url = location.search;
	var theRequest = new Object();
	if (url.indexOf("?") != -1) {
		var str = url.substr(1);
		var strs = str.split("&");
		for (var i = 0; i < strs.length; i++) {
			theRequest[strs[i].split("=")[0]] = unescape(strs[i].split("=")[1]);
		}
	}
	return theRequest;
}


function gettimestamp() {
	var timestamp = new Date().getTime();
	return timestamp;
}
function getCurrentTime() {
	var time = new Date();
	var year = time.getFullYear();
	var month = time.getMonth() + 1;
	var day = time.getDate();

	var currentTime = year + "-" + month + "-" + day;
	return escape(currentTime);
}


function setCookie(cname, cvalue, exdays) {			//cookie 封装
	var d = new Date();
	d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000);
	var expires = "expires=" + d.toUTCString();
	document.cookie = cname + "=" + cvalue + "; " + expires;
}
function getCookie(cname) {
	var name = cname + "=";
	var ca = document.cookie.split(';');
	for (var i = 0; i < ca.length; i++) {
		var c = ca[i].trim();
		if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
	}
	return "";
}


function getUserInfo() {					// 验证登录及获取用户信息/免密登录
	var output, utoken=null;
	if( sessionStorage.getItem('_zdkj_userInfo') ) { return true }
	if (window.navigator.cookieEnabled) {
		if( !getCookie('_ZDKJCREDENT')) { return false }
	} else {
		if( !localStorage.getItem('zdkjutoken') ) { return false;  }
		utoken = localStorage.getItem('zdkjutoken');
	}
	$.ajax({
		type: 'POST',
		xhrFields: { withCredentials: true },
		crossDomain: true,
		url:  preHttp + 'valieLogin',
		data: { ucookie: getCookie("_ZDKJCREDENT"), utoken: utoken },
		success: function success(res) {
			// console.log(res);
			if (res.code == 200 || res.code == 201) {		//服务器session未失效
				sessionStorage.setItem("_zdkj_userInfo",JSON.stringify({'uname': res.uname, 'score': res.score}) );
				setCookie("PHPSESSID", res.phpsessid);
				output = true;
			} else if (res.code == 201) {					//免密登录时间内
				//ucookie 登录
				localStorage.setItem("zdkjutoken", res.token );
				if (window.navigator.cookieEnabled) {
					setCookie("_ZDKJCREDENT", res.cookie, 7);
				} else {
					alert("浏览器未启用cookie,无法自动登录,请重新设置浏览器！");
				}
			} else if ( res.code == 401 ) {
				sessionStorage.clear();
				localStorage.clear();
				setCookie("_ZDKJCREDENT", "", -1);
				output = false;
			}
		},
		error: function error(XMLHttpRequest, textStatus, errorThrown) {
			console.log(XMLHttpRequest, textStatus, errorThrown);
		}
	});
	return output;
}


function valiLogin() {				//验证登录
	if (!sessionStorage.getItem('_zdkj_userInfo') ) {
		console.log("准备跳转登录页面")
		window.location.href = "login.html";
	}
}


function valiToken() {				//验证token
	var utoken = JSON.parse(localStorage.getItem("zdkjtoken")),
	    output = false;

	if (utoken) {
		$.ajax({
			type: 'post',
			async: false,
			xhrFields: { withCredentials: true },
			crossDomain: true,
			url: 'http://community.73776.com/index.php/shop/WebShop/vaile_token',
			data: { utoken: utoken },
			success: function success(res) {

				if (res.code == 200) {
					output = true;
				} else if (res.code == 400) {
					sessionStorage.removeItem("zdkjuname");
					localStorage.removeItem("zdkjutoken");
					setCookie("PHPSESSID", "", -1);

					window.location.href = "login.html";
				}
			},
			error: function error() {
				alert("服务器错误，请稍候重试");
			}
		});
	} else {
		window.location.href = "login.html";
	}

	return output;
}



function expandClick(selector) {			//扩展点击跳转范围
	$(selector).on("click", function () {
		window.location.href = $(this).find("a").attr("href");
	});
}

//返回上一页  数据刷新
$("[data-type=back]").click(function (e) {
	e.preventDefault();history.back(-1);
});
//返回上一页  非刷新模式
$("[data-type=go]").click(function (e) {
	e.preventDefault();history.go(-1);
});

//添加弹窗块
var popBlock = "<div id='pop' class='pop'></div>";
$('body *:first').before(popBlock);

//loading效果每个页面添加loading效果
(function () {
	var str = "";
	str += "<div id=\"loading\"><div class=\"loading\">";
	str += "<div><span></span></div>".repeat(3);
	str += "</div>".repeat(2);
	var loadingDiv = str;
	$('body *:first').before(loadingDiv);

	document.addEventListener("touchmove", forbidMove);
	function forbidMove(ev) {
		var ev = ev || window.event;
		var target = ev.target || ev.srcElement;
		window.event.returnValue = false || ev.preventDefault();
	}
	$(document).ready(function () {
		document.removeEventListener("touchmove", forbidMove);
		$('#loading').hide(0);
	});
})();

function setPop(m, el, time1, time2) {
	//统一设置弹窗提示
	if (!m) return; //弹窗内容

	var msg = "<i>" + m + "</i>",			//弹窗信息
	ele = el || "#pop",						//弹窗父元素
	timer = time1 || 2000,					//弹窗持续时间
	end = time2 || 500; //提示消失过度时间
	var element = $(ele);
	
	element.html(msg).css({					//弹窗属性设置
		'display': 'block',
		'opacity': 1,
		'transition': 'all .5s linear'
	});
	
	setTimeout(function () {				//弹窗消失设置
		element.css("opacity", 0);
		setTimeout(function () {
			element.html("").hide();
		}, end);
	}, timer);
}